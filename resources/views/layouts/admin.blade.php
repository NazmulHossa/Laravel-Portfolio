<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active-link {
            background: rgba(255,255,255,0.15);
            border-radius: 6px;
        }
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     ADMIN NAVBAR
     request()->routeIs('admin.skills.*') → true on any skills page
     This highlights the current section in the navbar
══════════════════════════════════════════════════════ --}}
<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">⚙ Admin Panel</a>

    <div class="d-flex align-items-center gap-1">

        <a href="{{ route('admin.skills.index') }}"
           class="nav-link text-white px-3 py-2 {{ request()->routeIs('admin.skills.*') ? 'active-link' : '' }}">
            🎯 Skills
        </a>

        <a href="{{ route('admin.projects.index') }}"
           class="nav-link text-white px-3 py-2 {{ request()->routeIs('admin.projects.*') ? 'active-link' : '' }}">
            📁 Projects
        </a>

        {{-- Messages link with unread count badge
             $unreadCount is shared by AppServiceProvider (or pass from each controller) --}}
        <a href="{{ route('admin.messages.index') }}"
           class="nav-link text-white px-3 py-2 position-relative {{ request()->routeIs('admin.messages.*') ? 'active-link' : '' }}">
            📨 Messages
            @php
                // Count unread messages for the navbar badge
                // We call this directly here so every admin page shows it
                $navUnread = \App\Models\Message::unread()->count();
            @endphp
            @if($navUnread > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size: 0.65rem;">
                    {{ $navUnread }}
                </span>
            @endif
        </a>

        <span class="text-secondary mx-1">|</span>

        <a href="{{ route('home') }}" class="nav-link text-info px-2">
            ← View Site
        </a>

        {{-- Logout — POST required by Laravel Breeze --}}
        <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                Logout
            </button>
        </form>

    </div>
</nav>

{{-- ══════════════════════════════════════════════════════
     FLASH MESSAGES
══════════════════════════════════════════════════════ --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════
     PAGE CONTENT
══════════════════════════════════════════════════════ --}}
<div class="container my-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>