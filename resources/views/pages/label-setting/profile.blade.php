
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
<!-- BEGIN col-9 -->
    {{-- <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ul>--}}
    <div id="general" class="mb-5">
        <h4>
            <i class="bi bi-person-circle fa-fw text-theme"></i>  Profile <!-- <small>page header description goes here...</small> -->
        </h4> 
        <p>View and update your Profile information and settings.</p>
        
        <!-- BEGIN #readonlyPlainText -->
        <div id="readonlyPlainText" class="mb-5">
            <!-- <h4>Readonly plain text</h4>
            <p>If you want to have <code>&lt;input readonly&gt;</code> elements in your form styled as plain text, use the .form-control-plaintext class to remove the default form field styling and preserve the correct margin and padding.</p> -->
            <div class="card">
                <form method="post" action="{{ route('profile.save') }}" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="@if(isset($setting->id)){{ $setting->id }}@endif" name="id">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <div class="text-inverse text-opacity-70 d-flex align-items-center">Name</div>
                            </div>
                            <div class="flex-1">
                                <input type="text" class="form-control plaintext" id="name" name="name" placeholder="Name" value="@if(isset($authDetails->name)){{ $authDetails->name }}@else{{old('name')}}@endif">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <div class="text-inverse text-opacity-70 d-flex align-items-center" for="follow">Website</div>
                            </div>
                            <div class="flex-1">
                                <input type="text" class="form-control plaintext" id="website" name="website" placeholder="Website" value="@if(isset($authDetails->website)){{ $authDetails->website }}@else{{old('website')}}@endif">
                                @if ($errors->has('website'))
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <label class="form-check-label" for="sponsored">Logo</label>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" class="form-control" id="logo" />
                                <small class="form-text text-muted">Supported file types: jpg, jpeg, png, svg</small>
                                <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                @if ($errors->has('logo'))
                                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                                @endif
                                @if($authDetails->logo != NULL)
                                    <div class="col-sm-5">
                                        <input type="hidden" value="{{ $authDetails->logo }}" name="old_logo">
                                        <img src="{{ getFileFromStorage($path.$authDetails->logo) }}" alt=""  width="50" height="50">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <div class="text-inverse text-opacity-70 d-flex align-items-center">Background</div>
                            </div>
                            <div class="flex-1">
                                <input type="text" value="@if(isset($authDetails->bg_color)){{ $authDetails->bg_color }}@else #007aff @endif" class="form-control" id="colorpicker" name="bg_color" value="{{ $authDetails->bg_color }}" />
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <div class="text-inverse text-opacity-70 d-flex align-items-center">Image</div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="bg_image" class="form-control" id="bg_image" />
                                <small class="form-text text-muted">Supported file types: jpg, jpeg, png, svg</small>
                                <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                @if($authDetails->bg_image != NULL)
                                <div class="col-sm-2">
                                    <input type="hidden" value="{{ $authDetails->bg_image }}" name="old_bg_image">
                                    <img src="{{ getFileFromStorage($path.$authDetails->bg_image) }}" alt=""  width="50" height="50">
                                </div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="d-flex align-items-center mb-md-3 mb-2">
                                    <div class="ms-auto">
                                        <button type="submit" class="btn btn-outline-theme">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END #readonlyPlainText -->
       