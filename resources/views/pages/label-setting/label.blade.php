@php
$data = array(0);
if(isset($errors) && $errors->has('label_name.*') || $errors->has('light_version_logo.*') || $errors->has('url.*') || $errors->has('email_address.*') || $errors->has('full_company_address.*')){
// if(isset($errors)){
    $data = old('label_name',[]);
    // dd($errors,$errors->has('light_version_logo.*'));
}elseif(count(old('label_name',[])) > 0 ){
    $data = old('label_name',[]);
}elseif(count($labelSettings) > 0){
    $data = $labelSettings;
}

@endphp
<div id="label-section" class="mb-5">
    <h4><i class="bi bi-tag fa-fw text-theme"></i> Label</h4>
    <p>View and update your label information and settings.</p>
    <div class="card p-3">
        <div class="form-group row mb-2">
            <div class="d-flex align-items-center mb-md-3 mb-2">
                <div class="ms-auto">
                    <button type="button" id="addRow" class="btn btn-outline-theme add_button">Add</button>
                </div>
            </div>
        </div>
        <form method="post" action="{{ route('label-setting-store') }}" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            @foreach($data as $key => $value)
            {{-- {{ dd(old('label_name.'.$key.'.label_name')) }} --}}
            <div class="card p-3 @if($key > 0) mt-3 @endif" id="@if($key > 0 && isset($value->id)){{ "remove-div-".$value->id }}@elseif($key > 0){{ "remove-div-".$key}}@endif">
                @if($key > 0  && isset($value->id))
                <div class="d-flex align-items-center mb-md-3 mb-2">
                    <div class="ms-auto">
                        <button type="button" class="remove_button btn btn-danger  remove-{{$value->id}}" onClick="removeLabelSettings({{ $value->id }})">Remove</button>
                    </div>
                </div>
                @elseif($key > 0)
                <div class="d-flex align-items-center mb-md-3 mb-2">
                    <div class="ms-auto">
                        <button type="button" class="remove_button btn btn-danger  remove-{{$key}}" onClick="removeLabelSettingErrorBlock({{ $key }})">Remove</button>
                    </div>
                </div>
                @endif
                <input type="hidden" value="@if(isset($value->id)){{$value->id}}@else{{ old('id.'.$key.'.id') }}@endif" name="id[{{$key}}][id]">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Name</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($value->label_name)) {{ $value->label_name }}@else{{ old('label_name.'.$key.'.label_name') }}@endif"
                                id="label_name" name="label_name[{{$key}}][label_name]" placeholder="Name">
                            @if ($errors->has('label_name.'.$key.'.label_name'))
                                <span class="text-danger">{{ $errors->first('label_name.'.$key.'.label_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">URL</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($value->url)) {{ $value->url }}@else{{ old('url.'.$key.'.url') }}@endif"
                                id="url" name="url[{{$key}}][url]" placeholder="URL">
                            @if ($errors->has('url.'.$key.'.url'))
                                <span class="text-danger">{{ $errors->first('url.'.$key.'.url') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Email Address</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($value->email_address)) {{ $value->email_address }}@else{{ old('email_address.'.$key.'.email_address') }}@endif"
                                id="email_address" name="email_address[{{$key}}][email_address]" placeholder="Email Address">
                            @if ($errors->has('email_address.'.$key.'.email_address'))
                                <span class="text-danger">{{ $errors->first('email_address.'.$key.'.email_address') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Full Company Address
                            </div>
                        </div>
                        <div class="flex-1">
                            <textarea class="form-control" name="full_company_address[{{$key}}][full_company_address]" placeholder="Full Company Address">@if(isset($value->full_company_address)){{$value->full_company_address}}@else{{ old('full_company_address.'.$key.'.full_company_address') }}@endif</textarea>
                            @if ($errors->has('full_company_address.'.$key.'.full_company_address'))
                                <span class="text-danger">{{ $errors->first('full_company_address.'.$key.'.full_company_address') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Light Version Logo</div>
                        </div>
                        <div class="flex-1">
                            <input type="file" class="form-control form-control-lg" id="light_version_logo"
                                name="light_version_logo[{{$key}}][light_version_logo]">

                            <input type="hidden" class="form-control form-control-lg" name="old_light_version_logo[{{$key}}][old_light_version_logo]"
                                value="@if (isset($value->light_version_logo)) {{ $value->light_version_logo }}@else{{old('old_light_version_logo.'.$key.'.old_light_version_logo')}}@endif">

                            @if ($errors->has('light_version_logo.'.$key.'.light_version_logo'))
                                <span class="text-danger">{{ $errors->first('light_version_logo.'.$key.'.light_version_logo') }}</span>
                            @endif

                            @if (isset($value->light_version_logo))
                                <div class="profile-img mt-2">
                                    <img src="{{ getFileFromStorage($labelSettingsPath . $value->light_version_logo) }}"
                                        width="100" height="100" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Dark Version Logo</div>
                        </div>
                        <div class="flex-1">
                            <input type="file" class="form-control form-control-lg" id="dark_version_logo"
                                name="dark_version_logo[{{$key}}][dark_version_logo]">
                            <input type="hidden" class="form-control form-control-lg" name="old_dark_version_logo[{{$key}}][old_dark_version_logo]"
                                value="@if (isset($value->dark_version_logo)) {{ $value->dark_version_logo }}@else{{ old('old_dark_version_logo.'.$key.'.old_dark_version_logo') }} @endif">

                            @if ($errors->has('dark_version_logo.'.$key.'.dark_version_logo'))
                                <span class="text-danger">{{ $errors->first('dark_version_logo.'.$key.'.dark_version_logo') }}</span>
                            @endif
                            @if (isset($value->dark_version_logo))
                                <div class="profile-img mt-2">
                                    <img src="{{ getFileFromStorage($labelSettingsPath . $value->dark_version_logo) }}"
                                        width="100" height="100" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Background Image</div>
                        </div>
                        <div class="flex-1">
                            <input type="file" class="form-control form-control-lg" id="backgroung_image"
                                name="backgroung_image[{{$key}}][backgroung_image]">
                            <input type="hidden" class="form-control form-control-lg" name="old_backgroung_image[{{$key}}][old_backgroung_image]"
                                value="@if (isset($value->backgroung_image)) {{ $value->backgroung_image }}@else{{ old('old_backgroung_image.'.$key.'.old_backgroung_image') }} @endif">
                            @if ($errors->has('backgroung_image.'.$key.'.backgroung_image'))
                                <span class="text-danger">{{ $errors->first('backgroung_image.'.$key.'.backgroung_image') }}</span>
                            @endif
                            @if (isset($value->backgroung_image))
                                <div class="profile-img mt-2">
                                    <img src="{{ getFileFromStorage($labelSettingsPath . $value->backgroung_image) }}"
                                        width="100" height="100" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Theme Mode</div>
                        </div>
                        <div class="flex-1">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="theme_mode[{{$key}}][theme_mode]" id="customSwitch1"
                                    @if (isset($value->theme_mode) && $value->theme_mode == 1) checked @endif>
                                <label class="form-check-label" for="customSwitch1">Dark Mode</label>
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
            @endforeach
            <div id="inputFormRow" class="field_wrapper">
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

        </form>
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            var x = {{ count($data) }} - 1;
            var maxField = 10;
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper

            $(addButton).click(function() {
                if (x < maxField) {
                    x++;
                    var fieldHTML = '<div class="card p-3 mt-3">';
                    fieldHTML += '<div class="form-group row mb-2">';
                    fieldHTML += '<div class="d-flex align-items-center mb-md-3 mb-2">';
                    fieldHTML += '<div class="ms-auto">';
                    fieldHTML += '<a href="javascript:void(0);" class="remove_button btn btn-danger">Remove</a>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div id="inputFormRow" class="field_wrapper">';
                    fieldHTML += '<div class="list-group list-group-flush">';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML += '<div class="text-inverse text-opacity-70 d-flex align-items-center">Name</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<input type="text" class="form-control plaintext" id="label_name" name="label_name[${x}][label_name]" placeholder="Name">`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML += '<div class="text-inverse text-opacity-70 d-flex align-items-center">URL</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML += `<input type="text" class="form-control plaintext" id="url" name="url[${x}][url]" placeholder="URL">`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML += '<div class="text-inverse text-opacity-70 d-flex align-items-center">Email Address</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<input type="text" class="form-control plaintext" id="email_address" name="email_address[${x}][email_address]" placeholder="Email Address">`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML += '<div class="text-inverse text-opacity-70 d-flex align-items-center">Full Company Address';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<textarea class="form-control" name="full_company_address[${x}][full_company_address]" placeholder="Full Company Address"></textarea>`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML +=
                        '<div class="text-inverse text-opacity-70 d-flex align-items-center">Light Version Logo</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<input type="file" class="form-control form-control-lg" id="light_version_logo" name="light_version_logo[${x}][light_version_logo]">`;

                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML +=
                        '<div class="text-inverse text-opacity-70 d-flex align-items-center">Dark Version Logo</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<input type="file" class="form-control form-control-lg" id="dark_version_logo" name="dark_version_logo[${x}][dark_version_logo]">`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML +=
                        '<div class="text-inverse text-opacity-70 d-flex align-items-center">Background Image</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML +=
                        `<input type="file" class="form-control form-control-lg" id="backgroung_image" name="backgroung_image[${x}][backgroung_image]">`;
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="list-group-item d-flex align-items-center">';
                    fieldHTML += '<div class="flex-1 text-break">';
                    fieldHTML += '<div class="text-inverse text-opacity-70 d-flex align-items-center">Theme Mode</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '<div class="flex-1">';
                    fieldHTML += '<div class="form-check form-switch">';
                    fieldHTML += `<input type="checkbox" class="form-check-input" name="theme_mode[${x}][theme_mode]" id="customSwitch1">`;
                    fieldHTML += '<label class="form-check-label" for="customSwitch1">Dark Mode</label>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';
                    fieldHTML += '</div>';

                    fieldHTML += '<div class="card-arrow">';
                    fieldHTML += '<div class="card-arrow-top-left"></div>';
                    fieldHTML += '<div class="card-arrow-top-right"></div>';
                    fieldHTML += '<div class="card-arrow-bottom-left"></div>';
                    fieldHTML += '<div class="card-arrow-bottom-right"></div>';
                    fieldHTML += '</div>';

                    fieldHTML += '</div>';
                    $(wrapper).append(fieldHTML);
                }
            });

            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent('div').parent('div').parent('div').parent('div').remove();
                x--;
            });
        });

        function removeLabelSettings(id){
            // console.log(id);
            
            // console.log($(this).parent('div'));
            $.ajax({
                type: "POST",
                url: "{{ route('label-setting-delete') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id':id
                },
                success: function(data) {
                    $(`#remove-div-${id}`).remove();
                },
            });
        }
        function removeLabelSettingErrorBlock(id){
            $(`#remove-div-${id}`).remove();
        }
    </script>
@endpush
