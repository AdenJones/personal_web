<?php

namespace App\Http\Controllers;

use App\Project;

use App\Helpers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        
        $validator = Validator::make(Input::all(), [
        'name' => 'required|max:255',
	     'description' => 'required|max:65000',
	     'url' => 'required|max:255',
        'slider_image' => 'required|mimes:jpeg,jpg,bmp,png,gif|max:200000',
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
          
          $unique_filename = Helpers::makeUniqueName(Input::file('slider_image'),public_path().'/images/');
          
          $project->slider_image = $unique_filename;
          Input::file('slider_image')->move(public_path().'/images/',$unique_filename);
          
          $project->save();

          return redirect('/admin/view_projects');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $project = Project::find($id);
       
       return view('project_update', ['project' => $project]);
    }
        
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        
        if( $project->slider_image == '' or is_null($project->slider_image) )
        {
            $slide_big = 'required|';
        } else {
            $slide_big = '';
        }
        
        if( $project->slider_image_small == '' or is_null($project->slider_image_small) )
        {
            $slide_small = 'required|';
        } else {
            $slide_small = '';
        }
        
        $validator = Validator::make(Input::all(), [
        'name' => 'required|max:255',
	     'description' => 'required|max:65000',
	     'url' => 'required|max:255',
        'slider_image' => $slide_big.'mimes:jpeg,jpg,bmp,png,gif|max:200000',
        'slider_image_small' => $slide_small.'mimes:jpeg,jpg,bmp,png,gif|max:200000',
          ]);

         if ($validator->fails()) {
              return redirect()->action('ProjectController@edit', array('project' => $id))
                  ->withInput()
                  ->withErrors($validator);
          }
          
          $project->name = $request->name;
          $project->description = $request->description;
          $project->url = $request->url;
          
          if(!is_null(Input::file('slider_image')))
          {
             $unique_filename = Helpers::makeUniqueName(Input::file('slider_image'),public_path().'/images/');
             $project->slider_image = $unique_filename;
             Input::file('slider_image')->move(public_path().'/images/',$unique_filename);
          }
          
          if(!is_null(Input::file('slider_image_small')))
          {
             $unique_filename_small = Helpers::makeUniqueName(Input::file('slider_image_small'),public_path().'/images/');
             $project->slider_image_small = $unique_filename_small;
             Input::file('slider_image_small')->move(public_path().'/images/',$unique_filename_small);
          }
          
          $project->update();

          return redirect('/admin/view_projects'); 

          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
