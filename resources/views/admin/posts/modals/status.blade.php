{{-- Visible --}}
@if ($post->trashed())
<div class="modal fade" id="visible-post-{{$post->id}}">
  <div class="modal-dialog">
    <div class="modal-content border-primary">
      <div class="modal-header border-primary">
        <h3 class="h5 modal-title text-primary">
          <i class="fa-solid fa-eye"></i> Visible Post
        </h3>
      </div>
      <div class="modal-body">
        Are you ure you want to show this post?
        @if ($post->image)
          <img src="{{ $post->image }}" alt="{{$post->id}}" class="d-block me-auto image-md img-thumbnail">
        @else
          <i class="fa-solid fa-circle-user text-secondary icon-md d-block mx-auto"></i>
        @endif
        {{$post->description}}
      </div>
      <div class="modal-footer border-0">
        <form action="{{route('admin.posts.visible', $post->id)}}" method="post">
          @csrf
          @method('PATCH')
          <button class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Visible</button>
        </form>
      </div>
    </div>
  </div>
</div>
@else
{{-- Invisible --}}
<div class="modal fade" id="invisible-post-{{$post->id}}">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header border-danger">
        <h3 class="h5 modal-title text-danger">
          <i class="fa-solid fa-eye-slash"></i> Invisible Post
        </h3>
      </div>
      <div class="modal-body">
        Are you ure you want to show this post?
        @if ($post->image)
          <img src="{{ $post->image }}" alt="{{$post->id}}" class="d-block me-auto image-md img-thumbnail">
        @else
          <i class="fa-solid fa-circle-user text-secondary icon-md d-block mx-auto"></i>
        @endif
        {{$post->description}}
      </div>
      <div class="modal-footer border-0">
        <form action="{{route('admin.posts.invisible', $post->id)}}" method="post">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Invisible</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif