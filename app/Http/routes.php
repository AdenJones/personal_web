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

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/', function () {
   
    $projects = Project::orderBy('created_at', 'asc')->get();
   
    return view('splash', ['projects' => $projects]);
});


Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleWare'], function()
{
    
    
    Route::get('/admin/view_projects', function () {
       
       $projects = Project::orderBy('created_at', 'asc')->get();

       return view('view_projects', [
           'projects' => $projects
       ]);
   });
   
   Route::get('/admin/projects', function () {
      return view('projects', ['message' => 'No Message!']);
   });

   Route::post('/admin/projects', 'ProjectController@create');
   
   Route::get('/admin/projects/{project}', ['uses' => 'ProjectController@edit' ]);
   
   Route::post('/admin/projects/{project}', ['uses' => 'ProjectController@update' ]);
   
   Route::delete('/admin/projects/delete/{project}', function (Project $project) {
      $project->delete();

       return redirect('/admin/view_projects');
   });

});
