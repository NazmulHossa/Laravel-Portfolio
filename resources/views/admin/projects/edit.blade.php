@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit: <span class="text-primary">{{ $project->title }}</span></h2>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm" style="max-width: 680px;">
    <div class="card-body">

        <form action="{{ route('admin.projects.update', $project) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $project->title) }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                <textarea name="description"
                          rows="5"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- LIVE URL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Live URL <span class="text-muted fw-normal">(optional)</span>
                </label>
                <input type="url"
                       name="url"
                       class="form-control @error('url') is-invalid @enderror"
                       value="{{ old('url', $project->url) }}"
                       placeholder="https://myproject.com">
                @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- IMAGE UPLOAD --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Project Image <span class="text-muted fw-normal">(leave empty to keep current)</span>
                </label>

                {{-- Current image — uses $project->image_url accessor.
                     Always shows something: real image or placeholder. --}}
                <div id="imagePreviewBox" class="mb-2">
                    <img id="imagePreview"
                         src="{{ $project->image_url }}"
                         alt="Current image"
                         class="rounded border"
                         style="height: 130px; object-fit: cover;">
                    <small class="text-muted d-block mt-1" id="previewLabel">
                        {{ $project->image ? 'Current image' : 'No image (placeholder shown)' }}
                    </small>
                </div>

                <input type="file"
                       name="image"
                       id="imageInput"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Accepted: jpg, png, webp — Max: 2MB. Leave empty to keep current.</div>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">💾 Update Project</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // When a new image file is selected, show a preview of it immediately
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('imagePreview').src = event.target.result;
            document.getElementById('previewLabel').textContent = 'New image (not saved yet)';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush