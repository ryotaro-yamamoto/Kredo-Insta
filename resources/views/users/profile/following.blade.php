@extends('layouts.app')

@section('title', 'Following')

@section('content')
  @include('users.profile.header')

  <div class="container mt-5">
    <h3 class="text-center mb-4">Following</h3>
    @forelse ($user->following as $following)
    <div class="row">
      <div class="col-4"></div>
      <div class="col-4 mb-3">
        <div class="card-body d-flex align-items-center">
          <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark d-flex align-items-center">
            @if ($following->following->avatar)
              <img src="{{ $following->following->avatar }}" alt="{{ $following->following->name }}" class="rounded-circle avatar-sm me-2">
            @else
              <i class="fa-solid fa-circle-user text-secondary icon-sm me-2"></i>
            @endif

            <span class="me-5">{{ $following->following->name }}</span>

            @if (Auth::user()->id !== $following->following->id)
              @if ($following->following->isFollowed())
                <form action="{{ route('follow.destroy', $following->following->id) }}" method="post" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                      class="dropdown-item border-0 bg-transparent text-secondary">Following</button>
                </form>
              @else
                <form action="{{ route('follow.store', $following->following->id) }}" method="post">
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
      <p class="text-muted text-center">No followings yet.</p>
    @endforelse
  </div>
@endsection
