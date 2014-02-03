@extends('layout')

@section('title')
Delete user
@stop

@section('content')

<h2>Delete user?</h2>

@include('partial.binary-decision', array('action' => $app->path('user.delete', array('user' => $user->id))))

@stop