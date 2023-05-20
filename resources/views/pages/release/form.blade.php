@extends('layout.default')

@section('title', 'Release')
@push('css')
    <link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />
    <link href="/assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />
    <style>
        .moveable-box {
            padding: 20px;
            /* background-color: #eaecec;
            border: 1px dotted #ccc; */
            padding : 0px;
            cursor: move;
            /* margin-top: 12px; */
        }
        .social-icon-label{
            width: 50px;
        }
    </style>
@endpush

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    {{-- <script src="/assets/js/demo/highlightjs.demo.js"></script>
    <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script> --}}
    <script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/plugins/select-picker/dist/picker.min.js"></script>
    <script>   
        $('#release_date').datepicker({
            autoclose: true,
            endDate: new Date(),
            format: "yyyy-mm-dd",
        });
        $(document).ready(function(){
            $("input[name='coverType']").click(function(){
                var type = $(this).val();
                if(type == "Image"){
                   $('.url-field').addClass('d-none');
                   $('.image-field').removeClass('d-none');
                }else if(type == "URL"){
                    $('.url-field').removeClass('d-none');
                    $('.image-field').addClass('d-none');
                }
            });
        });
        
        $(document).ready(function(){
            var count = 0;
            var x = 0;
            @if(Route::currentRouteName() == 'release.create')
                var x = {{count($musicLabels) }};
                i = 0;
            @else
                var x = {{count($release->platform) }};
                i = {{count($release->platform) }}-1;
            @endif
            
            $(".add_button").click(function () {            
                          
                var maxField = count; //Input fields increment limitation
                if(x > maxField){ 
                    x++;
                    ++i;  
                    console.log(i); 
                    //var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="remove-icon.png"/></a></div>'; //New input field html 
                    var fieldHTML = '';
                    
                    fieldHTML += '<div class="mb-3 track-div" id="inputFormRow">';
                    fieldHTML += '<div class="card p-3">';
                    // Remove Fields Button Here   
                    // if(x > 1){
                        fieldHTML += '<div class="d-flex align-items-center mb-md-3 mb-2">';
                        fieldHTML += '<div class="ms-auto">';
                        fieldHTML += '<a href="javascript:void(0);" class="remove_button btn btn-danger">Remove</a>';
                        fieldHTML += '</div>';
                        fieldHTML += '</div>';
                    // }
                    // Track Field Added Here
                    fieldHTML += '<div class="form-group row mb-3">';
                    fieldHTML += '<label for="staticEmail" class="col-sm-2 col-form-label">Track</label>';
                    fieldHTML += '<div class="col-sm-10">';
                    fieldHTML += '<select class="form-select" name="platformCode[' + i +'][code]">';
                        fieldHTML += '<option value="">Select Platform</option>';
                        @foreach($musicLabels as $key => $musicLabel)
                        fieldHTML += '<option value="{{$musicLabel->storename}}">{{$musicLabel->storename}}</option>';
                        @endforeach
                    fieldHTML += '</select>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    
                    fieldHTML += '<div class="form-group row mb-3">';
                    fieldHTML += '<label for="staticEmail" class="col-sm-2 col-form-label">Type</label>';
                    fieldHTML += '<div class="col-sm-10">';
                    fieldHTML += '<div class="form-group mb-3">';
                    fieldHTML += '<div class="form-check form-check-inline">';
                    fieldHTML += '<input class="form-check-input track_type" name="track_type[' + i +'][track_type]" onclick="trackType(`track_url`,'+i+')" type="radio" value="track_url" id="track_url[' + i +'][track_type]" checked/>';
                    fieldHTML += '<label class="form-check-label" for="track_url[' + i +'][track_type]">Track URL</label>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="form-check form-check-inline">';
                    fieldHTML += '<input class="form-check-input" type="radio" name="track_type[' + i +'][track_type]" onclick="trackType(`track_id`,'+i+')" value="track_id" id="track_id[' + i +'][track_type]"/>';
                    fieldHTML += '<label class="form-check-label" for="track_id[' + i +'][track_type]">Track ID</label>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';

                    // Track Genre Field Added Here
                    fieldHTML += '<div class="form-group row mb-3 track_url-block-'+i+'">';
                    fieldHTML += '<label for="staticEmail" class="col-sm-2 col-form-label">Track URL</label>';
                    fieldHTML += '<div class="col-sm-10">';
                    fieldHTML += '<input type="text" class="form-control plaintext platform_url-'+i+'" id="platform_url" name="platformUrl[' + i +'][urls]" placeholder="Track URL">';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';

                    fieldHTML += '<div class="form-group row mb-3 track_id-block-'+i+' d-none">';
                    fieldHTML += '<label for="staticEmail" class="col-sm-2 col-form-label">Track ID</label>';
                    fieldHTML += '<div class="col-sm-10">';
                    fieldHTML += '<input type="text" class="form-control plaintext platform_id-'+i+'" id="platform_url" name="platformUrl[' + i +'][id]" placeholder="Track ID">';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';

                    // Side Arrow Here        
                    fieldHTML += '<div class="card-arrow">';
                    fieldHTML += '<div class="card-arrow-top-left"></div>';
                    fieldHTML += '<div class="card-arrow-top-right"></div>';
                    fieldHTML += '<div class="card-arrow-bottom-left"></div>';
                    fieldHTML += '<div class="card-arrow-bottom-right"></div>';
                    fieldHTML += '</div>';

                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    //var x = 1; //Initial field counter is 1
                    $('.field_wrapper').append(fieldHTML);
                }
            });
            $(document).on('click', '.remove_button', function () {
                $(this).parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                x--; 
            });
        });

        function removeReleasePlatform(id){
            $.ajax({
                type: "POST",
                url: "{{ route('release.delete.platform') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id':id
                },
                success: function(data) {
                    console.log(data);
                    // $('.removed-track-'+id).remove();

                    // if($(".track-div").length <= 1){
                    //     $(".track-div .btn.btn-danger").hide();
                    // }
                },
            });
        }
        function trackType(type,id){
            if(type == 'track_url') {
                $('.track_id-block-'+id).addClass('d-none');
                $('.track_url-block-'+id).removeClass('d-none');
                $(".platform_id-"+id).val('');
            }else if(type == 'track_id') {
                $('.track_url-block-'+id).addClass('d-none');
                $('.track_id-block-'+id).removeClass('d-none');
                $(".platform_url-"+id).val('');
                //$("input[name=platformUrl["+id+"]url]").val(null);
                
            }
            
        }

        $(document).ready(function() {
                $("#post_list").sortable({
                    // placeholder: "ui-state-highlight",
                    update: function(event, ui) {
                        var levelOrder = new Array();
                        $('#post_list .moveable-box').each(function() {
                            levelOrder.push($(this).data("post-id"));
                        });
                        $.ajax({
                            url: "{{route('release.platform.ordering')}}",
                            method: "POST",
                            data: {
                           "_token": "{{ csrf_token() }}",
                                levelOrder: levelOrder
                            },
                            success: function(data) {
                                console.log(data);
                            }
                        });
                    }
                });
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
                <li class="breadcrumb-item"><a href="{{ route('releases') }}">Release</a></li>
                <li class="breadcrumb-item active">{{ isset($release->id) ? 'Edit' : 'Add' }} Release</li>
            </ul>

            <h1 class="page-header">
                {{ isset($release->id) ? 'Edit' : 'Add' }} Release
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4> -->
                <div class="card">
                    <div class="card-body">
                       
                        <form method="post" action=" @if(Route::currentRouteName() == 'release.create'){{ route('release.save') }}@else{{ route('release.update')}}@endif" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="@if(isset($release->id)){{ $release->id }}@endif">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Cover Type</label>
                                <div class="col-sm-10">
                                    <div class="form-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="coverType" type="radio" value="Image" id="image" @if(old('coverType', 'Image') == 'Image') checked @endif/>
                                            <label class="form-check-label" for="image">Image</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="coverType" value="URL" id="url" @if(old('coverType', 'Image') == 'URL') checked @endif />
                                            <label class="form-check-label" for="url">URL</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Cover</label>
                                <div class="@if(isset($release->cover)) col-sm-8 @else col-sm-10 @endif">
                                    <div class="image-field @if(old('coverType', 'Image') == 'URL') d-none @endif">
                                        <input type="file" class="form-control" name="cover">
                                        <small class="form-text text-muted">Supported file types: jpg, jpeg, png</small>
                                        <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                        @if ($errors->has('cover'))
                                            <br/><span class="text-danger">{{ $errors->first('cover') }}</span>
                                        @endif
                                    </div>
                                    <div class="url-field @if(old('coverType', 'Image') == 'Image') d-none @endif">
                                        <input type="text" class="form-control" name="cover_url" placeholder="Cover URL">
                                        @if ($errors->has('cover_url'))
                                            <span class="text-danger">{{ $errors->first('cover_url') }}</span>
                                        @endif
                                    </div>
                                    @if(isset($release->cover)) 
                                        <div class="col-sm-2">
                                            <input type="hidden" name="oldCover" value="{{ $release->cover }}">
                                            <img src="{{ $release->cover }}" alt="{{ $release->cover }}" width="50" height="50">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Artist</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="artist" placeholder="Artist" value="@if(isset($release->artist)){{ $release->artist }}@else{{old('artist')}}@endif">
                                    @if ($errors->has('artist'))
                                        <span class="text-danger">{{ $errors->first('artist') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Track</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="track" placeholder="Track" value="@if(isset($release->track)){{ $release->track }}@else{{old('track')}}@endif">
                                    @if ($errors->has('track'))
                                        <span class="text-danger">{{ $errors->first('track') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Label</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="label" name="label" placeholder="Label" value="@if(isset($release->label)){{ $release->label }}@else{{old('label')}}@endif">
    								</div>
                                    @if ($errors->has('label'))
                                        <span class="text-danger">{{ $errors->first('label') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Release Date</label>
                                <div class="col-sm-10">
    								<div class="input-group">
    									<input type="text" class="form-control plaintext" id="release_date" name="release_date" placeholder="Release Date" value="@if(isset($release->release_date)){{ $release->release_date }}@else{{old('release_date')}}@endif">
    								</div>
                                    {{-- @if ($errors->has('release_date'))
                                        <span class="text-danger">{{ $errors->first('release_date') }}</span>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Audio Preview</label>
                                <div class="@if(isset($release->audio_preview)) col-sm-8 @else col-sm-10 @endif">
                                    <input type="file" class="form-control" name="audio_preview">
                                    <small class="form-text text-muted">Supported file types: mp3, wav</small>
                                    <small class="form-text text-muted">Max File size: {{ getFileSizeInReadable(ini_get('upload_max_filesize')); }}</small>
                                    @if ($errors->has('audio_preview'))
                                        <br/><span class="text-danger">{{ $errors->first('audio_preview') }}</span>
                                    @endif
                                    @if(isset($release->audio_preview)) 
                                        <div class="col-sm-2">
                                            <input type="hidden" name="old_audio_preview" value="{{ $release->audio_preview }}">
                                            <audio controls="controls">
                                                <source src="{{ $release->audio_preview }}" type="audio/wav">
                                            </audio>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Add New Platform start Here --}}
                            <div class="mb-3" id="inputFormRow">
                                <div class="card p-3">
                                    <div class="form-group row mb-2">
                                        <div class="d-flex align-items-center mb-md-3 mb-2">
                                            <div class="flex-fill">
                                                <label class="form-check-label">Release Platfrom</label>
                                            </div>
                                            <div class="ms-auto">
                                                <button type="button" id="addRow" class="btn btn-outline-theme add_button">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if ((!$errors->has('platformUrl.*') || count(old('track_type',[])) == 0 || !$errors->has('platformCode.*')) && Route::currentRouteName() == 'release.create' )
                                    <div class="card p-3">
                                        <div class="form-group row mb-3">
                                            <label for="staticEmail" class="col-sm-2 col-form-label">Platforms</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" name="platformCode[0][code]">
                                                    <option value="">Select Platform</option>
                                                    @foreach($musicLabels as $key => $musicLabel)
                                                    <option value="{{$musicLabel->storename}}">{{$musicLabel->storename}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('platformCode.*'))
                                                    <span class="text-danger">{{ $errors->first('platformCode.*') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label for="staticEmail" class="col-sm-2 col-form-label">Type</label>
                                            <div class="col-sm-10">
                                                <div class="form-group mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input track_type" name="track_type[0][track_type]" onclick="trackType('track_url',0)" type="radio" value="track_url" id="track_url[0][track_type]" @if(old('track_type', 'track_url') == 'track_url') checked @endif/>
                                                        <label class="form-check-label" for="track_url[0][track_type]">Track URL</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="track_type[0][track_type]" onclick="trackType('track_id',0)" value="track_id" id="track_id[0][track_type]"  @if(old('track_type', 'track_url') == 'track_id') checked @endif />
                                                        <label class="form-check-label" for="track_id[0][track_type]">Track ID</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 track_url-block-0">
                                            <label for="staticEmail" class="col-sm-2 col-form-label">Track URL</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="platform_url" name="platformUrl[0][urls]" placeholder="Track URL">
                                                @if ($errors->has('platformUrl.*'))
                                                    <span class="text-danger">{{ $errors->first('platformUrl.*') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 track_id-block-0 d-none">
                                            <label for="staticEmail" class="col-sm-2 col-form-label">Track ID</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="platform_url" name="platformUrl[0][id]" placeholder="Track ID">
                                                @if ($errors->has('platformUrl.0.id'))
                                                    <span class="text-danger">{{ $errors->first('platformUrl.0.id') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-arrow">
                                            <div class="card-arrow-top-left"></div>
                                            <div class="card-arrow-top-right"></div>
                                            <div class="card-arrow-bottom-left"></div>
                                            <div class="card-arrow-bottom-right"></div>
                                        </div>
                                    </div>
                                    @endif
                                    {{-- Add New Platform end Here --}}

                                    <div class="mb-3 mt-3 field_wrapper">
                                        <div id="post_list">
                                        {{-- Edit OR Update Form Start Here --}}
                                            @if ($errors->has('platformCode.*') || $errors->has('platformUrl.*')|| $errors->has('platformUrl.0.id') || !empty($release->platform) )
                                                @php                                                
                                                    if(count(old('platformUrl',[])) != 0 || count(old('track_type',[])) != 0 || count(old('platformCode',[])) != 0){
                                                        $platformErrors = old('platformCode', []);
                                                    }else{
                                                        $platformErrors = $release->platform;
                                                    }
                                                    $i = -1;
                                                @endphp
                                                @foreach($platformErrors as $key => $error)
                                                    <?php $i++;  ?>
                                                    <input type="hidden" name="platformId[{{$i}}][id]" value="@if(!empty($release->platform) && isset($error->id)){{ $error->id }}@elseif(isset(old('platformId')[$i]['id'])){{ old('platformId')[$i]['id'] }}@endif">
                                                    <div class="mb-3 track-div moveable-box" id="inputFormRow" data-post-id="@if(!empty($release->platform) && isset($error->id)){{ $error->id }}@endif">
                                                        <div class="card p-3">                                                
                                                            @if ((!$errors->has('platformUrl.*') || !$errors->get('platformCode.*')) && $key != 0)
                                                                <div class="d-flex align-items-center mb-md-3 mb-2">
                                                                    <div class="ms-auto">
                                                                        @if(!empty($release->platform) && isset($error->id))
                                                                            <button class="remove_button btn btn-danger  remove-{{$error->id}}" onClick="removeReleasePlatform({{ $error->id }})">Remove</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @elseif(isset(old('platformId')[$i]['id']))
                                                                <div class="d-flex align-items-center mb-md-3 mb-2">
                                                                    <div class="ms-auto">
                                                                        <a href="javascript:void(0);" class="remove_button btn btn-danger">Remove</a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="form-group row mb-3">
                                                                <label for="staticEmail" class="col-sm-2 col-form-label">Platform</label>
                                                                <div class="col-sm-10">
                                                                    <select class="form-select" name="platformCode[{{$i}}][code]">
                                                                        <option value="">Select Platform</option>
                                                                            @foreach($musicLabels as $key => $musicLabel)
                                                                                <option value="{{$musicLabel->storename}}" @if(!empty($release->platform) && isset($error->code) && $error->code == $musicLabel->storename || isset(old('platformCode')[$i]['code']) && old('platformCode')[$i]['code'] == $musicLabel->storename) selected @endif>{{$musicLabel->storename}}</option>
                                                                            @endforeach
                                                                    </select>
                                                                    @if($errors->has('platformCode.'.$i.'.code'))
                                                                        <span class="text-danger">{{ $errors->first('platformCode.'.$i.'.code') }}</span> 
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="staticEmail" class="col-sm-2 col-form-label">Type</label>
                                                                <div class="col-sm-10">
                                                                    <div class="form-group mb-3">
                                                                        <div class="form-check form-check-inline">
                                                                            
                                                                            <input class="form-check-input track_type" name="track_type[{{$i}}][track_type]" onclick="trackType('track_url',{{ $i }})" type="radio" value="track_url" id="track_url[{{$i}}][track_type]" @if(!empty(old('track_type')[$i]['track_type']) && old('track_type')[$i]['track_type'] == 'track_url') checked @elseif(!empty($release->platform) && isset($error->url)) checked @else checked @endif/>
                                                                            <label class="form-check-label" for="track_url[{{ $i }}][track_type]">Track URL</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="track_type[{{$i}}][track_type]" onclick="trackType('track_id',{{ $i }})" value="track_id" id="track_id[{{$i}}][track_type]"  @if(!empty(old('track_type')[$i]['track_type']) && old('track_type')[$i]['track_type'] == 'track_id') checked @elseif(!empty($release->platform) && isset($error->track_id)) checked @endif />
                                                                            <label class="form-check-label" for="track_id[{{ $i }}][track_type]">Track ID</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3 track_url-block-{{$i}} @if ($errors->has('platformUrl.'.$i.'.id') || !empty($release->platform) && isset($error->track_id) || !empty(old('track_type')[$i]['track_type']) && old('track_type')[$i]['track_type'] == 'track_id') d-none @endif">
                                                                <label for="staticEmail" class="col-sm-2 col-form-label">Track URL</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control plaintext platform_url-{{$i}}"  name="platformUrl[{{$i}}][urls]" placeholder="Platform URL" value="@if(!empty($release->platform) && isset($error->url)) {{ $error->url }} @elseif(!empty(old('platformUrl')[$i]['urls'])){{ old('platformUrl')[$i]['urls'] }}@endif">
                                                                            @if($errors->has('platformUrl.'.$i.'.urls'))
                                                                                <span class="text-danger">{{ $errors->first('platformUrl.'.$i.'.urls') }}</span> 
                                                                            @endif
                                                                    </div>
                                                            </div>
                                                            <div class="form-group row mb-3 track_id-block-{{$i}} @if($errors->has('platformUrl.'.$i.'.urls') || !empty(old('platformUrl')[$i]['urls']) || !empty($release->platform) && isset($error->url) && $error->url != '') d-none @endif">
                                                                <label for="staticEmail" class="col-sm-2 col-form-label">Track ID</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control platform_id-{{$i}}" name="platformUrl[{{ $i }}][id]" placeholder="Track ID" value="@if(!empty($release->platform) && isset($error->track_id)) {{ $error->track_id }} @elseif(!empty(old('platformUrl')[$i]['id'])){{ old('platformUrl')[$i]['id'] }}@endif">
                                                                    @if ($errors->has('platformUrl.'.$i.'.id'))
                                                                        <span class="text-danger">{{ $errors->first('platformUrl.'.$i.'.id') }}</span>
                                                                    @endif
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
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- Edit OR Update Form End Here --}}
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Social Icons Start Here --}}
                            <div class="mb-3" id="inputFormRow">
                                <div class="card p-3">
                                    <div class="form-group row mb-2">
                                        <div class="d-flex align-items-center mb-md-3 mb-2">
                                            <div class="flex-fill">
                                                <label class="form-check-label">Social Media</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card p-3">
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Facebook</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-facebook-square"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="facebook_url" type="text" placeholder="Facebook" value="@if(isset($release->facebook_url)){{ $release->facebook_url }}@else{{old('facebook_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('facebook_url'))
                                                    <span class="text-danger">{{ $errors->first('facebook_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Twitter</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-twitter"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="twitter_url" type="text" placeholder="Twitter" value="@if(isset($release->twitter_url)){{ $release->twitter_url }}@else{{old('twitter_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('twitter_url'))
                                                    <span class="text-danger">{{ $errors->first('twitter_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Youtube</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-youtube"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="youtube_url" type="text" placeholder="Youtube" value="@if(isset($release->youtube_url)){{ $release->youtube_url }}@else{{old('youtube_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('youtube_url'))
                                                    <span class="text-danger">{{ $errors->first('youtube_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Spotify</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-spotify"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="spotify_url" type="text" placeholder="Spotify" value="@if(isset($release->spotify_url)){{ $release->spotify_url }}@else{{old('spotify_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('spotify_url'))
                                                    <span class="text-danger">{{ $errors->first('spotify_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Instagram</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-instagram"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="instagram_url" type="text" placeholder="Instagram" value="@if(isset($release->instagram_url)){{ $release->instagram_url }}@else{{old('instagram_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('instagram_url'))
                                                    <span class="text-danger">{{ $errors->first('instagram_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Soundcloud</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fab fa-soundcloud"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="soundcloud_url" type="text" placeholder="Soundcloud" value="@if(isset($release->soundcloud_url)){{ $release->soundcloud_url }}@else{{old('soundcloud_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('soundcloud_url'))
                                                    <span class="text-danger">{{ $errors->first('soundcloud_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">TikTok</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="bi bi-tiktok"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="tiktok_url" type="text" placeholder="TikTok" value="@if(isset($release->tiktok_url)){{ $release->tiktok_url }}@else{{old('tiktok_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('tiktok_url'))
                                                    <span class="text-danger">{{ $errors->first('tiktok_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            {{-- <label for="staticEmail" class="col-sm-2 col-form-label">Web</label> --}}
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <label class="input-group-text social-icon-label">
                                                        <i class="fas fa-globe"></i>
                                                    </label>
                                                    <input class="form-control plaintext" name="web_url" type="text" placeholder="Web" value="@if(isset($release->web_url)){{ $release->web_url }}@else{{old('web_url')}}@endif"/>
                                                </div>
                                                @if ($errors->has('web_url'))
                                                    <span class="text-danger">{{ $errors->first('web_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-arrow">
                                            <div class="card-arrow-top-left"></div>
                                            <div class="card-arrow-top-right"></div>
                                            <div class="card-arrow-bottom-left"></div>
                                            <div class="card-arrow-bottom-right"></div>
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

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Release</button>
                                    <a href="{{ route('releases') }}" class="btn btn-outline-warning">Cancel</a>
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