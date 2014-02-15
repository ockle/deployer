@extends('layout')

@section('title')
Manual deployment
@stop

@section('content')

<h2>Deploy this project?</h2>

@include('partial.binary-decision', array('action' => $app->path('deployment.manual', array('project' => $project->id))))

@stop