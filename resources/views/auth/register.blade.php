@extends('layout.default', [
    'bodyClass' => 'pace-top',
    'appHeaderHide' => true,
    'appSidebarHide' => true,
    'appContentHide' => true,
    'appClass' => 'app-full-height app-without-header p-0'
])

@section('title', 'Sign-Up')

@section('content')
 <!-- BEGIN login -->
    <div class="register">
        <!-- BEGIN login-content -->
        <div class="register-content">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1 class="text-center">Sign Up</h1>
                <div class="text-white text-opacity-50 text-center mb-4">
                   One Admin ID is all you need to access all the Admin services.
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}<span class="text-danger">*</span></label>
                    <input id="name" type="text" class="form-control form-control-lg bg-white bg-opacity-5 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Email Address') }}<span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control form-control-lg bg-white bg-opacity-5 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <input id="password" type="password" class="form-control form-control-lg bg-white bg-opacity-5 @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                    <input id="password-confirm" type="password" class="form-control form-control-lg bg-white bg-opacity-5" name="password_confirmation" autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">{{ __('Sign Up') }}</button>
                <div class="text-center text-white text-opacity-50">
                    Don't have an account yet? <a href="{{ route('login') }}">Sign In</a>.
                </div>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection