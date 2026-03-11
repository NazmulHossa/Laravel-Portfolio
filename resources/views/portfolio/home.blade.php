@extends('layouts.app')

@section('title', 'Home — My Portfolio')

@section('content')


<div class="py-5 text-center bg-dark text-white rounded-3 mb-5 px-4">
    <h1 class="display-4 fw-bold mb-3">Hi, I'm a Full Stack Developer 👋</h1>
    <p class="lead text-secondary mb-4">
        I build modern web applications with Laravel, Vue.js, and MySQL.
        Passionate about clean code, great UX, and solving real problems.
    </p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('portfolio') }}" class="btn btn-primary btn-lg px-4">
            View My Projects →
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">
            Get In Touch
        </a>
    </div>
</div>


<div class="row justify-content-center mb-5">
    <div class="col-lg-8">

        <h2 class="fw-bold mb-1">My Skills</h2>
        <p class="text-muted mb-4">Technologies and tools I work with</p>

        @forelse($skills as $skill)
            <div class="mb-3">

                {{-- Skill label row: name on left, badge + % on right --}}
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-semibold">{{ $skill->name }}</span>
                    <div class="d-flex align-items-center gap-2">
                        {{-- $skill->level → "Expert" / "Advanced" etc. (Model accessor) --}}
                        {{-- $skill->badge_color → "success" / "primary" etc. (Model accessor) --}}
                        <span class="badge bg-{{ $skill->badge_color }}">{{ $skill->level }}</span>
                        <span class="text-muted small">{{ $skill->percentage }}%</span>
                    </div>
                </div>

                {{-- Progress bar
                     data-width="{{ $skill->percentage }}" is read by the JS in app.blade.php
                     The bar starts at 0 and animates to the real percentage on page load --}}
                <div class="progress" style="height: 14px; border-radius: 8px;">
                    <div class="progress-bar {{ $skill->bar_color }}"
                         role="progressbar"
                         style="width: 0%; border-radius: 8px;"
                         data-width="{{ $skill->percentage }}"
                         aria-valuenow="{{ $skill->percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>

            </div>
        @empty
            <p class="text-muted">No skills added yet — check back soon!</p>
        @endforelse

    </div>
</div>


@if($recentProjects->isNotEmpty())
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Recent Projects</h2>
            <a href="{{ route('portfolio') }}" class="btn btn-outline-primary btn-sm">
                View All →
            </a>
        </div>

        <div class="row g-4">
            @foreach($recentProjects as $project)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        {{-- $project->image_url returns real URL or placeholder (Model accessor) --}}
                        <img src="{{ $project->image_url }}"
                             class="card-img-top"
                             alt="{{ $project->title }}"
                             style="height: 180px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                            {{-- $project->short_description trims to 120 chars (Model accessor) --}}
                            <p class="card-text text-muted small">{{ $project->short_description }}</p>
                        </div>
                        @if($project->has_live_url)
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="{{ $project->url }}" target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    View Live →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@endsection
