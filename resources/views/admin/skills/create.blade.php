@extends('layouts.admin')

@section('title', 'Add New Skill')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Skill</h2>
    <a href="{{ route('admin.skills.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="row g-4">

    {{-- ── FORM ── --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('admin.skills.store') }}" method="POST" id="skillForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Skill Name</label>
                        <input type="text"
                               id="skillName"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Laravel">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Percentage: <span id="percentageDisplay" class="text-primary fw-bold">50</span>%
                        </label>
                        {{-- type="range" gives a drag slider instead of a number box --}}
                        <input type="range"
                               id="skillPercentage"
                               name="percentage"
                               class="form-range @error('percentage') is-invalid @enderror"
                               value="{{ old('percentage', 50) }}"
                               min="1"
                               max="100">
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Skill</button>
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
                    <span class="fw-semibold" id="previewName">Skill Name</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge" id="previewBadge">Intermediate</span>
                        <span class="text-muted small" id="previewPercent">50%</span>
                    </div>
                </div>
                <div class="progress" style="height: 14px; border-radius: 8px;">
                    <div class="progress-bar" id="previewBar"
                         style="width: 50%; border-radius: 8px; transition: width 0.3s;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

{{-- Live preview JavaScript --}}
@push('scripts')
<script>
    const nameInput       = document.getElementById('skillName');
    const percentageSlider= document.getElementById('skillPercentage');
    const percentDisplay  = document.getElementById('percentageDisplay');
    const previewName     = document.getElementById('previewName');
    const previewBadge    = document.getElementById('previewBadge');
    const previewPercent  = document.getElementById('previewPercent');
    const previewBar      = document.getElementById('previewBar');

    // Determine level label and color from percentage
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

        percentDisplay.textContent  = p;
        previewName.textContent     = name;
        previewPercent.textContent  = p + '%';
        previewBar.style.width      = p + '%';
        previewBadge.textContent    = info.label;
        previewBadge.className      = `badge bg-${info.color}`;
        previewBar.className        = `progress-bar bg-${info.color}`;
    }

    nameInput.addEventListener('input', updatePreview);
    percentageSlider.addEventListener('input', updatePreview);

    // Run once on load to sync with old() values
    updatePreview();
</script>
@endpush