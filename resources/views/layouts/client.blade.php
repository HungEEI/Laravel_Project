<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>{{$pageTitle}}</title>
		<link rel="stylesheet" href="{{asset('client/css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/slick.css')}}" />
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
		<link rel="stylesheet" href="{{asset('client/css/reset.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/header.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/home.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/course.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/course-detail.css')}}" />
		<link rel="stylesheet" href="{{asset('client/css/footer.css')}}" />
		<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
        @yield('stylesheets')
	</head>
<body>
    @include('parts.client.header')

    <main>
        @yield('content')
    </main>

    @include('parts.client.footer')
	@yield('scripts')
	<div class="modal fade" id="modal"">
		<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

			</div>
		</div>
		</div>
	</div>
</body>
    <script src="{{asset('client/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('client/js/jquery.min.js')}}"></script>
	<script src="{{asset('client/js/jquery-migrate-1.2.1.min.js')}}"></script>
	<script src="{{asset('client/js/slick.min.js')}}"></script>
	<script src="{{asset('client/js/slider-home.js')}}"></script>
	<script src="{{asset('client/js/home.js')}}"></script>
	<script src="{{asset('client/js/accordion.js')}}"></script>
	<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
</html>