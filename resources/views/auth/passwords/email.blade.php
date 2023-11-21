@extends('auth.layouts.auth')
@section('title', 'Reset Your Password')
@section('content')
    <div class="auth-content">
        <div class="text-left">
            <h5 class="mb-0">Reset Your Password</h5>
            <p class="text-muted mt-2">Enter the email address associated with your account and we will send you a link to
                reset yor password.</p>
        </div>
        <form class="mt-4 pt-2" action="{{ route('password.email') }}" method="POST">
            @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="position-relative">
                <img src="{{ asset('public/assets/images/icons/mail.svg') }}" alt="" class="left-icon-form">
                <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"
                    value="{{ old('email') }}">
            </div>
            @error('email')
                <span class="text-danger">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="mb-3 mt-3">
                <button class="btn btn-primary w-100" type="submit">Continue</button>
            </div>
            <div class="new-account mt-3">
                @if (Route::has('login'))
                    <p>
                        <a class="btn-forget-pass" href="{{ route('login') }}">
                            {{ __('Back to Sign In') }}
                        </a>
                    </p>
                @endif
            </div>
        </form>
    </div>
@endsection
