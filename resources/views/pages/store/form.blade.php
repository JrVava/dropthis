@extends('layout.default')

@section('title', 'Store')
@push('css')
    <link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />
@endpush
@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="/assets/js/demo/highlightjs.demo.js"></script>
    <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script>
    <script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/plugins/select-picker/dist/picker.min.js"></script>
    <script>
  $('#datepicker').datepicker({
    autoclose: true,
    startDate: new Date()
  });
  $('#groups').picker({search : true});
    $(document).ready(function(){
    $('#rules').on('change',function(){
        var changeRules = this.value;
        var baseUrl = "{{ url('/') }}";
        if(changeRules == 'any' || changeRules == baseUrl){
            $('#defaultUrl').text(baseUrl+"/");
        }else{
            $('#defaultUrl').text(changeRules+"/");
        }
    })
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
                <li class="breadcrumb-item"><a href="{{ route('stores') }}">Store</a></li>
                <li class="breadcrumb-item active">{{ isset($store->id) ? 'Edit' : 'Add' }} Store</li>

            </ul>

            <h1 class="page-header">
                {{ isset($store->id) ? 'Edit' : 'Add' }} Store
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                        
                        <form method="post" action="@if(Route::currentRouteName() == 'store.create'){{ route('store.save') }}@else{{ route('store.update')}}@endif" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($store->id)){{ $store->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Store Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="storename" name="storename" placeholder="Store Name" value="@if(isset($store->storename)){{ $store->storename }}@else{{ old('storename') }}@endif">
                                    @if ($errors->has('storename'))
                                        <span class="text-danger">{{ $errors->first('storename') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Light Logo</label>
                                <div class="@if(isset($store->light_logo)) col-sm-8 @else col-sm-10 @endif">
                                    <div class="image-field">
                                        <input type="file" class="form-control" name="light_logo">
                                        <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                        <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                        @if ($errors->has('light_logo'))
                                            <br/><span class="text-danger">{{ $errors->first('light_logo') }}</span>
                                        @endif
                                    </div>
                                    @if(isset($store->light_logo)) 
                                        <div class="col-sm-2">
                                            <input type="hidden" name="old_light_logo" value="{{ $store->light_logo }}">
                                            <img src="{{ $store->light_logo }}" alt="{{ $store->light_logo }}" width="300" height="70">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Dark Logo</label>
                                <div class="@if(isset($store->dark_logo)) col-sm-8 @else col-sm-10 @endif">
                                    <div class="image-field">
                                        <input type="file" class="form-control" name="dark_logo">
                                        <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                        <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                        @if ($errors->has('dark_logo'))
                                            <br/><span class="text-danger">{{ $errors->first('dark_logo') }}</span>
                                        @endif
                                    </div>
                                    @if(isset($store->dark_logo)) 
                                        <div class="col-sm-2">
                                            <input type="hidden" name="old_dark_logo" value="{{ $store->dark_logo }}">
                                            <img src="{{ $store->dark_logo }}" alt="{{ $store->dark_logo }}" width="300" height="70">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Base URL</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="base_url" name="base_url" placeholder="Base URL" value="@if(isset($store->base_url)){{ $store->base_url }}@else{{ old('base_url') }}@endif">
                                    @if ($errors->has('base_url'))
                                        <span class="text-danger">{{ $errors->first('base_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Store</button>
                                    <a href="{{ route('stores') }}" class="btn btn-outline-warning">Cancel</a>
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