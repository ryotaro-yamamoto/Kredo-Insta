<div>
  {{-- Clickable image --}}
  <div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none">
      @if ($post->images->isNotEmpty())
        <div class="carousel-wrapper border"> 
          <div id="carousel-{{ $post->id }}" class="carousel slide h-100" data-bs-ride="carousel">
            <div class="carousel-indicators">
              @foreach ($post->images as $index => $image)
                <button
                  type="button"
                  data-bs-target="#carousel-{{ $post->id }}"
                  data-bs-slide-to="{{ $index }}"
                  class="{{ $index === 0 ? 'active' : '' }}"
                  aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                  aria-label="Slide {{ $index + 1 }}"
                  style="background-color: gray; width: 10px; height: 10px; border-radius: 50%;"
                ></button>
              @endforeach
            </div>
            <div class="carousel-inner h-100">
              @foreach ($post->images as $index => $image)
                <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                  <div class="d-flex align-items-center justify-content-center h-100 bg-white">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100 object-fit-contain" alt="Image {{ $index + 1 }}">
                  </div>
                </div>
              @endforeach
            </div>
            @if ($post->images->count() > 1)
              <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black;"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black;"></span>
                <span class="visually-hidden">Next</span>
              </button>
            @endif
          </div>
        </div>
      @else
        <div class="carousel-wrapper border d-flex align-items-center justify-content-center">
          <img src="{{ asset('no-image.png') }}" alt="no image" class="d-block w-100 object-fit-contain">
        </div>
      @endif
    </a>
  </div>

  <div class="card-body">
    {{-- heart button + comment button + categories --}}
    <div class="row align-items-center">
      <div class="col-auto">
        <livewire:like-button :post="$post" />
      </div>

      <div class="col-auto px-2 ms-2">
        <a href="{{ route('post.show', $post->id) }}" class="btn btn-sm p-0 text-decoration-none">
          <i class="fa-regular fa-comment p-0"></i>
        </a>
      </div>
      <div class="col-auto px-0">
        <span class="fw-bold fs-5">{{ $comments_count }}</span>
      </div>

      <div class="col text-end">
        @if ($post->categoryPost->isEmpty())
          <div class="badge bg-dark">
            Uncategorized
          </div>
        @else
          @foreach ($post->categoryPost as $category_post)
            <div class="badge bg-secondary bg-opacity-50">
              {{ $category_post->category->name }}
            </div>
          @endforeach
        @endif
      </div>
    </div>

    {{-- owner + description --}}
    <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark fw-bold">{{$post->user->name}}</a>
    &nbsp;
    <p class="d-inline fw-light">{{$post->description}}</p>
    <p class="text-uppercase text-muted xsmall">
      {{date('M d, Y', strtotime($post->created_at))}}
    </p>

    {{-- Comments --}}
    <livewire:comment-section :post="$post" />
  </div>
</div>