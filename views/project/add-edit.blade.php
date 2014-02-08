@extends('layout')

@section('title')
Add a project
@stop

@section('content')
<h2>Add a project</h2>

@include('partial.success-error')

<form action="" method="post">
	<label>Name</label>
	<input type="text" name="name" value="{{{ $app->oldValue('name') ?: (isset($project->name) ? $project->name : '') }}}">

	<label>Directory</label>
	<input type="text" name="directory" value="{{{ $app->oldValue('directory') ?: (isset($project->directory) ? $project->directory : '') }}}">

	<label>Repository URL</label>
	<input type="text" name="repository" value="{{{ $app->oldValue('repository') ?: (isset($project->repository) ? $project->repository : '') }}}">

	<label>Branch</label>
	<input type="text" name="branch" value="{{{ $app->oldValue('branch') ?: (isset($project->branch) ? $project->branch : '') }}}">

	<input type="radio" name="trigger" value="manual" {{ ($app->oldValue('trigger') == 'manual') ? 'checked' : (((isset($project->trigger) && ($project->trigger == 'manual')) || (!isset($project->trigger))) ? 'checked' : '') }}>
	<label>Manual</label>
	<input type="radio" name="trigger" value="automatic" {{ ($app->oldValue('trigger') == 'automatic') ? 'checked' : ((isset($project->trigger) && ($project->trigger == 'automatic')) ? 'checked' : '') }}>
	<label>Automatic</label>

	<label>Deployment hook URL</label>
	<input type="text" value="{{{ $app->url('deployment.hook', array('hash' => $app->oldValue('hash') ?: (isset($hash) ? $hash : ''))) }}}" readonly>
	<input type="hidden" name="hash" value="{{{ $app->oldValue('hash') ?: (isset($hash) ? $hash : '') }}}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('project.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop