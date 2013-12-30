@extends('layout')

@section('title')
Users
@stop

@section('content')
<h2>Users</h2>

@include('partial.success-error')

<table class="column">
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Column 3</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($users as $user)
		<tr>
			<td><a href="{{ $app->path('user.edit', array('user' => $user->id)) }}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
			<td>{{ $user->email }}</td>
			<td>Cell 3</td>
		</tr>
		@endforeach
	</tbody>
</table>

<a href="{{ $app->path('user.add') }}" class="button tiny radius"><i class="fa fa-plus"></i> Add a user</a>
@stop