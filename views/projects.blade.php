@extends('layout')

@section('title')
Projects
@stop

@section('content')
<h2>Projects</h2>

<table class="column">
	<thead>
		<tr class="row">
			<th class="small-4 columns">Name</th>
			<th class="small-4 columns">Depoyment Destination</th>
			<th class="small-4 columns">Host</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($projects as $project)
		<tr class="row">
			<td class="small-4 columns"><a href="{{ $app->path('project.read', array('project' => $project->id)) }}">{{ $project->name }}</a></td>
			<td class="small-4 columns">/var/www/site.com/</td>
			<td class="small-4 columns">Bitbucket</td>
		</tr>
		@endforeach
	</tbody>
</table>

<a href="{{ $app->path('project.add') }}" class="tiny radius button">
	<i class="fa fa-plus"></i>
	Add Project
</a>
@stop