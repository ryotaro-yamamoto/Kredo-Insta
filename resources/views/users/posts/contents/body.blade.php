{{-- Clickable image --}}
<div class="container p-0">
  <a href="{{route('post.show', $post->id)}}">
    <img src="{{ $post->image }}" alt="post id {{$post->id}}" class="w-100">
  </a>
</div>
<div class="card-body">
  {{-- heart button + number of likes + categories --}}
  <div class="row align-items-center">
    <div class="col-auto">
      @if ($post->isLiked())
        <form action="{{route('like.destroy', $post->id)}}" method="post">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm p-0">
            <i class="fa-solid fa-heart text-danger"></i>
          </button>
        </form>
      @else
        <form action="{{route('like.store', $post->id)}}" method="post">
          @csrf
          <button type="submit" class="btn btn-sm p-0">
            <i class="fa-regular fa-heart"></i>
          </button>
        </form>
      @endif
    </div>
    <div class="col-auto px-0">
      <span class="fw-bold fs-5">{{$post->likes->count()}}</span>
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
  @include('users.posts.contents.comments')
</div>