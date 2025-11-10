<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Institute Portal')</title>
    @vite('resources/css/institute.css')
    @stack('head')
</head>
<body class="@yield('body_class', 'portal-body')">
    <main class="portal-page">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
