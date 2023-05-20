@extends('layout.default')

@section('title', 'User')
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
        #users-groups tr td{
            padding-top:4px;
            padding-bottom:3px;  
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
        function getUserDetails(id){
            $.ajax({
                url:"{{ route('get.user-details') }}",
                type: "POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },success:function(res){
                    
                    $('#get-user-details').html('');
                    var html ='<div class="modal-content">';
                        html +='<div class="modal-header">';
                        html +='<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                        html +='</div>';
                        html +='<div class="modal-body">';
                        html +='<table>';
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >Name</label>';
                        html +='</td>';
                        html +='<td>';
                        html +='<label id="user-name" > : '+res.user.name+'</label>';
                        html +='</td>';
                        html +='</tr>';
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >Email</label>';
                        html +='</td>';
                        html +='<td>';
                        html +='<label id="user-name" > : '+res.user.email+'</label>';
                        html +='</td>';
                        html +='</tr>';
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >Can submit feedbacks</label>';
                        html +='</td>';
                        html +='<td>';
                            var can_submit_feedbacks  = res.user.can_submit_feedbacks == 1 ? "Yes" : "No";
                        html +='<label id="user-name" > : '+can_submit_feedbacks +'</label>';
                        html +='</td>';
                        html +='</tr>';
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >website</label>';
                        html +='</td>';
                        html +='<td>';
                            var website = res.user.website == null ? '-' : res.user.website;
                        html +='<label id="user-name" > : '+website+'</label>';
                        html +='</td>';
                        html +='</tr>';
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >Background Color</label>';
                        html +='</td>';
                        html +='<td>';
                            var bg_color = res.user.bg_color == null ? '-' : res.user.bg_color;
                        html +='<label id="user-name" > : '+bg_color+'</label>';
                        html +='</td>';
                        html +='</tr>';
                        if(res.logo != null){
                            html +='<tr>';
                            html +='<td>';
                            html +='<label class="user-label" >logo</label>';
                            html +='</td>';
                            html +='<td>';
                            html +=' : <img src="'+res.logo+'" alt="" width="50" height="50">';
                            html +='</td>';
                            html +='</tr>';
                        }
                        if(res.bg_image != null){
                            html +='<tr>';
                            html +='<td>';
                            html +='<label class="user-label" >Background Image</label>';
                            html +='</td>';
                            html +='<td>';
                            html +=' : <img src="'+res.bg_image+'" alt="" width="50" height="50">';
                            html +='</td>';
                            html +='</tr>';
                        }
                        html +='<tr>';
                        html +='<td>';
                        html +='<label class="user-label" >Credits</label>';
                        html +='</td>';
                        html +='<td>';
                        html +='<label id="user-name" > : '+res.user.credits+'</label>';
                        html +='</td>';
                        html +='</tr>';
                        html +='</table>';
                        html +='</div>';
                        html +='<div class="modal-footer">';
                        html +='<a href="#" class="btn btn-outline-default" data-bs-dismiss="modal">Cancel</a>';
                        html +='</div>';
                        html +='</div>';
                    console.log(res.user.id);
                    $('#get-user-details').html(html);
                }
            })
        }
        // $(document).ready(function(){
        $("body").on("click", ".plan-delete-link", function(e){
            e.preventDefault();
            let text = "Are you sure you want to delete this plan?";
            if (confirm(text) == true) {
                $(this).prev('form').submit();
            }
        });
        // Datatable Yajra getting data along with data filter
        $(function () {  
            var i = 1;  
            var dataSet = [];      
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:"{{ route('users') }}",
                },language: {
                    searchPlaceholder: "Search" // Placeholder
                },"oLanguage": {
                    "sSearch": "", // Remove Label text of search box
                }, pageLength: 25,
                columns: [
                   // {data: 'id', name: 'id'},
                   {data: 'DT_RowIndex', name: 'DT_RowIndex'}, // DT_RowIndex is call Index Column 
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'can_submit_feedbacks', name: 'can_submit_feedbacks'},
                    {data: 'website', name: 'website'},
                    {data: 'credits', name: 'credits'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
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
                        <li class="breadcrumb-item active">Users</li>
                    </ul>
                    <h1 class="page-header mb-0">Users</h1>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('users.create') }}" class="btn btn-outline-theme">
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

            <div id="bootstrapTable">
                <div class="card">
                    <div class="card-body">
                        <table id="users-groups" class="table w-100 table-bordered table-hover data-table">
                            <thead>                                
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Can Submit Feedbacks</th>
                                    <th>Website</th>
                                    <th>Credit</th>
                                    <th>Status</th>
                                    <th>Created on</th>
                                    <th>Action</th>
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

    <!-- BEGIN modal -->
    <div class="modal fade" id="modalAddTask">
        <div class="modal-dialog modal-lg" id="get-user-details">

            {{-- <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <td>
                                <label class="user-label" >Name : </label>    
                            </td>
                            <td>
                                <label id="user-name" >Ashish Sitaram</label> 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >Email</label> 
                            </td>
                            <td>
                                <label id="user-name" >ashishp.brainerhu@gmail.com</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >can_submit_feedbacks</label> 
                            </td>
                            <td>
                                <label id="user-name" >Yes</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >website</label> 
                            </td>
                            <td>
                                <label id="user-name" >http://localhost/phpmyadmin/index.php?route=/table/structure&db=dropthis&table=users</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >Background Color</label> 
                            </td>
                            <td>
                                <label id="user-name" >#ff6060</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >logo</label> 
                            </td>
                            <td>
                                <img src="http://127.0.0.1:8000/storage/uploads/user/1/MRob2ngHqB00ZzviXX3E.jpg" alt="" width="100" height="100">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >Background Image</label> 
                            </td>
                            <td>
                                <img src="http://127.0.0.1:8000/storage/uploads/user/1/MRob2ngHqB00ZzviXX3E.jpg" alt="" width="100" height="100">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="user-label" >Credits</label> 
                            </td>
                            <td>
                                <label id="user-name" >10</label>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-default" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div> --}}

        </div>
    </div>
    <!-- END modal -->
@endsection