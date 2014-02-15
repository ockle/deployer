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
		<dd><i class="fa {{ ($project->trigger == 'manual') ? 'fa-wrench' : 'fa-cogs' }} round"></i> {{ ucfirst($project->trigger) }}</dd>

		<dt>Directory</dt>
		<dd>{{{ $project->directory }}}</dd>
	</div>

	@if (!$project->deployments->isEmpty())
	<div class="small-12 columns">
		<dt>Current commit</dt>
		<dd><span class="muted">[abc123]</span> This is the commit message</dd>
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
			<th class="small-6">Commit</th>
			<th class="small-2">Date</th>
			<th class="small-2">User</th>
			<th class="small-1 text-center">Details</th>
		</tr>
	</thead>
	<tbody>
		<tr class="row">
			<td>
				<span class="label success radius column">Success</span>
			</td>
			<td>This is the commit message</td>
			<td>11/11/2011 11:11:11</td>
			<td>
				<a href="">Johnathan Charleston</a>
			</td>
			<td class="text-center">
				<a href="">View</a>
			</td>
		</tr>
		<tr class="row">
			<td>
				<span class="label alert radius column">Error</span>
			</td>
			<td>This is the commit message</td>
			<td>11/11/2011 11:11:11</td>
			<td>
				<a href="">User</a>
			</td>
			<td class="text-center">
				<a href="">View</a>
			</td>
		</tr>
	</tbody>
</table>
@else
<div class="alert-box info radius">This project has never been deployed</div>
@endif

@stop