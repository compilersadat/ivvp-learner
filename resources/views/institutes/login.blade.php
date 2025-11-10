@extends('layouts.institute')

@section('title', 'Institute Login')
@section('body_class', 'portal-body portal-body--accent')

@section('content')
    <div class="portal-grid portal-grid--split">
        <section class="portal-hero">
            <div class="portal-hero__badge">IVVP</div>
            <h1 class="portal-hero__title">Welcome back, institutes</h1>
            <p class="portal-hero__copy">
                Access curated classroom-ready videos, tests and study packs built for your cohorts.
                Sign in with the institute credentials shared by our onboarding team or plug in
                your secure USB key.
            </p>
            <ul class="portal-hero__list">
                <li>Single dashboard for all branches and batches</li>
                <li>Fresh video drops every month</li>
                <li>One-click secure downloads, no S3 exposure</li>
            </ul>
        </section>

        <section class="portal-card portal-card--form">
            <div class="portal-card__header">
                <div>
                    <p class="portal-kicker">Institute Portal</p>
                    <h2>Login with email</h2>
                </div>
                <a href="https://www.ivvp.co.in" class="portal-link" target="_blank" rel="noopener">Need help?</a>
            </div>

            <form id="institute-login-form" data-redirect="{{ route('institutes.portal.home') }}" class="portal-form">
                <div class="portal-form__group">
                    <label for="email">Institute email</label>
                    <input type="email" id="email" name="email" placeholder="eg: admin@institution.com" required>
                </div>
                <div class="portal-form__group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>
                <button type="submit" class="portal-btn portal-btn--primary" id="login-submit">
                    <span class="btn-label">Access dashboard</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
                <p class="portal-form__hint">
                    This login is restricted to verified institutes. Students should continue using the mobile app.
                </p>
            </form>

            <div class="portal-divider">
                <span>or</span>
            </div>

            <div class="portal-card__header portal-card__header--compact">
                <div>
                    <p class="portal-kicker">USB key</p>
                    <h2>Quick unlock</h2>
                </div>
                <p class="portal-text-sm">Plug the registered USB device and enter its identifier.</p>
            </div>

            <form id="usb-login-form" data-redirect="{{ route('institutes.portal.home') }}" class="portal-form">
                <div class="portal-form__group">
                    <label for="usb_identifier">USB identifier</label>
                    <input type="text" id="usb_identifier" name="usb_identifier" placeholder="AUTO-XXXX-XXXX" required>
                </div>
                <button type="submit" class="portal-btn portal-btn--ghost" id="usb-submit">
                    <span class="btn-label">Login with USB</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <div class="portal-alert portal-alert--error d-none" role="alert" data-error-box></div>
        </section>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/institute/login.js')
@endpush
