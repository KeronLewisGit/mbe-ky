@php
    $mbe = config('mbe');
@endphp
<x-layout title="Virtual Mailbox" description="View and manage your postal mail online, anytime and from anywhere, with the MBE Virtual Mailbox powered by Anytime Mailbox.">
    <x-page-hero eyebrow="Virtual Mailbox" title="Check and manage your postal mail. Anywhere, anytime."
        lead="The new way to receive and manage your postal mail from the comfort of home or office, without having to check a physical mailbox."
        image="virtual-mailbox.jpg" image-alt="A phone showing a new mail item notification" :crumb="['Mailboxes', route('mailboxes')]">
        <a href="#get-started" class="btn btn-primary">Get started</a>
        <a href="{{ $mbe['links']['virtual_login'] }}" target="_blank" rel="noopener" class="btn btn-onDark">Already have a Virtual Mailbox? Sign in <x-icon name="arrow-up-right" class="size-4" /></a>
    </x-page-hero>

    {{-- Actions --}}
    <section class="section">
        <div class="wrap grid items-center gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="eyebrow">Manage your postal mail online</p>
                <h2 class="h-section mt-4">Your mail arrives. You decide what happens next.</h2>
                <p class="lead mt-4">Use your PC, laptop or any mobile device to view images of your incoming postal and courier mail, then choose an action with a few simple clicks.</p>
                <ul class="mt-8 space-y-3 text-sm">
                    @foreach ([
                        'You get a Cayman Islands mailing address',
                        'Functions like a normal P.O. Box and we scan your mail for online viewing via the app',
                        'We will open & scan the contents at your request',
                        'Hold for pick-up, shred, recycle and forward mail with one click',
                        'Pay monthly or save 10% when you pay for the year',
                    ] as $feature)
                        <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $feature }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Interactive inbox preview --}}
            <div class="lg:col-span-7" data-reveal style="--reveal-delay: 120ms" x-data="{ action: null }">
                <div class="rounded-3xl bg-ink p-3 shadow-lift sm:p-4">
                    <div class="flex items-center gap-1.5 px-2 pb-3">
                        <span class="size-2.5 rounded-full bg-white/20"></span><span class="size-2.5 rounded-full bg-white/20"></span><span class="size-2.5 rounded-full bg-white/20"></span>
                        <span class="ml-3 text-xs text-white/40">Illustration of the Virtual Mailbox inbox</span>
                    </div>
                    <div class="rounded-2xl bg-paper p-5 sm:p-6">
                        <div class="flex items-center justify-between">
                            <p class="font-display text-lg font-bold">Inbox</p>
                            <span class="chip"><span class="size-1.5 rounded-full bg-brand-600"></span> 1 new item</span>
                        </div>
                        <div class="mt-4 flex gap-4 rounded-2xl bg-white p-4 ring-1 ring-line">
                            <div class="grid h-20 w-28 shrink-0 place-items-center rounded-lg bg-paper-deep ring-1 ring-line">
                                <x-icon name="mail" class="size-8 text-muted/60" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold">Envelope received</p>
                                <p class="text-xs text-muted">Scanned at Camana Bay</p>
                                <p class="mt-3 text-xs font-semibold" :class="action ? 'text-emerald-700' : 'text-muted'" x-text="action ? 'Request sent: ' + action : 'Choose an action below'">Choose an action below</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-5">
                            @foreach ([
                                ['scan-line', 'Open & scan'],
                                ['forward', 'Forward'],
                                ['store', 'Hold for pick-up'],
                                ['shredder', 'Shred'],
                                ['recycle', 'Recycle'],
                            ] as [$icon, $label])
                                <button type="button" @click="action = '{{ $label }}'"
                                    class="flex flex-col items-center gap-2 rounded-xl p-3 text-xs font-semibold ring-1 transition"
                                    :class="action === '{{ $label }}' ? 'bg-brand-600 text-white ring-brand-600' : 'bg-white text-ink ring-line hover:ring-ink/40'">
                                    <x-icon :name="$icon" /> {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Anytime / anywhere / any device --}}
    <section class="section bg-white">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">Powered by Anytime Mailbox</p>
                <h2 class="h-section mt-4">Securely access your Virtual Mailbox from the app.</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['clock', 'Anytime', 'Access to your mail 24/7. No more driving to your PO Box or waiting until you return from your trip.'],
                    ['globe', 'Anywhere', 'Our secure cloud-based platform enables you to view and manage your mail anywhere in the world.'],
                    ['monitor-smartphone', 'Any device', 'From PC to Mac, Apple to Android, smart phone to tablet, we’ve got you covered.'],
                ] as $i => [$icon, $title, $text])
                    <div class="card text-center" data-reveal style="--reveal-delay: {{ $i * 90 }}ms">
                        <span class="icon-tile mx-auto !size-14"><x-icon :name="$icon" class="size-6" /></span>
                        <h3 class="mt-5 font-display text-2xl font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
            <img src="{{ asset('images/anytime-mailbox.webp') }}" alt="The Anytime Mailbox app on a laptop, tablet and phone" class="mx-auto mt-14 w-full max-w-3xl" loading="lazy" data-reveal>
        </div>
    </section>

    {{-- How it works --}}
    <section class="section" id="get-started">
        <div class="wrap">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">How it works</p>
                <h2 class="h-section mt-4">Set up in three steps.</h2>
            </div>
            <ol class="mt-12 grid gap-6 lg:grid-cols-3">
                @foreach ([
                    ['Pick your location', 'Your incoming mail will be received at this location. If you request a Hold Mail for Pick Up, this is where you will pick up during regular business hours.'],
                    ['Pick a plan & create account', 'Three plans to choose from. Monthly plans available. Save when you sign up for a year. Rates are shown in USD. USD credit card and KYD debit cards accepted. Photo ID required to create account.'],
                    ['Manage your mail', 'Download the Anytime Mailbox Renter app to your mobile device or sign in from the webpage. When mail arrives for you, it will automatically appear in your inbox.'],
                ] as $i => [$title, $text])
                    <li class="card" data-reveal style="--reveal-delay: {{ $i * 90 }}ms">
                        <span class="inline-flex rounded-full bg-ink px-3 py-1 text-xs font-semibold text-white">Step {{ $i + 1 }}</span>
                        <h3 class="mt-4 font-display text-xl font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 rounded-[2rem] bg-ink p-6 text-white sm:p-10" data-reveal>
                <h3 class="font-display text-2xl font-bold">To get started, choose your location</h3>
                <p class="mt-2 text-white/60">You will complete sign-up securely with our partner, Anytime Mailbox.</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($mbe['locations'] as $location)
                        <a href="{{ $location['virtual_signup'] }}" target="_blank" rel="noopener" class="group flex items-center justify-between gap-4 rounded-2xl bg-white/5 p-5 ring-1 ring-white/15 transition hover:bg-brand-600 hover:ring-brand-600">
                            <span>
                                <span class="block font-display text-xl font-bold">{{ $location['name'] }}</span>
                                <span class="block text-sm text-white/60 group-hover:text-white/80">{{ $location['address'] }}</span>
                            </span>
                            <x-icon name="arrow-up-right" class="size-5 shrink-0" />
                        </a>
                    @endforeach
                </div>
                <p class="mt-6 text-xs text-white/50">By signing up you agree to the <a href="{{ route('legal', 'virtual-mailbox-terms') }}" class="underline hover:text-white">Virtual Mailbox terms &amp; conditions</a> and <a href="{{ route('legal', 'virtual-mailbox-privacy') }}" class="underline hover:text-white">privacy policy</a>.</p>
            </div>
        </div>
    </section>

    <x-help-band topic="Mailboxes" />
</x-layout>
