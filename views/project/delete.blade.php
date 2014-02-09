@extends('layout')

@section('title')
Delete project
@stop

@section('content')

<h2>Delete project?</h2>

@include('partial.binary-decision', array('action' => $app->path('project.delete', array('project' => $project->id))))

@stop