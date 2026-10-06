<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Staff sign in · Mail Boxes Etc.</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen lg:grid-cols-2">
    <div class="relative hidden overflow-hidden bg-ink p-12 text-white lg:flex lg:flex-col lg:justify-between">
        <div class="grid-ink absolute inset-0 opacity-60"></div>
        <div class="absolute -bottom-40 -left-32 size-[30rem] rounded-full bg-brand-600/40 blur-3xl"></div>
        <img src="{{ asset('images/logo.png') }}" alt="Mail Boxes Etc." class="relative h-10 w-auto self-start invert">
        <div class="relative">
            <p class="font-display text-lg font-bold text-brand-400">{{ config('mbe.tagline') }}</p>
            <h1 class="mt-3 font-display text-5xl leading-tight font-extrabold">Every enquiry,<br>one inbox.</h1>
            <p class="mt-4 max-w-md text-white/60">Mailbox applications, print quotes, ocean pre-alerts and messages from the website, plus the sailing schedule and site announcements.</p>
        </div>
        <div class="airmail relative h-1.5 w-40 rounded-full"></div>
    </div>

    <div class="flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-sm">
            <img src="{{ asset('images/logo.png') }}" alt="Mail Boxes Etc." class="mb-10 h-9 w-auto lg:hidden">
            <h2 class="font-display text-3xl font-bold">Staff sign in</h2>
            <p class="mt-2 text-sm text-muted">Sign in to manage website enquiries.</p>

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-4">
                @csrf
                <x-field name="email" type="email" label="Email" required autofocus autocomplete="username" :value="app()->isLocal() ? 'admin@mbe.ky' : null" />
                <x-field name="password" type="password" label="Password" required autocomplete="current-password" />
                <label class="flex items-center gap-2.5 text-sm">
                    <input type="checkbox" name="remember" value="1" class="size-4 rounded accent-brand-600"> Keep me signed in
                </label>
                <button type="submit" class="btn btn-primary w-full !py-3.5 !text-base">Sign in</button>
            </form>

            @if (app()->isLocal())
                <div class="mt-6 rounded-2xl bg-white p-4 text-sm ring-1 ring-line">
                    <p class="font-semibold">Demo access</p>
                    <p class="mt-1 text-muted">Email <code class="font-mono text-ink">admin@mbe.ky</code><br>Password <code class="font-mono text-ink">mbe-demo-2026</code></p>
                    <p class="mt-2 text-xs text-muted">Shown in the local environment only.</p>
                </div>
            @endif

            <a href="{{ route('home') }}" class="link-arrow mt-8"><x-icon name="arrow-left" class="size-4" /> Back to website</a>
        </div>
    </div>
</body>
</html>
