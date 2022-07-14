<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AdminLTE 3 | Log in</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="/css/adminlte.min.css?v=3.2.0">
	<link rel="stylesheet" href="{{ asset ('css/style.css?v=' . filemtime(public_path('css/style.css'))) }}">

	<script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
	<script src="/js/global.js"></script>
</head>
<body class="hold-transition login-page">

@yield('content')

<div class="errors-list section-shadow" id="errors_list">
    @include('blocks.errors')
    @include('blocks.status')
</div>

<script src="https://adminlte.io/themes/v3/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js?v=3.2.0"></script>
<script src="https://adminlte.io/themes/v3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>$(function () {  bsCustomFileInput.init(); });</script>

<script src="{{ asset ('js/main.js?v=' . filemtime(public_path('js/main.js'))) }}"></script>
</body>
</html>