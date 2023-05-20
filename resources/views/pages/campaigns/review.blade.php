@extends('layout.default')

@section('title', 'Review')
@push('css')

<link href="/assets/plugins/bootstrap-slider/dist/css/bootstrap-slider.min.css" rel="stylesheet" />
<link href="/css/rateit.css" rel="stylesheet" />
<link href="/css/custom.css" rel="stylesheet" />
	<style>
		.song-active {
			pointer-events:none;
		}
		.tooltip-inner {
			white-space:pre;
			/* display: block; */
			max-width: none;
		}
		.canvas-block {
			padding-left: 0 !important;
		}
		.campaign-description{
			padding-bottom:20px;
		}
		@media screen and (max-width: 768px) {
			.canvas-block {
			padding-left: 15px !important;
		}
		
		i.custom-play-btn {
			border: 0 !important
		}
		}
	</style>
@endpush

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="/assets/plugins/bootstrap-slider/dist/bootstrap-slider.min.js"></script>
    {{-- Rateit plugin use below --}}
    <script src="/script/jquery.rateit.js"></script>
    {{-- Wave surfer lib Downloaded In public/assets/js--}}
    <script src="/assets/js/wavesurfer.min.js"></script>    
	
	<script src="/script/custom.js"></script>

	<script>
		$(document).ready(function(){
			$('.app-theme-cover-item').removeClass('active');
			$('.review-block').css('display','');
			var bg_url = "{{ getFileFromStorage($userProfilePath) }}";
			var bg_color = "{{ $userBgColor }}";
			if(bg_url){
				$('html').attr("class", "");
				$('html').addClass('bg-cover-6');
				$("body").append('<style>.bg-cover-6:after{background-image: url("' + bg_url + '")}</style>');
			}else if(bg_color){
				$("body").append('<style>html:after{background: ' + bg_color + '}</style>');
			}
			$('.review-block').addClass('active');

			$('.download-mp3').click(function(){
				$(this).prev('.mp3-download-form').submit();
			});
			$('.download-wav').click(function(){
				$(this).next().next('.wav-download-form').submit();
			});
			$('.download-zip').click(function(){
				$(this).prev('.zip-download-form').submit();
			});
			//$("[data-toggle=tooltip]").tooltip();
		});
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		});
	</script>
@endpush

@section('content')

<!-- BEGIN container -->
<div class="row justify-content-center">
	<!-- BEGIN col-9 -->
	<div class="col-md-@if(isset($pass_key) && $pass_key != ''){{ 6 }}@else{{ 9 }}@endif">
		<ul class="breadcrumb">
			@if(isset(auth::user()->id))
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="{{ route('campaigns') }}">Sendouts</a></li>			
			<li class="breadcrumb-item active">Review</li>
			@endif
		</ul>
		@if (session('error'))
			<div class="alert alert-danger alert-dismissable fade show p-3 d-flex">
				<div class="flex-fill">{{ session('error') }}</div>
				<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
			</div>
		@endif
		@if (session('status'))
			<div class="alert alert-success alert-dismissable fade show p-3 d-flex">
				<div class="flex-fill">{{ session('status') }}</div>
				<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
			</div>
		@endif
		<div class="d-flex align-items-center mb-1">
			<div class="flex-fill text-center">
				<h4 class="mb-0">
                    {{ $campaigns->release_number }} <small>{{ $campaigns->label }}</small>
                  </h4>
            </div>
		</div>
		<hr class="mb-4" />
		<div class="card p-3">
			<div class="row mb-3">
				<div class="col-xl-8 campaign-description">
					<div class="form-check mb-2px">
						{!! str_replace('white-space: pre;',"",$campaigns->description) !!}
					</div>
				</div>
				<div class="col-xl-4">
					<img class="border border-2" src="{{ getFileFromStorage($campaignPath.'/'.$campaigns->cover_artwork) }}" width="100%">
				</div>
			</div>
			<!-- BEGIN #formControls -->
			<div id="formControls" class="mb-4">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="d-flex align-items-center mb-1">
								<div class="flex-fill">
									<div class="form-group row mb-2">
										<label>
											Please use the audio player to preview.
										</label>
									</div>
									<div class="card audio-record-block">
										<div class="wave" data-path={{ getFileFromStorage($campaignPath.'/'.$track->mp3_audio) }} data-peak="{{ $track->mp3_peak }}">
											<div class="row">
												<div class="col-md-2">
													<div class="spinner-border d-none">	
													</div>
                                                    <i class="fas fa-lg fa-fw fa-play text-inverse custom-play-btn" onclick="wavesurfer.playPause()" id="play-pause"></i>
                                                </div>
												<div class="col-md-10 canvas-block px-3">
													<div class="d-flex align-items-center mb-1 audio-counter">
														<div class="flex-fill">
															<label class="form-check-label text-inverse wave-song-title">{{ $track->track }} [{{ $campaigns->label }}]</label>
														</div>
														<div class="ms-auto">
															<label class="form-label mb-0 text-inverse" id="duration-remaining"> 00:00 </label>
															<label class="form-label mb-0 text-inverse">/</label>
															<label class="form-label mb-0 text-inverse" id="duration-total"> 00:00 </label>
														</div>
													</div>
													<div class="wave-container ">
														<div class="progress" id="progress"  style="display: none; position: relative;
														top: 30%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
																<span class="progressBar"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="volume-controller form-check form-check-inline">
												<div class="row">
													<div class="col-md-3 text-start">
														<label class="text-inverse">Track name</label>
													</div>
													<div class="col-md-6 text-center">
														<div>
															<label>VOLUME</label> <span id="current-volume"></span><i class="fas fa-lg fa-fw me-2 fa-volume-down text-inverse" id="mute-unmute"></i>
															<input type="text" id="volume" class="form-control" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3" />
															<i class="fas fa-lg fa-fw ms-2 fa-volume-up text-inverse"></i> </div> {{--
														<input type="range" id="volume" min="1" max="10" class="text-inverse"> --}} </div>
													<div class="col-md-3 text-end">
														<label class="text-inverse"> Download </label>
														{{-- <i class="fas fa-lg fa-fw me-2 fa-flag text-inverse"></i> --}}
													</div>
												</div>
											</div>
										</div>

										<div class="card-arrow">
											<div class="card-arrow-top-left"></div>
											<div class="card-arrow-top-right"></div>
											<div class="card-arrow-bottom-left"></div>
											<div class="card-arrow-bottom-right"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex align-items-center mb-1">
								<div class="flex-fill">
									<label class="form-check-label text-inverse">Trance Links</label>
								</div>
							</div>
							@foreach($tranceLinks as $key => $tranceLink)
							<div class="d-flex align-items-center mb-1">
								<div class="flex-fill">
									<label class="form-check-label text-inverse song-switch @if($key == 0)song-active @endif"  rel='song-switch' data-song-url='{{ getFileFromStorage($campaignPath.'/'.$tranceLink->mp3_audio) }}' data-song='{{ $tranceLink->track }} [{{ $campaigns->label }}]' data-peak-switch="{{ $tranceLink->mp3_peak }}">
										{{ $tranceLink->track }} [{{ $campaigns->label }}]
									</label>
								</div>
								<div class="ms-auto">
									{{-- <a href="{{ route('campaigns.review.download-audio',['id'=>$tranceLink->id,'audioExtension'=>'mp3','pass_key'=>$pass_key]) }}" class="btn btn-outline-link text-inverse">
										MP3
									</a> --}}
										{{-- @php 
											$filename = getFileFromStorage($campaignPath.'/'.$tranceLink->mp3_audio);
											dd($filename);
										@endphp --}}
										<form action="{{ route('campaigns.review.download-audio',['id'=>$tranceLink->id,'audioExtension'=>'mp3','pass_key'=>$pass_key]) }}" method="get" class="mp3-download-form"></form>
										<label class="btn text-inverse download-mp3" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='text-start'><b>Download MP3:</b><br/>Artist: @if(isset($campaigns->userDetails->name)){{ $campaigns->userDetails->name }}@endif<br/>Title: @if(isset($tranceLink->track)){{$tranceLink->track}}@endif<br/>Mix Name: Extended Mix<br/>Genre: @if(isset($tranceLink->track_genre)){{$tranceLink->track_genre}}@endif<br/>Track Time: @if(isset($tranceLink->mp3_time)){{$tranceLink->mp3_time}}@endif</div>">MP3</label>
										|
										<label class="btn text-inverse download-wav" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='text-start'><b>Download WAV:</b><br/>Artist:@if(isset($campaigns->userDetails->name)){{ $campaigns->userDetails->name }}@endif<br/>Title: @if(isset($tranceLink->track)){{$tranceLink->track}}@endif<br/>Mix Name: Extended Mix<br/>Genre:  @if(isset($tranceLink->track_genre)){{$tranceLink->track_genre}}@endif<br/>Track Time: @if(isset($tranceLink->wav_time)){{$tranceLink->wav_time}}@endif</div>">WAV</label>
										<i class="fas fa-lg fa-fw me-2 fa-flag"></i>
										<form action="{{ route('campaigns.review.download-audio',['id'=>$tranceLink->id,'audioExtension'=>'wav','pass_key'=>$pass_key]) }}" method="get" class="wav-download-form"></form>
									
                                    {{-- <a href=""  class="btn btn-outline-link text-inverse"> WAV </a> --}}
								</div>
							</div>
							@endforeach
						</div>
						<div class="mb-3">
							<div class="card p-3">
								<div class="form-group row mb-1">
									<div class="d-flex align-items-center">
										<div class="flex-fill">
											<label class="form-check-label ">File Name</label>
										</div>
										<div class="ms-auto">
											<label class="form-check-label">Download</label>
										</div>
									</div>
								</div>
								<hr>
								<div class="form-group row mb-3">
									<div class="d-flex align-items-center">
											<label class="form-check-label">
												{{ $track->track }} [{{ $campaigns->label }}].zip
												{{-- {{ $track->mp3_audio }} --}}
											</label>
										<div class="ms-auto">
											<form action="{{ route('campaigns.zip',['id'=>$track->campaign_id,'pass_key'=>$pass_key]) }}" method="get" class="zip-download-form"></form>
											<label class="btn btn-outline-link mb-1 download-zip">ZIP</label>
											{{-- <a href="{{ route('campaigns.zip',['id'=>$track->campaign_id,'pass_key'=>$pass_key]) }}" class="btn btn-outline-link mb-1">
												ZIP
											</a> --}}
										</div>
									</div>
								</div>
								<div class="card-arrow">
									<div class="card-arrow-top-left"></div>
									<div class="card-arrow-top-right"></div>
									<div class="card-arrow-bottom-left"></div>
									<div class="card-arrow-bottom-right"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-arrow">
						<div class="card-arrow-top-left"></div>
						<div class="card-arrow-top-right"></div>
						<div class="card-arrow-bottom-left"></div>
						<div class="card-arrow-bottom-right"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group row mb-4">
					<label> Please take the time to complete our feedback form. </label>
				</div>
			</div>
			<!-- END #formControls -->			
			<div class="mb-3">
				<div class="card p-3 @if(!$feedbackAlreadyGave) bg-dark @endif" @if(!$feedbackAlreadyGave) style="opacity: 0.5;" @endif>
					<div class="form-group row mb-1">
						<div class="d-flex align-items-center">
							<div class="flex-fill">
								<label class="form-check-label">Feedback Form</label>
							</div>
						</div>
					</div>
					<hr>
					<form action="{{ route('campaigns.track.feedback') }}" method="post">
						@csrf
						<input type="hidden" name="campaignId" value="{{ $campaigns->id }}">
						@if(isset($pass_key) && !empty($pass_key))
						<input type="hidden" name="pass_key" value="{{ $pass_key }}">
						@endif
						<div class="form-group row mb-3">
							<label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Your Name</label>
							<div class="col-sm-10">
								@php
									$disabled = '';
									$name = '';
									if(!$feedbackAlreadyGave){
										$disabled = "disabled";
										if(isset($feedback->name)){
											$name = $feedback->name;
										}elseif(isset($authDetails->name)){
											$name = $authDetails->name;
										}elseif(isset($emailGroup->artist)){
											$name = $emailGroup->artist;
										}
									}else{
										$disabled = "";
										if(isset($feedback->name)){
											$name = $feedback->name;
										}elseif(isset($authDetails->name)){
											$name = $authDetails->name;
										}elseif(isset($emailGroup->artist)){
											$name = $emailGroup->artist;
										}
									}
								@endphp
								{{-- <input type="text" class="form-control plaintext" id="name" name="name" placeholder="Your Name" @if(!$feedbackAlreadyGave) disabled value="{{ isset($feedback->name) ? $feedback->name : '' }}" @else value="@if(isset($authDetails->name)){{ $authDetails->name }}@elseif(isset($emailGroup->artist)){{ $emailGroup->artist }}@endif" @endif> --}}
								<input type="text" class="form-control plaintext" id="name" name="name" placeholder="Your Name" value="{{ $name }}" {{ $disabled }}>
								@if ($errors->has('name'))
									<span class="text-danger">{{ $errors->first('name') }}</span>
								@endif
							</div>
						</div>
						<div class="form-group row mb-3">
							<label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Your Email</label>
							<div class="col-sm-10">
								@php
									$disabled = '';
									$email = '';
									if(!$feedbackAlreadyGave){
										$disabled = "disabled";
										if(isset($feedback->email)){
											$email = $feedback->email;
										}elseif(isset($authDetails->email)){
											$email = $authDetails->email;
										}elseif(isset($emailGroup->email)){
											$email = $emailGroup->email;
										}
									}else{
										$disabled = "";
										if(isset($feedback->email)){
											$email = $feedback->email;
										}elseif(isset($authDetails->email)){
											$email = $authDetails->email;
										}elseif(isset($emailGroup->email)){
											$email = $emailGroup->email;
										}
									}
								@endphp
								{{-- <input type="email" class="form-control plaintext" id="email" name="email" placeholder="Your Email" @if(!$feedbackAlreadyGave) disabled value="{{ isset($feedback->email) ? $feedback->email : '' }}" @else value="@if(isset($authDetails->email)){{ $authDetails->email }}@elseif(isset($emailGroup->email)){{ $emailGroup->email }}@endif"  @endif> --}}
								<input type="email" class="form-control plaintext" id="email" name="email" placeholder="Your Email" value="{{ $email }}" {{ $disabled }}>
								@if ($errors->has('email'))
									<span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
							</div>
						</div>
						<div class="form-group row mb-3">
							<div class="col-sm-2">
								<label class="form-check-label" for="status">Supporting</label>
							</div>
							<div class="col-sm-10 d-flex">
								<div class="form-group mb-3">
									@php
										$supportingYes = '';
										$supportingNo = '';
										$supportingDefault = '';
										$disabled = '';
										if(!$feedbackAlreadyGave){
											$disabled = "disabled";											
											if(isset($feedback->supporting) && $feedback->supporting == 1){
												$supportingYes = "checked";
											}elseif(isset($feedback->supporting) && $feedback->supporting == 0){
												$supportingNo = "checked";
											}else{
												$supportingNo = "checked";
											}
										}else{
											$disabled = "";
											$supportingYes = "checked";
										}
									@endphp
									<div class="form-check form-check-inline">
										{{-- <input class="form-check-input" name="supporting" type="radio" id="supportingYes" value="1" @if(!$feedbackAlreadyGave) disabled {{ isset($feedback->supporting) && $feedback->supporting == 1 ? 'checked' : '' }} @else checked @endif> --}}
										<input class="form-check-input" name="supporting" type="radio" id="supportingYes" value="1" {{ $supportingYes." ".$disabled }}>
										<label class="form-check-label" for="supportingYes">Yes</label>
									</div>
									<div class="form-check form-check-inline">
										{{-- <input class="form-check-input" name="supporting" type="radio" id="supportingNo" value="0" @if(!$feedbackAlreadyGave) disabled {{ isset($feedback->supporting) && $feedback->supporting == 0 ? 'checked' : '' }} @endif> --}}
										<input class="form-check-input" name="supporting" type="radio" id="supportingNo" value="0" {{ $supportingNo." ".$disabled }} >
										<label class="form-check-label" for="supportingNo">No</label>
									</div>
								</div>
								@if ($errors->has('supporting'))
									<span class="text-danger">{{ $errors->first('supporting') }}</span>
								@endif
							</div>
						</div>
						<div class="form-group row mb-3">
							<label class="col-sm-2 col-form-label" for="exampleFormControlInput1">DJ Quote</label>
							<div class="col-sm-10">
								@php
									$disabled = '';
									$dj_quote = '';
									if(!$feedbackAlreadyGave){
										$disabled = "disabled";											
										if(isset($feedback->dj_quote)){
											$dj_quote = $feedback->dj_quote;
										}else{
											$dj_quote = 'Not for me';
										}
									}
								@endphp
								<textarea class="form-control" name="dj_quote" placeholder="DJ Quote" {{ $disabled }}>{{ $dj_quote }}</textarea>
								@if ($errors->has('dj_quote'))
									<span class="text-danger">{{ $errors->first('dj_quote') }}</span>
								@endif
							</div>
						</div>
						<div class="form-group row mb-3">
							<div class="col-sm-2">
								<label class="form-check-label" for="status">Best Mix</label>
							</div>
							<div class="col-sm-10">
								@foreach($tranceLinks as $key => $tranceLink)
								<div class="form-group mb-3">
									<div class="form-check">
										@php
											$disabled ='';
											$best_mix = '';
											if(!$feedbackAlreadyGave){
												$disabled = "disabled";
												if(isset($feedback->best_mix) && $feedback->best_mix == $tranceLink->track){
													$best_mix = 'checked';
												}
											}
										@endphp
										<input class="form-check-input" name="best_mix" type="radio" id="bestmix{{ $key }}" value="{{ $tranceLink->track }}" {{ $best_mix." ".$disabled }}>
										<label class="form-check-label" for="bestmix{{ $key }}">
											{{ $tranceLink->track }}
											{{-- ({{ $tranceLink->track_genre }}) --}}
										</label>
									</div>
								</div>
								@endforeach
								{{-- @if ($errors->has('best_mix'))
									<span class="text-danger">{{ $errors->first('best_mix') }}</span>
								@endif --}}
							</div>
						</div>
						<div class="form-group row mb-2 d-flex align-items-baseline">
							<div class="col-sm-2">
								<label class="form-check-label" for="status">Overall Rating</label>
							</div>
							<div class="col-sm-10">
                                <div class="form-group mb-3">
                                    <label class="pe-3">BAD</label>
									@for($i = 1; $i<11; $i++)
									<div class="form-check form-check-inline me-1">
										@php
											$disabled ='';
											$rating = '';
											if(!$feedbackAlreadyGave){
												$disabled = "disabled";
												if(isset($feedback->rating) && $feedback->rating == $i){
													$rating = 'checked';
												}
											}elseif($i == 6){
												$rating = 'checked';
											}
										@endphp
										{{-- <input class="form-check-input" name="rating" type="radio" id="inlineRadio{{ $i }}" value="{{ $i }}" @if(!$feedbackAlreadyGave) disabled {{ isset($feedback->rating) && $feedback->rating == $i ? 'checked' : '' }} @else @if($i == 6) checked @endif  @endif> --}}
										<input class="form-check-input" name="rating" type="radio" id="inlineRadio{{ $i }}" value="{{ $i }}" {{ $rating." ".$disabled }}>
										<label class="form-check-label" for="inlineRadio{{ $i }}">{{ $i }}</label>
									</div>
									@endfor
									<label class="ps-3">GOOD</label>
                                </div>     
								@if ($errors->has('rating'))
									<span class="text-danger">{{ $errors->first('rating') }}</span>
								@endif                   
                                {{-- <div class="form-group mb-3">
                                    <input type="hidden" name="rating" id="rating" value="6">
                                    <div  class="rateit bigstars" data-rateit-resetable="false" data-rateit-value="6"  data-rateit-ispreset="true"
                                    data-rateit-min="0" data-rateit-max="10" data-rateit-starwidth="32" data-rateit-starheight="32"></div>
                                </div> --}}
							</div>
							
						</div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
								<input type="submit" class="btn btn-outline-theme" name="sendFeedback" value="Send Feedback" @if(!$feedbackAlreadyGave) disabled @endif>
								<input type="submit" name="notForMe" class="btn btn-outline-warning" value="Not For Me" @if(!$feedbackAlreadyGave) disabled @endif>
                                {{-- <button type="submit" class="btn btn-outline-theme">Send Feedback</button>
                                <button type="submit" class="btn btn-outline-warning">Not For Me</button> --}}
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
			{{-- @endif --}}
			<div class="card-arrow">
				<div class="card-arrow-top-left"></div>
				<div class="card-arrow-top-right"></div>
				<div class="card-arrow-bottom-left"></div>
				<div class="card-arrow-bottom-right"></div>
			</div>
		</div>
	</div>
	<!-- END col-9-->
</div>
<!-- END row -->
<!-- END container -->
@endsection
