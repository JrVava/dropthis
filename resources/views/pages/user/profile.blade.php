@extends('layout.default')

@section('title', 'Profile')
@push('css')
<link href="/assets/plugins/spectrum-colorpicker2/dist/spectrum.min.css" rel="stylesheet" />
@endpush
@push('js')
<script src="/assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<script>
    $('#colorpicker').spectrum({
      "showInput": true
    });
  </script>
@endpush
@section('content')
    <!-- BEGIN row -->
    <div class="row">
        <!-- BEGIN col-9 -->
        <div class="col-xl-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ul>

            <h1 class="page-header">
                Profile <!-- <small>page header description goes here...</small> -->
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
                        <form method="post" action="{{ route('profile.save') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="@if(isset($setting->id)){{ $setting->id }}@endif" name="id">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="name" name="name" placeholder="Name" value="@if(isset($authDetails->name)){{ $authDetails->name }}@else{{old('name')}}@endif">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="follow">Website</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="website" name="website" placeholder="Website" value="@if(isset($authDetails->website)){{ $authDetails->website }}@else{{old('website')}}@endif">
                                    @if ($errors->has('website'))
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    @endif
                                </div>
                                
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="sponsored">Logo</label>
                                </div>
                                <div class="col-sm-@if($authDetails->logo == NULL){{ 10 }}@else{{ 5 }}@endif">
                                    <input type="file" name="logo" class="form-control" id="logo" />
                                    <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                </div>
                                @if($authDetails->logo != NULL)
                                    <div class="col-sm-5">
                                        <input type="hidden" value="{{ $authDetails->logo }}" name="old_logo">
                                        <img src="{{ getFileFromStorage($path.$authDetails->logo) }}" alt=""  width="50" height="50">
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="parameter_forward">Background</label>
                                </div>
                                <div class="col-sm-@if($authDetails->bg_image == NULL){{ 5 }}@else{{ 4 }}@endif">
                                    <input type="text" value="@if(isset($authDetails->bg_color)){{ $authDetails->bg_color }}@else #007aff @endif" class="form-control" id="colorpicker" name="bg_color" value="{{ $authDetails->bg_color }}" />
                                </div>

                                <div class="col-sm-@if($authDetails->bg_image == NULL){{ 5 }}@else{{ 4 }}@endif">
                                    <input type="file" name="bg_image" class="form-control" id="bg_image" />
                                    <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                </div>
                                @if($authDetails->bg_image != NULL)
                                <div class="col-sm-2">
                                    <input type="hidden" value="{{ $authDetails->bg_image }}" name="old_bg_image">
                                    <img src="{{ getFileFromStorage($path.$authDetails->bg_image) }}" alt=""  width="50" height="50">
                                </div>
                                @endif
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