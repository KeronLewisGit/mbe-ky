@php
    $mbe = config('mbe');
@endphp
<x-layout title="Contact & Locations" description="Contact Mail Boxes Etc. Cayman Islands. Store hours, directions and contacts for Camana Bay and Harbour Walk.">
    <x-page-hero eyebrow="Contact us" title="Have a question or comment?"
        lead="Send us a message, call the team, or drop into either of our Grand Cayman stores.">
        <a href="tel:{{ $mbe['phone_href'] }}" class="btn btn-primary"><x-icon name="phone" class="size-4" /> {{ $mbe['phone'] }}</a>
        <a href="#locations" class="btn btn-onDark">Store hours &amp; directions</a>
    </x-page-hero>

    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7" data-reveal>
                <div class="card">
                    <h2 class="font-display text-2xl font-bold">Send us a message</h2>
                    <p class="mt-1 mb-6 text-sm text-muted">Pick a topic and your message goes straight to the right team.</p>
                    <x-contact-form :topic="request('topic')" />
                </div>
            </div>

            <aside class="space-y-5 lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                <div class="card">
                    <h2 class="font-display text-xl font-bold">Email the right team</h2>
                    <ul class="mt-4 divide-y divide-line text-sm">
                        @foreach ([
                            ['plane', 'E-box shipping', $mbe['emails']['ebox']],
                            ['ship', 'Ocean shipping', $mbe['emails']['ocean']],
                            ['mailbox', 'Mailbox, courier, postal, general queries', $mbe['emails']['general']],
                            ['printer', 'Printing services', $mbe['emails']['print']],
                        ] as [$icon, $label, $email])
                            <li class="flex items-center gap-4 py-3.5">
                                <x-icon :name="$icon" class="size-5 shrink-0 text-brand-600" />
                                <div class="min-w-0">
                                    <p class="text-muted">{{ $label }}</p>
                                    <a href="mailto:{{ $email }}" class="font-semibold hover:text-brand-700">{{ $email }}</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-3xl bg-ink p-6 text-white sm:p-8">
                    <p class="text-xs font-semibold tracking-[0.16em] text-white/50 uppercase">Corporate address</p>
                    <address class="mt-3 text-lg leading-relaxed not-italic">
                        @foreach ($mbe['corporate_address'] as $line){{ $line }}<br>@endforeach
                    </address>
                    <a href="{{ $mbe['links']['facebook'] }}" target="_blank" rel="noopener" class="btn btn-onDark mt-6 !py-2.5">Follow MBE Cayman on Facebook <x-icon name="arrow-up-right" class="size-4" /></a>
                </div>
            </aside>
        </div>
    </section>

    <section class="section scroll-mt-20 bg-white" id="locations">
        <div class="wrap">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">Store locations</p>
                <h2 class="h-section mt-4">Conveniently located in Camana Bay and Harbour Walk.</h2>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @foreach ($mbe['locations'] as $location)
                    <x-location-card :location="$location" data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms" />
                @endforeach
            </div>
            <div class="mt-6 overflow-hidden rounded-3xl ring-1 ring-line" data-reveal>
                <iframe title="Map of Camana Bay, Grand Cayman" class="h-96 w-full border-0 grayscale-[35%]" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.088224695626!2d-81.38030018599156!3d19.321977586947273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f2587ac0cfe0749%3A0x2684b31630213fbc!2sCamana%20Bay!5e0!3m2!1sen!2sgt!4v1626730844889!5m2!1sen!2sgt"></iframe>
            </div>
        </div>
    </section>
</x-layout>
