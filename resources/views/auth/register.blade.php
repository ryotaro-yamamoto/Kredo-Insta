@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-1 p-3 mx-auto" style="max-width: 400px; width: 100%;">
                <div class="card-body">
                    <h1 class="fancy-font text-center mb-3">{{ __('Register') }}</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="my-3">
                            <input id="name" type="text" class="form-control w-100 bg-body-secondary @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Name') }}" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <input id="email" type="email" class="form-control w-100 bg-body-secondary @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <input id="password" type="password" class="form-control w-100 bg-body-secondary @error('password')is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <input id="password-confirm" type="password" class="form-control w-100 bg-body-secondary" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                        </div>

                        <div class="mb-0">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary w-100 mt-3 fw-bold">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3 shadow-sm border-1 w-100 text-center mx-auto" style="max-width: 400px;">
                <div class="card-body py-3">
                    <span class="text-muted">
                        {{ __("You have an account?") }}
                        <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">
                            {{ __('Login') }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
