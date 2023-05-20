@extends('layout.default')
@section('title', 'Link')
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
                <li class="breadcrumb-item"><a href="{{ route('links') }}">Links</a></li>
                <li class="breadcrumb-item active">Edit Link</li>
            </ul>

            <h1 class="page-header">
                Edit Link <!-- <small>page header description goes here...</small> -->
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4>
                <p>If you want to have <code>&lt;input readonly&gt;</code> elements in your form styled as plain text, use the .form-control-plaintext class to remove the default form field styling and preserve the correct margin and padding.</p> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('link.update') }}">
                            @csrf
                            <input type="hidden" value="{{ $link->id }}" name="id">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="title" name="title" placeholder="Title" value="{{ $link->name }}">
                                    @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Target URL</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="target_url" name="target_url" placeholder="Target URL" value="{{ $link->url }}">
                                    @if ($errors->has('target_url'))
                                        <span class="text-danger">{{ $errors->first('target_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Short URL</label>
                                <div class="col-sm-10">
									<div class="input-group">
										<label class="input-group-text" id="defaultUrl">
											@if(!empty($link->rules) && (isset(unserialize($link->rules)[1]) && url('/') == unserialize($link->rules)[1] || unserialize($link->rules)[1] == 'any'))
                                                {{ url('')."/" }}
                                            @else
                                                @if(!empty($link->rules))
                                                    {{ unserialize($link->rules)[1] }}
                                                @else
                                                    {{ url('')."/" }}
                                                @endif
                                            @endif
										</label>
										<input type="text" class="form-control plaintext" id="short_url" name="short_url" placeholder="Short URL" value="{{ $link->slug }}">
									</div>
                                    @if ($errors->has('short_url'))
                                        <span class="text-danger">{{ $errors->first('short_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Redirection</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="redirection">
                                        <option value="307" @if($link->redirect_type == 307) selected @endif>307 (Temporary)</option>
                                        <option value="302" @if($link->redirect_type == 302) selected @endif>302 (Temporary)</option>
                                        <option value="301" @if($link->redirect_type == 301) selected @endif>301 (Permanent)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Domain</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="rules" id="rules">
                                        @if(count($domains) > 0)
                                            <option value="any"> All my domains </option>
                                        @endif
                                            <option value="{{ url('/') }}" @if(isset(unserialize($link->rules)[1]) && url('/') == unserialize($link->rules)[1]) selected @endif>{{ url('/') }}</option>
                                        @if(count($domains) > 0)
                                            @foreach($domains as $domain)
                                                @if($domain->host != url('/'))
                                                    <option value="{{ $domain->host }}" @if(isset(unserialize($link->rules)[1]) && $domain->host == unserialize($link->rules)[1]) selected @endif>{{ $domain->host }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label" for="exampleFormControlSelect2">Groups</label>
                                <div class="col-sm-10">
                                	@php
                                		$oldSelectedValue = array();
                                		foreach ($link->linksGroup as $oldSelect) {
                                			$oldSelectedValue[] = $oldSelect->group_id;	
                                		}
                                	@endphp
                                    <select class="form-control" id="groups" multiple name="groups[]">
                                    	@foreach($group as $grpVal)
                                        	<option value="{{ $grpVal->id }}" @if(in_array($grpVal->id,$oldSelectedValue)) selected @endif>{{ $grpVal->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('groups'))
                                        <span class="text-danger">{{ $errors->first('groups') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="follow">No Follow</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="follow" name="follow" @if($link->nofollow == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="sponsored">Sponsored</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="sponsored" name="sponsored"   @if($link->sponsored == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="parameter_forward">Parameter Forwarding</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="parameter_forward" name="parameter_forward" @if($link->params_forwarding == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="tracking">Tracking</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="tracking" name="tracking" @if($link->track_me == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                            	<label for="staticEmail" class="col-sm-2 col-form-label">Expiration Date</label>
                            	<div class="col-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="expiration" id="datepicker" placeholder="Expiration Date" value="{{ $link->expires_at }}" autocomplete="off" readonly onclick="this.removeAttribute('readOnly');" />
											<label class="input-group-text" for="datepicker">
											<i class="fa fa-calendar"></i>
										</label>
									</div>
                                    @if ($errors->has('expiration'))
                                        <span class="text-danger">{{ $errors->first('expiration') }}</span>
                                    @endif
								</div>
							</div>
							<div class="form-group row mb-3">
                            	<label for="staticEmail" class="col-sm-2 col-form-label">Password</label>
                            	<div class="col-sm-10">
									<div class="input-group">
										<input type="text" class="form-control plaintext" id="password" value="{{ $link->password }}" name="password" placeholder="Password" autocomplete="off" readonly onclick="this.removeAttribute('readOnly');">
									</div>
								</div>
							</div>
                            <div class="form-group row mb-2">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Notes</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="notes" placeholder="Notes">{{ $link->description }}</textarea>
                                    @if ($errors->has('notes'))
                                    <span class="text-danger">{{ $errors->first('notes') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Link</button>
                                    <a href="{{ route('links') }}" class="btn btn-outline-warning">Cancel</a>
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