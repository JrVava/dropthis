@extends('layout.default')

@section('title', 'Group')

@push('js')
    <script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="/assets/js/demo/highlightjs.demo.js"></script>
    <script src="/assets/js/demo/sidebar-scrollspy.demo.js"></script>
@endpush

@section('content')
    <!-- BEGIN row -->
    <div class="row">
        <!-- BEGIN col-9 -->
        <div class="col-xl-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('groups') }}">Groups</a></li>
                <li class="breadcrumb-item active">Edit Group</li>
            </ul>

            <h1 class="page-header">
                Edit Group <!-- <small>page header description goes here...</small> -->
            </h1>

            <hr class="mb-4" />

            <!-- BEGIN #readonlyPlainText -->
            <div id="readonlyPlainText" class="mb-5">
                <!-- <h4>Readonly plain text</h4>
                <p>If you want to have <code>&lt;input readonly&gt;</code> elements in your form styled as plain text, use the .form-control-plaintext class to remove the default form field styling and preserve the correct margin and padding.</p> -->
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('group.update') }}">
                        	@csrf
                        	<input type="hidden" value="{{ $group->id }}" name="id">
                            <div class="form-group row mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control plaintext" id="name" name="group" placeholder="Name" value="{{ $group->name }}">
									@if ($errors->has('group'))
									<span class="text-danger">{{ $errors->first('group') }}</span>
									@endif
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                	<textarea class="form-control" name="description" placeholder="Description">{{ $group->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-theme">Save Changes</button>
                                    <a href="{{ route('groups') }}" class="btn btn-outline-warning">Cancel</a>
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