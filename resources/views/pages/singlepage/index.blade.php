@extends('layout.default')

@section('title', 'Settings')

@push('js')
    <script src="/assets/js/sidebar-scrollspy.demo.js"></script>
@endpush

@section('content')
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN row -->
        <div class="row justify-content-center">
            <!-- BEGIN col-10 -->
            <div class="col-xl-10">

               
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-9 -->
                    <div class="col-xl-9">
                        <!-- BEGIN #general -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissable fade show p-3 d-flex">
                                <div class="flex-fill">{{ session('status') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @include('pages.label-setting.profile')
                        {{-- <div id="general" class="mb-5">
                            <h4><i class="far fa-user fa-fw text-theme"></i> General</h4>
                            <p>View and update your general account information and settings.</p>
                            <div class="card">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-1 text-break">
                                            <div>Name</div>
                                            <div class="text-inverse text-opacity-50">Sean Ngu</div>
                                        </div>
                                        <div class="w-100px">
                                            <a href="#modalEdit" data-bs-toggle="modal"
                                                class="btn btn-outline-default w-100px">Edit</a>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-1 text-break">
                                            <div>Username</div>
                                            <div class="text-inverse text-opacity-50">@seantheme</div>
                                        </div>
                                        <div>
                                            <a href="#modalEdit" data-bs-toggle="modal"
                                                class="btn btn-outline-default w-100px">Edit</a>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-1 text-break">
                                            <div>Phone</div>
                                            <div class="text-inverse text-opacity-50">+1-202-555-0183</div>
                                        </div>
                                        <div>
                                            <a href="#modalEdit" data-bs-toggle="modal"
                                                class="btn btn-outline-default w-100px">Edit</a>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-1 text-break">
                                            <div>Email address</div>
                                            <div class="text-inverse text-opacity-50">support@seantheme.com</div>
                                        </div>
                                        <div>
                                            <a href="#modalEdit" data-bs-toggle="modal"
                                                class="btn btn-outline-default w-100px">Edit</a>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-1 text-break">
                                            <div>Password</div>
                                        </div>
                                        <div>
                                            <a href="#modalEdit" data-bs-toggle="modal"
                                                class="btn btn-outline-default w-100px">Edit</a>
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
                        </div> --}}
                        <!-- END #general -->

                       @include('pages.label-setting.label')

                        <!-- BEGIN #labelSettings -->
                        @include('pages.smtp.index')
                        <!-- END #labelSettings -->

                    </div>
                    <!-- END col-9-->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-3">
                        <!-- BEGIN #sidebar-bootstrap -->
                        <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                            <nav class="nav">
                                <a class="nav-link" href="#general" data-toggle="scroll-to">General Settings</a>
                                <a class="nav-link" href="#label-section" data-toggle="scroll-to">Label Settings</a>
                                <a class="nav-link" href="#smtp-section" data-toggle="scroll-to">SMTP</a>
                                {{-- <a class="nav-link" href="#smtp-section" data-toggle="scroll-to">Notifications</a> --}}
                            </nav>
                        </nav>
                        <!-- END #sidebar-bootstrap -->
                    </div>
                    <!-- END col-3 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END col-10 -->
        </div>
        <!-- END row -->
    </div>
    <!-- END container -->

@endsection
