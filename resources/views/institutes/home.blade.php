@extends('layouts.institute')

@section('title', 'Institute Dashboard')
@section('body_class', 'portal-body portal-body--light')

@section('content')
    <div class="portal-shell">
        <header class="portal-topbar">
            <div>
                <p class="portal-kicker">Institute Portal</p>
                <h1>Content library</h1>
                <p class="portal-text-sm">Browse curated drops by branch, year, and month. Downloads stay on IVVP—no raw S3 links.</p>
            </div>
            <div class="portal-user">
                <div class="portal-user__meta">
                    <p class="portal-user__name" data-institute-name>Loading…</p>
                    <p class="portal-user__email" data-institute-email></p>
                    <p class="portal-user__phone" data-institute-phone></p>
                </div>
                <button class="portal-btn portal-btn--ghost" data-logout>Logout</button>
            </div>
        </header>

        <section class="portal-spotlight">
            <div>
                <p class="portal-kicker">Weekly highlight</p>
                <h2>Plan lessons faster with the curated dropboard</h2>
                <p class="portal-text-sm">Pick a branch, lock a year, and the system stacks all videos, PDFs, and tests in one place. Use it as your ready-to-teach queue.</p>
                <div class="portal-spotlight__actions">
                    <a href="mailto:support@ivvp.co.in" class="portal-btn portal-btn--primary">Ask for new content</a>
                    <a href="https://www.ivvp.co.in" target="_blank" rel="noopener" class="portal-btn portal-btn--ghost">Visit ivvp.co.in</a>
                </div>
            </div>
            <div class="portal-spotlight__badge">
                <span>NEW</span>
                <p>Smart month accordions keep everything tidy—no spreadsheets needed.</p>
            </div>
        </section>

        <section class="portal-stats">
            <article class="portal-stat-card">
                <p class="portal-stat-label">Branches on-boarded</p>
                <p class="portal-stat-value" data-stat-branches>0</p>
                <p class="portal-stat-hint">Unique institute streams</p>
            </article>
            <article class="portal-stat-card">
                <p class="portal-stat-label">Academic years</p>
                <p class="portal-stat-value" data-stat-years>0</p>
                <p class="portal-stat-hint">Mapped to your syllabi</p>
            </article>
            <article class="portal-stat-card">
                <p class="portal-stat-label">Resources live</p>
                <p class="portal-stat-value" data-stat-assets>0</p>
                <p class="portal-stat-hint">Videos • PDFs • Tests</p>
            </article>
        </section>

        <section id="institute-home-root" class="portal-home">
            <div class="portal-state" data-state="loading">
                <span class="spinner"></span>
                <p>Gathering your institute data…</p>
            </div>
        </section>

        <div id="portal-viewer" class="portal-viewer" aria-hidden="true">
            <div class="portal-viewer__backdrop" data-viewer-close></div>
            <div class="portal-viewer__dialog" role="dialog" aria-modal="true">
                <button type="button" class="portal-viewer__close" data-viewer-close>&times;</button>
                <div class="portal-viewer__body" data-viewer-body>
                    <p class="portal-text-sm">Choose a video to preview it here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/institute/home.js')
@endpush
