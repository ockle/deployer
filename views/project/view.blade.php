@extends('layout')

@section('title')
{{ $project->name }}
@stop

@section('content')
<h2>{{ $project->name }}</h2>

@include('partial.success-error')

<dl class="panel radius row">
	<div class="small-12 medium-6 columns">
		<dt>Last deployed</dt>
		<dd>
			@if (!$project->deployments->isEmpty())
			<span class="has-tip" title="{{ $project->deployments[0]->created_at->format('d/m/Y H:i:s') }}" data-tooltip>{{ $project->deployments[0]->created_at->diffForHumans() }}</span> by <a href="{{ $app->path('user.edit', array('user' => $project->deployments[0]->user->id)) }}">{{{ $project->deployments[0]->user->first_name }}} {{{ $project->deployments[0]->user->last_name }}}</a>
			@else
			Never
			@endif
		</dd>

		<dt>Respository</dt>
		<dd>
			<a href="{{ $project->repository }}">View on {{ $project->host }}</a> (branch: {{{ $project->branch }}})
		</dd>
	</div>

	<div class="small-12 medium-6 columns">
		<dt>Deployment trigger</dt>
		<dd><i class="fa {{ ($project->isTriggeredManually()) ? 'fa-wrench' : 'fa-cogs' }} round"></i> {{ ($project->isTriggeredManually()) ? 'Manual' : 'Automatic' }}</dd>

		<dt>Directory</dt>
		<dd>{{{ $project->directory }}}</dd>
	</div>

	@if (!is_null($project->lastSuccessfulDeployment))
	<div class="small-12 columns">
		<dt>Current commit</dt>
		<dd>{{{ $project->lastSuccessfulDeployment->message }}}</dd>
	</div>
	@endif
</dl>

<ul class="button-group radius">
	<li class="small-12 medium-6">
		<a href="{{ $app->path('deployment.manual', array('project' => $project->id)) }}" class="large success button"><i class="fa fa-download"></i> Deploy</a>
	</li>
	<li class="small-12 medium-6">
		<a href="{{ $app->path('project.edit', array('project' => $project->id)) }}" class="large button"><i class="fa fa-cog"></i> Settings</a>
	</li>
</ul>

<h3>Deployment history</h3>

@if (!$project->deployments->isEmpty())
<table class="column">
	<thead>
		<tr class="row">
			<th class="small-1 text-center">Status</th>
			<th class="small-6">Message</th>
			<th class="small-2">Date</th>
			<th class="small-2">User</th>
			<th class="small-1 text-center">Details</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($project->deployments as $deployment)
		<tr class="row">
			<td>
				@if ($deployment->status)
				<span class="label success radius column">Success</span>
				@else
				<span class="label alert radius column">Failure</span>
				@endif
			</td>
			<td>{{{ $deployment->message }}}</td>
			<td>{{ $deployment->created_at->format('d/m/Y H:i:s') }}</td>
			<td>
				<a href="{{ $app->path('user.edit', array('user' => $deployment->user->id)) }}">{{{ $deployment->user->first_name }}} {{{ $deployment->user->last_name }}}</a>
			</td>
			<td class="text-center">
				<a href="{{ $app->path('deployment.view', array('deployment' => $deployment->id)) }}">View</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@else
<div class="alert-box info radius">This project has never been deployed</div>
@endif

@stop