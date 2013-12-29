@extends('layout')

@section('title')
{{ ucfirst($type) }} a user
@stop

@section('content')
<h2>{{ ucfirst($type) }} a user</h2>

@if (isset($errorMessages) && !empty($errorMessages))
<div class="error panel radius">
	<ul>
		@foreach ($errorMessages as $message)
		<li>{{ $message }}</li>
		@endforeach
	</ul>
</div>
@endif

<form action="" method="post">
	<fieldset>
		<legend>Details</legend>

		<label>First name</label>
		<input type="text" name="first_name" value="{{ $app->oldValue('first_name') ?: (isset($user->first_name) ? $user->first_name : '') }}">

		<label>Last name</label>
		<input type="text" name="last_name" value="{{ $app->oldValue('last_name') ?: (isset($user->last_name) ? $user->last_name : '') }}">

		<label>Email address</label>
		<input type="text" name="email" value="{{ $app->oldValue('email') ?: (isset($user->email) ? $user->email : '') }}">

		<label>Password</label>
		<input type="password" name="password">

		<label>Confirm password</label>
		<input type="password" name="password_confirmation">
	</fieldset>

	<fieldset>
		<legend>Host accounts</legend>

		<label>Bitbucket</label>
		<input type="text">

		<label>Github</label>
		<input type="text">
	</fieldset>

	<input type="hidden" name="type" value="{{ $type }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('user.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop