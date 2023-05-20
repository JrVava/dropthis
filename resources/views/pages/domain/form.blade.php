@extends('layout.default')

@section('title', 'Domain')

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="/assets/js/demo/highlightjs.demo.js"></script>
    <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script>
@endpush

@section('content')
    <!-- BEGIN row -->
    <div class="row">
        <!-- BEGIN col-9 -->
        <div class="col-xl-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('domains') }}">Domains</a></li>
                <li class="breadcrumb-item active">@if(isset($actionType) && $actionType == 'edit') Edit Domain @else Add Domain @endif</li>
            </ul>

            <h1 class="page-header">
               @if(isset($actionType) && $actionType == 'edit') Edit Domain @else Add Domain @endif
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4>
                <p>If you want to have <code>&lt;input readonly&gt;</code> elements in your form styled as plain text, use the .form-control-plaintext class to remove the default form field styling and preserve the correct margin and padding.</p> -->
                <div class="card">
                    <div class="card-body">
                        @if(isset($actionType) && $actionType == 'edit')
                        <form method="post" action="{{ route('domains.update') }}">
                        @else
                        <form method="post" action="{{ route('domains.add') }}">
                        @endif
                        	@csrf
                        @if(isset($actionType) && $actionType == 'edit')
                            <input type="hidden" value="{{ $domain->id }}" name="id">
                        @endif
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Domain</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="host" name="host" placeholder="Domain" value="@if(isset($actionType) && $actionType == 'edit'){{$domain->host}}@else {{old('host')}} @endif">
									@if ($errors->has('host'))
									<span class="text-danger">{{ $errors->first('host') }}</span>
									@endif
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                               <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="status" name="status" value="1" @if(isset($domain->status) && $domain->status ==1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Domain</button>
                                    <a href="{{ route('domains') }}" class="btn btn-outline-warning">Cancel</a>
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