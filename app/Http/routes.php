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
    return view('welcome', ['message' => 'No Message!']);
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

Route::post('/projects', function (Request $request) {
    $validator = Validator::make(Input::all(), [
        'name' => 'required|max:255',
	     'description' => 'required|max:65000',
	     'url' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/projects')
            ->withInput()
            ->withErrors($validator);
    }

    $project = new Project;
    $project->name = $request->name;
    $project->description = $request->description;
    $project->url = $request->url;
    $project->slider_image = Input::file('slider_image')->getClientOriginalName();
    Input::file('slider_image')->move('/public_html/images');
    $project->save();

    return redirect('/view_projects');
});

Route::delete('/projects/{project}', function (Project $project) {
   $project->delete();

    return redirect('/view_projects');
});

Route::get('/{message}', function ($message) {
    return view('welcome', ['message' => $message]);
});


