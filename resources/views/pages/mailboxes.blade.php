@php
    $mbe = config('mbe');
    $plans = $mbe['mailbox']['plans'];
@endphp
<x-layout title="Mailbox Services" description="Virtual and physical mailboxes in Grand Cayman. Both give you a Cayman Islands street address that accepts post and courier deliveries.">
    <x-page-hero eyebrow="Mailbox services" title="A Cayman address that works like you do."
        lead="Choose between a virtual or a physical mailbox. Both give you a Cayman Islands mailing address and function just like a normal P.O. Box, with a few added benefits."
        image="physical-mailbox.jpg" image-alt="Brass MBE mailboxes, one open with mail inside">
        <a href="#compare" class="btn btn-primary">Compare mailboxes</a>
        <a href="{{ $mbe['links']['virtual_login'] }}" target="_blank" rel="noopener" class="btn btn-onDark">Virtual Mailbox sign in <x-icon name="arrow-up-right" class="size-4" /></a>
    </x-page-hero>

    {{-- Shared features --}}
    <section class="section">
        <div class="wrap grid items-center gap-12 lg:grid-cols-2">
            <div data-reveal>
                <p class="eyebrow">Included with every mailbox</p>
                <h2 class="h-section mt-4">Virtual and physical mailboxes available.</h2>
                <ul class="mt-8 space-y-5">
                    @foreach ([
                        ['truck', 'A street address, not a P.O. Box', 'Use it to receive regular postal mail and courier from FedEx, DHL & UPS, which do not deliver to P.O. Boxes.'],
                        ['shield-check', 'Customs handled', 'We will handle the Customs clearance of all your incoming packages.'],
                        ['forward', 'Mail holding and forwarding', 'Travelling? We hold your mail or forward it on.'],
                        ['map-pin', 'Two convenient locations', 'Camana Bay and Harbour Walk (Red Bay).'],
                    ] as [$icon, $title, $text])
                        <li class="flex gap-4">
                            <span class="icon-tile"><x-icon :name="$icon" /></span>
                            <div>
                                <h3 class="font-display text-lg font-bold">{{ $title }}</h3>
                                <p class="mt-1 text-sm text-muted">{{ $text }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative" data-reveal style="--reveal-delay: 120ms">
                <img src="{{ asset('images/work-from-home.jpg') }}" alt="A customer managing her mail from a laptop at home" class="aspect-square w-full rounded-3xl object-cover shadow-lift" loading="lazy">
                <div class="absolute -bottom-6 -left-4 max-w-60 rounded-2xl bg-white p-4 shadow-lift ring-1 ring-line sm:-left-8">
                    <p class="flex items-center gap-2 text-xs font-semibold text-emerald-700"><span class="size-2 rounded-full bg-emerald-500"></span> Courier delivery received</p>
                    <p class="mt-1 text-sm text-muted">When UPS, FedEx and DHL come knocking, someone is always in the office at MBE.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Compare --}}
    <section class="section bg-white" id="compare">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">Compare</p>
                <h2 class="h-section mt-4">Choose between a physical or a virtual mailbox.</h2>
                <p class="lead mt-4">Select your preferred mailbox type to get started.</p>
            </div>

            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                <article class="card flex flex-col" data-reveal>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="chip">Paid annually</span>
                            <h3 class="mt-4 font-display text-3xl font-bold">Physical Mailbox</h3>
                        </div>
                        <span class="icon-tile"><x-icon name="key-round" /></span>
                    </div>
                    <p class="mt-4 text-sm text-muted">All the general features, plus:</p>
                    <ul class="mt-4 space-y-3 text-sm">
                        @foreach ([
                            '24-hour access to your mailbox (at Camana Bay only)',
                            'Mailbox comes with a key',
                            'Email notifications for packages received',
                            '3 mailbox sizes based on your needs: Personal, Small Business and Corporate',
                            'Subscription paid annually',
                        ] as $feature)
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-auto pt-8">
                        <p class="text-sm text-muted">Starting at</p>
                        <p class="font-display text-4xl font-extrabold">CI${{ $plans['personal']['price'] }}<span class="text-base font-medium text-muted"> /year plus refundable security deposit</span></p>
                        <a href="{{ route('physical') }}#form-mailbox" class="btn btn-dark mt-6 w-full sm:w-auto">Physical Mailbox application <x-icon name="arrow-right" class="size-4" /></a>
                    </div>
                </article>

                <article class="relative flex flex-col overflow-hidden rounded-3xl bg-ink p-6 text-white shadow-lift sm:p-8" data-reveal style="--reveal-delay: 100ms">
                    <div class="absolute -top-24 -right-24 size-72 rounded-full bg-brand-600/40 blur-3xl"></div>
                    <div class="relative flex items-start justify-between gap-4">
                        <div>
                            <span class="chip !bg-white/10 !text-white !ring-white/20">Monthly or annually</span>
                            <h3 class="mt-4 font-display text-3xl font-bold">Virtual Mailbox</h3>
                        </div>
                        <span class="grid size-12 place-items-center rounded-2xl bg-brand-600"><x-icon name="smartphone" /></span>
                    </div>
                    <ul class="relative mt-8 space-y-3 text-sm text-white/85">
                        @foreach ([
                            'View images of your mail on any mobile device or PC',
                            'Select an action such as open & scan, forward or discard',
                            'Hold important mail for in-store pickup',
                            'Secure Android and Apple compatible app',
                            'Powered by Anytime Mailbox',
                            'Subscription paid monthly or annually',
                        ] as $feature)
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-brand-400" /> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <div class="relative mt-auto pt-8">
                        <p class="text-sm text-white/60">Starting at</p>
                        <p class="font-display text-4xl font-extrabold">CI${{ $mbe['mailbox']['virtual_month'] }}<span class="text-base font-medium text-white/60"> /month or CI${{ $mbe['mailbox']['virtual_year'] }}/year</span></p>
                        <a href="{{ route('virtual') }}" class="btn btn-primary mt-6 w-full sm:w-auto">Virtual Mailbox application <x-icon name="arrow-right" class="size-4" /></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- On-demand --}}
    <section class="section">
        <div class="wrap">
            <div class="grid gap-10 rounded-[2rem] bg-paper-deep p-6 ring-1 ring-line sm:p-12 lg:grid-cols-12" data-reveal>
                <div class="lg:col-span-7">
                    <p class="eyebrow">MBE On-Demand Address Service</p>
                    <h2 class="h-section mt-4">Need a Cayman street address without committing to a mailbox long term?</h2>
                    <p class="lead mt-4">Get access to a real street address for a single delivery or important documents exactly when you need it. Our On-Demand Address Service eliminates missed delivery attempts and removes the limitations of P.O. Boxes, ensuring your package or paperwork is received securely and without delay.</p>
                    <a href="#form-contact" class="btn btn-dark mt-8">Ask about On-Demand <x-icon name="arrow-right" class="size-4" /></a>
                </div>
                <div class="lg:col-span-5">
                    <div class="card h-full">
                        <h3 class="font-display text-lg font-bold">Perfect for</h3>
                        <ul class="mt-4 space-y-4 text-sm">
                            @foreach ([
                                ['laptop', 'Non-residents or remote workers without a permanent mailbox'],
                                ['plane', 'Visitors to the island receiving deliveries'],
                                ['mailbox', 'Residents who only have a P.O. Box'],
                                ['bell', 'Anyone expecting an important delivery and concerned about missing the courier'],
                            ] as [$icon, $text])
                                <li class="flex gap-3"><x-icon :name="$icon" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $text }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-help-band topic="On-Demand Address Service" title="Not sure which mailbox fits?" text="Tell us how you receive mail today and we will recommend the right option, or set up a one-off On-Demand address for you." />
</x-layout>
