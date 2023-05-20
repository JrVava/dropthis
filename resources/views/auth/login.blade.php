@extends('layout.default', [
    'bodyClass' => 'pace-top',
    'appHeaderHide' => true,
    'appSidebarHide' => true,
    'appContentHide' => true,
    'appClass' => 'app-full-height app-without-header p-0'
])

@section('title', 'Login')

@section('content')
    <!-- BEGIN login -->
    <div class="login">
        
       
        <!-- BEGIN login-content -->
        <div class="login-content">
           
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1 class="text-center">Sign In</h1>
                @if(session()->has('message'))
                    <div class="alert alert-danger">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="text-white text-opacity-50 text-center mb-4">
                    For your protection, please verify your identity.
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    {{-- <input type="text" class="form-control form-control-lg bg-white bg-opacity-5" value="" placeholder="" /> --}}
                    <input id="email" type="text" class="form-control form-control-lg bg-white bg-opacity-5 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        @if (Route::has('password.request'))
                            <a class="ms-auto text-white text-decoration-none text-opacity-50" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>
                    {{-- <input type="password" class="form-control form-control-lg bg-white bg-opacity-5" value="" placeholder="" /> --}}
                    <input id="password" type="password" class="form-control form-control-lg bg-white bg-opacity-5 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
                <div class="text-center text-white text-opacity-50">
                    Don't have an account yet? <a href="{{ route('register') }}">Sign up</a>.
                </div>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection

