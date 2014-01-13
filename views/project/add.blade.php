@extends('layout')

@section('title')
Add a project
@stop

@section('content')
<h2>Add a project</h2>

<form>
	<label>Name</label>
	<input type="text">

	<label>Host</label>
	<select>
		@foreach($hosts as $host)
		<option>{{ $host }}</option>
		@endforeach
	</select>

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('project.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop