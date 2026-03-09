@extends('layouts.admin')

@section('title', 'Messages Inbox')

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">📨 Inbox</h2>
        <p class="text-muted mb-0">
            {{ $totalMessages }} total
            @if($unreadCount > 0)
                • <span class="text-danger fw-semibold">{{ $unreadCount }} unread</span>
            @else
                • <span class="text-success">All caught up ✓</span>
            @endif
        </p>
    </div>
    @if($totalMessages > 0)
        <form action="{{ route('admin.messages.destroyAll') }}"
              method="POST"
              onsubmit="return confirm('Delete ALL {{ $totalMessages }} messages? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger btn-sm">🗑 Clear All</button>
        </form>
    @endif
</div>

{{-- ── SEARCH ── --}}
<form action="{{ route('admin.messages.index') }}" method="GET" class="mb-4">
    <div class="input-group" style="max-width: 420px;">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search name, email or subject..."
               value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">🔍 Search</button>
        @if(request('search'))
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-danger">✕ Clear</a>
        @endif
    </div>
</form>

{{-- ── MESSAGES TABLE ── --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="40">#</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Received</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                {{-- Unread messages get a light-blue background --}}
                <tr class="{{ !$message->is_read ? 'table-primary' : '' }}">
                    <td class="text-muted">{{ $loop->iteration }}</td>

                    <td>
                        <div class="fw-semibold">
                            {{-- Bold dot for unread messages --}}
                            @if(!$message->is_read)
                                <span class="text-primary me-1">●</span>
                            @endif
                            {{ $message->name }}
                        </div>
                        <small class="text-muted">{{ $message->email }}</small>
                    </td>

                    <td>{{ Str::limit($message->subject, 45) }}</td>

                    {{-- $message->time_ago → "2 hours ago" (Model accessor)
                         title="{{ $message->received_at }}" shows full date on hover --}}
                    <td>
                        <span title="{{ $message->received_at }}" class="text-muted small">
                            {{ $message->time_ago }}
                        </span>
                    </td>

                    <td>
                        {{-- View button — also marks message as read (see MessageController::show) --}}
                        <a href="{{ route('admin.messages.show', $message) }}"
                           class="btn btn-sm btn-info text-white me-1">View</a>

                        <form action="{{ route('admin.messages.destroy', $message) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this message?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">✕</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        @if(request('search'))
                            No messages matching "{{ request('search') }}".
                            <a href="{{ route('admin.messages.index') }}">Clear search</a>
                        @else
                            No messages yet. When someone submits the contact form, it appears here.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── PAGINATION ── --}}
@if($messages->hasPages())
    <div class="mt-3 d-flex justify-content-center">
        {{ $messages->links() }}
    </div>
@endif

@endsection