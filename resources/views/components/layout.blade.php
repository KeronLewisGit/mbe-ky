@props(['title' => null, 'description' => null])
@php
    $mbe = config('mbe');
    $status = \App\Support\Content::storeStatus($mbe['locations']['camana-bay']);
    $nav = [
        'Mailboxes' => [
            'route' => 'mailboxes',
            'active' => ['mailboxes', 'virtual', 'physical'],
            'items' => [
                ['mailboxes', 'Mailbox services', 'Compare virtual and physical mailboxes', 'mailbox'],
                ['virtual', 'Virtual Mailbox', 'Read your postal mail online, anywhere', 'smartphone'],
                ['physical', 'Physical Mailbox', 'A real street address with a key', 'key-round'],
            ],
        ],
        'Shop & Ship' => [
            'route' => 'ebox',
            'active' => ['ebox', 'ocean', 'pack-ship', 'store-change'],
            'items' => [
                ['ebox', 'E-box', 'Your U.S. address for online shopping', 'plane'],
                ['ocean', 'Ocean Ship', 'Large cargo by sea from Miami', 'ship'],
                ['pack-ship', 'Pack & Ship', 'UPS, FedEx, DHL and postal services', 'package'],
            ],
        ],
        'Print & Design' => [
            'route' => 'printing',
            'active' => ['printing', 'graphic-design'],
            'items' => [
                ['printing', 'Printing services', 'From business cards to bound booklets', 'printer'],
                ['graphic-design', 'Graphic design', 'In-house design, layout to print', 'palette'],
            ],
        ],
        'More' => [
            'route' => 'services',
            'active' => ['services'],
            'items' => [
                ['services', 'Print from Phone', 'Photo prints in minutes', 'image', '#print-from-phone'],
                ['services', 'Shredding', 'Secure document destruction', 'shredder', '#shredding'],
                ['services', 'Photo Booth Rental', 'Everything for your event', 'camera', '#photo-booth'],
            ],
        ],
    ];
    $current = Route::currentRouteName();
    $announcement = \App\Models\Announcement::current();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}Mail Boxes Etc. Cayman Islands</title>
    <meta name="description" content="{{ $description ?? 'Mailboxes, a U.S. address for online shopping, worldwide shipping and printing in Grand Cayman. Visit Mail Boxes Etc. at Camana Bay and Harbour Walk.' }}">
    <meta name="theme-color" content="#141517">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col" x-data="{ menu: false, search: false }" :class="menu && 'overflow-hidden'" @keydown.escape.window="menu = false; search = false">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold">Skip to content</a>

    @if ($announcement)
        <div class="bg-brand-600 text-white">
            <div class="wrap flex min-h-10 flex-wrap items-center justify-center gap-x-3 gap-y-1 py-2 text-center text-sm">
                <x-icon name="megaphone" class="hidden size-4 shrink-0 sm:block" />
                <p class="font-medium">{{ $announcement->message }}</p>
                @if ($announcement->link_url)
                    <a href="{{ $announcement->link_url }}" class="inline-flex items-center gap-1 font-semibold underline decoration-white/50 underline-offset-4 hover:decoration-white">{{ $announcement->link_text }} <x-icon name="arrow-right" class="size-3.5" /></a>
                @endif
            </div>
        </div>
    @endif

    {{-- Utility bar --}}
    <div class="bg-ink text-white">
        <div class="wrap flex h-10 items-center justify-between gap-4 text-xs">
            <a href="{{ route('contact') }}#locations" class="flex min-w-0 items-center gap-2 text-white/80 transition hover:text-white">
                <span class="relative flex size-2 shrink-0">
                    @if ($status['open'])
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                    @endif
                    <span class="relative inline-flex size-2 rounded-full {{ $status['open'] ? 'bg-emerald-400' : 'bg-white/40' }}"></span>
                </span>
                <span class="truncate"><span class="font-semibold text-white">Camana Bay</span> &middot; {{ $status['text'] }}</span>
            </a>
            <div class="flex shrink-0 items-center gap-5">
                <a href="tel:{{ $mbe['phone_href'] }}" class="hidden items-center gap-1.5 font-semibold transition hover:text-brand-300 sm:flex">
                    <x-icon name="phone" class="size-3.5" /> {{ $mbe['phone'] }}
                </a>
                <a href="{{ $mbe['links']['virtual_login'] }}" target="_blank" rel="noopener" class="hidden text-white/80 transition hover:text-white md:inline">Virtual Mailbox sign in</a>
                <a href="{{ $mbe['links']['ebox_login'] }}" target="_blank" rel="noopener" class="flex items-center gap-1 font-semibold transition hover:text-brand-300">
                    E-box log in <x-icon name="arrow-up-right" class="size-3.5" />
                </a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-line/80 bg-paper/85 backdrop-blur-lg">
        <div class="wrap flex h-[4.5rem] items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="Mail Boxes Etc. home">
                <img src="{{ asset('images/logo.png') }}" alt="Mail Boxes Etc." width="854" height="237" class="h-9 w-auto sm:h-10">
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
                @foreach ($nav as $label => $group)
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @focusout="if (! $el.contains($event.relatedTarget)) open = false">
                        <a href="{{ route($group['route']) }}" @focus="open = true"
                            class="flex items-center gap-1 rounded-full px-3.5 py-2 text-sm font-medium transition hover:bg-ink/5 {{ in_array($current, $group['active']) ? 'text-brand-700' : 'text-ink' }}"
                            :aria-expanded="open">
                            {{ $label }}
                            <x-icon name="chevron-down" class="size-3.5 opacity-50 transition" ::class="open && 'rotate-180'" />
                        </a>
                        <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute top-full left-1/2 w-80 -translate-x-1/2 pt-3">
                            <div class="rounded-2xl bg-white p-2 shadow-lift ring-1 ring-line">
                                @foreach ($group['items'] as $item)
                                    <a href="{{ route($item[0]).($item[4] ?? '') }}" class="group flex items-center gap-3 rounded-xl p-2.5 transition hover:bg-paper">
                                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-paper text-ink transition group-hover:bg-brand-600 group-hover:text-white">
                                            <x-icon :name="$item[3]" class="size-[18px]" />
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold">{{ $item[1] }}</span>
                                            <span class="block text-xs text-muted">{{ $item[2] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('blog') }}" class="rounded-full px-3.5 py-2 text-sm font-medium transition hover:bg-ink/5 {{ str_starts_with((string) $current, 'blog') ? 'text-brand-700' : '' }}">News</a>
                <a href="{{ route('contact') }}" class="rounded-full px-3.5 py-2 text-sm font-medium transition hover:bg-ink/5 {{ $current === 'contact' ? 'text-brand-700' : '' }}">Contact</a>
            </nav>

            <div class="flex items-center gap-2">
                <button type="button" class="grid size-10 place-items-center rounded-full transition hover:bg-ink/5" @click="search = true; $nextTick(() => $refs.search.focus())" aria-label="Search">
                    <x-icon name="search" class="size-[18px]" />
                </button>
                <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn btn-primary hidden !py-2.5 sm:inline-flex">Get a U.S. address</a>
                <button type="button" class="grid size-10 place-items-center rounded-full bg-ink text-white lg:hidden" @click="menu = true" aria-label="Open menu">
                    <x-icon name="menu" class="size-[18px]" />
                </button>
            </div>
        </div>
    </header>

    {{-- Search overlay --}}
    <div x-cloak x-show="search" x-transition.opacity class="fixed inset-0 z-50 bg-ink/60 p-4 backdrop-blur-sm sm:p-10" @click.self="search = false" role="dialog" aria-modal="true" aria-label="Search">
        <form action="{{ route('search') }}" class="mx-auto mt-16 flex max-w-2xl items-center gap-3 rounded-2xl bg-white p-3 pl-5 shadow-lift">
            <x-icon name="search" class="size-5 shrink-0 text-muted" />
            <input x-ref="search" type="search" name="q" placeholder="Search services, rates, FAQs…" class="w-full border-0 bg-transparent py-2 text-lg outline-none placeholder:text-muted/60" required>
            <button class="btn btn-dark">Search</button>
        </form>
    </div>

    {{-- Mobile menu --}}
    <div x-cloak x-show="menu" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="Menu">
        <div x-show="menu" x-transition.opacity class="absolute inset-0 bg-ink/50" @click="menu = false"></div>
        <div x-show="menu" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:leave="transition duration-200 ease-in" x-transition:leave-end="translate-x-full"
            class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col overflow-y-auto bg-paper shadow-lift">
            <div class="flex h-[4.5rem] shrink-0 items-center justify-between border-b border-line px-5">
                <img src="{{ asset('images/logo.png') }}" alt="Mail Boxes Etc." class="h-8 w-auto">
                <button type="button" class="grid size-10 place-items-center rounded-full bg-ink text-white" @click="menu = false" aria-label="Close menu">
                    <x-icon name="x" class="size-[18px]" />
                </button>
            </div>
            <div class="flex-1 space-y-6 px-5 py-6">
                @foreach ($nav as $label => $group)
                    <div>
                        <p class="mb-2 text-xs font-semibold tracking-[0.16em] text-muted uppercase">{{ $label }}</p>
                        <div class="space-y-1">
                            @foreach ($group['items'] as $item)
                                <a href="{{ route($item[0]).($item[4] ?? '') }}" @click="menu = false" class="flex items-center gap-3 rounded-xl bg-white p-3 ring-1 ring-line/70">
                                    <x-icon :name="$item[3]" class="size-5 text-brand-700" />
                                    <span class="text-sm font-semibold">{{ $item[1] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('blog') }}" class="btn btn-light">News</a>
                    <a href="{{ route('contact') }}" class="btn btn-light">Contact</a>
                </div>
            </div>
            <div class="space-y-2 border-t border-line p-5">
                <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn btn-primary w-full">Get a U.S. address</a>
                <a href="tel:{{ $mbe['phone_href'] }}" class="btn btn-dark w-full"><x-icon name="phone" class="size-4" /> {{ $mbe['phone'] }}</a>
            </div>
        </div>
    </div>

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="relative bg-ink text-white">
        <div class="airmail h-1.5 w-full"></div>
        <div class="wrap py-16">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Mail Boxes Etc." class="h-10 w-auto invert">
                    <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/60">
                        Worldwide parcel delivery, courier and postal services, printing and binding, mailboxes and a U.S. address for online shopping. Proudly part of the global Mail Boxes Etc. family.
                    </p>
                    <p class="mt-4 font-display text-lg font-bold text-brand-400">{{ $mbe['tagline'] }}</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="tel:{{ $mbe['phone_href'] }}" class="btn btn-onDark !py-2.5"><x-icon name="phone" class="size-4" /> {{ $mbe['phone'] }}</a>
                        <a href="mailto:{{ $mbe['emails']['general'] }}" class="btn btn-onDark !py-2.5"><x-icon name="mail" class="size-4" /> {{ $mbe['emails']['general'] }}</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 sm:grid-cols-4 lg:col-span-8">
                    @foreach ([
                        'E-box' => [
                            ['E-box overview', route('ebox')],
                            ['User guide', route('legal', 'user-guide')],
                            ['Dangerous & prohibited goods', route('legal', 'dangerous-and-prohibited-goods')],
                            ['Terms & conditions', route('legal', 'terms-and-conditions')],
                            ['Change pick-up store', route('store-change')],
                        ],
                        'Mailbox service' => [
                            ['Compare mailboxes', route('mailboxes')],
                            ['Physical mailbox T&C', route('legal', 'physical-mailbox-terms')],
                            ['Virtual mailbox T&C', route('legal', 'virtual-mailbox-terms')],
                            ['Virtual mailbox privacy', route('legal', 'virtual-mailbox-privacy')],
                        ],
                        'Shipping' => [
                            ['Pack & Ship', route('pack-ship')],
                            ['Ocean Ship', route('ocean')],
                            ['Sailing dates', route('ocean').'#sailings'],
                            ['Ocean pre-alert', route('ocean').'#form-ocean-pre-alert'],
                        ],
                        'Printing' => [
                            ['Our services', route('printing')],
                            ['Request a quote', route('printing').'#form-print-quote'],
                            ['Graphic design', route('graphic-design')],
                            ['Print from Phone', route('services').'#print-from-phone'],
                        ],
                    ] as $heading => $links)
                        <div>
                            <h3 class="font-sans text-xs font-semibold tracking-[0.16em] text-white/40 uppercase">{{ $heading }}</h3>
                            <ul class="mt-4 space-y-2.5 text-sm">
                                @foreach ($links as [$text, $url])
                                    <li><a href="{{ $url }}" class="text-white/75 transition hover:text-white">{{ $text }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-14 grid gap-4 sm:grid-cols-2">
                @foreach ($mbe['locations'] as $location)
                    <div class="flex flex-wrap items-start justify-between gap-4 rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div>
                            <p class="font-display text-lg font-bold">{{ $location['name'] }}</p>
                            <p class="mt-1 text-sm text-white/60">{{ $location['address'] }}</p>
                            <a href="{{ $location['directions'] }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-brand-300 hover:text-brand-200">Get directions <x-icon name="arrow-up-right" class="size-3.5" /></a>
                        </div>
                        <dl class="text-xs text-white/60">
                            @foreach ($location['hours'] as [$days, $hours])
                                <div class="flex justify-between gap-6 py-0.5"><dt>{{ $days }}</dt><dd class="font-medium text-white/85">{{ $hours }}</dd></div>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-6 text-xs text-white/45 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} Mail Boxes Etc. Participating stores only. Services vary by location.</p>
                <p class="flex flex-wrap gap-x-5 gap-y-1">
                    <a href="{{ $mbe['links']['mbe_global'] }}" target="_blank" rel="noopener" class="hover:text-white">MBE Global</a>
                    <a href="{{ $mbe['links']['facebook'] }}" target="_blank" rel="noopener" class="hover:text-white">Facebook</a>
                    <a href="{{ route('login') }}" class="hover:text-white">Staff sign in</a>
                    <span>Powered by <a href="https://www.findyello.com/" target="_blank" rel="noopener" class="hover:text-white">Yello</a></span>
                </p>
            </div>
        </div>
    </footer>

    {{-- Cookie notice --}}
    <div x-cloak x-show="show" x-transition
        x-data="{
            show: false,
            init() { try { this.show = ! localStorage.getItem('mbe-cookies') } catch (e) { this.show = true } },
            accept() { this.show = false; try { localStorage.setItem('mbe-cookies', '1') } catch (e) {} },
        }"
        class="fixed inset-x-4 bottom-4 z-40 mx-auto flex max-w-3xl flex-col gap-4 rounded-2xl bg-white p-5 shadow-lift ring-1 ring-line sm:flex-row sm:items-center">
        <p class="text-sm text-muted">We use cookies to provide a more efficient and personalised service. By clicking “I accept” you agree to our use of cookies and <a href="{{ route('legal', 'virtual-mailbox-privacy') }}" class="link">privacy policy</a>.</p>
        <button type="button" class="btn btn-dark shrink-0" @click="accept()">I accept</button>
    </div>
</body>
</html>
