@extends('layouts.admin')

@section('title', 'Manage Skills')

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">🎯 Skills</h2>
        {{-- $totalSkills and $avgSkill are passed from SkillController::index() --}}
        <p class="text-muted mb-0">
            {{ $totalSkills }} skill{{ $totalSkills !== 1 ? 's' : '' }} •
            Average: <strong>{{ round($avgSkill) }}%</strong>
        </p>
    </div>
    <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">+ Add New Skill</a>
</div>

{{-- ── TABLE ── --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="50">#</th>
                    <th>Skill Name</th>
                    <th>Level</th>
                    <th>Percentage</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($skills as $skill)
                <tr>
                    {{-- $loop->iteration gives the current row number 1, 2, 3... --}}
                    <td class="text-muted">{{ $loop->iteration }}</td>

                    <td class="fw-semibold">{{ $skill->name }}</td>

                    {{-- $skill->level and $skill->badge_color come from Model accessors --}}
                    <td>
                        <span class="badge bg-{{ $skill->badge_color }} px-3 py-2">
                            {{ $skill->level }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1"
                                 style="height: 16px; max-width: 160px; border-radius: 8px;">
                                {{-- $skill->bar_color gives the Bootstrap bg-* class --}}
                                <div class="progress-bar {{ $skill->bar_color }}"
                                     style="width: {{ $skill->percentage }}%; border-radius: 8px;">
                                </div>
                            </div>
                            <span class="text-muted small">{{ $skill->percentage }}%</span>
                        </div>
                    </td>

                    <td>
                        <a href="{{ route('admin.skills.edit', $skill) }}"
                           class="btn btn-sm btn-warning me-1">Edit</a>

                        {{-- DELETE requires a form with POST + @method('DELETE')
                             because HTML <a> tags can only do GET requests --}}
                        <form action="{{ route('admin.skills.destroy', $skill) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete {{ addslashes($skill->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        No skills yet.
                        <a href="{{ route('admin.skills.create') }}">Add your first skill →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection