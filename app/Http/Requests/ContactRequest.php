<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ContactRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true; // Anyone can send a contact message
    }

   
    public function rules(): array
    {
        return [
          
            'name'    => ['required', 'string', 'max:255'],

          
            'email'   => ['required', 'string', 'email', 'max:255'],

            'subject' => ['required', 'string', 'max:255'],

         
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

  
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
