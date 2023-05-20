@extends('layout.default')

@section('title', 'Sendouts')

@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />

    <link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
    <style>
        /* .review{
                                                                            color:#ff9f0c;
                                                                        }
                                                                        .ready{
                                                                            color:#1ecc33;
                                                                        }
                                                                        .attention{
                                                                            color:#ff6060;
                                                                        }
                                                                        .sent{
                                                                            color:#3cd2a5;
                                                                        }
                                                                        .view-feedback{
                                                                            color:#30beff;
                                                                        } */
        .list-table .bootstrap-table .fixed-table-body {
            overflow-x: initial;
            overflow-y: initial;
        }

        .light-theme {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        }

        .bottom-paragraph-block {
            width: 60% !important;
        }

        .theme-card {
            pointer-events: none !important;
            user-select: none;
            height: 100%;
        }

        .emailBox input:checked+label .theme-card::before {
            content: "\f00c";
            position: absolute;
            z-index: 10;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            color: var(--bs-inverse);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Font Awesome\ 5 Free, Font Awesome\ 5 Pro, FontAwesome !important;
            font-weight: 900;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            background: rgba(var(--bs-theme-rgb), .5);
        }

        .emailBox label {
            cursor: pointer !important;
            /* border: 1px solid transparent !important; */
            position: relative;
            transition: all 0.5s ease;
            height: 100%;
            width: 100%;
            padding: 10px;
            user-select: none;
        }

        /* .emailBox input:checked+label {
                    border: 1px solid #3cd2a5 !important;
                } */

        /* .checkIcon {
                    background: #000;
                    height: 30px;
                    width: 30px;
                    position: absolute;
                    border-radius: 50%;
                    border: 2px solid #3cd2a5;
                    right: -12px;
                    top: -12px;
                    opacity: 0;
                    transition: all 0.5s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .checkIcon:after {
                    content: '';
                    height: 7px;
                    width: 12px;
                    border-left: 3px solid #3cd2a5;
                    border-bottom: 3px solid #3cd2a5;
                    display: block;
                    transform: rotate(-45deg);
                    margin: -2px 0 0;
                }

                .emailBox input:checked+label .checkIcon {
                    opacity: 100%;
                } */
    </style>
@endpush

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.js"></script>

    <script src="/assets/plugins/lity/dist/lity.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.campaigns-delete').click(function(e) {
                e.preventDefault();
                let text = "Are you sure you want to delete this campaign?";
                if (confirm(text) == true) {
                    $(this).prev('form').submit();
                }
            });
            // $('.export-pdf').click(function(e){
            //     e.preventDefault();
            //     $(this).next('.export-pdf-id').submit();
            // });

            $(".copy-btn").click(function() {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(this).data("url").trim()).select();
                document.execCommand("copy");
                var _this = $(this);
                $(this).parent().parent().parent().parent().append(
                    '<div style="padding-left: 15px" class="text-lime">Copied!</div>');
                $temp.remove();
                setTimeout(function() {
                    _this.parent().parent().parent().parent().find('div.text-lime').remove();
                }, 1000);
            });
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="d-flex align-items-center mb-md-3 mb-2">
                <div class="flex-fill">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sendouts</li>
                    </ul>
                    <h1 class="page-header mb-0">Sendouts</h1>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('campaigns.create') }}" class="btn btn-outline-theme">
                        <i class="fa fa-plus-circle me-1"></i>Add New
                    </a>
                </div>
            </div>
            <hr class="mb-4" />
            @if (session('status'))
                <div class="alert alert-success alert-dismissable fade show p-3 d-flex">
                    <div class="flex-fill">{{ session('status') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="jquery-success-msg"></div>
            @if (session('error'))
                <div class="alert alert-danger alert-dismissable fade show p-3 d-flex">
                    <div class="flex-fill">{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div id="bootstrapTable" class="mb-5 list-table">
                <div class="card">
                    <div class="card-body">
                        <table class="table w-100" data-toggle="table" data-sort-class="table-active" data-sortable="true"
                            data-search="true" data-pagination="true" data-show-refresh="false" data-show-columns="false"
                            data-show-fullscreen="false">
                            <thead>
                                <tr>
                                    <th data-sortable="true">#</th>
                                    <th data-sortable="true">Cover</th>
                                    <th data-sortable="true">Artist Track</th>
                                    <th data-sortable="true">Label</th>
                                    <th data-sortable="true">Release</th>
                                    <th data-sortable="true">Sendout / Release</th>
                                    <th data-sortable="true">Status</th>
                                    <th data-sortable="true">Created On</th>
                                    <th data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($campaigns as $key => $campaign)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <a href="{{ getFileFromStorage($campaignPath . $campaign->id . '/' . $campaign->cover_artwork) }}"
                                                data-lity class="profile-img-list-link d-flex justify-content-center">
                                                <img src="{{ getFileFromStorage($campaignPath . $campaign->id . '/' . $campaign->cover_artwork) }}"
                                                    width="50" height="50">
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $tracks = [];
                                                $trance = [];
                                            @endphp
                                            @foreach ($campaign->getTrack as $key => $track)
                                                @php
                                                    $tracks[] = '<h6>' . str_replace('-', '</h6>', $track->track);
                                                    $trance[] = $track->track_genre;
                                                @endphp
                                            @endforeach
                                            <label title='{!! implode(',', $trance) !!}'>
                                                {!! implode(',<br>', $tracks) !!}
                                            </label>
                                        </td>
                                        <td>{{ $campaign->label }}</td>
                                        <td>{{ $campaign->release_number }}</td>
                                        <td>
                                            <label title="Sendout/Release">
                                                @php
                                                    $promo_sendout = date('M d,Y', strtotime($campaign->promo_sendout));
                                                    $release_date = date('M d,Y', strtotime($campaign->release_date));
                                                @endphp
                                                {!! $promo_sendout . '<br>' . $release_date !!}
                                            </label>
                                        </td>
                                        <td>
                                            <label
                                                class="@if ($campaign->campaign_status == CAMPAIGN_STATUS_READY) {{ 'ready' }}@elseif($campaign->campaign_status == CAMPAIGN_STATUS_ATTENTION){{ 'attention' }}@elseif($campaign->campaign_status == CAMPAIGN_STATUS_REVIEW){{ 'review' }}@elseif($campaign->campaign_status == CAMPAIGN_STATUS_SENT){{ 'sent' }}@elseif($campaign->campaign_status == CAMPAIGN_STATUS_VIEW_FEEDBACK){{ 'view-feedback' }} @endif d-flex justify-content-center align-items-center">
                                                {{-- {{ $campaign->campaign_status }} --}}
                                                @if ($campaign->campaign_status == CAMPAIGN_STATUS_READY)
                                                    <span class="badge rounded-pill bg-success py-1 px-3"
                                                        data-toggle="tooltip" title="{{ CAMPAIGN_STATUS_READY }}">
                                                        <i class="fas fa-lg fa-fw fa-check-circle"></i>
                                                    </span>
                                                @elseif($campaign->campaign_status == CAMPAIGN_STATUS_ATTENTION)
                                                    <span class="badge rounded-pill bg-danger py-1 px-3"
                                                        data-toggle="tooltip" title="{{ CAMPAIGN_STATUS_ATTENTION }}">
                                                        <i class="bi fa-lg fa-fw bi-x-circle-fill"></i>
                                                    </span>
                                                @elseif($campaign->campaign_status == CAMPAIGN_STATUS_REVIEW)
                                                    <span class="badge rounded-pill bg-warning py-1 px-3"
                                                        data-toggle="tooltip" title="{{ CAMPAIGN_STATUS_REVIEW }}">
                                                        <i class="bi fa-lg fa-fw bi-star"></i>
                                                    </span>
                                                @elseif($campaign->campaign_status == CAMPAIGN_STATUS_SENT)
                                                    <span class="badge rounded-pill bg-primary py-1 px-3"
                                                        data-toggle="tooltip" title="{{ CAMPAIGN_STATUS_SENT }}">
                                                        <i class="bi fa-lg fa-fw bi-envelope"></i>
                                                    </span>
                                                @elseif($campaign->campaign_status == CAMPAIGN_STATUS_VIEW_FEEDBACK)
                                                    <span class="badge rounded-pill bg-info text-dark py-1 px-3"
                                                        data-toggle="tooltip" title="{{ CAMPAIGN_STATUS_VIEW_FEEDBACK }}">
                                                        <i class="bi fa-lg fa-fw bi-star-fill"></i>
                                                    </span>
                                                @endif
                                            </label>
                                        </td>
                                        <td>{{ $campaign->created_at->format('F d,Y') }}</td>
                                        <td>
                                            <div class="">
                                                {{-- <div class="dropdown"> --}}

                                                <button class="btn btn-outline-theme dropdown-toggle" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true"
                                                    title="Status">
                                                    Action
                                                    {{-- Status --}}
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    @if($campaign->label_id != null)
                                                    <li class="dropdown-item">
                                                        <button type="button" class="btn btn-outline-info email-theme"
                                                            onClick="emailTheme({{ $campaign->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#modalXl"
                                                            title="Email Theme">
                                                            <i class="bi bi-layout-sidebar"></i> Email Theme
                                                        </button>
                                                    </li>
                                                    @endif
                                                    <li class="dropdown-item">
                                                        @if (auth::user()->user_role == USER_ROLE_ADMIN)
                                                            <a href="{{ route('campaigns.send-test-email', ['id' => $campaign->id]) }}"
                                                                class="btn btn-outline-info" title="Statistics">
                                                                {{-- <i class="fa fa-chart-bar"></i> --}} Send Test Mail Now
                                                            </a>
                                                        @endif
                                                    </li>
                                                    <li class="dropdown-item">
                                                        <a href="{{ route('campaigns.statistics', ['id' => $campaign->id]) }}"
                                                            class="btn btn-outline-info" title="Statistics">
                                                            <i class="fa fa-chart-bar"></i> Statistics
                                                        </a>
                                                    </li>

                                                    <li class="dropdown-item">
                                                        <a href="{{ route('campaigns.review', ['id' => $campaign->id]) }}"
                                                            class="btn btn-outline-primary" title="Review"
                                                            target="_blank">
                                                            <i class="fas fa-eye"></i> Review
                                                        </a>
                                                    </li>

                                                    <li class="dropdown-item">
                                                        <a data-url="{{ route('campaigns.review', ['id' => $campaign->id]) }}"
                                                            title="Copy Short URL" href="javascript:;"
                                                            class="btn btn-outline-lime copy-btn">
                                                            <i class="fas fa-copy"></i> Copy Short URL
                                                        </a>
                                                    </li>

                                                    @if ($campaign->campaign_status != CAMPAIGN_STATUS_READY || auth::user()->user_role == USER_ROLE_ADMIN)
                                                        <li class="dropdown-item">
                                                            <a title="Edit"
                                                                href="{{ route('campaigns.edit', ['id' => $campaign->id]) }}"
                                                                class="btn btn-outline-success">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="dropdown-item">
                                                        <form method="post"
                                                            action="{{ route('campaigns.delete', ['id' => $campaign->id]) }}">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                        </form>
                                                        <a title="Delete" href="#" data-url=""
                                                            class="btn btn-outline-danger campaigns-delete">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    </li>
                                                    @if (isset(auth::user()->user_role) && USER_ROLE_ADMIN == auth::user()->user_role)
                                                        @if (
                                                            $campaign->campaign_status == CAMPAIGN_STATUS_REVIEW ||
                                                                ($campaign->campaign_status != CAMPAIGN_STATUS_READY && $campaign->campaign_status != CAMPAIGN_STATUS_SENT))
                                                            <li class="dropdown-item">
                                                                <form action="{{ route('campaigns.status') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $campaign->id }}">
                                                                    <input type="hidden" name="status"
                                                                        value="{{ CAMPAIGN_STATUS_READY }}">
                                                                    <button type="submit" class="btn btn-outline-green"
                                                                        title="READY TO SEND">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        {{ CAMPAIGN_STATUS_READY }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li class="dropdown-item">
                                                                <form action="{{ route('campaigns.status') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $campaign->id }}">
                                                                    <input type="hidden" name="status"
                                                                        value="{{ CAMPAIGN_STATUS_ATTENTION }}">
                                                                    <button type="submit" class="btn btn-outline-danger"
                                                                        title="NEEDS ATTENTION">
                                                                        <i class="bi bi-x-circle-fill"></i>
                                                                        {{ CAMPAIGN_STATUS_ATTENTION }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                                {{-- </div> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </div>

    <div class="modal fade" id="modalXl">
        <div class="modal-dialog modal-xl" style="max-width: 1400px; padding: 15px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">choose Email Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="email-preview"></div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                var app_colors = [];
                $.each(app.color, function(name, color) {
                    if (!name.endsWith("Rgb") && !app_colors.includes(color)) {
                        app_colors.push(color);
                    }
                });         
                $('.color').val(app_colors);
            });

            function emailTheme(id) {
                $.ajax({
                    url: `{{ url('/campaigns-email-preview/') }}/${id}`,
                    type: "GET",
                    success: function(res) {
                        let html = "<div class='row'>";
                        for(let i = 0; i < res.key.length; i++) {
                            html += "<div class='col-lg-4 col-12 light-theme emailBox'>";
                            html +=
                                `<input type="radio" name="imgbackground" id="img-${i}" class="d-none imgbgchk" onClick="addTheme(${i+1},${res.id})" value="${i+1}" ${res.selectedCampaignTheme == i+1 ? 'checked' : '' }>`;
                            html += `<label for="img-${i}" class="card">`;
                            html += "<div class='checkIcon'>";
                            html += "</div>";

                            html += "<div class='theme-card'>";
                            html += res[res.key[i]]
                            html += "</div>";
                            html += '<div class="card-arrow">';
                            html += '<div class="card-arrow-top-left"></div>';
                            html += '  <div class="card-arrow-top-right"></div>';
                            html += '  <div class="card-arrow-bottom-left"></div>';
                            html += '  <div class="card-arrow-bottom-right"></div>';
                            html += '</div>';
                            html += '</label>';
                            html += "</div>";
                        }
                        html += "</div>";
                        $('#email-preview').html(html)
                    }
                })
            }

            function addTheme(theme_id,id){
                $.ajax({
                    url:"{{ route('campaigns-email-preview.theme.add') }}",
                    type:"POST",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        theme_id:theme_id,
                        id:id
                    },
                    success:function(res){
                        if(res.status === 200) {
                            $('.btn-close').trigger( "click" );
                            let html =`<div class="alert alert-success alert-dismissable fade show p-3 d-flex">`;
                            html +=`<div class="flex-fill">${res.message}</div>`;
                            html +=`<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>`;
                            html +=`</div>`;
                            $('.jquery-success-msg').html(html);
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
