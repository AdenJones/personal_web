<!-- resources/views/projects.blade.php -->

@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
         {{ Form::open(array('action' => array('ProjectController@update',  $project->id),'files'=>true)) }}
        
            {{ csrf_field() }}
            
            <!-- Task Name -->
            <div class="form-group">
                <label for="task" class="col-sm-6 control-label">Project</label>

                <div class="col-sm-6">
                   name: <input type="text" name="name" id="project-name" class="form-control" value="{{$project->name}}">
                </div>
		         <div class="col-sm-6">
                   description: <textarea name="description" id="project-description" class="form-control" rows="4" cols="50">{{$project->description}}</textarea>
                </div>
		         <div class="col-sm-6">
                   url: <input type="text" name="url" id="project-url" class="form-control" value="{{$project->url}}">
                </div>
		         <div class="col-sm-6">
                   slider image: <input type="file" name="slider_image" id="project-slider-image" class="form-control">
                </div>
                <div class="col-sm-6">
                   slider image small: <input type="file" name="slider_image" id="project-slider-image" class="form-control">
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Update Project
                    </button>
                </div>
            </div>
        {{ Form::close() }}
    </div>

    <!-- TODO: Current Tasks -->
@endsection
