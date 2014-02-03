@extends('layout')

@section('title')
Logout
@stop

@section('content')

<h2>Logout?</h2>

@include('partial.binary-decision', array('action' => $app->path('logout')))

@stop