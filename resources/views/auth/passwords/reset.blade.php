@extends('auth.layouts.auth')
@section('title', 'Reset Your Password')
@section('content')
    <div class="auth-content">
        <div class="text-left">
            <h5 class="mb-0">Reset Your Password</h5>
            <p class="text-muted mt-2">Create your new Password!</p>
        </div>
        <form class="mt-4 pt-2" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="position-relative">
                <img src="{{ asset('public/assets/images/icons/mail.svg') }}" alt="" class="left-icon-form">
                <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"
                    value="{{ $email ?? old('email') }}" required readonly>
            </div>
            @error('email')
                <span class="text-danger">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="mt-3 mb-3">
                <div class="input-group auth-pass-inputgroup">
                    <img src="{{ asset('public/assets/images/icons/lock.svg') }}" alt="" class="left-icon-form">
                    <input type="password" class="form-control" placeholder="Enter new password" name="password">
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

            <div class="mt-3 mb-3">
                <div class="input-group auth-pass-inputgroup">
                    <img src="{{ asset('public/assets/images/icons/lock.svg') }}" alt="" class="left-icon-form">
                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="Confirmation password" required>
                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon-1">
                        <img src="{{ asset('public/assets/images/icons/eye-off.svg') }}" alt="">
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="text-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <button class="btn btn-primary w-100" type="submit">Log In</button>
            </div>
        </form>
    </div>
@endsection
