@extends('layouts.app')

@section('title', 'Followers')

@section('content')
  @include('users.profile.header')

  <div class="container mt-5">
    <h3 class="text-center mb-4">Followers</h3>
    @forelse ($user->followers as $follower)
    <div class="row">
      <div class="col-4"></div>
      <div class="col-4 mb-3">
        <div class="card-body d-flex align-items-center">
          <a href="{{ route('profile.show', $follower->follower->id) }}" class="text-decoration-none text-dark d-flex align-items-center">
            @if ($follower->follower->avatar)
              <img src="{{ $follower->follower->avatar }}" alt="{{ $follower->follower->name }}" class="rounded-circle avatar-sm me-2">
            @else
              <i class="fa-solid fa-circle-user text-secondary icon-sm me-2"></i>
            @endif

            <span class="me-5">{{ $follower->follower->name }}</span>

            @if (Auth::user()->id !== $follower->follower->id)
              @if ($follower->follower->isFollowed())
                <form action="{{ route('follow.destroy', $follower->follower->id) }}" method="post" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                      class="dropdown-item border-0 bg-transparent text-secondary">Following</button>
                </form>
              @else
                <form action="{{ route('follow.store', $follower->follower->id) }}" method="post">
                  @csrf
                  <button type="submit" class="dropdown-item border-0 bg-transparent text-primary">Follow</button>
                </form>
              @endif
            @endif
          </a>
        </div>
      </div>
      <div class="col"></div>
    </div>
    @empty
      <p class="text-muted text-center">No followers yet.</p>
    @endforelse
  </div>
@endsection
