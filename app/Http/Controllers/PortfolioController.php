<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;

class PortfolioController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // HOME PAGE  —  Route: GET /
    // Shows the hero section + all skills as animated progress bars
    // ═══════════════════════════════════════════════════════════
    public function home()
    {
        // ordered() is the local scope defined in Skill model.
        // It adds: ORDER BY percentage DESC
        // So "Laravel 90%" always appears above "Git 70%"
        $skills = Skill::ordered()->get();

        // Also pass a few recent projects to show on the homepage
        // recent(3) is the scope in Project model → latest 3 projects
        $recentProjects = Project::recent(3)->get();

        // compact() creates ['skills' => $skills, 'recentProjects' => $recentProjects]
        return view('portfolio.home', compact('skills', 'recentProjects'));
    }

    // ═══════════════════════════════════════════════════════════
    // PORTFOLIO PAGE  —  Route: GET /portfolio
    // Shows all projects as a card grid
    // ═══════════════════════════════════════════════════════════
    public function portfolio()
    {
        $projects = Project::latest()->get();

        // Pass total count so the view can display "Showing X projects"
        $totalProjects = $projects->count();

        return view('portfolio.projects', compact('projects', 'totalProjects'));
    }

    // ═══════════════════════════════════════════════════════════
    // CONTACT PAGE  —  Route: GET /contact
    // Just renders the contact form — no data needed from DB
    // ═══════════════════════════════════════════════════════════
    public function contact()
    {
        return view('portfolio.contact');
    }

    // ═══════════════════════════════════════════════════════════
    // SEND MESSAGE  —  Route: POST /contact
    // Processes the contact form and saves to messages table
    //
    // Flow:
    //  1. Browser POSTs form data to /contact
    //  2. Laravel injects ContactRequest — runs validation automatically
    //     - Fail → redirect back with $errors (shown as red text in form)
    //     - Pass → this method body executes
    //  3. Message::create() inserts one row into the messages table
    //  4. Redirect back with a session flash message
    // ═══════════════════════════════════════════════════════════
    public function sendMessage(ContactRequest $request)
    {
        // $request->validated() returns ONLY the fields that passed the rules in ContactRequest.
        // Safer than $request->all() — prevents unexpected extra fields from being saved.
        Message::create($request->validated());

        // redirect()->route('contact') sends user back to /contact
        // ->with('success', '...') stores a one-time flash message in the session
        // In the Blade view: @if(session('success')) ... @endif
        return redirect()
            ->route('contact')
            ->with('success', 'Thank you! Your message has been sent. I\'ll get back to you soon.');
    }
}