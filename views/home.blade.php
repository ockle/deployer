@extends('layout')

@section('title')
Home
@stop

@section('content')
<h2>Activity feed</h2>

@if (!$deployments->isEmpty())
<ul class="feed no-bullet">
	@foreach ($deployments as $deployment)
	<li class="row">
		<div class="small-2 medium-1 columns" title="{{ ucfirst($deployment->trigger) }} deployment">
			<i class="fa {{ ($deployment->wasTriggeredManually()) ? 'fa-wrench' : 'fa-cogs' }} round"></i>
		</div>
		<div class="small-7 medium-9 columns">
			<a href="{{ $app->path('user.edit', array('user' => $deployment->user->id)) }}">{{{ (isset($deployment->user)) ? $deployment->user->first_name : 'Unknown' }}} {{{ (isset($deployment->user)) ? $deployment->user->last_name : 'User' }}}</a> deployed <a href="{{ $app->path('project.view', array('project' => $deployment->project->id)) }}">{{{ $deployment->project->name }}}</a>
		</div>
		<div class="small-3 medium-2 columns">
			<span class="has-tip" title="{{ $deployment->created_at->format('d/m/Y H:i:s') }}" data-tooltip>{{ $deployment->created_at->diffForHumans() }}</span>
		</div>
	</li>
	@endforeach
</ul>
@else
<div class="alert-box info radius">There have not yet been any deployments</div>
@endif

@stop