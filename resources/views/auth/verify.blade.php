@extends('layout.default', [
    'bodyClass' => 'pace-top',
    'appHeaderHide' => true,
    'appSidebarHide' => true,
    'appContentHide' => true,
    'appClass' => 'app-full-height app-without-header p-0'
])

@section('title', 'Verify Email')

@section('content')
 <!-- BEGIN login -->
    <div class="register">
        <!-- BEGIN login-content -->
        <div class="register-content">
            <h1 class="text-center">{{ __('Verify Your Email Address') }}</h1>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <div class="text-white text-opacity-50 text-center mb-4">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </div>
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">{{ __('click here to request another') }}</button>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection