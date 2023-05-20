@extends('layout.default', [
    'bodyClass' => 'pace-top',
    'appHeaderHide' => true,
    'appSidebarHide' => true,
    'appContentHide' => true,
    'appClass' => 'app-full-height app-without-header p-0'
])

@section('title', 'Reset Password')

@section('content')
 <!-- BEGIN login -->
    <div class="register">
        <!-- BEGIN login-content -->
        <div class="register-content">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1 class="text-center">{{ __('Reset Password') }}</h1>
                <div class="text-white text-opacity-50 text-center mb-4">
                   One Admin ID is all you need to access all the Admin services.
                </div>
                @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                <div class="mb-3">
                    <label class="form-label">{{ __('Email Address') }}<span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control form-control-lg bg-white bg-opacity-5 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">
                    {{ __('Send Password Reset Link') }}
                </button>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection