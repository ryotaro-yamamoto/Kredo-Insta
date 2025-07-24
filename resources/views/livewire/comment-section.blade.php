<div>
  @if ($comments->isNotEmpty())
      <ul class="list-group mb-2">
          @foreach ($showAll ? $comments : $comments->take(1) as $comment)
              <li class="list-group-item border-0 p-0 mb-2">
                  <a href="{{route('profile.show', $comment->user->id)}}" class="text-decoration-none text-dark fw-bold">{{$comment->user->name}}</a>
                  &nbsp;
                  <p class="d-inline fw-light">{{$comment->body}}</p>

                  <form action="{{route('comment.destroy', $comment->id)}}" method="post">
                      @csrf
                      @method('DELETE')
                      <span class="text-uppercase text-muted xsmall">
                      {{date('M d, Y', strtotime($comment->created_at))}}
                      </span>

                      {{-- If the auth user is the owner of the comment, show a delete btn --}}
                      @if (Auth::user()->id === $comment->user->id)
                      &middot;
                      <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                      @endif
                  </form>
              </li>
          @endforeach

          @if ($showAll)
              <li class="list-group-item border-0 px-0 pt-0">
                  <a href="#" wire:click.prevent="toggleShowAll(false)" class="small text-decoration-none">Show less</a>
              </li>
          @elseif ($comments->count() > 1)
              <li class="list-group-item border-0 px-0 pt-0">
                  <a href="#" wire:click.prevent="toggleShowAll(true)" class="small text-decoration-none">
                      View all {{ $comments->count() }} comments
                  </a>
              </li>
          @endif
      </ul>
  @endif

  <form wire:submit.prevent="addComment">
      <div class="input-group">
          <textarea wire:model.defer="newComment" cols="30" rows="1" class="form-control form-control-sm" placeholder="Add a comment..."></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm">
              <i class="fa-regular fa-paper-plane text-primary"></i>
          </button>
      </div>
      @error('newComment')
          <div class="text-danger small">{{ $message }}</div>
      @enderror
  </form>
</div>