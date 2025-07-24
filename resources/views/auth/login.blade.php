@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-1 p-3 mx-auto" style="max-width: 400px; width: 100%;">
                <div class="card-body">
                    <h1 class="fancy-font text-center mb-3">{{ __('Login') }}</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="my-3">
                            <input id="email" type="email" class="form-control w-100 bg-body-secondary @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class=mb-3">
                            <input id="password" type="password" class="form-control w-100 bg-body-secondary @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary w-100 mt-4 fw-bold">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3 shadow-sm border-1 w-100 text-center mx-auto" style="max-width: 400px;">
                <div class="card-body py-3">
                    <span class="text-muted">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none">
                            {{ __('Register') }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
