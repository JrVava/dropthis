@extends('layout.default')

@section('title', 'Home Page')
@push('js')
    <script src="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script>
        $(document).ready(function(){
            $(".copy-btn").click(function(){
                console.log("hello");
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(this).data("url")).select();
                document.execCommand("copy");
                var _this = $(this);
                $(this).parent().append('<div style="padding-left: 15px" class="text-lime">Copied!</div>');
                $temp.remove();
                setTimeout(function(){
                    _this.parent().find('div.text-lime').remove();
                }, 1000);
            });
        });
    </script>
@endpush
@section('content')

    {{-- <ul class="breadcrumb"> --}}
        {{-- <li class="breadcrumb-item"><a href="#">BREADCRUMB</a></li> --}}
        {{-- <li class="breadcrumb-item active">DASHBOARD</li> --}}
    {{-- </ul> --}}
    <h1 class="page-header">
        <a class="d-none" target="_blank" href="http://localhost:8000/qm7g">Open Url</a>
    </h1>

    <div class="d-flex align-items-center mb-md-3 mb-2">
        <div class="flex-fill">
            <div class="link">
                {{ $groups->name }}
            </div>
        </div>
        <div class="ms-auto">
            <a href="#" class="btn btn-sm btn-outline-theme rounded-0" id="daterangepicker">
            <i class="fa fa-fw fa-calendar"></i>
            <span data-id="daterangepicker-date">
                @if(isset($from) && isset($to))
                    {{ date('m/d/Y', strtotime($from)) }} To {{ date('m/d/Y', strtotime($to)) }}
                @else
                    This Month
                @endif
            </span>
            <i class="fa fa-fw fa-caret-down me-n1"></i>
        </a>
        </div>
    </div>

    <!-- BEGIN daterangepicker -->
    <form method="post" id="dateFilter" action="{{ route('group.dateFilter',['id'=>$groups->id]) }}">
        @csrf
        <input type="hidden" name="startDate" id="startDate">
        <input type="hidden" name="endDate" id="endDate">
        <input type="hidden" name="range" id="range">
    </form>
    <!-- END daterangepicker -->
    <div class="row">
                <!-- BEGIN col-3 -->
        <div class="col-xl-3 col-lg-6">
            <!-- BEGIN card -->
            <div class="card mb-3">
                <!-- BEGIN card-body -->
                <div class="card-body">
                    <!-- BEGIN title -->
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOTAL CLICKS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <!-- END title -->
                    <!-- BEGIN stat-lg -->
                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">
                                @if(isset($from) && isset($to))
                                {{ $groups->groupTotalClickFilter($from,$to) }}
                                @else
                                    {{ $groups->groupTotalClick() }}
                                @endif
                            </h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
                        </div>
                    </div>
                    <!-- END stat-lg -->
                    <!-- BEGIN stat-sm -->
                    {{-- <div class="small text-inverse text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 33.3% more than last week<br />
                        <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br />
                        <i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate
                    </div> --}}
                    <!-- END stat-sm -->
                </div>
                <!-- END card-body -->

                <!-- BEGIN card-arrow -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <!-- END card-arrow -->
            </div>
            <!-- END card -->
        </div>
        <!-- END col-3 -->

        <!-- BEGIN col-3 -->
        <div class="col-xl-3 col-lg-6">
            <!-- BEGIN card -->
            <div class="card mb-3">
                <!-- BEGIN card-body -->
                <div class="card-body">
                    <!-- BEGIN title -->
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">UNIQUE CLICKS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <!-- END title -->
                    <!-- BEGIN stat-lg -->
                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">@if(isset($from) && isset($to))                                
                                {{ $groups->groupUniqueClickFilter($from,$to) }}
                                @else
                                {{ $groups->groupUniqueClick() }}
                                @endif
                            </h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
                        </div>
                    </div>
                    <!-- END stat-lg -->
                    <!-- BEGIN stat-sm -->
                   {{--  <div class="small text-inverse text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 20.4% more than last week<br />
                        <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br />
                        <i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate
                    </div> --}}
                    <!-- END stat-sm -->
                </div>
                <!-- END card-body -->

                <!-- BEGIN card-arrow -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <!-- END card-arrow -->
            </div>
            <!-- END card -->
        </div>
        <!-- END col-3 -->

        <!-- BEGIN col-3 -->
        <div class="col-xl-3 col-lg-6">
            <!-- BEGIN card -->
            <div class="card mb-3">
                <!-- BEGIN card-body -->
                <div class="card-body">
                    <!-- BEGIN title -->
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">LINKS AMOUNT</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <!-- END title -->
                    <!-- BEGIN stat-lg -->
                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">
                                @if(isset($from) && isset($to))
                                    {{ $groups->grpLink->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])->count() }}
                                @else
                                    {{ $groups->grpLink->count() }}
                                @endif
                            </h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors" data-height="45"></div>
                        </div>
                    </div>
                    <!-- END stat-lg -->
                    <!-- BEGIN stat-sm -->
                    {{-- <div class="small text-inverse text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 59.5% more than last week<br />
                        <i class="fab fa-facebook-f fa-fw me-1"></i> 45.5% from facebook<br />
                        <i class="fab fa-youtube fa-fw me-1"></i> 15.25% from youtube
                    </div> --}}
                    <!-- END stat-sm -->
                </div>
                <!-- END card-body -->

                <!-- BEGIN card-arrow -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <!-- END card-arrow -->
            </div>
            <!-- END card -->
        </div>
        <!-- END col-3 -->

        <!-- BEGIN col-3 -->
        <div class="col-xl-3 col-lg-6">
            <!-- BEGIN card -->
            <div class="card mb-3">
                <!-- BEGIN card-body -->
                <div class="card-body">
                    <!-- BEGIN title -->
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">GROUPS AMOUNT</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <!-- END title -->
                    <!-- BEGIN stat-lg -->
                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">{{ $groups->where('id', $groups->id)->count() }}</h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors" data-height="45"></div>
                        </div>
                    </div>
                    <!-- END stat-lg -->
                    <!-- BEGIN stat-sm -->
                    {{-- <div class="small text-inverse text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 5.3% more than last week<br />
                        <i class="far fa-hdd fa-fw me-1"></i> 10.5% from total usage<br />
                        <i class="far fa-hand-point-up fa-fw me-1"></i> 2MB per visit
                    </div> --}}
                    <!-- END stat-sm -->
                </div>
                <!-- END card-body -->

                <!-- BEGIN card-arrow -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <!-- END card-arrow -->
            </div>
            <!-- END card -->
        </div>
        <!-- END col-3 -->
        {{-- total clicks line chart --}}
        <div class="col-xl-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">
                            @if(isset($from) && isset($to))
                                {{ $groups->groupTotalClickFilter($from,$to) }}
                            @else
                                {{ $groups->groupTotalClick() }}
                            @endif TOTAL CLICKS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <div class="ratio ratio-21x9">
                        <canvas id="total-clicks-chart"></canvas>
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
        <div class="col-xl-6">
            <!-- BEGIN card -->
            <div class="card mb-3">
                <!-- BEGIN card-body -->
                <div class="card-body">
                    <!-- BEGIN title -->
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TRAFFIC ANALYTICS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <!-- END title -->
                    <!-- BEGIN map -->
                    <div class="ratio ratio-21x9">
                        <div id="world-map" class="jvectormap-without-padding"></div>
                    </div>
                    <!-- END map -->
                    <!-- BEGIN row -->
                    <div class="row gx-4 table-block">
                        <!-- BEGIN col-6 -->
                        <div class="col-lg-12 mb-3 mb-lg-0">
                            <table class="w-100 small mb-0 text-truncate text-inverse text-opacity-60">
                                <thead>
                                    <tr class="text-inverse text-opacity-75">
                                        <th class="w-50">COUNTRY</th>
                                        <th class="w-25 text-end">CLICKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($countryTable as $country)
                                    <tr>
                                        <td>{{ $country['name'] }}</td>
                                        <td class="text-end">{{ $country['total'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END row -->
                </div>
                <!-- END card-body -->

                <!-- BEGIN card-arrow -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <!-- END card-arrow -->
            </div>
            <!-- END card -->
        </div>

        {{-- referrers table --}}
        <div class="col-xl-6 d-none">
            <div class="card mb-3">
                <div class="card-body" style="min-height: 433px">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">REFERRERS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <table class="w-100 small mb-0 text-truncate text-inverse text-opacity-60">
                        <thead>
                            <tr class="text-inverse text-opacity-75">
                                <th class="w-75">REFERRER</th>
                                <th class="w-25 text-end">COUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                			@php 
                				$maxRows = 16;
                				$totalRows = count($referers);

                				$blankRows = 0;
                				if($maxRows > $totalRows){
                					$blankRows = $maxRows - $totalRows;
                				}
                			@endphp
                        	@foreach($referers as $key => $referer)
	                        	<tr>
	                                <td>{{ strlen($referer['referer']) > 20 ? substr($referer['referer'], 0, 20)."..." : $referer['referer']; }}</td>
	                                <td class="text-end">{{ $referer['total'] }}</td>
	                            </tr>
                        	@endforeach
                        	@if($blankRows)
                        		@for($i=0; $i<$blankRows; $i++)
                        			<tr>
		                                <td>&nbsp;</td>
		                                <td>&nbsp;</td>
		                            </tr>
                        		@endfor
                        	@endif
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

        {{-- top devices pie chart --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOP DEVICES</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <div class="m-auto"><canvas id="top-devices-chart"></canvas></div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        {{-- top browsers pie chart --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOP BROWSERS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <div class="m-auto"><canvas id="top-browsers-chart"></canvas></div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
        
        {{-- top platforms pie chart --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOP PLATFORMS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                   <div class="m-auto"><canvas id="top-platforms-chart"></canvas></div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        {{-- clicks details table (datatable) --}}
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">Links Info</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <table id="clicks-details-table" class="table text-nowrap w-100" data-toggle="table"
                            data-sort-class="table-active"
                            data-sortable="true"
                            data-search="true"
                            data-pagination="true"
                            data-show-refresh="false"
                            data-show-columns="false"
                            data-show-fullscreen="false">
                        <thead>
                            <tr>
                                <th data-sortable="true">#</th>
                                <th data-sortable="true">Title</th>
                                <th data-sortable="true">Clicks</th>
                                <th data-sortable="true">CLICKED ON</th>
                                <th data-sortable="true">Link</th>
                                <th data-sortable="true">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linksInfo as $key => $link)
                                <tr>
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        {{ $link->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('link.statistics',['id'=>$link->id]) }}" class="text-inverse text-opacity-50 text-decoration-none" title="Unique Clicks / Total Clicks">
                                            {{ $link->dateFilterClick->where('is_first_click', 1)->count() }} / {{ $link->dateFilterClick->count() }}
                                        </a>
                                    </td>
                                    <td>{{ $link->created_at->format('F d,Y H:i:s') }}</td>
                                    <td>
                                        {{ $link->slug }}
                                    </td>
                                    <td>
                                        @if(isset(unserialize($link->rules)[1]))
                                            <a data-url="@if(unserialize($link->rules)[1] == "any") {{ url('/')."/".$link->slug }} @else {{ unserialize($link->rules)[1]."/".$link->slug }} @endif" title="Copy Short URL" href="javascript:;" class="btn btn-outline-lime copy-btn">
                                                <i class="fas fa-copy"></i>
                                            </a>
                                        @else
                                            <a data-url="{{ route('link',['slug'=>$link->slug]) }}" title="Copy Short URL" href="javascript:;" class="btn btn-outline-lime copy-btn">
                                                <i class="fas fa-copy"></i>
                                            </a>                                            
                                        @endif
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
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">CLICKS DETAILS</span>
                        <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>
                    <table id="clicks-details-table" class="table text-nowrap w-100" data-toggle="table"
                            data-sort-class="table-active"
                            data-sortable="true"
                            data-search="true"
                            data-pagination="true"
                            data-show-refresh="false"
                            data-show-columns="false"
                            data-show-fullscreen="false">
                        <thead>
                            <tr>
                                <th data-sortable="true">IP</th>
                                <th data-sortable="true">URI</th>
                                <th data-sortable="true">Link</th>
                                <th data-sortable="true">HOST</th>
                                <th data-sortable="true">REFERRER</th>
                                <th data-sortable="true">CLICKED ON</th>
                                <th data-sortable="true">INFO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupLinksClickDetail as $key => $click)
                                <tr>
                                    <td>
                                        @if ( ! empty( $click->getCountryName() ) )
                                            @php
                                                $icon = str_replace( ' ', '-', strtolower( $click->getCountryName() ) ) . '.svg';
                                            @endphp
                                            @if ( file_exists( 'countries/'.$icon ))
                                                <img src="{{ asset('countries/'.$icon) }}"  width="20" height="20" title="{{$click->getCountryName() }}">
                                            @else
                                                <img src="{{ asset('countries/earth.svg') }}" width="20" height="20">
                                            @endif
                                        @else
                                            <img src="{{ asset('countries/earth.svg') }}" width="20" height="20">
                                        @endif
                                        {{ $click->ip }}
                                    </td>
                                    <td>{{ $click->uri }}</td>
                                    <td>{{ $click->getLink() }}</td>
                                    <td>{{ $click->host }}</td>
                                    <td title="{{ $click->referer }}">
                                        {{ strlen($click->referer) > 20 ? substr($click->referer, 0, 20)."..." : $click->referer; }}
                                    </td>
                                    <td>{{ $click->created_at->format('F d,Y H:i:s') }}</td>
                                    <td>
                                        <img src="{{ asset('devices/'.getDeviceIcon($click->device)) }}" width="20" height="20" title="{{ $click->user_agent }}">
                                        <img src="{{ asset('browsers/'.getBrowserIcon($click->browser_type)) }}" width="20" height="20" title="{{ $click->browser_type }}">
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
    @php
    // $diff = strtotime($from) - strtotime($to);
    if(empty($from)){
        $from = $to = "";
    }
@endphp
@endsection

@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <link href="/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
    {{-- daterangepicker --}}
    <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style type="text/css">
        .card {
            position: relative;
            /*height: 460px;*/
        }
        .table-block {
            position: absolute;
            border-bottom: 40px;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 20px;
        }
    </style>
@endpush
@push('js')
    {{-- required js for chart start --}}
    <script src="/assets/plugins/chart.js/dist/chart.min.js"/></script>
    {{-- required js for chart end --}}

    {{-- required js for datatable start --}}
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    {{-- <script src="/assets/js/demo/highlightjs.demo.js"></script> --}}
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
    {{-- <script src="/assets/js/demo/table-plugins.demo.js"></script> --}}
    {{-- <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script> --}}
    {{-- required js for datatable end --}}

    <script src="/assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
    <script src="/assets/plugins/jvectormap-content/world-mill.js"></script>
    {{-- <script src="assets/plugins/apexcharts/dist/apexcharts.min.js"></script> --}}
    {{-- daterangepicker --}}
    <script src="/assets/plugins/moment/min/moment.min.js"></script>
    <script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

    <script>
        var dateRanges = "{{ isset($ranges) ? $ranges : '' }}";
        var handleDaterangepicker = function() {
             // $('[data-id="prev-date"]').html(moment().add(-1, 'd').format('D MMM YYYY'));
            // $('[data-id="today-date"]').html(moment().format('D MMM YYYY'));

            // var start = moment().subtract(12 + 31, 'days');
            // var end = moment().subtract(13, 'days');

            var start = moment().startOf('month');
            var end = moment();

            if(dateRanges == 'Today'){
                start = moment();
                end = moment();
            } else if(dateRanges == 'Yesterday'){
                start = moment().subtract(1, 'days');
                end = moment().subtract(1, 'days');
            } else if(dateRanges == 'Last 7 Days'){
                start = moment().subtract(6, 'days');
                end = moment();
            } else if(dateRanges == 'Last 30 Days'){
                start = moment().subtract(29, 'days');
                end = moment();
            } else if(dateRanges == 'This Month'){
                start = moment().startOf('month');
                end = moment();
            } else if(dateRanges == 'Last Month'){
                start = moment().subtract(1, 'month').startOf('month');
                end = moment().subtract(1, 'month').endOf('month');
            } else if(dateRanges == 'This Year'){
                start = moment().startOf('year');
                end = moment();
            } else if(dateRanges == 'Last Year'){
                start = moment().subtract(1,'year');
                end = moment();
            } else if(dateRanges == "Custom Range"){
                start = moment(new Date('{{ $from }}'));
                end = moment(new Date('{{ $to }}'));
            }
        
            function cb(start, end,ranges) {
                $('#daterangepicker-date').text(start.format('MM/DD/YYYY') + ' TO ' + end.format('MM/DD/YYYY'));
                var startDate = start.format('YYYY-MM-DD');
                var endDate = end.format('YYYY-MM-DD');
               $('#startDate').val(startDate);
               $('#endDate').val(endDate);
               $('#range').val(ranges);
               $('form#dateFilter').submit();
            }
            $('#daterangepicker').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment()],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment()],
                'Last Year': [moment().subtract(1,'year'), moment()],
                }
          },cb);
        };
    var handleRenderMap = function() {
         $('#world-map').vectorMap({
            map: 'world_mill',
            normalizeFunction: 'polynomial',
            hoverOpacity: 0.5,
            hoverColor: false,
            zoomOnScroll: false,
            series: {
                regions: [{
                    normalizeFunction: 'polynomial'
                }]
            },
            focusOn: {
                x: 0.5,
                y: 0.5,
                scale: 1
            },
            markerStyle: {
                initial: {
                    fill: app.color.theme,
                    stroke: 'none',
                    "stroke-width": 2,
                }
            },
            regionStyle: {
                initial: {
                    fill: app.color.bodyColor,
                    "fill-opacity": 0.35,
                    stroke: 'none',
                    "stroke-width": 0.4,
                    "stroke-opacity": 1
                },
                hover: {
                    "fill-opacity": 0.5
                }
            },
            backgroundColor: 'transparent',
            markers: {!! isset($countries) ? json_encode($countries) : '' !!}
        });
    }
    var total_clicks_chart,pieChart1,pieChart2,pieChart3;
    var handleRenderChartJs = function() {
        var app_colors = [];
        $.each(app.color, function(name, color){
            if(!name.endsWith("Rgb") && !app_colors.includes(color)){
                app_colors.push(color);
            }
        });
        var ctx1 = document.getElementById('total-clicks-chart');
        total_clicks_chart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    color: app.color.theme,
                    backgroundColor: 'rgba(' + app.color.themeRgb + ', .2)',
                    borderColor: app.color.theme,
                    borderWidth: 1.5,
                    pointBackgroundColor: app.color.white,
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverBackgroundColor: app.color.theme,
                    pointHoverBorderColor: app.color.white,
                    pointHoverRadius: 7,
                    label: 'Clicks',
                    data: {!! json_encode($months_wise_total) !!}
                }, {
                    color: app.color.green,
                    backgroundColor: app.color.inverse,//'rgba(' + app.color.themeRgb + ', .2)',
                    borderColor: app.color.inverse,//app.color.green,
                    borderWidth: 1.5,
                    pointBackgroundColor: app.color.white,
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverBackgroundColor: app.color.green,
                    pointHoverBorderColor: app.color.white,
                    pointHoverRadius: 7,
                    label: 'Unique Clicks',
                    data: {!! json_encode($months_wise_unique) !!}
                }],
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: app.color.inverse//'rgba(255, 255, 255, 0.75)'
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    {{-- yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }] --}}
                }
            }
        });
        var ctx2 = document.getElementById('top-devices-chart');
        pieChart1 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: @if(isset($devices['name'])) {!! json_encode($devices['name']) !!} @else [] @endif,
                datasets: [{
                    data: @if(isset($devices['data'])) {!! json_encode($devices['data'])  !!} @else [] @endif,
                    backgroundColor: app_colors,
                    hoverBackgroundColor: app_colors,
                    borderWidth: 0
                }],
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    }
                }
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            color: app.color.inverse//'rgba(255, 255, 255, 0.75)'
                        }
                    }
                }
            }
        });
        // ctx2.parentNode.style.height = '300px';
        ctx2.parentNode.style.width = '300px';
         var ctx3 = document.getElementById('top-browsers-chart');
        pieChart2 = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: @if( isset($browsers['browser'])) {!! json_encode($browsers['browser']) !!} @else [] @endif,
                //['Chrome', 'Firefox', 'Edge', 'Safari'],
                datasets: [{
                    data:  @if(isset($browsers['total'])) {!! json_encode($browsers['total']) !!} @else [] @endif,
                    // [117, 3, 2, 1],
                    backgroundColor: app_colors,
                    hoverBackgroundColor: app_colors,
                    borderWidth: 0
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            color: app.color.inverse//'rgba(255, 255, 255, 0.75)'
                        }
                    }
                }
            }
        });
        ctx3.parentNode.style.width = '332px';
        var ctx4 = document.getElementById('top-platforms-chart');
        pieChart3 = new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: @if(isset($platforms['os'])) {!! json_encode($platforms['os']) !!} @else [] @endif,
                 //['Apple', 'Linux', 'Windows', 'Android'],
                datasets: [{
                    data: @if(isset($platforms['total'])) {!! json_encode($platforms['total']) !!} @else [] @endif,//[116, 3, 3, 1],
                    backgroundColor: app_colors,
                    hoverBackgroundColor: app_colors,
                    borderWidth: 0
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            color: app.color.inverse//'rgba(255, 255, 255, 0.75)'
                        }
                    }
                }
            }
        });
        ctx4.parentNode.style.width = '332px';
    };
    $(document).ready(function() {
        handleDaterangepicker();
	    handleRenderMap();
        handleRenderChartJs();
        document.addEventListener('theme-reload', function() {
            $('#world-map').empty();
            handleRenderMap();
            total_clicks_chart.destroy()
            pieChart1.destroy()
            pieChart2.destroy()
            pieChart3.destroy()
            handleRenderChartJs();
        });
    });
    </script>
@endpush