@extends('auth.layouts.auth')
@section('title', 'Sign in your account')
@section('content')
    <div class="auth-content">
        <div class="text-left">
            <h5 class="mb-0">Sign In to your Account</h5>
            <p class="text-muted mt-2">Welcome back! please enter your detail</p>
        </div>
        <form class="mt-4 pt-2" action="{{ url('password/set') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="position-relative">
                <img src="{{ asset('public/assets/images/icons/mail.svg') }}" alt="" class="left-icon-form">
                <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"
                    value="raj75k@gmail.com">
            </div>
            @error('email')
                <span class="text-danger">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="mt-3 mb-3">
                <div class="input-group auth-pass-inputgroup">
                    <img src="{{ asset('public/assets/images/icons/lock.svg') }}" alt="" class="left-icon-form">
                    <input type="password" class="form-control" placeholder="Enter password" name="password"
                        value="admin@123">
                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon">
                        <img src="{{ asset('public/assets/images/icons/eye-off.svg') }}" alt="">
                    </button>
                </div>
                @error('password')
                    <span class="text-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row mb-4">
                <div class="col d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-check" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember-check">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <div class="flex-shrink-0">
                            <div class="">
                                <a href="{{ route('password.request') }}" class="btn-forget-pass">Forgot password?</a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            <div class="mb-3">
                <button class="btn btn-primary w-100" type="submit">Log In</button>
            </div>
        </form>
    </div>
@endsection
