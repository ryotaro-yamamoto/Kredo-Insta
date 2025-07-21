{{-- Clickable image --}}
<div class="container p-0">
  <a href="{{route('post.show', $post->id)}}">
    <img src="{{ $post->image }}" alt="post id {{$post->id}}" class="w-100">
  </a>
</div>

<livewire:post-body :post="$post" />