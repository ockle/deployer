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
						<h1><i class="fa fa-download"></i> Deployer</h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
				</ul>

				@if ($app['sentry']->check())
				<section class="top-bar-section">
					<ul class="right">
						<li class="has-dropdown">
							<a href="javascript:void(0)">
								<i class="fa fa-user"></i> {{{ $app['sentry']->getUser()->first_name }}} {{{ $app['sentry']->getUser()->last_name }}}
							</a>
							<ul class="dropdown">
								<!-- <li>
									<a href="{{-- $app->path('account') --}}">
										<i class="fa fa-book"></i> Account
									</a>
								</li> -->
								<li>
									<a href="{{ $app->path('logout') }}">
										<i class="fa fa-power-off"></i> Logout
									</a>
								</li>
							</ul>
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
