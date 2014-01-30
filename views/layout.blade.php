<!doctype html>
<html>
	<head>
		<title>Deployer - @yield('title')</title>

		<base href="http://php55/deployer/public/"><!-- @TODO: remove this -->

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="stylesheet" type="text/css" href="assets/deployer/css/main.css">

		<script type="text/javascript" src="assets/modernizr/modernizr.js"></script>
	</head>
	<body>
		<div class="fixed">
			<nav class="top-bar" data-topbar>
				<ul class="title-area">
					<li class="name">
						<h1><a href=""><i class="fa fa-download"></i> Deployer</a></h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>

				@if ($app['sentry']->check())
				<section class="top-bar-section">
					<ul class="right">
						<li>
							<a href="{{ $app->path('account') }}">Welcome back, User</a>
						</li>
					</ul>

					<ul class="left">
						<li>
							<a href="{{ $app->path('home') }}" class="item">
								<i class="fa fa-home"></i> Home
							</a>
						</li>
						<li>
							<a href="{{ $app->path('project.list') }}" class="item">
								<i class="fa fa-folder-open"></i> Projects
							</a>
						</li>
						<li>
							<a href="{{ $app->path('user.list') }}" class="item">
								<i class="fa fa-users"></i> Users
							</a>
						</li>
					</ul>
				</section>
				@endif
			</nav>
		</div>

		<div class="container">
			@yield('content')
		</div>

		<script src="assets/jquery/jquery.min.js"></script>
		<script src="assets/foundation/js/foundation.min.js"></script>
		<script>
			$(document).foundation();
		</script>
	</body>
</html>
