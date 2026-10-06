@php
    $mbe = config('mbe');
@endphp
<x-layout title="Pack & Ship" description="Expert packing and worldwide shipping from Grand Cayman. Mail Boxes Etc. is an Authorised Shipping Centre for UPS, FedEx and DHL and offers postal services.">
    <x-page-hero eyebrow="Pack &amp; Ship" title="Around the corner or around the world."
        lead="Whatever you want to send, the team at Mail Boxes Etc. can expertly pack it ready for safe delivery, and ship it with the courier that suits you best."
        image="pack-ship.jpg" image-alt="An MBE team member taping up a parcel">
        <a href="#form-contact" class="btn btn-primary">Ask for a shipping quote</a>
        <a href="{{ route('contact') }}#locations" class="btn btn-onDark">Find a store</a>
    </x-page-hero>

    {{-- Couriers --}}
    <section class="border-b border-line bg-white">
        <div class="wrap flex flex-wrap items-center justify-between gap-x-10 gap-y-4 py-7">
            <p class="text-xs font-semibold tracking-[0.16em] text-muted uppercase">Choose your courier</p>
            <div class="flex flex-wrap items-center gap-x-10 gap-y-2">
                @foreach ($mbe['couriers'] as $courier)
                    <span class="font-display text-2xl font-extrabold tracking-tight text-ink/40">{{ $courier }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap grid gap-6 lg:grid-cols-2">
            <article class="card" data-reveal>
                <span class="icon-tile"><x-icon name="package-open" /></span>
                <h2 class="mt-6 font-display text-3xl font-bold">Packaging solutions &amp; packing materials</h2>
                <div class="prose-mbe mt-4">
                    <p>We can give you peace of mind that your items are secure and protected while in transit.</p>
                    <p>We provide a range of custom packaging solutions to help with those unusual shapes and sizes, or particularly fragile items which require specialist packaging. And by adopting the most up-to-date techniques we can advise you on the best packaging methods.</p>
                </div>
                <ul class="mt-6 flex flex-wrap gap-2">
                    @foreach (['Boxes', 'Tape', 'Bubble Wrap', 'Fragile items', 'Unusual shapes', 'Oversized items'] as $tag)
                        <li class="chip !bg-paper">{{ $tag }}</li>
                    @endforeach
                </ul>
            </article>

            <article class="relative overflow-hidden rounded-3xl bg-ink p-6 text-white shadow-lift sm:p-8" data-reveal style="--reveal-delay: 100ms">
                <div class="absolute -right-24 -bottom-24 size-72 rounded-full bg-brand-600/40 blur-3xl"></div>
                <div class="relative">
                    <span class="grid size-12 place-items-center rounded-2xl bg-brand-600"><x-icon name="send" /></span>
                    <h2 class="mt-6 font-display text-3xl font-bold">Send a parcel</h2>
                    <p class="mt-4 text-white/75">Do you need to ship a package? Are your items small, fragile or oversized? At Mail Boxes Etc., each and every shipment is handled with the utmost of care. With us, all your shipments are in good hands.</p>
                    <ul class="mt-6 grid gap-3 text-sm sm:grid-cols-2">
                        @foreach (['Pick-up service', 'On-going assistance', 'Tracking', 'Proof of delivery', 'Prepaid return labels', 'Passport & visa documents'] as $feature)
                            <li class="flex gap-2.5"><x-icon name="check" class="size-[18px] shrink-0 text-brand-400" /> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-6 border-t border-white/10 pt-6 text-sm text-white/60">We are an Authorised Shipping Centre for UPS&reg;, FedEx&reg; and DHL&reg;, and we offer postal services.</p>
                </div>
            </article>
        </div>
    </section>

    {{-- Process --}}
    <section class="section bg-white">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">How it works</p>
                <h2 class="h-section mt-4">Bring it in. We do the rest.</h2>
            </div>
            <ol class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['store', 'Visit a store', 'Bring your item to our store in Camana Bay or Harbour Walk (Grand Harbour).'],
                    ['package-check', 'We pack it properly', 'Our team chooses the right materials and packs your item ready for safe delivery.'],
                    ['globe', 'Pick a courier and track', 'Compare your options at the counter, then follow your shipment all the way to proof of delivery.'],
                ] as $i => [$icon, $title, $text])
                    <li class="card bg-paper text-center" data-reveal style="--reveal-delay: {{ $i * 90 }}ms">
                        <span class="icon-tile mx-auto !size-14 !bg-white"><x-icon :name="$icon" class="size-6" /></span>
                        <h3 class="mt-5 font-display text-xl font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
            <p class="mt-10 text-center text-sm text-muted">Sending something restricted? Check the list of <a href="{{ route('legal', 'dangerous-and-prohibited-goods') }}" class="link">dangerous &amp; prohibited goods</a> first.</p>
        </div>
    </section>

    <x-help-band topic="Pack & Ship" title="Need a price before you come in?" text="Tell us what you are sending, roughly how big it is and where it is going, and we will come back with your courier options." />
</x-layout>
