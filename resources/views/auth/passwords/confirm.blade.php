@extends('layout.default', [
    'bodyClass' => 'pace-top',
    'appHeaderHide' => true,
    'appSidebarHide' => true,
    'appContentHide' => true,
    'appClass' => 'app-full-height app-without-header p-0'
])

@section('title', 'Confirm Password')

@section('content')
 <!-- BEGIN login -->
    <div class="register">
        <!-- BEGIN login-content -->
        <div class="register-content">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1 class="text-center">{{ __('Confirm Password') }}</h1>
                <div class="text-white text-opacity-50 text-center mb-4">
                  {{ route('password.confirm') }}
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Password') }}<span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control form-control-lg bg-white bg-opacity-5 @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" autocomplete="password" autofocus>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">
                    {{ __('Confirm Password') }}
                </button>
                <div class="text-center text-white text-opacity-50">
                    <a href="{{ route('password.request') }}" class="btn btn-link">
                        {{ __('Forgot Your Password?') }}
                    </a>.
                </div>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection