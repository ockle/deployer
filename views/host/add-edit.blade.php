@extends('layout')

@section('title')
{{ ucfirst($type) }} a host
@stop

@section('content')
<h2>{{ ucfirst($type) }} a host</h2>

@include('partial.success-error')

<form action="" method="post">
	<label>Name</label>
	<input type="text" name="name" value="{{{ $app->oldValue('name') ?: (isset($host->name) ? $host->name : '') }}}">

	<input type="hidden" name="type" value="{{ $type }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('host.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop