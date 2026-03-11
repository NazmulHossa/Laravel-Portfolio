<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class SkillRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
          
            'name' => ['required', 'string', 'max:255'],

          
            'percentage' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Please enter the skill name.',
            'name.max'            => 'Skill name cannot exceed 255 characters.',
            'percentage.required' => 'Please enter a percentage value.',
            'percentage.integer'  => 'Percentage must be a whole number.',
            'percentage.min'      => 'Percentage must be at least 1.',
            'percentage.max'      => 'Percentage cannot exceed 100.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'Skill Name',
            'percentage' => 'Percentage',
        ];
    }
}
