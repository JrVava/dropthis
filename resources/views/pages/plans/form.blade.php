@extends('layout.default')

@section('title', 'Plan')
@push('css')
    <link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="/assets/plugins/summernote/dist/summernote-lite.min.js"></script>
    <script>
        var $editor = $('.description');
        $editor.summernote({
            callbacks: {
                onPaste(e) {
                const bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
                }
            },
            height: 200,
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
                <li class="breadcrumb-item"><a href="{{ route('plans') }}">Plan</a></li>
                <li class="breadcrumb-item active">{{ isset($plan->id) ? 'Edit' : 'Add' }} Plan</li>

            </ul>

            <h1 class="page-header">
                {{ isset($plan->id) ? 'Edit' : 'Add' }} Plan
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('plan.save') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($plan->id)){{ $plan->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Plan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="plan_name" name="plan_name" placeholder="Plan" value="@if(isset($plan->plan_name)){{ $plan->plan_name }}@else{{ old('plan_name') }}@endif">
                                    @if ($errors->has('plan_name'))
                                        <span class="text-danger">{{ $errors->first('plan_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">No of creadit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="no_of_credits" name="no_of_credits" placeholder="No of creadit" value="@if(isset($plan->no_of_credits)){{ $plan->no_of_credits }}@else{{old('no_of_credits')}}@endif">
                                    @if ($errors->has('no_of_credits'))
                                        <span class="text-danger">{{ $errors->first('no_of_credits') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Currency</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="currency">
                                        <option value="">Select Currency</option>
                                        <option value="USD" @if(isset($plan->currency) && $plan->currency == 'USD') selected @endif>USD</option>
                                        <option value="CAD" @if(isset($plan->currency) && $plan->currency == 'CAD') selected @endif>CAD</option>
                                        <option value="EUR" @if(isset($plan->currency) && $plan->currency == 'EUR') selected @endif>EUR</option>
                                        <option value="GBP" @if(isset($plan->currency) && $plan->currency == 'GBP') selected @endif>GBP</option>
                                    </select>
                                    @if ($errors->has('currency'))
                                        <span class="text-danger">{{ $errors->first('currency') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="price" name="price" placeholder="price" value="@if(isset($plan->price)){{ $plan->price }}@else{{old('price')}}@endif">
    								</div>
                                    @if ($errors->has('price'))
                                        <span class="text-danger">{{ $errors->first('price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-sm-2 col-form-label">
                                    Description
                                </label>
                                <div class="col-sm-10 card" style="z-index: 1020;">
                                    <textarea name="description" class="description" id="description" title="Description">
                                        @if(isset($plan->description))
                                            {{ $plan->description }}
                                        @else
                                        {{old('description')}}
                                        @endif
                                    </textarea>                                    
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>               
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-10">               
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('price') }}</span>
                                    @endif
                                </div>
                            </div> 
                            
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="status" name="status" @if(isset($plan->status) && $plan->status == 1) {{ $plan->status }} checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Plan</button>
                                    <a href="{{ route('plans') }}" class="btn btn-outline-warning">Cancel</a>
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