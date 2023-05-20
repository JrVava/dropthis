@extends('layout.default')

@section('title', 'Emails')

@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <style>
        .dataTables_length{
            display: none;
        }
        .group-block{
            position: relative;
            top:4px;
        }
        #group{
            position: absolute;
            z-index: 1;
        }
        input[type=search] {
            display: block !important;
            margin-left: 0 !important;
            width: 100% !important;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 300;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.75);
            background-color: transparent;
            background-clip: padding-box;
            border: 1px solid rgba(255, 255, 255, 0.3);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            margin-bottom: 0.25rem !important;
        }
        #csv{
            display: none;
        }
        #email-group tr td{
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
    <script>
        $(document).ready(function(){
            $("body").on("click", ".delete-link", function(e){
                e.preventDefault();
                let text = "Are you sure you want to delete this email?";
                if (confirm(text) == true) {
                    $(this).prev('form').submit();
                }
            });
            $("body").on('click','.email-status',function(){
                var url = $(this).attr('data-url');
                $.ajax({
                    url:url,
                    type:"GET",
                    success:function(res){
                        var table = $('.data-table').DataTable();
                        table.ajax.reload(null, false);
                    }
                });              
            });
            
        });
        // Datatable Yajra getting data along with data filter
        $(function () {  
            var i = 1;  
            var dataSet = [];      
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:"{{ route('emails') }}",
                    data: function (d) {
                        d.group = $('#group').val()
                    }
                },language: {
                    searchPlaceholder: "Search" // Placeholder
                },"oLanguage": {
                    "sSearch": "", // Remove Label text of search box
                }, pageLength: 25,
                columns: [
                   // {data: 'id', name: 'id'},
                   {data: 'DT_RowIndex', name: 'DT_RowIndex'}, // DT_RowIndex is call Index Column 
                    {data: 'artist', name: 'artist'},
                    // {data: 'email', name: 'email'},
                    {data: 'group', name: 'group'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'last_send', name: 'last_send'},
                    @if(auth::user()->user_role == USER_ROLE_ADMIN) {data: 'action', name: 'action', orderable: false, searchable: false}, @endif
                ],
                columnDefs: [{
                    "targets": 0
                }],
            });
            // getting data onChange for Custom Filter using dropdown
            $(document).ready(function() { 
                $('#group').on('change',function(){
                    var group = this.value;
                    table.draw();
                });
                $('#csv').on('change',function(){
                    $('#csvForm').submit();
                });
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
                        <li class="breadcrumb-item active">Emails</li>
                    </ul>
                    <h1 class="page-header mb-0">Emails</h1>
                </div>
                @if(auth::user()->user_role == USER_ROLE_ADMIN)
                <div class="ms-auto">
                    <a href="{{ route('email.create') }}" class="btn btn-outline-theme">
                        <i class="fa fa-plus-circle me-1"></i>Add New
                    </a>
                    <label class="btn btn-outline-success">
                        <i class="fa fa-upload me-1"></i>Import CSV File
                        <form action="{{ route('email.import') }}" method="post" id="csvForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="csv" id="csv">
                        </form>
                    </label>
                </div>
                @endif
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
                        <div class="col-sm-2 group-block mb-1">
                            <select class="form-select" id="group">
                                <option value="">Show All</option>
                                @foreach($groups as $key => $group)
                                    <option value="{{ $group->group }}">{{ $group->group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <table id="email-group" class="table w-100 table-bordered table-hover data-table">
                            <thead>                                
                                <tr>
                                    <th>#</th>
                                    <th>ARTIST</th>
                                    {{-- <th>E-MAIL</th> --}}
                                    <th>GROUP</th>
                                    <th>STATUS</th>
                                    <th>ADDED</th>
                                    <th>LAST SEND</th>
                                    @if(auth::user()->user_role == USER_ROLE_ADMIN)<th>Action</th>@endif
                                </tr>                                
                            </thead>
                            <tbody id="example">
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