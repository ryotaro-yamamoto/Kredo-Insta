<div class="modal fade" id="delete-post-{{$post->id}}">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header border-danger">
        <h3 class="h5 modal-title text-danger">
          <i class="fa-solid fa-circle-exclamation"></i> Delete Post
        </h3>
      </div>
      <div class="modal-body">
        <p>Are you sure you wan to delete this post?</p>
        <div class="mt-3">
          @if ($post->images->isNotEmpty())
            <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="post id {{$post->id}}" class="image-lg">
          @else
            <img src="{{ asset('no-image.png') }}" alt="no image" class="image-lg">
          @endif
          <p class="mt-1 text-muted">{{ $post->description }}</p>
        </div>
      </div>
      <div class="modal-footer border-0">
        <form action="{{route('post.destroy', $post->id)}}" method="post">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>