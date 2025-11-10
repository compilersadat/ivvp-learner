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

        <section id="institute-home-root" class="portal-home">
            <div class="portal-state" data-state="loading">
                <span class="spinner"></span>
                <p>Gathering your institute data…</p>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/institute/home.js')
@endpush
