<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

Route::get('/', function () {
   
    $projects = Project::orderBy('created_at', 'asc')->get();
   
    return view('welcome', ['projects' => $projects]);
});

Route::get('/phpinfo', function(){
 return View::make('phpnfo');
});

Route::get('/file_upload', function(){
 return View::make('file_uploads');
});

Route::any('form-submit', function(){
   return Input::file('file')->move(public_path().'/images/',Input::file('file')->getClientOriginalName());
//var_dump(Input::file('file'));
});

Route::get('/view_projects', function () {
    
    $projects = Project::orderBy('created_at', 'asc')->get();

    return view('view_projects', [
        'projects' => $projects
    ]);
});

Route::get('/projects', function () {
    return view('projects', ['message' => 'No Message!']);
});

Route::get('/tasks', 'TaskController@index');

Route::post('/projects', 'ProjectController@create');

Route::delete('/projects/{project}', function (Project $project) {
   $project->delete();

    return redirect('/view_projects');
});

Route::get('/{message}', function ($message) {
    return view('welcome', ['message' => $message]);
});


