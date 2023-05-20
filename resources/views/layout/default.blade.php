<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"{{ (!empty($htmlAttribute)) ? $htmlAttribute : '' }}>
<head>
	@include('partial.head')
</head>
<body class="{{ (!empty($bodyClass)) ? $bodyClass : '' }}">
	<!-- BEGIN #app -->
	<div id="app" class="app {{ auth::user() ? '' : 'app-sidebar-collapsed' }} {{ (!empty($appClass)) ? $appClass : '' }}">
	{{-- <div @if(isset(auth::user()->id)) id="app" @endif class="app @if(isset(auth::user()->id)){{ (!empty($appClass)) ? $appClass : '' }}@else app-sidebar-collapsed @endif"> --}}
	    @includeWhen(empty($appHeaderHide), 'partial.header')

		@includeWhen(empty($appSidebarHide), 'partial.sidebar')

		@if (empty($appContentHide))
            <!-- BEGIN #content -->
            <div id="content" class="app-content  {{ (!empty($appContentClass)) ? $appContentClass : '' }}">
                @yield('content')
            </div>
            <!-- END #content -->
		@else
            @yield('content')
		@endif

		@includeWhen(!empty($appFooter), 'partial.footer')
	</div>
	<!-- END #app -->

	@include('partial.scroll-top-btn')

	@include('partial.theme-panel')

	@include('partial.scripts')
</body>
</html>
