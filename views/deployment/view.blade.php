@extends('layout')

@section('title')
Deployment details
@stop

@section('content')
<h2>Deployment details</h2>

@include('partial.success-error')
<div class="row">
		@if ($deployment->status)
		<span class="label deployment-status success radius column">Success</span>
		@else
		<span class="label deployment-status alert radius column">Failure</span>
		@endif
</div>

<dl class="panel radius">
	<dt>Project</dt>
	<dd>{{{ $deployment->project->name }}}</dd>

	<dt>Deployed</dt>
	<dd>{{ ($deployment->wasTriggeredManually()) ? 'Manually' : 'Automatically' }} at {{ $deployment->created_at->format('d/m/Y H:i:s') }} by <a href="{{ $app->path('user.edit', array('user' => $deployment->user->id)) }}">{{{ $deployment->user->first_name}}} {{{ $deployment->user->last_name }}}</a></dd>

	<dt>Duration</dt>
	<dd>{{ $deployment->duration }}ms</dd>

	<dt>Details</dt>
	<dd>{{ nl2br(e($deployment->details)) }}</dd>
</dl>
@stop