@extends('layouts.app')

@section('content')
    <!-- Create Task Form... -->

    <!-- Current Tasks -->
    @if (count($projects) > 0)
    <h3>{{count($projects)}} Projects at this time! <a href="{{ url('/admin/projects')}}">Add Project</a></h3>
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Projects
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Project </th>
                        <th>Description</th>
                        <th>URL</th>
                        <th>Image</th>
                        <th>Image Small</th>
                        <th>Delete</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div><a href="{{ url('/admin/projects/'.$project->id) }}">{{ $project->name }}</a></div>
                                </td>
                                <td class="table-text">
                                    <div>{!! $project->description !!}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $project->url }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $project->slider_image }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $project->slider_image_small }}</div>
                                </td>
                                 <td>
                                    <form action="{{ url('/admin/projects/delete/'.$project->id) }}" method="POST">
				                            {{ csrf_field() }}
				                            {{ method_field('DELETE') }}

				                            <button type="submit" class="btn btn-danger">
				                                <i class="fa fa-trash"></i> Delete
				                            </button>
				                         </form>
			                        </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
    <h1>No projects at this time! <a href="{{ url('/projects')}}">Add Project</a></h1>
    
    @endif
@endsection
