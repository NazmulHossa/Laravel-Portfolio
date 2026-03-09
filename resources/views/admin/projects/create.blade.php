@extends('layouts.admin')

@section('title', 'Add New Project')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Project</h2>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm" style="max-width: 680px;">
    <div class="card-body">

        {{-- enctype="multipart/form-data" is REQUIRED when the form contains a file upload.
             Without it, the uploaded file is NOT sent to the server — $request->file() returns null. --}}
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       placeholder="e.g. E-Commerce Website">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                <textarea name="description"
                          rows="5"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Describe the project — tech stack, features, your role...">{{ old('description') }}</textarea>
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
                       value="{{ old('url') }}"
                       placeholder="https://myproject.com">
                @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- IMAGE UPLOAD with live preview --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Project Image <span class="text-muted fw-normal">(optional, max 2MB)</span>
                </label>

                {{-- Image preview box — hidden until a file is selected --}}
                <div id="imagePreviewBox" class="mb-2" style="display: none;">
                    <img id="imagePreview"
                         src=""
                         alt="Preview"
                         class="rounded border"
                         style="height: 130px; object-fit: cover;">
                    <small class="text-muted d-block mt-1">Image preview</small>
                </div>

                <input type="file"
                       name="image"
                       id="imageInput"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Accepted: jpg, png, webp — Max: 2MB</div>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Save Project</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show a preview of the selected image before uploading
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('imagePreview').src = event.target.result;
            document.getElementById('imagePreviewBox').style.display = 'block';
        };
        reader.readAsDataURL(file); // Reads file as base64 data URL for preview
    });
</script>
@endpush