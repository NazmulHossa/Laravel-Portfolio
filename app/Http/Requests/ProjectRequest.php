<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Used by ProjectController::store() and ProjectController::update()
//
// The image validation rule differs between creating (POST) and updating (PUT):
//   - Creating: image is optional (nullable), but if provided must be valid
//   - Updating:  same — leave the field empty to keep the existing image
//
// Usage in controller:
//   public function store(ProjectRequest $request) { ... }
//   public function update(ProjectRequest $request, Project $project) { ... }

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware on the admin group already handles this
    }

    public function rules(): array
    {
        return [
            // Title: required text, max 255 chars (matches VARCHAR(255) in migration)
            'title' => ['required', 'string', 'max:255'],

            // Description: required, at least 10 chars to prevent empty submissions
            'description' => ['required', 'string', 'min:10'],

            // URL: completely optional (nullable).
            // If provided, must be a valid URL format: https://example.com
            // 'url' rule checks for http:// or https:// prefix automatically
            'url' => ['nullable', 'url', 'max:255'],

            // Image: optional on both create AND update.
            //
            // Rules explained:
            //   'nullable'      → the field can be missing or empty (no image = that's fine)
            //   'image'         → must be an image file type (jpg, png, gif, bmp, svg, webp)
            //   'mimes:...'     → restrict to specific formats (more specific than 'image')
            //   'max:2048'      → maximum 2048 kilobytes = 2MB file size limit
            //
            // On CREATE: no image uploaded → $imagePath stays null in controller
            // On UPDATE: no image uploaded → controller keeps the existing image path
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter a project title.',
            'title.max'            => 'Project title cannot exceed 255 characters.',
            'description.required' => 'Please enter a project description.',
            'description.min'      => 'Description must be at least 10 characters.',
            'url.url'              => 'Please enter a valid URL, e.g. https://myproject.com',
            'image.image'          => 'The uploaded file must be an image.',
            'image.mimes'          => 'Only JPG, JPEG, PNG, and WEBP images are accepted.',
            'image.max'            => 'Image file size must not exceed 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'Project Title',
            'description' => 'Description',
            'url'         => 'Live URL',
            'image'       => 'Project Image',
        ];
    }
}