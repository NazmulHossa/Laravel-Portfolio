<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;

class SkillController extends Controller
{
  
    public function index()
    {
       
        $skills = Skill::ordered()->get();

       
        $totalSkills  = $skills->count();
        $avgSkill     = $skills->avg('percentage') ?? 0;
     

        return view('admin.skills.index', compact('skills', 'totalSkills', 'avgSkill'));
    }

 
    public function create()
    {
        return view('admin.skills.create');
    }


    public function store(SkillRequest $request)
    {
      
        Skill::create($request->validated());

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$request->name}\" added successfully!");
    }


    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

   
    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$skill->name}\" updated successfully!");
    }

 
    public function destroy(Skill $skill)
    {
        $name = $skill->name; 

       
        $skill->delete();

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Skill \"{$name}\" deleted.");
    }
}
