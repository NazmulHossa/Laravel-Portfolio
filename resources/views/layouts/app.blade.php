<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Portfolio')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Smooth fill animation for skill progress bars */
        .progress-bar {
            transition: width 1.2s ease-in-out;
        }

        /* Start all bars at 0 width — JS will animate them to real % */
        .progress-bar-animated-load {
            width: 0 !important;
        }

        /* Active nav link underline */
        .navbar .nav-link.active {
            border-bottom: 2px solid #fff;
        }

        /* Footer always at bottom */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1; }
    </style>

    {{-- Extra head content per page (e.g. page-specific CSS) --}}
    @stack('styles')
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     NAVBAR
     request()->routeIs('home') → true when on the home page
     This adds Bootstrap's "active" class to the right link
══════════════════════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            💼 MyPortfolio
        </a>

        {{-- Mobile toggle button --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portfolio') ? 'active' : '' }}"
                       href="{{ route('portfolio') }}">Projects</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                       href="{{ route('contact') }}">Contact</a>
                </li>

                {{-- Show Admin panel link ONLY when logged in --}}
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-semibold"
                           href="{{ route('admin.dashboard') }}">⚙ Admin</a>
                    </li>
                    <li class="nav-item">
                        {{-- Logout must be a POST request — use a form with @csrf --}}
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="nav-link btn btn-link text-danger p-0 px-2">
                                Logout
                            </button>
                        </form>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════
     FLASH MESSAGES  (session-based, one-time notifications)
     These appear after form submissions and redirects.
══════════════════════════════════════════════════════ --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════
     MAIN CONTENT
     Each page view fills in this @yield('content') section
══════════════════════════════════════════════════════ --}}
<main class="container my-4">
    @yield('content')
</main>

{{-- ══════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════ --}}
<footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
        <p class="mb-1 fw-semibold">💼 MyPortfolio</p>
        <p class="mb-0 text-secondary small">&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</footer>

{{-- Bootstrap 5 JS (needed for navbar toggle and alert dismiss) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Animate skill bars on page load --}}
<script>
    // When the page has finished loading, animate each .progress-bar
    // from 0% to its real width (stored in data-width attribute)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.progress-bar[data-width]').forEach(function (bar) {
            // Small timeout so the "0 → real" transition is visible
            setTimeout(function () {
                bar.style.width = bar.getAttribute('data-width') + '%';
            }, 200);
        });
    });
</script>

{{-- Extra scripts per page --}}
@stack('scripts')

</body>
</html>