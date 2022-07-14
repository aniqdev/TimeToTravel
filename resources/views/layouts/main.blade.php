<!DOCTYPE html>
<html lang="en">
<head>
	<!-- AdminLTE 3 | Dashboard 3 -->
	<!-- https://adminlte.io/themes/v3/index3.html -->
	<title>Time To Travel</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/css/adminlte.min.css?v=3.2.0">
	<link rel="stylesheet" href="/css/lightbox.min.css">
	<link rel="stylesheet" href="{{ asset ('css/style.css?v=' . filemtime(public_path('css/style.css'))) }}">

	<script src="/js/jquery.min.js"></script>
	<script src="/js/global.js"></script>
</head>

<body class="hold-transition sidebar-mini">

	@include('layouts.wrapper')

	<div class="errors-list section-shadow" id="errors_list">
	    @include('blocks.errors')
	    @include('blocks.status')
	</div>

@yield('modals')

<script src="/js/jquery-ui.min.js"></script>
<script src="https://www.cssscript.com/demo/reordering-grid-items-via-drag-drop-grabbable/grabbable.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/adminlte.min.js?v=3.2.0"></script>
<script src="/js/lightbox.min.js"></script>
{{-- <script src="/js/demo.js"></script> --}}{{-- for sutomize menu --}}

<script src="/js/bs-custom-file-input.min.js"></script>
<script>$(function () {  bsCustomFileInput.init(); });</script>

<script src="{{ asset ('js/main.js?v=' . filemtime(public_path('js/main.js'))) }}"></script>

</body>
</html>