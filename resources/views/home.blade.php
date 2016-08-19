@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                <div class="col-md-12">
                  <h3><a href="{{url('/')}}/admin/view_projects">View Projects</a></h3>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
