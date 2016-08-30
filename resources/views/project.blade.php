<!-- resources/views/project.blade.php -->

@extends('layouts.main')

@section('content')

<div class="main-content">
   <h2>Project: {{$project->name}}</h2>

   <div class="inner-block">{!! $project->description !!}</div>
   
   <picture class="variable-width">
      <source srcset="{{url('/')}}/images/{{$project->slider_image}}" media="(min-width: 560px)">
      <img src="{{url('/')}}/images/{{$project->slider_image_small}}" alt="{{$project->name}}">
    </picture>
   
   <p><a target="_new" href="{{$project->url}}">View the project</a></p>
</div>
    
    
    
@endsection
