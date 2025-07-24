<div class="d-flex align-items-center">
  <div class="me-2">
    <button wire:click="toggleLike" class="btn btn-sm p-0 border-0 bg-transparent">
      @if ($post->isLiked())
        <i class="fa-solid fa-heart text-danger"></i>
      @else
        <i class="fa-regular fa-heart"></i>
      @endif
    </button>
  </div>
  <span class="fw-bold fs-5">
    {{ $post->likes->count() }}
  </span>
</div>