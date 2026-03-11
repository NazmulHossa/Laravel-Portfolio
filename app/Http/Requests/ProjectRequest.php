<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware on the admin group already handles this
    }

    public function rules(): array
    {
        return [
          
            'title' => ['required', 'string', 'max:255'],

            
            'description' => ['required', 'string', 'min:10'],

            
            'url' => ['nullable', 'url', 'max:255'],

          
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
