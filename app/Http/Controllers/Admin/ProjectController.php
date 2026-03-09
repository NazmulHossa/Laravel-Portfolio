<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // INDEX  —  Route: GET /admin/projects
    // Lists all projects with optional search + pagination
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        // search() is the local scope defined in Project model.
        // If ?search=laravel is in the URL it filters, otherwise returns all.
        $projects = Project::search($request->input('search'))
                           ->latest()        // ORDER BY created_at DESC
                           ->paginate(9)     // 9 per page (3×3 grid looks good)
                           ->withQueryString(); // keeps ?search=... in pagination links

        $totalProjects = Project::count(); // Total for header stat

        return view('admin.projects.index', compact('projects', 'totalProjects'));
    }

    // ═══════════════════════════════════════════════════════════
    // CREATE  —  Route: GET /admin/projects/create
    // ═══════════════════════════════════════════════════════════
    public function create()
    {
        return view('admin.projects.create');
    }

    // ═══════════════════════════════════════════════════════════
    // STORE  —  Route: POST /admin/projects
    // Saves a new project; handles optional image upload
    // ═══════════════════════════════════════════════════════════
    public function store(ProjectRequest $request)
    {
        // Start with no image path
        $imagePath = null;

        if ($request->hasFile('image')) {
            // store() does three things:
            //   1. Generates a unique filename (e.g. "abc123def456.jpg")
            //   2. Saves the file to storage/app/public/projects/
            //   3. Returns the relative path: "projects/abc123def456.jpg"
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

    // ═══════════════════════════════════════════════════════════
    // EDIT  —  Route: GET /admin/projects/{project}/edit
    // ═══════════════════════════════════════════════════════════
    public function edit(Project $project)
    {
        // Route Model Binding auto-finds the project by ID
        return view('admin.projects.edit', compact('project'));
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE  —  Route: PUT /admin/projects/{project}
    // Updates project data; replaces image if a new one is uploaded
    // ═══════════════════════════════════════════════════════════
    public function update(ProjectRequest $request, Project $project)
    {
        // Keep the current image path by default
        $imagePath = $project->image;

        if ($request->hasFile('image')) {
            // Delete OLD image file from disk first
            // (The boot() method on the model would NOT handle this because
            //  we're updating, not deleting — so we do it manually here)
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }

            // Store the new uploaded image and get its path
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

    // ═══════════════════════════════════════════════════════════
    // DESTROY  —  Route: DELETE /admin/projects/{project}
    // Deletes the project; image file is auto-deleted by Model boot()
    // ═══════════════════════════════════════════════════════════
    public function destroy(Project $project)
    {
        $title = $project->title;

        // $project->delete() triggers the "deleting" event in Project::boot()
        // That event listener deletes the image file from storage automatically
        // So we don't need to call Storage::delete() here
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$title}\" deleted.");
    }
}