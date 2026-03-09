<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// A FormRequest is a dedicated class for validating one specific form.
// Instead of writing $request->validate([...]) inside the controller method,
// you type-hint this class as the parameter and Laravel runs it automatically
// BEFORE the controller method executes.
//
// How it works:
//   1. User submits the contact form (POST /contact)
//   2. Laravel sees ContactRequest in sendMessage(ContactRequest $request)
//   3. Laravel calls authorize() — if false, returns 403 Forbidden
//   4. Laravel calls rules() — if validation fails, redirects back with $errors
//   5. Only if everything passes does sendMessage() body execute
//
// Usage in controller:
//   public function sendMessage(ContactRequest $request) {
//       Message::create($request->validated());
//   }

class ContactRequest extends FormRequest
{
    // ── AUTHORIZE ────────────────────────────────────────────
    // Who is allowed to submit this form?
    // Return true  = everyone (public contact form, no login needed)
    // Return false = nobody (would return a 403 error)
    // You could also check: return auth()->check(); for logged-in only
    public function authorize(): bool
    {
        return true; // Anyone can send a contact message
    }

    // ── RULES ────────────────────────────────────────────────
    // Validation rules for each form field.
    // Laravel checks these before the controller method runs.
    public function rules(): array
    {
        return [
            // 'required'  → field must be present and not empty
            // 'string'    → must be text (not array, not file)
            // 'max:255'   → no longer than 255 characters
            'name'    => ['required', 'string', 'max:255'],

            // 'email'     → must be a valid email format e.g. user@domain.com
            'email'   => ['required', 'string', 'email', 'max:255'],

            'subject' => ['required', 'string', 'max:255'],

            // 'min:10'    → message must be at least 10 characters
            // 'max:5000'  → prevents huge spam messages
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    // ── MESSAGES ─────────────────────────────────────────────
    // Custom error messages shown to the user when validation fails.
    // If you don't define these, Laravel uses its default messages
    // like "The name field is required." which are fine too.
    public function messages(): array
    {
        return [
            'name.required'    => 'Please enter your full name.',
            'email.required'   => 'Please enter your email address.',
            'email.email'      => 'Please enter a valid email address (e.g. you@example.com).',
            'subject.required' => 'Please enter a subject for your message.',
            'message.required' => 'Please write a message.',
            'message.min'      => 'Your message must be at least 10 characters long.',
            'message.max'      => 'Your message is too long (max 5000 characters).',
        ];
    }

    // ── ATTRIBUTES ───────────────────────────────────────────
    // Rename field names used in default error messages.
    // Without this, Laravel says "The name field is required."
    // With this, it says "The Full Name field is required."
    public function attributes(): array
    {
        return [
            'name'    => 'Full Name',
            'email'   => 'Email Address',
            'subject' => 'Subject',
            'message' => 'Message',
        ];
    }
}