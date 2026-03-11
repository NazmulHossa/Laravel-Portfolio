@extends('layouts.app')

@section('title', 'Contact Me')

@section('content')

<div class="row g-5">

    {{-- ══ LEFT: Contact Form ══════════════════════════════ --}}
    <div class="col-lg-7">

        <h1 class="fw-bold mb-1">Get In Touch</h1>
        <p class="text-muted mb-4">Have a project in mind or want to collaborate? Send me a message!</p>

       
        <form action="{{ route('contact.send') }}" method="POST" novalidate>
            @csrf

           
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="John Doe"
                       autocomplete="name">
                @error('name')
                    {{-- $message is automatically set to the validation error text --}}
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL FIELD --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="john@example.com"
                       autocomplete="email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- SUBJECT FIELD --}}
            <div class="mb-3">
                <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                <input type="text"
                       id="subject"
                       name="subject"
                       class="form-control @error('subject') is-invalid @enderror"
                       value="{{ old('subject') }}"
                       placeholder="e.g. Project Inquiry">
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- MESSAGE FIELD --}}
            <div class="mb-4">
                <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                <textarea id="message"
                          name="message"
                          rows="6"
                          class="form-control @error('message') is-invalid @enderror"
                          placeholder="Write your message here...">{{ old('message') }}</textarea>
                          {{-- old('message') re-fills the textarea if validation failed --}}
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg px-5">
                📤 Send Message
            </button>

        </form>

    </div>

    {{-- ══ RIGHT: Contact Info ══════════════════════════════ --}}
    <div class="col-lg-5">

        <div class="bg-dark text-white rounded-3 p-4 h-100">
            <h4 class="fw-bold mb-4">Contact Information</h4>

            <div class="mb-3 d-flex align-items-start gap-3">
                <span class="fs-4">📧</span>
                <div>
                    <div class="fw-semibold">Email</div>
                    <a href="mailto:hello@myportfolio.com" class="text-info text-decoration-none">
                        hello@myportfolio.com
                    </a>
                </div>
            </div>

            <div class="mb-3 d-flex align-items-start gap-3">
                <span class="fs-4">📍</span>
                <div>
                    <div class="fw-semibold">Location</div>
                    <span class="text-secondary">Chattogram, Bangladesh</span>
                </div>
            </div>

            <div class="mb-4 d-flex align-items-start gap-3">
                <span class="fs-4">⏰</span>
                <div>
                    <div class="fw-semibold">Response Time</div>
                    <span class="text-secondary">Usually within 24 hours</span>
                </div>
            </div>

            <hr class="border-secondary">

            <p class="text-secondary small mb-0">
                I'm currently available for freelance work and full-time opportunities.
                Let's build something great together!
            </p>
        </div>

    </div>

</div>

@endsection
