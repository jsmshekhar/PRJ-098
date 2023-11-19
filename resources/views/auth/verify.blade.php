@extends('auth.layouts.auth')
@section('title', 'Verify Your Email Address')
@section('content')
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href=""><img src="{{ asset('public/web-2.O/images/logo/logo-full.png') }}" alt=""></a>
        </div>
        <h4 class="text-center mb-4">{{ __('Verify Your Email Address') }}</h4>
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <div class="mb-3">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Click here to request another') }}</button>
            </div>
        </form>
        <div class="new-account mt-3">
            @if (Route::has('password.request'))
                <p>
                    <a class="text-primary" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </p>
            @endif

            @if (Route::has('login'))
                <p>
                    <a class="text-primary" href="{{ route('login') }}">
                        {{ __('Login here') }}
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection
