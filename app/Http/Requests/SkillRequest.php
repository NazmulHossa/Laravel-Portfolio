<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Used by SkillController::store() and SkillController::update()
// Both store and update use the same rules, so one class handles both.
//
// Usage in controller:
//   public function store(SkillRequest $request) {
//       Skill::create($request->validated());
//   }
//   public function update(SkillRequest $request, Skill $skill) {
//       $skill->update($request->validated());
//   }

class SkillRequest extends FormRequest
{
    // Only authenticated admins reach this — the 'auth' middleware
    // on the admin route group already blocks guests before this runs.
    // So we just return true here.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'required' → must be present and non-empty
            // 'string'   → must be text
            // 'max:255'  → stays within the VARCHAR(255) column size in the DB
            'name' => ['required', 'string', 'max:255'],

            // 'integer'  → must be a whole number (not 90.5)
            // 'min:1'    → percentage must be at least 1
            // 'max:100'  → percentage cannot exceed 100
            // These match the unsignedTinyInteger(1-100) we defined in the migration
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
