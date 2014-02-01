@extends('layout')

@section('title')
Login
@stop

@section('content')

<form action="{{ $app->path('login') }}" method="post" class="row">
	<div class="small-12 medium-7 large-6 small-centered columns">
		@include('partial.success-error')

		<h3>Please login</h3>

		<div class="panel">
			<input type="text" name="email" placeholder="Email" value="{{{ $app->oldValue('email') ?: '' }}}">
			<input type="password" name="password" placeholder="Password">

			<button type="submit" class="radius tiny small-12">Login</button>
		</div>
	</div>
</form>
@stop