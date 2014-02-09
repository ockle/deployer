@extends('layout')

@section('title')
{{ ucfirst($type) }} a user
@stop

@section('content')
<h2>{{ ucfirst($type) }} a user</h2>

@include('partial.success-error')

<dl class="tabs" data-tab>
	<dd class="active">
		<a href="#details">Details</a>
	</dd>
	<dd>
		<a href="#host-accounts">Host accounts</a>
	</dd>
</dl>


<form action="" method="post">
	<div class="tabs-content">
		<div class="content active column" id="details">
			<label for="user_first_name">First name</label>
			<input type="text" name="first_name" value="{{{ $app->oldValue('first_name') ?: (isset($user->first_name) ? $user->first_name : '') }}}" id="user_first_name">

			<label for="user_last_name">Last name</label>
			<input type="text" name="last_name" value="{{{ $app->oldValue('last_name') ?: (isset($user->last_name) ? $user->last_name : '') }}}" id="user_last_name">

			<label for="user_email">Email address</label>
			<input type="text" name="email" value="{{{ $app->oldValue('email') ?: (isset($user->email) ? $user->email : '') }}}" id="user_email">

			<label for="user_password">Password</label>
			<input type="password" name="password" id="user_password">

			<label for="user_password_confirmation">Confirm password</label>
			<input type="password" name="password_confirmation" id="user_password_confirmation">
		</div>

		<div class="content column" id="host-accounts">
			@if (!empty($hosts))
				@foreach ($hosts as $host)
				<label for="user_host-{{{ $host }}}">{{{ $host }}}</label>
				<input type="text" name="hosts[{{ $host }}]" value="{{{ $app->oldValue(array('hosts', $host)) ?: ((isset($usernames) && array_key_exists($host, $usernames)) ? $usernames[$host] : '') }}}" id="user_host-{{{ $host }}}">
				@endforeach
			@else
			<div class="information panel radius">
				<p>There are currently no hosts</p>
			</div>
			@endif
		</div>
	</div>

	<input type="hidden" name="type" value="{{ $type }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('user.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop