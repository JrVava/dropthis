@extends('layout.default')

@section('title', 'Order History')
@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <style>
        .dataTables_length{
            display: none;
        }
        
    </style>
@endpush

@push('js')
    <script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script>
        // Datatable Yajra getting data along with data filter
        $(function () {  
            var i = 1;  
            var dataSet = [];      
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:"{{ route('order-history') }}",
                },language: {
                    searchPlaceholder: "Search" // Placeholder
                },"oLanguage": {
                    "sSearch": "", // Remove Label text of search box
                }, pageLength: 25,
                columns: [
                   // {data: 'id', name: 'id'},
                   {data: 'DT_RowIndex', name: 'DT_RowIndex'}, // DT_RowIndex is call Index Column 
                   {data:'user',name:'user', visible:'{{ $visible_user_column }}'},
                   {data:'plan',name:'plan'},
                   {data: 'payment_method', name: 'payment_method'},
                   {data: 'amount', name: 'amount'},
                   {data: 'no_of_credits', name: 'no_of_credits'},
                   {data: 'currency', name: 'currency'},
                   {data: 'status', name: 'status'},
                   {data: 'transaction_create_time', name: 'transaction_create_time'},
                ],
                columnDefs: [{
                    "targets": 0
                }],
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
                        <li class="breadcrumb-item active">Order History</li>
                    </ul>
                    <h1 class="page-header mb-0">Order History</h1>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('coupon.create') }}" class="btn btn-outline-theme">
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
                        <table class="table w-100 table-bordered table-hover data-table">
                            <thead>                                
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Payment mode</th>
                                    <th>Amount</th>
                                    <th>Credits</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                    <th>Created on</th>
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