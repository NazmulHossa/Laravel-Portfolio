@extends('layouts.admin')

@section('title', 'Message from ' . $message->name)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📩 Message Detail</h2>
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
        ← Back to Inbox
    </a>
</div>

{{-- NOTE: $message->markAsRead() is called in MessageController::show()
     BEFORE this view is rendered, so by the time this page loads,
     is_read is already true and the unread badge count has updated. --}}

<div class="card shadow-sm" style="max-width: 700px;">

    {{-- ── CARD HEADER: subject + time ── --}}
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-6">{{ $message->subject }}</span>
        {{-- $message->time_ago → "3 hours ago" (Model accessor) --}}
        <small class="text-secondary">{{ $message->time_ago }}</small>
    </div>

    {{-- ── CARD BODY: message details ── --}}
    <div class="card-body">

        {{-- <dl> = definition list — perfect for label: value pairs --}}
        <dl class="row mb-0">

            <dt class="col-sm-3 text-muted">From</dt>
            <dd class="col-sm-9 fw-semibold">{{ $message->name }}</dd>

            <dt class="col-sm-3 text-muted">Email</dt>
            <dd class="col-sm-9">
                {{-- Clickable email link --}}
                <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                    {{ $message->email }}
                </a>
            </dd>

            <dt class="col-sm-3 text-muted">Subject</dt>
            <dd class="col-sm-9">{{ $message->subject }}</dd>

            {{-- $message->received_at → "15 Jan 2026, 02:30 PM" (Model accessor) --}}
            <dt class="col-sm-3 text-muted">Received</dt>
            <dd class="col-sm-9 text-muted small">{{ $message->received_at }}</dd>

            <dt class="col-sm-3 text-muted pt-2">Message</dt>
            <dd class="col-sm-9">
                {{-- white-space: pre-wrap preserves line breaks the user typed
                     without it, all text appears on one line --}}
                <div class="p-3 bg-light rounded border" style="white-space: pre-wrap; line-height: 1.7;">
                    {{ $message->message }}
                </div>
            </dd>

        </dl>

    </div>

    {{-- ── CARD FOOTER: action buttons ── --}}
    <div class="card-footer bg-transparent d-flex gap-2 flex-wrap">

        {{-- $message->reply_link → "mailto:user@email.com?subject=Re: Hello" (Model accessor)
             Opens the system email client pre-filled with recipient and subject --}}
        <a href="{{ $message->reply_link }}" class="btn btn-primary">
            ✉ Reply via Email
        </a>

        <form action="{{ route('admin.messages.destroy', $message) }}"
              method="POST"
              onsubmit="return confirm('Delete this message permanently?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">🗑 Delete</button>
        </form>

    </div>
</div>

@endsection