@extends('layout')

@section('title')
Hosts
@stop

@section('content')
<h2>Hosts</h2>

@include('partial.success-error')

@if (!$hosts->isEmpty())
<table class="column">
	<thead>
		<tr>
			<th>Host</th>
			<th>Number of projects</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($hosts as $host)
		<tr>
			<td><a href="{{ $app->path('host.edit', array('host' => $host->id)) }}">{{{ $host->name }}}</a></td>
			<td>Cell 2</td>
		</tr>
		@endforeach
	</tbody>
</table>
@else
<div class="panel information radius">
	<p>There are currently no hosts</p>
</div>
@endif

<a href="{{ $app->path('host.add') }}" class="button tiny radius"><i class="fa fa-plus"></i> Add a host</a>

@stop