<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;

class PortfolioController extends Controller
{
   
    public function home()
    {
      
        $skills = Skill::ordered()->get();

       
        $recentProjects = Project::recent(3)->get();

       
        return view('portfolio.home', compact('skills', 'recentProjects'));
    }

   
    public function portfolio()
    {
        $projects = Project::latest()->get();

     
        $totalProjects = $projects->count();

        return view('portfolio.projects', compact('projects', 'totalProjects'));
    }

    
    public function contact()
    {
        return view('portfolio.contact');
    }

   
    public function sendMessage(ContactRequest $request)
    {
       
        Message::create($request->validated());

       
        return redirect()
            ->route('contact')
            ->with('success', 'Thank you! Your message has been sent. I\'ll get back to you soon.');
    }
}
