@extends('layout')

@section('title')
Logout
@stop

@section('content')

<h2>Logout?</h2>

<form action="{{ $app->path('logout') }}" method="post">
	<ul class="button-group radius">
		<li class="small-12 medium-6">
			<button type="submit" class="large success button">Yes</button>
		</li>
		<li class="small-12 medium-6">
			<a href="{{ $app->path('home') }}" class="large alert button">No</a>
		</li>
	</ul>
</form>
@stop