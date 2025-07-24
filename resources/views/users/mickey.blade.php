@extends('layouts.app')

@section('title', 'Mickey Support')

@section('content')
<div class="container text-center">
    <h1>Hello, I am Mickey! How can I help you?</h1>
    <img src="{{ asset('images/S__55205928.jpg') }}" alt="Mickey" class="img-fluid" style="max-width: 300px;">
</div>

<h2 class="text-center">Let's chat with Mickey!</h2>

<!-- チャットウィンドウを表示 -->
<div id="chat" style="max-height: 400px;"></div>

<script>
    var botmanWidget = {
        aboutText: 'Mickey サポート',
        introMessage: "🙋‍♂️ こんにちは！Mickeyです。質問があれば「質問」と入力してください！",
        title: 'Mickey チャット',
        mainColor: '#0099ff',
        bubbleBackground: '#0078cc',
        chatServer: '{{ url("/botman") }}',
        placeholderText: 'メッセージを入力...',
    };
</script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection





    
