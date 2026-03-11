<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    
    public function index(Request $request)
    {
       
        $projects = Project::search($request->input('search'))
                           ->latest()        // ORDER BY created_at DESC
                           ->paginate(9)     // 9 per page (3×3 grid looks good)
                           ->withQueryString(); // keeps ?search=... in pagination links

        $totalProjects = Project::count(); // Total for header stat

        return view('admin.projects.index', compact('projects', 'totalProjects'));
    }

 
    public function create()
    {
        return view('admin.projects.create');
    }

   
    public function store(ProjectRequest $request)
    {
        // Start with no image path
        $imagePath = null;

        if ($request->hasFile('image')) {
          
            $imagePath = $request->file('image')->store('projects', 'public');
        }

        Project::create([
            'title'       => $request->title,
            'description' => $request->description,
            'url'         => $request->url,         // null if left empty
            'image'       => $imagePath,             // null if no file uploaded
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$request->title}\" added successfully!");
    }

   
    public function edit(Project $project)
    {
     
        return view('admin.projects.edit', compact('project'));
    }

  
    public function update(ProjectRequest $request, Project $project)
    {
        
        $imagePath = $project->image;

        if ($request->hasFile('image')) {
           
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }

         
            $imagePath = $request->file('image')->store('projects', 'public');
        }

        $project->update([
            'title'       => $request->title,
            'description' => $request->description,
            'url'         => $request->url,
            'image'       => $imagePath,
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$project->title}\" updated successfully!");
    }

 
    public function destroy(Project $project)
    {
        $title = $project->title;

      
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$title}\" deleted.");
    }
}
