@extends('layout')

@section('title')
Users
@stop

@section('content')
<h2>Users</h2>

@include('partial.success-error')

@if (!empty($users))
<table class="column">
	<thead>
		<tr>
			<th class="small-5">Name</th>
			<th class="small-5">Email</th>
			<th class="small-2 text-center">Actions</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($users as $user)
		<tr>
			<td>{{ $user->first_name }} {{ $user->last_name }}</td>
			<td>{{ $user->email }}</td>
			<td class="text-center">
				<a href="{{ $app->path('user.edit', array('user' => $user->id)) }}" class="action" title="Edit">
					<i class="fa fa-pencil"></i>
				</a>
				<a href="{{ $app->path('user.delete', array('user' => $user->id)) }}" class="action alert" title="Delete">
					<i class="fa fa-ban"></i>
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@else
<p>There are currently no users</p>
@endif

<a href="{{ $app->path('user.add') }}" class="button tiny radius"><i class="fa fa-plus"></i> Add a user</a>
@stop