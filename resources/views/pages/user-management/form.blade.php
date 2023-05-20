@extends('layout.default')

@section('title', 'User')
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
                <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ isset($user->id) ? 'Edit' : 'Add' }} User</li>

            </ul>

            <h1 class="page-header">
                {{ isset($user->id) ? 'Edit' : 'Add' }} User
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="@if(isset($user->id)){{ route('user.update') }}@else{{ route('user.save') }}@endif" autocomplete="off"  enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($user->id)){{ $user->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="name" name="name" placeholder="Name" value="@if(!empty(old('name'))){{ old('name') }}@elseif(isset($user->name)){{ $user->name }}@endif">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">                                    
                                    <input type="text" class="form-control plaintext" id="email" name="email" placeholder="Email" value="@if(!empty(old('email'))){{old('email')}}@elseif(isset($user->email)){{ $user->email }}@endif">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control plaintext" id="password" name="password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control plaintext" id="cpassword" name="cpassword" placeholder="Confirm Password">
                                    @if ($errors->has('cpassword'))
                                        <span class="text-danger">{{ $errors->first('cpassword') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="follow">Website</label>
                                </div>
                                <div class="col-sm-10">
                                    
                                    <input type="text" class="form-control plaintext" id="website" name="website" placeholder="Website" value="@if(!empty(old('website'))){{ old('website') }}@elseif(isset($user->website)){{ $user->website }}@endif">
                                    @if ($errors->has('website'))
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    @endif
                                </div>                                
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="sponsored">Logo</label>
                                </div>
                                <div class="col-sm-@if(isset($user->logo) && $user->logo == NULL){{ 10 }}@else{{ 5 }}@endif">
                                    <input type="file" name="logo" class="form-control" id="logo" />
                                    <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                    @if ($errors->has('logo'))
                                        <span class="text-danger">{{ $errors->first('logo') }}</span>
                                    @endif
                                </div>
                                @if(isset($user->logo) && $user->logo != NULL)
                                    <div class="col-sm-5">
                                        <input type="hidden" value="{{ $user->logo }}" name="old_logo">
                                        <img src="{{ getFileFromStorage($path.$user->logo) }}" alt=""  width="50" height="50">
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="parameter_forward">Background</label>
                                </div>
                                <div class="col-sm-@if(isset($user->bg_image) && $user->bg_image == NULL){{ 5 }}@else{{ 4 }}@endif">                                    
                                    <input type="text" value="@if(!empty(old('bg_color'))){{ old('bg_color') }}@elseif(isset($user->bg_color)){{ $user->bg_color }}@else{{'#007aff'}}@endif" class="form-control" id="colorpicker" name="bg_color"/>
                                </div>

                                <div class="col-sm-@if(isset($user->bg_image) && $user->bg_image == NULL){{ 5 }}@else{{ 4 }}@endif">
                                    <input type="file" name="bg_image" class="form-control" id="bg_image" />
                                    <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                </div>
                                @if(isset($user->bg_image) && $user->bg_image != NULL)
                                <div class="col-sm-2">
                                    <input type="hidden" value="{{ $user->bg_image }}" name="old_bg_image">
                                    <img src="{{ getFileFromStorage($path.$user->bg_image) }}" alt=""  width="50" height="50">
                                </div>
                                @endif
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="follow">Credits</label>
                                </div>
                                <div class="col-sm-10">
                                    
                                    <input type="text" class="form-control plaintext" id="credits" name="credits" placeholder="credits" value="@if(!empty(old('credits'))){{ old('credits') }}@elseif(isset($user->credits)){{ $user->credits }}@endif">
                                    @if ($errors->has('credits'))
                                        <span class="text-danger">{{ $errors->first('credits') }}</span>
                                    @endif
                                </div>                                
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Can Submit Feedbacks</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">                                        
                                      <input type="checkbox" class="form-check-input" id="can_submit_feedbacks" name="can_submit_feedbacks" @if(isset($user->can_submit_feedbacks) && $user->can_submit_feedbacks == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status_approve">Approve user Status</label>
                                </div>
                                
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">                                        
                                      <input type="checkbox" class="form-check-input" id="user_status" name="user_status" @if(isset($user->status) && $user->status == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save User</button>
                                    <a href="{{ route('users') }}" class="btn btn-outline-warning">Cancel</a>
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