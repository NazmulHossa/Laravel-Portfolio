@extends('layouts.admin')

@section('title', 'Manage Projects')

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">📁 Projects</h2>
        <p class="text-muted mb-0">{{ $totalProjects }} project{{ $totalProjects !== 1 ? 's' : '' }} total</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ Add New Project</a>
</div>

{{-- ── SEARCH FORM ── --}}
{{-- method="GET" keeps the search term visible in the URL: /admin/projects?search=laravel
     This also means the browser's back button works correctly --}}
<form action="{{ route('admin.projects.index') }}" method="GET" class="mb-4">
    <div class="input-group" style="max-width: 420px;">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search by title or description..."
               value="{{ request('search') }}">
               {{-- request('search') keeps the typed value visible in the box after submit --}}
        <button class="btn btn-outline-secondary" type="submit">🔍 Search</button>
        @if(request('search'))
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-danger">✕ Clear</a>
        @endif
    </div>
</form>

{{-- ── PROJECTS GRID ── --}}
@if($projects->isEmpty())
    <div class="text-center text-muted py-5">
        @if(request('search'))
            No projects found for "{{ request('search') }}".
            <a href="{{ route('admin.projects.index') }}">Clear search</a>
        @else
            No projects yet. <a href="{{ route('admin.projects.create') }}">Add your first project →</a>
        @endif
    </div>
@else
    <div class="row g-3">
        @foreach($projects as $project)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">

                {{-- $project->image_url accessor returns real URL or placeholder --}}
                <img src="{{ $project->image_url }}"
                     class="card-img-top"
                     alt="{{ $project->title }}"
                     style="height: 160px; object-fit: cover;">

                <div class="card-body">
                    <h5 class="card-title fw-semibold">{{ $project->title }}</h5>
                    {{-- $project->short_description accessor trims to 120 chars --}}
                    <p class="card-text text-muted small">{{ $project->short_description }}</p>

                    @if($project->has_live_url)
                        <a href="{{ $project->url }}" target="_blank"
                           class="btn btn-outline-primary btn-sm">🌐 View Live</a>
                    @endif
                </div>

                <div class="card-footer d-flex gap-2 bg-transparent">
                    <a href="{{ route('admin.projects.edit', $project) }}"
                       class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('admin.projects.destroy', $project) }}"
                          method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($project->title) }}\'?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── PAGINATION ── --}}
    {{-- $projects->links() renders Bootstrap-styled Previous/Next + page number links --}}
    {{-- $projects->hasPages() returns false if all results fit on one page --}}
    @if($projects->hasPages())
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @endif
@endif

@endsection