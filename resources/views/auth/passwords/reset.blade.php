@extends('auth.layouts.auth')
@section('title', 'Reset Your Password')
@section('content')
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href=""><img src="{{ asset('public/web-2.O/images/logo/logo-full.png') }}" alt=""></a>
        </div>
        <h4 class="text-center mb-4">Reset Your Password</h4>
        <form method="POST" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mb-3">
                <label class="mb-1"><strong>Email</strong></label>
                <input type="email" class="form-control" name="email" id="email"
                    value="{{ $email ?? old('email') }}" required placeholder="Enter Email" readonly />
                @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="mb-1"><strong>Password</strong></label>
                <input type="password" class="form-control" name="password" placeholder="Password" required />
                @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="mb-1"><strong>Confirm password</strong></label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation password"
                    required />
                @error('password_confirmation')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
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
