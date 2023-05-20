@extends('layout.default')

@section('title','Sendouts')
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
    <script src="/assets/plugins/summernote/dist/summernote-lite.min.js"></script>
    <script src="/assets/js/wavesurfer.min.js"></script>
    <script>
        $(document).ready(function(){
            // $(".mp3-input, .wav-input").change(function(e){
            $(document).on("change", ".mp3-input, .wav-input", function(e){
                var file = e.target.files[0];
                var _this = $(this);

                $("."+_this.data('container')).html("");
                var wavesurfer = WaveSurfer.create({
                    container: "." + _this.data('container'),
                    waveColor: '#a8b6bc',
                    progressColor: '#4e9cff',
                    height:100,
                });
                
                if (file) {
                    var reader = new FileReader();                    
                    reader.onload = function (evt) {
                        // Create a Blob providing as first argument a typed array with the file buffer
                        var blob = new window.Blob([new Uint8Array(evt.target.result)]);
                        // Load the blob into Wavesurfer
                        wavesurfer.loadBlob(blob);
                    };
                    reader.onerror = function (evt) {
                        console.error("An error ocurred reading the file: ", evt);
                    };
                    // Read File as an ArrayBuffer
                    reader.readAsArrayBuffer(file);
                }
                wavesurfer.load(file);
                $('#save').attr('disabled','disabled');
                wavesurfer.on('ready', function () {
                    var length  = wavesurfer.getDuration();
                    var start   = 0;
                    var end     = length;
                    var peak = wavesurfer.backend.getPeaks(length, start, end);                    
                    _this.next().val(peak);
                    setTimeout(() => {
                        $('#save').removeAttr('disabled');                        
                    }, 1000);
                });
                
                // $(this).parent().next().find('.my-waves');
            });
        });
        // SummerNote Here
        // $('.description').summernote({
        //     height: 200,
        // });
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
        // Release DatePicker Here
        $('#release_date').datepicker({
            autoclose: true,
            startDate: new Date()
        });
        // Promo SendOut DatePicker Here
        $('#promo_sendout').datepicker({
            autoclose: true,
            startDate: new Date()
        });

        // add row
        // var maxField = 10;
        var x = 0;
        //$('.remove-0').hide();
        $("#addRow").click(function () {
            //var a = $('.index').val()
            // $('.index').each(function(){
            x++;
            var html = '';
            html += '<div class="mb-3 track-div" id="inputFormRow">';
            html += '<div class="card p-3">';
            // Remove Fields Button Here   
            // if(x > 1){
                html += '<div class="d-flex align-items-center mb-md-3 mb-2">';
                html += '<div class="ms-auto">';
                html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
                html += '</div>';
                html += '</div>';
            // }
            // Track Field Added Here
            html += '<div class="form-group row mb-3">';
            html += '<label for="staticEmail" class="col-sm-2 col-form-label">Track</label>';
            html += '<div class="col-sm-10">';
            html += '<input type="text" class="form-control plaintext" id="track" name="track[]" placeholder="Track">';
            html += '</div>';
            html += '</div>';

            // Track Genre Field Added Here
            html += '<div class="form-group row mb-3">';
            html += '<label for="staticEmail" class="col-sm-2 col-form-label">Track Genre</label>';
            html += '<div class="col-sm-10">';
            html += '<input type="text" class="form-control plaintext" id="track_genre" name="track_genre[]" placeholder="Track Genre">';
            html += '</div>';
            html += '</div>';

            // MP3 Audio Field Added Here
            html += '<div class="form-group row mb-3">';
            html += '<label class="form-label col-sm-2 col-form-label">MP3 Audio</label>';
            html += '<div class="col-sm-10">';
            html += '<input type="file" name="mp3_audio[]" data-container="wave-container-mp3-'+x+'" onChange="getFileName(this)" class="form-control mp3-input"/>';
            html += '<input type="hidden" name="peak_mp3_audio[]" value=""/><input type="hidden" name="mp3[]" id="mp3">';
            html += "<small class='form-text text-muted'>Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>";
            html += "<div class='my-waves'><div class='wave-container-mp3-"+x+"'></div></div>";
            html += '</div>';
            html += '</div>';

            // WAV Audio Field Added Here
            html += '<div class="form-group row mb-3">';
            html += '<label class="form-label col-sm-2 col-form-label">WAV Audio</label>';
            html += '<div class="col-sm-10">';
            html += '<input type="file" name="wav_audio[]" data-container="wave-container-wav-'+x+'" class="form-control wav-input" id="wav_audio" onChange="getWavFileName(this)" /><input type="hidden" name="peak_wav_audio[]" value=""/><input type="hidden" name="wav[]" id="wav">';
            html += "<small class='form-text text-muted'>Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>"
            html += "<div class='my-waves'><div class='wave-container-wav-"+x+"'></div></div>";
            html += '</div>';
            html += '</div>';

            // Side Arrow Here        
            html += '<div class="card-arrow">';
            html += '<div class="card-arrow-top-left"></div>';
            html += '<div class="card-arrow-top-right"></div>';
            html += '<div class="card-arrow-bottom-left"></div>';
            html += '<div class="card-arrow-bottom-right"></div>';
            html += '</div>';

            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
            $(".track-div .btn.btn-danger").show();

        });
        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
            // if($(".track-div").length <= 1){
            //     $(".track-div .btn.btn-danger").hide();
            // }
        });
        function getFileName(elm){
            var fn = $(elm).val();
            var FileNameWithExtension = fn.split('\\').pop();
            var extension = FileNameWithExtension.replace(/^.*\./, '.');
            var avoidedExtension = FileNameWithExtension.replace(extension,'');            
            var track = $(elm).parent().parent().parent().children().children().children('input[name="track[]"]').val();
            $(elm).next().next('input[name="mp3[]"]').val(avoidedExtension);
            if(track == ''){
                $(elm).parent().parent().parent().children().children().children('input[name="track[]"]').val(avoidedExtension);
            }
        }
        function getWavFileName(elm){
            var fn = $(elm).val();
            var FileNameWithExtension = fn.split('\\').pop();
            var extension = FileNameWithExtension.replace(/^.*\./, '.');
            var avoidedExtension = FileNameWithExtension.replace(extension,'');
            $(elm).next().next('input[name="wav[]"]').val(avoidedExtension);
        }
        function removeTrack(id){  
            $.ajax({
                type: "POST",
                url: "{{ route('campaigns.delete.track') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id':id
                },
                success: function(data) {
                    $('.removed-track-'+id).remove();

                    if($(".track-div").length <= 1){
                        $(".track-div .btn.btn-danger").hide();
                    }
                },
            });
        }
    </script>
@endpush

@section('content')
    <!-- BEGIN row -->
    <div class="row">
        <!-- BEGIN col-9 -->
        <div class="col-xl-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('campaigns') }}">Sendouts</a></li>
                <li class="breadcrumb-item active">
                    @if(Route::currentRouteName() == 'campaigns.create')
                        Add Sendouts
                    @else
                        Edit Sendouts
                    @endif
                </li>
            </ul>
            <h1 class="page-header">
                @if(Route::currentRouteName() == 'campaigns.create')
                    Add Sendouts
                @else
                    Edit Sendouts
                @endif
            </h1>
            <hr class="mb-4" />
            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="@if(Route::currentRouteName() == 'campaigns.create'){{ route('campaigns.add') }}@else{{ route('campaigns.update')}}@endif" autocomplete="off"  enctype="multipart/form-data">
                            @csrf
                            @if(isset($campaigns->id))
                                <input type="hidden" name="id" value="{{ $campaigns->id }}">
                            @endif
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Label</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="label" name="label" placeholder="Label" value="@if(isset($campaigns->label)){{ $campaigns->label }}@elseif(!empty($authDetails->name)){{ $authDetails->name }}@else{{old('label')}}@endif">
                                    @if ($errors->has('label'))
                                        <span class="text-danger">{{ $errors->first('label') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Website</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="website" name="website" placeholder="Website" value="@if(isset($campaigns->website)){{ $campaigns->website }}@elseif(!empty($authDetails->name)){{ $authDetails->website }}@else{{old('website')}}@endif">
                                    @if ($errors->has('website'))
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Release Number</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="release_number" name="release_number" placeholder="Release Number" value="@if(isset($campaigns->release_number)){{ $campaigns->release_number }}@else{{old('release_number')}}@endif">
                                    @if ($errors->has('release_number'))
                                        <span class="text-danger">{{ $errors->first('release_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-sm-2 col-form-label">
                                    Cover Artwork
                                </label>
                                <div class="@if(isset($campaigns->cover_artwork)) col-sm-8 @else col-sm-10 @endif">
                                    <input type="file" name="cover_artwork" class="form-control" id="cover_artwork" />
                                    <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
									<span class="invalid-feedback"></span>
                                    @if ($errors->has('cover_artwork'))
                                        <span class="text-danger">{{ $errors->first('cover_artwork') }}</span>
                                    @endif
                                </div>
                                @if(isset($campaigns->cover_artwork)) 
                                <div class="col-sm-2">
                                    <input type="hidden" name="oldArtwork" value="{{ $campaigns->cover_artwork }}">
                                    <img src="{{ getFileFromStorage($campaignPath."/".$campaigns->cover_artwork) }}" alt="" width="50" height="50">
                                </div>
                                @endif
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-sm-2 col-form-label">
                                    Description
                                </label>
                                <div class="col-sm-10 card" style="z-index: 1020;">
                                    <textarea name="description" class="description" id="description" title="Description">
                                        @if(isset($campaigns->description))
                                            {{ $campaigns->description }}
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
                            </div>                         
                            <div class="mb-3">
                                <div class="card p-3">
                                    <div class="form-group row mb-2">
                                        <div class="d-flex align-items-center mb-md-3 mb-2">
                                            <div class="flex-fill">
                                                <label class="form-check-label">Tracks</label>
                                            </div>
                                            <div class="ms-auto">
                                                <button type="button" id="addRow" class="btn btn-outline-theme">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newRow">
                                        @if(($errors->has('track.0') || old('track', []) || $errors->has('track_genre.0') || $errors->has('mp3_audio.0') || $errors->has('mp3.0') || $errors->has('wav_audio.0') || $errors->has('wav.0')) && Route::currentRouteName() == 'campaigns.create' || (old('track') < 1  && empty($campaignTracks)))
                                        
                                        <div class="mb-3" id="inputFormRow">
                                            <div class="card p-3">
                                            <div class="form-group row mb-3">
                                                <label for="staticEmail" class="col-sm-2 col-form-label">Tracks</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control plaintext" id="track" name="track[]" placeholder="Track" value="{{ old('track.0') }}">
                                                    @if ($errors->has('track.0') && empty(old('track.0')))
                                                        <span class="text-danger">{{ $errors->first('track.0') }}</span>
                                                    @elseif($errors->has('track'))
                                                        <span class="text-danger">{{ $errors->first('track') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="staticEmail" class="col-sm-2 col-form-label">Track Genre</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control plaintext" id="track_genre" name="track_genre[]" placeholder="Track Genre" value="{{ old('track_genre.0') }}">
                                                    @if ($errors->has('track_genre.0') && empty(old('track_genre.0')))
                                                        <span class="text-danger">{{ $errors->first('track_genre.0') }}</span>
                                                    @elseif($errors->has('track_genre'))
                                                        <span class="text-danger">{{ $errors->first('track_genre') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="form-label col-sm-2 col-form-label">MP3 Audio</label>
                                                <div class="col-sm-10">
                                                    <input type="file" name="mp3_audio[]" data-container="wave-container-mp3-0" class="form-control mp3-input" onChange='getFileName(this)' />
                                                    <input type="hidden" name="peak_mp3_audio[]" value=""/>
                                                    <input type="hidden" name="mp3[]" id="mp3">
                                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                                    @if ($errors->has('mp3_audio.0'))
                                                        <span class="text-danger">{{ $errors->first('mp3_audio.0') }}</span>
                                                    @elseif($errors->has('mp3.0'))
                                                            <span class="text-danger">{{ $errors->first('mp3.0') }}</span>
                                                    @elseif($errors->has('mp3_audio'))
                                                        <span class="text-danger">{{ $errors->first('mp3_audio') }}</span>
                                                    @endif
                                                    <div class="my-waves">
                                                        <div class="wave-container-mp3-0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="form-label col-sm-2 col-form-label">WAV Audio</label>
                                                <div class="col-sm-10">
                                                    <input type="file" name="wav_audio[]" data-container="wave-container-wav-0" class="form-control wav-input" id="wav_audio" onChange='getWavFileName(this)' />
                                                    <input type="hidden" name="peak_wav_audio[]" value=""/>
                                                    <input type="hidden" name="wav[]" id="wav">
                                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                                    @if ($errors->has('wav_audio.0'))
                                                        <span class="text-danger">{{ $errors->first('wav_audio.0') }}</span>
                                                    @elseif($errors->has('wav.0'))
                                                        <span class="text-danger">{{ $errors->first('wav.0') }}</span>
                                                    @elseif($errors->has('wav_audio'))
                                                        <span class="text-danger">{{ $errors->first('wav_audio') }}</span>
                                                    @endif
                                                    <div class="my-waves">
                                                        <div class="wave-container-wav-0">
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
                                        @endif
                                        {{--  --}}
                                        {{-- @if($errors->has('track'))
                                            {{ dd($errors) }}
                                        @endif --}}
                                     
                                    @if($errors->has('track.*') || old('track', []) || $errors->has('track_genre.*') || $errors->has('mp3_audio.*') || $errors->has('mp3.*') || $errors->has('wav_audio.*') || $errors->has('wav.*') || !empty($campaignTracks))
                                        {{-- @for($i = 1; $i < count(old('track')); $i++) --}}
                                        
                                        @php
                                            // if(isset($campaignTracks)){

                                            //     // dd($campaignTracks);
                                            // }
                                            // if(empty($campaignTracks)){
                                            $data  = [];
                                            if(isset($campaignTracks) && $campaignTracks->count() == 0 && count(old('track')) > 1){
                                                $data = old('track', []);
                                            }else{
                                                if(isset($campaignTracks)){
                                                    $data = $campaignTracks;
                                                }
                                            }
                                        @endphp
                                        @foreach($data as $i => $old)
                                            <div class="mb-3 track-div @if(isset($old->id))removed-track-{{ $old->id }}@endif" id="inputFormRow">
                                                {{-- <input type="hidden" class="index" value="{{$i}}"> --}}
                                                <div class="card p-3">
                                                <div class="d-flex align-items-center mb-md-3 mb-2">
                                                    <div class="ms-auto">
                                                        @if(isset($old->id) && $i !=0)
                                                        <button id="remove-track" type="button" class="btn btn-danger remove-{{$i }}" @if(isset($old->id)) onClick="removeTrack({{ $old->id }})" @endif >Remove</button>
                                                        @elseif($i > 0)
                                                            <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label">Track</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control plaintext" id="track" name="track[]" placeholder="Track" value="@if(isset($old->track)){{ $old->track }}@else{{old('track.'.$i)}}@endif">
                                                        @if ($errors->has('track.'.$i) && empty(old('track.'.$i)))
                                                            <span class="text-danger">{{ $errors->first('track.'.$i) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label">Track Genre</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control plaintext" id="track_genre" name="track_genre[]" placeholder="Track Genre" value="@if(isset($old->track_genre)){{ $old->track_genre }}@else{{ old('track_genre.'.$i) }}@endif">
                                                        @if ($errors->has('track_genre.'.$i) && empty(old('track_genre.'.$i)))
                                                            <span class="text-danger">{{ $errors->first('track_genre.'.$i) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="form-label col-sm-2 col-form-label">MP3 Audioss</label>
                                                    <div class="@if(isset($old->mp3_audio)) col-sm-6 @else col-sm-10 @endif">
                                                        <input type="file" name="mp3_audio[]" data-container="wave-container-mp3-{{ $i }}" class="form-control mp3-input" id="mp3_audio" onChange='getFileName(this)'/>
                                                        <input type="hidden" name="peak_mp3_audio[]" value=""/>
                                                        <input type="hidden" name="mp3[]" id="mp3" value="@if(isset($old->mp3_audio)){{ $old->mp3_audio }}@endif">
                                                        <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                                        @if ($errors->has('mp3_audio.'.$i))
                                                            <span class="text-danger">{{ $errors->first('mp3_audio.'.$i) }}</span>
                                                        @elseif($errors->has('mp3.'.$i))
                                                            <span class="text-danger">{{ $errors->first('mp3.'.$i) }}</span>
                                                        @endif
                                                        <div class="my-waves">
                                                            <div class="wave-container-mp3-{{ $i }}">
                                                            </div>
                                                        </div>
                                                    </div>                                                   
                                                    
                                                    @if(isset($old->mp3_audio))
                                                    <div class="col-sm-2">
                                                        <input type="hidden" name="oldMp3Audio[]" value="{{ $old->mp3_audio }}">
                                                        <input type="hidden" name="old_mp3_peak[]" value="{{ $old->mp3_peak }}">
                                                        <input type="hidden" name="old_mp3_time[]" value="{{ $old->mp3_time }}">
                                                        <audio controls="controls">
                                                            <source src="{{ getFileFromStorage($campaignPath."/".$old->mp3_audio) }}" type="audio/wav">
                                                        </audio>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="form-label col-sm-2 col-form-label">WAV Audio</label>
                                                    <div class="@if(isset($old->wav_audio)) col-sm-6 @else col-sm-10 @endif">
                                                        <input type="file" name="wav_audio[]" data-container="wave-container-wav-{{ $i }}" class="form-control wav-input" id="wav_audio" onChange='getWavFileName(this)' />
                                                        <input type="hidden" name="peak_wav_audio[]" value=""/>
                                                        <input type="hidden" name="wav[]" id="wav" value="@if(isset($old->wav_audio)){{ $old->wav_audio }}@endif">
                                                        <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                                        @if ($errors->has('wav_audio.'.$i))
                                                            <span class="text-danger">{{ $errors->first('wav_audio.'.$i) }}</span>
                                                        @elseif($errors->has('wav.'.$i))
                                                            <span class="text-danger">{{ $errors->first('wav.'.$i) }}</span>
                                                        @endif
                                                        <div class="my-waves">
                                                            <div class="wave-container-wav-{{ $i }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="trackid[]" value="@if(isset($old->id)) {{ $old->id }} @endif">
                                                    
                                                    @if(isset($old->wav_audio))
                                                    <div class="col-sm-2">                                                        
                                                        <input type="hidden" name="oldWavAudio[]" value="{{ $old->wav_audio }}">
                                                        <input type="hidden" name="old_wav_peak[]" value="{{ $old->wav_peak }}">
                                                        <input type="hidden" name="old_wav_time[]" value="{{ $old->wav_time }}">
                                                        <audio controls="controls">
                                                            <source src="{{ getFileFromStorage($campaignPath."/".$old->wav_audio) }}" type="audio/wav">
                                                        </audio>
                                                        
                                                    </div>
                                                    @endif
                                                </div>   
                                                <div class="card-arrow">
                                                    <div class="card-arrow-top-left"></div>
                                                    <div class="card-arrow-top-right"></div>
                                                    <div class="card-arrow-bottom-left"></div>
                                                    <div class="card-arrow-bottom-right"></div>
                                                </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        {{--  --}}
                                    @endif
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="leave_rating_and_comment">Leave Rating & Comment</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" class="form-check-input" id="leave_rating_and_comment" name="leave_rating_and_comment" @if(Route::currentRouteName() == 'campaigns.create') checked @endif @if(isset($campaigns->leave_rating_and_comment) && $campaigns->leave_rating_and_comment == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                            	<label for="release_date" class="col-sm-2 col-form-label">
                                    Release Date
                                </label>
                            	<div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" name="release_date" id="release_date" autocomplete="off" readonly onclick="this.removeAttribute('readOnly');" placeholder="Release Date" value="@if(isset($campaigns->release_date)){{ $campaigns->release_date }}@else{{ old('release_date') }}@endif"/>
    										<label class="input-group-text" for="release_date">
    										    <i class="fa fa-calendar"></i>
    									    </label>                                           
    								</div>
                                    @if ($errors->has('release_date'))
                                    <span class="text-danger">{{ $errors->first('release_date') }}</span>
                                @endif
    							</div>
    						</div>
                            <div class="form-group row mb-3">
                            	<label for="promo_sendout" class="col-sm-2 col-form-label">
                                    Promo Sendout
                                </label>
                            	<div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" name="promo_sendout" id="promo_sendout" autocomplete="off" readonly onclick="this.removeAttribute('readOnly');" placeholder="Promo Sendout" value="@if(isset($campaigns->promo_sendout)){{ $campaigns->promo_sendout }}@else{{ old('promo_sendout') }}@endif"/>
    										<label class="input-group-text" for="promo_sendout">
    										<i class="fa fa-calendar"></i>
    									    </label>
                                        </div>
                                        @if ($errors->has('promo_sendout'))
                                            <span class="text-danger">{{ $errors->first('promo_sendout') }}</span>
                                        @endif
    							</div>
    						</div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                      <input type="checkbox" name="status" class="form-check-input" id="status" @if(Route::currentRouteName() == 'campaigns.create') checked @endif @if(isset($campaigns->status) && $campaigns->status == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="expire_link_once_downloaded">Expire Link Once Download</label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch ">
                                        <input type="checkbox" name="expire_link_once_downloaded" class="form-check-input" id="expire_link_once_downloaded" @if(isset($campaigns->expire_link_once_downloaded) && $campaigns->expire_link_once_downloaded == 1) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="email_group">Select E-Mail list group to send</label>
                                </div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="email_group" id="email_group">
                                        <option value="">Select</option>
                                        @foreach($groups as $group)
                                        <option value="{{ $group->group }}" @if(isset($campaigns->email_group) && $campaigns->email_group == $group->group) selected @endif>{{ $group->group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-2">
                                    <label class="form-check-label" for="label_id">Select Label</label>
                                </div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="label_id" id="label_id">
                                        <option value="">Select</option>
                                        @foreach($labelSettings as $labelSetting)
                                        <option value="{{ $labelSetting->id }}" @if(isset($campaigns->label_id) && $campaigns->label_id == $labelSetting->id) selected @endif>{{ $labelSetting->label_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme" id="save">Save Sendouts</button>
                                    <a href="{{ route('campaigns') }}" class="btn btn-outline-warning">Cancel</a>
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