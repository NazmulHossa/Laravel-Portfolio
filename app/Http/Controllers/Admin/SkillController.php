<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;

class SkillController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // INDEX  —  Route: GET /admin/skills
    // Lists all skills in a table with progress bars
    // ═══════════════════════════════════════════════════════════
    public function index()
    {
        // Use ordered() scope → sorts by highest percentage first
        // This matches what the public home page shows
        $skills = Skill::ordered()->get();

        // Also pass summary stats for the page header
        $totalSkills  = $skills->count();
        $avgSkill     = $skills->avg('percentage') ?? 0;
        // avg() returns null if no skills yet — ?? 0 gives a safe default

        return view('admin.skills.index', compact('skills', 'totalSkills', 'avgSkill'));
    }

    // ═══════════════════════════════════════════════════════════
    // CREATE  —  Route: GET /admin/skills/create
    // Shows the blank "Add Skill" form
    // ═══════════════════════════════════════════════════════════
    public function create()
    {
        return view('admin.skills.create');
    }

    // ═══════════════════════════════════════════════════════════
    // STORE  —  Route: POST /admin/skills
    // Saves a new skill to the database
    //
    // SkillRequest runs BEFORE this method:
    //   - Validates: name required, percentage integer 1-100
    //   - On fail: redirects back with $errors
    //   - On pass: continues here
    // ═══════════════════════════════════════════════════════════
    public function store(SkillRequest $request)
    {
        // $request->validated() returns exactly: ['name' => '...', 'percentage' => 90]
        // Nothing else — even if someone injected extra POST fields
        Skill::create($request->validated());

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$request->name}\" added successfully!");
    }

    // ═══════════════════════════════════════════════════════════
    // EDIT  —  Route: GET /admin/skills/{skill}/edit
    // Shows the form pre-filled with the skill's current data
    //
    // Route Model Binding: Laravel sees {skill} in the URL,
    // automatically runs Skill::findOrFail($id) and injects $skill.
    // If ID doesn't exist → 404 automatically.
    // ═══════════════════════════════════════════════════════════
    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE  —  Route: PUT /admin/skills/{skill}
    // Saves edited skill data to the database
    // ═══════════════════════════════════════════════════════════
    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$skill->name}\" updated successfully!");
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY  —  Route: DELETE /admin/skills/{skill}
    // Deletes the skill from the database
    // ═══════════════════════════════════════════════════════════
    public function destroy(Skill $skill)
    {
        $name = $skill->name; // Save name before delete (for flash message)

        // delete() removes the row from the skills table
        // No files to clean up — skills have no images
        $skill->delete();

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$name}\" deleted.");
    }
}