@extends('layouts.admin')

@section('title', 'Edit Skill')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Skill: <span class="text-primary">{{ $skill->name }}</span></h2>
    <a href="{{ route('admin.skills.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="row g-4">

    {{-- ── FORM ── --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- PUT method: Route::resource expects PUT for updates
                     HTML forms only support GET and POST.
                     @method('PUT') adds a hidden field: <input name="_method" value="PUT">
                     Laravel reads this and treats the request as PUT. --}}
                <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Skill Name</label>
                        <input type="text"
                               id="skillName"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $skill->name) }}">
                               {{-- old('name', $skill->name):
                                    First arg: field name. Second arg: fallback value.
                                    - If just redirected from failed validation → shows old typed value
                                    - On fresh page load → shows $skill->name from the database --}}
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Percentage: <span id="percentageDisplay" class="text-primary fw-bold">
                                {{ old('percentage', $skill->percentage) }}
                            </span>%
                        </label>
                        <input type="range"
                               id="skillPercentage"
                               name="percentage"
                               class="form-range @error('percentage') is-invalid @enderror"
                               value="{{ old('percentage', $skill->percentage) }}"
                               min="1"
                               max="100">
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update Skill</button>
                        <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ── LIVE PREVIEW ── --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">👁 Live Preview</div>
            <div class="card-body">
                <p class="text-muted small mb-3">This is how this skill will look on the public page:</p>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-semibold" id="previewName">{{ $skill->name }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $skill->badge_color }}" id="previewBadge">
                            {{ $skill->level }}
                        </span>
                        <span class="text-muted small" id="previewPercent">
                            {{ $skill->percentage }}%
                        </span>
                    </div>
                </div>
                <div class="progress" style="height: 14px; border-radius: 8px;">
                    <div class="progress-bar {{ $skill->bar_color }}"
                         id="previewBar"
                         style="width: {{ $skill->percentage }}%; border-radius: 8px; transition: width 0.3s;">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const nameInput        = document.getElementById('skillName');
    const percentageSlider = document.getElementById('skillPercentage');
    const percentDisplay   = document.getElementById('percentageDisplay');
    const previewName      = document.getElementById('previewName');
    const previewBadge     = document.getElementById('previewBadge');
    const previewPercent   = document.getElementById('previewPercent');
    const previewBar       = document.getElementById('previewBar');

    function getLevelInfo(p) {
        if (p >= 90) return { label: 'Expert',       color: 'success' };
        if (p >= 70) return { label: 'Advanced',     color: 'primary' };
        if (p >= 50) return { label: 'Intermediate', color: 'warning' };
        return              { label: 'Beginner',     color: 'secondary' };
    }

    function updatePreview() {
        const p    = parseInt(percentageSlider.value);
        const name = nameInput.value.trim() || 'Skill Name';
        const info = getLevelInfo(p);

        percentDisplay.textContent = p;
        previewName.textContent    = name;
        previewPercent.textContent = p + '%';
        previewBar.style.width     = p + '%';
        previewBadge.textContent   = info.label;
        previewBadge.className     = `badge bg-${info.color}`;
        previewBar.className       = `progress-bar bg-${info.color}`;
    }

    nameInput.addEventListener('input', updatePreview);
    percentageSlider.addEventListener('input', updatePreview);
</script>
@endpush