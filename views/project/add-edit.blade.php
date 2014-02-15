@extends('layout')

@section('title')
{{ ucfirst($type) }} a project
@stop

@section('content')
<h2>{{ ucfirst($type) }} a project</h2>

@include('partial.success-error')

<form action="" method="post">
	<label for="project_name">Name</label>
	<input type="text" name="name" value="{{{ $app->oldValue('name') ?: (isset($project->name) ? $project->name : '') }}}" id="project_name">

	<label for="project_directory">Directory</label>
	<input type="text" name="directory" value="{{{ $app->oldValue('directory') ?: (isset($project->directory) ? $project->directory : '') }}}" id="project_directory">

	<label for"project_host">Host</label>
	<select name="host" id="project_host">
		@foreach($hosts as $host)
		<option {{ ($app->oldValue('host') && ($app->oldValue('host') == $host)) ? 'selected' : ((isset($project->host) && ($project->host == $host)) ? 'selected' : '') }}>{{ $host }}</option>
		@endforeach
	</select>

	<label for="project_remote">Remote</label>
	<input type="text" name="remote" value="{{{ $app->oldValue('remote') ?: (isset($project->remote) ? $project->remote : '') }}}" id="project_remote">

	<label for="project_repository">Repository</label>
	<input type="text" name="repository" value="{{{ $app->oldValue('repository') ?: (isset($project->repository) ? $project->repository : '') }}}" id="project_repository">

	<label for="project_branch">Branch</label>
	<input type="text" name="branch" value="{{{ $app->oldValue('branch') ?: (isset($project->branch) ? $project->branch : '') }}}" id="project_branch">

	<input type="radio" name="trigger" value="manual" id="project_trigger-manual" {{ ($app->oldValue('trigger') == 'manual') ? 'checked' : (((isset($project->trigger) && ($project->trigger == 'manual')) || (!isset($project->trigger))) ? 'checked' : '') }}>
	<label for="project_trigger-manual">Manual</label>
	<input type="radio" name="trigger" value="automatic" id="project_trigger-automatic" {{ ($app->oldValue('trigger') == 'automatic') ? 'checked' : ((isset($project->trigger) && ($project->trigger == 'automatic')) ? 'checked' : '') }}>
	<label for="project_trigger-automatic">Automatic</label>

	<label for="project_hook">Deployment hook URL</label>
	<input type="text" value="{{{ $app->url('deployment.automatic', array('hash' => $app->oldValue('hash') ?: (isset($hash) ? $hash : ''))) }}}" id="project_hook" readonly>
	<input type="hidden" name="hash" value="{{{ $app->oldValue('hash') ?: (isset($hash) ? $hash : '') }}}">

	<input type="hidden" name="type" value="{{ $type }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('project.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop