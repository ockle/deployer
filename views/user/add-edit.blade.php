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
			<label>First name</label>
			<input type="text" name="first_name" value="{{{ $app->oldValue('first_name') ?: (isset($user->first_name) ? $user->first_name : '') }}}">

			<label>Last name</label>
			<input type="text" name="last_name" value="{{{ $app->oldValue('last_name') ?: (isset($user->last_name) ? $user->last_name : '') }}}">

			<label>Email address</label>
			<input type="text" name="email" value="{{{ $app->oldValue('email') ?: (isset($user->email) ? $user->email : '') }}}">

			<label>Password</label>
			<input type="password" name="password">

			<label>Confirm password</label>
			<input type="password" name="password_confirmation">
		</div>

		<div class="content column" id="host-accounts">
			@if (!$hosts->isEmpty())
				@foreach ($hosts as $host)
				<label>{{{ $host->name }}}</label>
				<input type="text" name="hosts[{{ $host->id }}]" value="{{{ (isset($user->hosts) && $user->hosts->contains($host->id)) ? $user->hosts->find($host->id)->pivot->username : '' }}}">
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