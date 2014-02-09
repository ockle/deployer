@extends('layout')

@section('title')
Projects
@stop

@section('content')
<h2>Projects</h2>

@include('partial.success-error')

<table class="column">
	<thead>
		<tr class="row">
			<th class="small-4">Name</th>
			<th class="small-4">Directory</th>
			<th class="small-2">Last deployed</th>
			<th class="small-2 text-center">Actions</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($projects as $project)
		<tr class="row">
			<td><a href="{{ $app->path('project.view', array('project' => $project->id)) }}">{{ $project->name }}</a></td>
			<td>{{{ $project->directory }}}</td>
			<td>
				@if (!is_null($project->lastDeployment))
				<span class="has-tip" title="{{ $project->lastDeployment->created_at->format('d/m/Y H:i:s') }}">{{ $project->lastDeployment->created_at->diffForHumans() }}</span>
				@else
				Never
				@endif
			</td>
			<td class="text-center">
				<a href="{{ $app->path('project.edit', array('project' => $project->id)) }}" class="action" title="Edit">
					<i class="fa fa-pencil"></i>
				</a>
				<a href="{{ $app->path('project.delete', array('project' => $project->id)) }}" class="action alert" title="Delete">
					<i class="fa fa-ban"></i>
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<a href="{{ $app->path('project.add') }}" class="tiny radius button"><i class="fa fa-plus"></i> Add Project</a>
@stop