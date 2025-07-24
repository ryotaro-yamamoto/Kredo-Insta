@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row gx-5">
    <div class="col-8">
        @forelse ($home_posts as $post)
            <div class="card mb-4">
                {{-- title --}}
                @include('users.posts.contents.title')
                {{-- body --}}
                @include('users.posts.contents.body')
            </div>

            {{-- 投稿5件ごとに広告を表示 --}}
            @if ($loop->iteration % 5 === 0 && floor($loop->iteration / 5) - 1 < count($ads))
                <div class="card mb-4 border border-warning">
                    <div class="card-header bg-warning text-white fw-bold">
                        Sponsored
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $ads[floor($loop->iteration / 5) - 1]->title }}</h5>
                        <p class="card-text">{{ $ads[floor($loop->iteration / 5) - 1]->description }}</p>
                        @if($ads[floor($loop->iteration / 5) - 1]->image)
                            <img src="{{ $ads[floor($loop->iteration / 5) - 1]->image }}" alt="ad image" class="w-100">
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center">
                <h2>Share Photos</h2>
                <p class="text-secondary">When you share photos, they'll appear on your profile.</p>
                <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
            </div>
        @endforelse
    </div>

    <div class="col-4">
        <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 py-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', Auth::user()->id) }}">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-md">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0">
                <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-decoration-none text-dark fw-bold">{{ Auth::user()->name }}</a>
                <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
            </div>
        </div>

        {{-- suggestions --}}
        @if ($suggested_users)
            <div class="row">
                <div class="col-auto">
                    <p class="fw-bold text-secondary">Suggestions For You</p>
                </div>
                <div class="col text-end">
                    <a href="{{ route('suggestions.index') }}" class="fw-bold text-dark text-decoration-none">See All</a>
                </div>
            </div>
            @foreach ($suggested_users as $user)
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
                        <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $user->name }}
                        </a>
                    </div>
                    <div class="col-auto">
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
