<!DOCTYPE html>
<html lang="{{ $lang }}">
	<head>
		<meta charset="{{ $charset }}">

		<title>{{ $page_title }}</title>

	@foreach ($metadata as $name => $content)
		<meta name="{{ $name }}" content="{{ $content }}">
	@endforeach

		<link href="{{ URL::to_asset('css/bootstrap.css') }}" rel="stylesheet">
		<style>
			body {
				padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>

	@if ($responsive_layout)
		<link href="{{ URL::to_asset('css/bootstrap-responsive.css') }}" rel="stylesheet">
	@endif

		<link href="{{ URL::to_asset('bundles/squire/css/squire.css') }}" rel="stylesheet">

		{{ Asset::container('header')->styles() }}
		{{ Asset::container('header')->scripts() }}

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>

		{{ $top }}

		{{ $header }}

		{{ $container }}

		{{ $footer }}

		{{ Asset::container('footer')->styles() }}

		<script src="{{ URL::to_asset('js/jquery.min.js') }}"></script>
		<script src="{{ URL::to_asset('js/bootstrap.min.js') }}"></script>

		{{ Asset::container('footer')->scripts() }}

	</body>
</html>
