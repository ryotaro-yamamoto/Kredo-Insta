<div class="d-flex mb-3">
  {{-- ログインユーザーのストーリー（固定） --}}

  @php
    $loginUser = Auth::user();
    $loginUserId = $loginUser->id;
    $myStories = $groupedStories[$loginUserId] ?? collect();
    unset($groupedStories[$loginUserId]);
  @endphp

  <a href="#"
    data-bs-toggle="modal"
    @if ($myStories->isNotEmpty())
      data-bs-target="#storyModal" data-user-id="{{ $loginUserId }}"
    @else
      data-bs-target="#createStoryModal"
    @endif
    class="text-decoration-none text-dark me-2 text-center flex-shrink-0">

    <div class="d-flex flex-column align-items-center">
      <div class="story-item
        @if ($myStories->isNotEmpty())
          {{ !$myStories->first()?->is_read ? 'story-border-gradient' : 'story-border-gray' }}
        @endif">
        <div class="story-item-inner position-relative">
          @if ($loginUser->avatar)
            <img src="{{ $loginUser->avatar }}" alt="{{ $loginUser->name }}" class="rounded-circle avatar-md">
          @else
            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
          @endif

          {{-- プラスマーク（常にストーリー作成モーダルを開く） --}}
          <div class="add-icon position-absolute bottom-0 end-0"
            data-bs-toggle="modal" data-bs-target="#createStoryModal"
            style="cursor: pointer;">
            <i class="fa-solid fa-plus text-white"></i>
          </div>


        </div>
      </div>
      <div class="text-truncate mt-1" style="max-width: 7ch;">
        <strong>{{ $loginUser->name }}</strong>
      </div>
    </div>
  </a>

  {{-- 他ユーザーのストーリー（横スクロール） --}}
  <div class="d-flex overflow-auto hide-scrollbar" style="gap: 0.5rem;">
    @foreach ($groupedStories as $user_id => $userStories)
      @php $firstStory = $userStories->first(); @endphp
      <a href="#" data-bs-toggle="modal" data-bs-target="#storyModal" data-user-id="{{ $user_id }}" class="text-decoration-none text-dark text-center flex-shrink-0">
        <div class="d-flex flex-column align-items-center">
          <div class="story-item {{ $firstStory->is_read ? 'story-border-gray' : 'story-border-gradient' }}">
            <div class="story-item-inner">
              @if ($firstStory->user->avatar)
                <img src="{{ $firstStory->user->avatar }}" alt="{{ $firstStory->user->name }}" class="rounded-circle avatar-md">
              @else
                <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
              @endif
            </div>
          </div>
          <div class="text-truncate mt-1" style="max-width: 7ch;">
            <strong>{{ $firstStory->user->name }}</strong>
          </div>
        </div>
      </a>
    @endforeach
  </div>
</div>




<!-- モーダル -->
<div class="modal fade" id="storyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex align-items-center justify-content-center position-relative">
        <div id="progressContainer" class="story-progress-container position-absolute top-0 start-0 w-100 d-flex px-2 pt-2"></div>

        <!-- 左矢印 -->
        <button id="prevStory" class="btn btn-light position-absolute start-0 top-50 translate-middle-y">&lt;</button>
        
        <!-- ストーリー画像、テキスト、削除時間 -->
        <div class="story-content text-center w-100"  style="padding-top: 40px;">
          <img id="storyImage" src="" class="img-fluid rounded" style="max-height: 500px;">
          <p id="storyText" class="mt-2 text-center text-white"></p>
          <p id="storyExpiresIn" class="text-center text-white" style="font-size: 0.9em;"></p>
        </div>

        <!-- 右矢印 -->
        <button id="nextStory" class="btn btn-light position-absolute end-0 top-50 translate-middle-y">&gt;</button>
      </div>
    </div>
  </div>
</div>

<script>
  const groupedStories = @json($groupedStories);
  const myStories = @json($myStories);
  const loginUserId = {{ $loginUserId }};

  // マージ処理（ログインユーザーのストーリーを先に代入）
  if (myStories.length > 0 && typeof groupedStories === 'object') {
    groupedStories[loginUserId] = myStories;
  }

  let currentStories = [];
  let currentIndex = 0;
  let progressTimer = null;

  const storyModal = document.getElementById('storyModal');
  const storyImage = document.getElementById('storyImage');
  const storyCaption = document.getElementById('storyCaption');
  const stories = document.querySelectorAll('.story-progress-bar');
  const storyDuration = 5000;

  document.querySelectorAll('[data-bs-target="#storyModal"]').forEach(button => {
    button.addEventListener('click', () => {
      const userId = button.getAttribute('data-user-id');
      currentStories = groupedStories[userId];
      currentIndex = 0;

      generateProgressBars(currentStories.length);
      updateStory();
    });
  });

  function updateStory() {
    if (currentStories.length === 0) return;

    clearTimeout(progressTimer);

    const story = currentStories[currentIndex];
    recordStoryView(story.id);

    storyImage.src = "{{ asset('storage') }}/" + story.image_path;
    document.getElementById('storyText').textContent = story.text ?? '';

    const expiresAt = new Date(story.expires_at);
    const now = new Date();
    const diffMs = expiresAt - now;
    if (diffMs > 0) {
      const hours = Math.floor(diffMs / (1000 * 60 * 60));
      const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
      document.getElementById('storyExpiresIn').textContent = `Expires in ${hours}h ${minutes}m`;
    } else {
      document.getElementById('storyExpiresIn').textContent = 'Expired';
    }
    // 直後に全件既読チェック（recordStoryViewが非同期なので追いつかないケース補完）
    const allRead = currentStories.every(s => s.is_read === true);
    if (allRead) {
      console.log("All stories read. Updating border.");
      updateBorderColor(currentStories[0]?.user_id);
    }
  }

  function generateProgressBars(count) {
    const container = document.getElementById('progressContainer');
    container.innerHTML = '';
    for (let i = 0; i < count; i++) {
      const segment = document.createElement('div');
      segment.className = 'story-progress-segment';

      const bar = document.createElement('div');
      bar.className = 'story-progress-bar';
      bar.id = `progress-bar-${i}`;

      segment.appendChild(bar);
      container.appendChild(segment);
    }
  }

  document.getElementById('prevStory').addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex--;
      updateStory();
      playStory();
    }
  });

  document.getElementById("nextStory").addEventListener("click", function () {
    if (currentIndex < currentStories.length - 1) {
      currentIndex++;
      updateStory();
      playStory();
    } else {
      const modalInstance = bootstrap.Modal.getInstance(document.getElementById('storyModal'));
      if (modalInstance) {
        modalInstance.hide();
      }
    }
  });

  document.getElementById('storyModal').addEventListener('hidden.bs.modal', () => {
    clearTimeout(progressTimer);
  });

  document.getElementById('storyModal').addEventListener('shown.bs.modal', () => {
    updateStory();
    playStory();
  });

  function findStoryById(storyId) {
    for (const userId in groupedStories) {
      const found = groupedStories[userId].find(story => story.id === storyId);
      if (found) return found;
    }
    return null;
  }


  function recordStoryView(storyId) {
    fetch(`/stories/${storyId}/view`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({}), // データ送らないなら空オブジェクトでOK
    })
      .then(response => {
        if (!response.ok) throw new Error('Failed to record view');
        return response.json();
      })
      .then(data => {
        const story = findStoryById(storyId);
        if (story) {
          story.is_read = true;

          const storyUserId = story.user_id;
          const allRead = groupedStories[storyUserId].every(s => s.is_read === true);

          if (allRead) {
            updateBorderColor(storyUserId);
          }
        }
      })
      .catch(error => console.error('Error recording story view:', error));
  }


  function updateBorderColor(userId) {
    const anchors = document.querySelectorAll(`[data-user-id="${userId}"]`);
    anchors.forEach(anchor => {
      const storyItem = anchor.querySelector('.story-item');
      console.log('Updating border color for user', userId);
      console.log('Anchor element:', anchor);
      console.log('story-item found:', storyItem);

      if (storyItem) {
        storyItem.classList.remove('story-border-gradient');
        storyItem.classList.add('story-border-gray');
      }
    });
  }


  function playStory() {
    const bars = document.querySelectorAll('.story-progress-bar');
    if (!bars.length) return;

    clearTimeout(progressTimer);

    bars.forEach((bar, i) => {
      bar.style.transition = 'none';
      bar.style.width = i < currentIndex ? '100%' : '0%';
    });

    const currentBar = bars[currentIndex];
    if (!currentBar) return;

    setTimeout(() => {
      currentBar.style.transition = `width ${storyDuration}ms linear`;
      currentBar.style.width = '100%';
    }, 50);

    progressTimer = setTimeout(() => {
      if (currentIndex < currentStories.length - 1) {
        currentIndex++;
        updateStory();
        playStory();
      } else {
        const storyUserId = currentStories[0]?.user_id;
        
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('storyModal'));
        if (modalInstance) {
          modalInstance.hide();
        }
      }
    }, storyDuration);
  }
</script>
