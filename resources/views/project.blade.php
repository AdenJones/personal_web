<!-- resources/views/project.blade.php -->

@extends('layouts.main')

@section('content')

<div class="main-content">
   <h2>Project: {{$project->name}}</h2>

   <div class="inner-block">{!! $project->description !!}</div>
   
   <img src="{{url('/')}}/images/{{$project->slider_image}}" alt="{{ $project->name }}" />
   
   <p><a target="_new" href="{{$project->url}}">View the project</a></p>
</div>
    
    
    
@endsection
