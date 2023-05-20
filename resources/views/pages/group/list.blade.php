@extends('layout.default')

@section('title', 'Groups')

@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <style>
        #group-table tr td{
            vertical-align: middle;
            padding-top:4px;
            padding-bottom:4px;  
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
            $('.group-delete').click(function(e){
                e.preventDefault();
                let text = "Are you sure you want to delete this group?";
                if (confirm(text) == true) {
                    $(this).prev('form').submit();
                }
            });
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
                        <li class="breadcrumb-item active">Groups</li>
                    </ul>
                    <h1 class="page-header mb-0">Groups</h1>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('group.create') }}" class="btn btn-outline-theme">
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
                        <table id="group-table" class="table w-100"
                            data-toggle="table"
                            data-sort-class="table-active"
                            data-sortable="true"
                            data-search="true"
                            data-pagination="true"
                            data-show-refresh="false"
                            data-show-columns="false"
                            data-show-fullscreen="false"
                            >
                            <thead>
                                <tr>
                                    <th class="border-bottom" data-sortable="true">#</th>
                                    <th class="border-bottom" data-sortable="true">Name</th>
                                    <th class="border-bottom" data-sortable="true">Links</th>
                                    <th class="border-bottom" data-sortable="true">Clicks</th>
                                    <th class="border-bottom" data-sortable="true">Created On</th>
                                    <th class="border-bottom" data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $key => $group)
                                <tr>
                                    <th scope="row">{{ $key+1 }}</th>
                                    <td>{{ $group->name }}</td>
                                    <td>
                                        <a href="{{ route('group.statistics',['id'=>$group->id]) }}" class="text-inverse text-decoration-none" title="Total Links">
                                            {{ $group->grp_link_count }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('group.statistics',['id'=>$group->id]) }}" class="text-inverse text-decoration-none" title="Unique Clicks / Total Clicks">
                                            {{ $group->groupClick() }}
                                        </a>
                                    </td>
                                    <td>{{ $group->created_at->format('F d,Y') }}</td>
                                    <td>
                                        <div class="btn btn-group p-0">
                                            <a href="{{ route('group.statistics',['id'=>$group->id]) }}" class="btn btn-outline-info" title="Statistics">
                                                <i class="fa fa-chart-bar"></i>
                                            </a>
                                            <a href="{{ route('group.edit',['id'=>$group->id]) }}" title="Edit" class="btn btn-outline-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" action="{{ route('group.delete',['id'=>$group->id]) }}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            <a href="#" data-url="" class="btn btn-outline-danger group-delete" title="Delete">
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
