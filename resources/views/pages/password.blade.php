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
            <form method="post" action="{{ route('link-post',['slug'=>$slug]) }}">
                @csrf
                @if(session()->has('error'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <h1 class="text-center">Link Password</h1>
                <div class="text-white text-opacity-50 text-center mb-4">
                    This confidential content is protected by a password.
                </div>
                <div class="mb-3">
                    <input id="password" type="text" class="form-control form-control-lg bg-white bg-opacity-5 @error('password') is-invalid @enderror" name="password" autocomplete="off">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Submit</button>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->
@endsection

