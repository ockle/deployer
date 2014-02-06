@extends('layout')

@section('title')
Add a project
@stop

@section('content')
<h2>Add a project</h2>

<form action="" method="post">
	<label>Name</label>
	<input type="text" name="name">

	<label>Directory</label>
	<input type="text" name="directory">

	<label>Repository URL</label>
	<input type="text" name="repository">

	<label>Branch</label>
	<input type="text" name="branch">

	<input type="radio" name="trigger" value="manual">
	<label>Manual</label>
	<input type="radio" name="trigger" value="automatic">
	<label>Automatic</label>

	<label>Deployment hook URL</label>
	<input type="text" value="{{ $app->url('deployment.hook', array('hash' => $hash)) }}" readonly>
	<input type="hidden" name="hash" value="{{ $hash }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('project.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop