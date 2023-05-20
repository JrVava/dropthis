@extends('layout.default')

@section('title', 'Links')

@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <style>
        #datatableDefault tr td{
            padding-top: 4px; 
            padding-bottom:4px
        }
        .link_footer{
            display: flex;
            margin-top: 18px;
        }
        .link_lengthpage{
            padding-top: 8px;
            
        }
        [name="datatableDefault_length"]{
            /* background-color:  var(--bs-theme);
            border: 2px solid var(--bs-theme); */
            border-radius: 5px;
            /* width: 38%; */
        }
    </style>
@endpush

@push('js')
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
    <script>
        $(document).ready(function(){
            $('.link-delete').click(function(e){
                e.preventDefault();
                let text = "Are you sure you want to delete this link?";
                if (confirm(text) == true) {
                    $(this).prev('form').submit();
                }
            });
            $(".copy-btn").click(function(){
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(this).data("url").trim()).select();
                document.execCommand("copy");
                var _this = $(this);
                $(this).parent().parent().append('<div style="padding-left: 15px" class="text-lime">Copied!</div>');
                $temp.remove();
                setTimeout(function(){
                    _this.parent().parent().find('div.text-lime').remove();
                }, 1000);
            });
        });
    </script>
    <script>
        $('#datatableDefault').DataTable({
            dom: '<"top">rt<"bottom"<"row link_footer"<"col-sm-2"i><"col-sm-4 link_lengthpage"l><"col-sm-6"p>>>',
            "lengthMenu": [[10, 20, 30, -1],
                    [10, 20, 30, 'All']],
            "language": { "search": "",searchPlaceholder: "Search" },
            "iDisplayLength": 20,
            searching: false,
            "ordering": false,
            "language": {
                "info": "Showing page _START_ to _END_ of _TOTAL_ rows"
             },
             "language": {
                "lengthMenu": "_MENU_ rows per page"
            }
            // "bPaginate": false,
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
                        <li class="breadcrumb-item active">Links</li>
                    </ul>

                <h1 class="page-header mb-0">
                    Links
                </h1>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('link.create') }}" class="btn btn-outline-theme">
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

            <div id="bootstrapTable" class="mb-5">
                <div class="card">
                    <div class="card-body">
                        <table id="datatableDefault" class="table w-100"
                            data-toggle="table"
                            data-sort-class="table-active"
                            data-sortable="true"
                            data-search="true"
                            {{-- data-pagination="true" --}}
                            data-show-refresh="false"
                            data-show-columns="false"
                            data-show-fullscreen="false"
                            >
                            <thead>
                                <tr>
                                    <th data-sortable="true">#</th>
                                    <th data-sortable="true">Name</th>
                                    <th data-sortable="true">Clicks</th>
                                    <th data-sortable="true">Redirect Type</th>
                                    <th data-sortable="true">Groups</th>
                                    <th data-sortable="true">Target URL</th>
                                    <th data-sortable="true">Link</th>
                                    <th data-sortable="true">Created On</th>
                                    <th data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($link as $key => $val)
                                <tr>
                                    <th scope="row">
                                        {{ $key+1 }}
                                    </th>
                                    <td>
                                        <img class="h-6 w-6 mr-2" style="min-width: 1.5rem;" src="https://www.google.com/s2/favicons?domain={{ $val->url }}" title="{{ $val->url }}"/>
                                        {{ $val->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('link.statistics',['id'=>$val->id]) }}" class="text-white text-opacity-50 text-decoration-none" title="Unique Clicks / Total Clicks">
                                            {{ $val->click->where('is_first_click', 1)->count() }} / {{ $val->click->count() }}
                                        </a>
                                    </td>
                                    <td>{{ $val->redirect_type }}</td>
                                    <td>
                                        @php $groups = []; @endphp
                                        @foreach ($val->linksGroup as $key => $group)
                                            @php $groups[] = $group->group->name; @endphp
                                        @endforeach
                                        {!! implode( ', ', $groups ) !!}
                                    </td>
                                    <td title="{{ $val->url }}">
                                        {{ strlen($val->url) > 20 ? substr($val->url, 0, 20)."..." : $val->url; }}
                                    </td>
                                    <td>{{ $val->slug }}</td>
                                    <td>{{ $val->created_at->format('F d,Y') }}</td>

                                    <td>
                                        <div class="btn btn-group p-0">
                                            <a href="{{ route('link.statistics',['id'=>$val->id]) }}" class="btn btn-outline-info" title="Statistics">
                                                <i class="fa fa-chart-bar"></i>
                                            </a>
                                            @if(isset(unserialize($val->rules)[1]))
                                                <a data-url="@if(unserialize($val->rules)[1] == "any"){{ url('/')."/".$val->slug }}@else{{ unserialize($val->rules)[1]."/".$val->slug }}@endif" title="Copy Short URL" href="javascript:;" class="btn btn-outline-lime copy-btn">
                                                    <i class="fas fa-copy"></i>
                                                </a>
                                            @else
                                                <a data-url="{{ route('link',['slug'=>$val->slug]) }}" title="Copy Short URL" href="javascript:;" class="btn btn-outline-lime copy-btn">
                                                    <i class="fas fa-copy"></i>
                                                </a>                                            
                                            @endif

                                            <a title="Edit" href="{{ route('link.edit',['id'=>$val->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" action="{{ route('link.delete',['id'=>$val->id]) }}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            <a title="Delete" href="#" data-url="" class="btn btn-outline-danger link-delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
@endsection
