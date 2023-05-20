@extends('layout.default')

@section('title', 'Setup')
@section('content')
    <!-- BEGIN row -->
    <div class="row">
        <!-- BEGIN col-9 -->
        <div class="col-xl-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Setup</li>
            </ul>

            <h1 class="page-header">
                Setup <!-- <small>page header description goes here...</small> -->
            </h1>

            <hr class="mb-4" />
			@if (session('status'))
				<div class="alert alert-success alert-dismissable fade show p-3 d-flex">
					<div class="flex-fill">{{ session('status') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
				</div>
			@endif
            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4>
                <p>If you want to have <code>&lt;input readonly&gt;</code> elements in your form styled as plain text, use the .form-control-plaintext class to remove the default form field styling and preserve the correct margin and padding.</p> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('setting.update') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" value="@if(isset($setting->id)){{ $setting->id }}@endif" name="id">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Redirection</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="redirection">
                                        <option value="307" @if(isset($setting->redirect_type) && $setting->redirect_type == 307) selected @endif>307 (Temporary)</option>
                                        <option value="302" @if(isset($setting->redirect_type) && $setting->redirect_type == 302) selected @endif>302 (Temporary)</option>
                                        <option value="301" @if(isset($setting->redirect_type) && $setting->redirect_type == 301) selected @endif>301 (Permanent)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="follow">No Follow</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="follow" name="follow" @if(isset($setting->nofollow) && $setting->nofollow == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="sponsored">Sponsored</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="sponsored" name="sponsored" @if(isset($setting->sponsored) && $setting->sponsored == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="parameter_forward">Parameter Forwarding</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="parameter_forward" name="parameter_forward" @if(isset($setting->params_forwarding) && $setting->params_forwarding == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="tracking">Tracking</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="tracking" name="tracking" @if(isset($setting->track_me) && $setting->track_me == 1) checked @endif>
                                      
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Changes</button>
                                    <a href="{{ route('home') }}" class="btn btn-outline-warning">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            <!-- END #readonlyPlainText -->
        </div>
        <!-- END col-9-->
    </div>
    <!-- END row -->
@endsection