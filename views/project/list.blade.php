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
			<th class="small-4">Last deployed</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($projects as $project)
		<tr class="row">
			<td class="small-4"><a href="{{ $app->path('project.view', array('project' => $project->id)) }}">{{ $project->name }}</a></td>
			<td class="small-4">{{{ $project->directory }}}</td>
			<td class="small-4">
				@if (!is_null($project->lastDeployment))
				<span class="has-tip" title="{{ $project->lastDeployment->created_at->format('d/m/Y H:i:s') }}">{{ $project->lastDeployment->created_at->diffForHumans() }}</span>
				@else
				Never
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<a href="{{ $app->path('project.add') }}" class="tiny radius button"><i class="fa fa-plus"></i> Add Project</a>
@stop