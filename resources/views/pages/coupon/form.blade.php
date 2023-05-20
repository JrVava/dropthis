@extends('layout.default')

@section('title', 'Coupon')
@push('css')
    <link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    {{-- <script src="/assets/js/demo/highlightjs.demo.js"></script>
    <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script> --}}
    <script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/plugins/select-picker/dist/picker.min.js"></script>
    <script>
        $('#start_date').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: new Date(),
        });

        
        $('#expiry_date').datepicker({
            autoclose: true,
            startDate: new Date(),
            format: "yyyy-mm-dd",
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
                <li class="breadcrumb-item"><a href="{{ route('coupons') }}">Coupon</a></li>
                <li class="breadcrumb-item active">{{ isset($coupon->id) ? 'Edit' : 'Add' }} Coupon</li>

            </ul>

            <h1 class="page-header">
                {{ isset($coupon->id) ? 'Edit' : 'Add' }} Coupon
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('coupon.save') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($coupon->id)){{ $coupon->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Coupon code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="code" name="code" placeholder="Coupon code" value="@if(isset($coupon->code)){{ $coupon->code }}@else{{ old('code') }}@endif">
                                    @if ($errors->has('code'))
                                        <span class="text-danger">{{ $errors->first('code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">No of creadit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="no_of_credits" name="no_of_credits" placeholder="No of creadit" value="@if(isset($coupon->no_of_credits)){{ $coupon->no_of_credits }}@else{{old('no_of_credits')}}@endif">
                                    @if ($errors->has('no_of_credits'))
                                        <span class="text-danger">{{ $errors->first('no_of_credits') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Start Date</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="start_date" name="start_date" placeholder="Start Date" value="@if(isset($coupon->start_date)){{ $coupon->start_date }}@elseif(old('start_date')){{old('start_date')}} @else{{date("Y-m-d")}}@endif">
    								</div>
                                    @if ($errors->has('start_date'))
                                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Expire Date</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="expiry_date" name="expiry_date" placeholder="Expiry Date" value="@if(isset($coupon->expiry_date)){{ $coupon->expiry_date }}@else{{old('expiry_date')}}@endif">
    								</div>
                                    @if ($errors->has('expiry_date'))
                                        <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">One Time Use</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="one_time_use" name="one_time_use" @if(isset($coupon->one_time_use) && $coupon->one_time_use == 1) {{ $coupon->one_time_use }} checked @elseif(old('one_time_use')) checked @endif>
                                    </div>
                                </div>
                            </div>                          
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="status" name="status" @if(isset($coupon->status) && $coupon->status == 1) {{ $coupon->status }} checked @elseif(old('status')) checked  @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Coupon</button>
                                    <a href="{{ route('coupons') }}" class="btn btn-outline-warning">Cancel</a>
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