@extends('layout.default')

@section('title', 'Email')
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
                <li class="breadcrumb-item"><a href="{{ route('emails') }}">Emails</a></li>
                <li class="breadcrumb-item active">{{ isset($emailGroup->id) ? 'Edit' : 'Add' }} Email</li>

            </ul>

            <h1 class="page-header">
                {{ isset($emailGroup->id) ? 'Edit' : 'Add' }} Email
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('email.add') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($emailGroup->id)){{ $emailGroup->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Artist</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="artist" name="artist" placeholder="Artist" value="@if(isset($emailGroup->artist)){{ $emailGroup->artist }}@else{{ old('artist') }}@endif">
                                    @if ($errors->has('artist'))
                                        <span class="text-danger">{{ $errors->first('artist') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">E-Mail</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="email" name="email" placeholder="E-Mail" value="@if(isset($emailGroup->email)){{ $emailGroup->email }}@else{{old('email')}}@endif">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Group</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="group" name="group" placeholder="Group" value="@if(isset($emailGroup->group)){{ $emailGroup->group }}@else{{old('group')}}@endif">
    								</div>
                                    @if ($errors->has('group'))
                                        <span class="text-danger">{{ $errors->first('group') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="status" name="status" @if(isset($emailGroup->status) && $emailGroup->status == 1) {{ $emailGroup->status }} checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Link</button>
                                    <a href="{{ route('emails') }}" class="btn btn-outline-warning">Cancel</a>
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