@extends('layouts.app')

@section('title', 'Chat with ' . $partner->name)

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        Chat with 
        <a href="{{ route('profile.show', $partner->id) }}" class="text-decoration-none">
            {{ $partner->name }}
        </a>
    </h2>

    {{-- チャット表示エリア --}}
    <div id="chat-box" class="border rounded p-3 mb-4" style="height: 400px; overflow-y: auto;">
        @foreach ($messages->reverse() as $message)
            <div class="mb-2 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}">
                <div class="d-inline-block p-2 rounded 
                    {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-secondary text-white' }}">
                    {{ $message->content }}
                </div>
                <div class="text-muted small">
                    {{ $message->created_at->format('H:i') }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- メッセージ送信フォーム --}}
    <form action="{{ route('messages.store') }}" method="POST" class="d-flex">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
        <input type="text" name="content" class="form-control me-2" placeholder="Type your message..." required>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

{{-- 自動スクロール --}}
<script>
    window.onload = function () {
        const chatBox = document.getElementById("chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    };
</script>
@endsection