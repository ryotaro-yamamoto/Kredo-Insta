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
      <span class="fw-bold fs-5">{{ $commentCount }}</span>
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