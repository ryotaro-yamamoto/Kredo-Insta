@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container">
    <h3>Message List</h3>

    {{-- チャット履歴のあるユーザー一覧 --}}
    @if ($users->isEmpty())
        <p>No conversations yet.</p>
    @else
        <ul class="list-group mb-4">
            @foreach ($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('messages.chat', $user->id) }}" class="text-decoration-none">
                        <strong>{{ $user->name }}</strong>
                    </a>
                    <span class="badge bg-secondary">In Chat</span>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- 新規チャットを始められるフォロー中のユーザー --}}
    <h5>Start New Chat</h5>
    @if ($newChatUsers->isEmpty())
        <p class="text-muted">No more users available to chat.</p>
    @else
        <ul class="list-group">
            @foreach ($newChatUsers as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $user->name }}</span>
                    <a href="{{ route('messages.chat', $user->id) }}" class="btn btn-sm btn-outline-primary">Message</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
