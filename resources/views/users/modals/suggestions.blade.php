{{-- New(RIKO) --}}
@extends('layouts.app')

@section('title', 'All Suggestions')

@section('content')
<div class="col-6 mx-auto">
    <h2 class="h4 mb-4 fw-bold">Suggestions For You</h2>

    @forelse ($suggested_users as $user)
        <div class="row align-items-center mb-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', $user->id) }}">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-sm">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0 text-truncate">
                <a href="{{ route('profile.show', $user->id) }}" class="text-dark fw-bold text-decoration-none">
                    {{ $user->name }}
                </a>
            </div>
            <div class="col-auto">
                <form action="{{ route('follow.store', $user->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">Follow</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">No suggestions available.</p>
    @endforelse
</div>
@endsection
