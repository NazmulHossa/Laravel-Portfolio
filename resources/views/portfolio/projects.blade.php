@extends('layouts.app')

@section('title', 'My Projects')

@section('content')

{{-- ══════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════ --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">My Projects</h1>
        {{-- $totalProjects is passed from PortfolioController::portfolio() --}}
        <p class="text-muted mb-0">{{ $totalProjects }} project{{ $totalProjects !== 1 ? 's' : '' }} total</p>
    </div>
    <a href="{{ route('contact') }}" class="btn btn-outline-primary">
        💬 Hire Me
    </a>
</div>

{{-- ══════════════════════════════════════════════════════
     PROJECTS GRID
     $projects is passed from PortfolioController::portfolio()
     @forelse = @foreach + handles the empty case with @empty
══════════════════════════════════════════════════════ --}}
<div class="row g-4">

    @forelse($projects as $project)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">

                {{-- Project thumbnail
                     $project->image_url returns real URL or placeholder (no @if needed) --}}
                <img src="{{ $project->image_url }}"
                     class="card-img-top"
                     alt="{{ $project->title }}"
                     style="height: 210px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $project->title }}</h5>

                    {{-- $project->short_description is a Model accessor — trims to 120 chars --}}
                    <p class="card-text text-muted flex-grow-1">
                        {{ $project->short_description }}
                    </p>

                    {{-- Only show the button if the project has a URL
                         $project->has_live_url is a Model accessor (returns true/false) --}}
                    @if($project->has_live_url)
                        <div class="mt-3">
                            <a href="{{ $project->url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-primary btn-sm">
                                🌐 View Live
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Project date in the footer --}}
                <div class="card-footer bg-transparent text-muted small">
                    Added {{ $project->created_at->format('M Y') }}
                </div>

            </div>
        </div>

    @empty
        {{-- This block shows when the $projects collection is empty --}}
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">No projects added yet. Check back soon!</p>
            @auth
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                    + Add First Project
                </a>
            @endauth
        </div>
    @endforelse

</div>

@endsection