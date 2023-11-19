@extends('auth.layouts.auth')
@section('title', 'Reset Your Password')
@section('content')
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href=""><img src="{{ asset('public/web-2.O/images/logo/logo-full.png') }}" alt=""></a>
        </div>
        <h4 class="text-center mb-4">Reset Your Password</h4>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mb-3">
                <label class="mb-1"><strong>Email</strong></label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Enter Email" required />
                @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
            </div>
        </form>
        <div class="new-account mt-3">
            @if (Route::has('login'))
                <p>
                    <a class="text-primary" href="{{ route('login') }}">
                        {{ __('Login account') }}
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection
