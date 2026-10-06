@props(['topic' => null, 'title' => 'Questions? Talk to a real person.', 'text' => 'Send us a note and the right team member will get back to you, or drop into either store.'])
<section class="section border-t border-line bg-white" id="contact">
    <div class="wrap grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5" data-reveal>
            <p class="eyebrow">Contact us</p>
            <h2 class="h-section mt-4">{{ $title }}</h2>
            <p class="lead mt-4">{{ $text }}</p>

            <dl class="mt-8 space-y-4 text-sm">
                <div class="flex items-center gap-4">
                    <span class="icon-tile"><x-icon name="phone" /></span>
                    <div>
                        <dt class="text-muted">Call both stores</dt>
                        <dd><a class="text-base font-semibold" href="tel:{{ config('mbe.phone_href') }}">{{ config('mbe.phone') }}</a></dd>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="icon-tile"><x-icon name="map-pin" /></span>
                    <div>
                        <dt class="text-muted">Visit us</dt>
                        <dd class="font-semibold">Camana Bay &middot; Harbour Walk</dd>
                    </div>
                </div>
            </dl>
        </div>
        <div class="lg:col-span-7" data-reveal style="--reveal-delay: 120ms">
            <div class="card bg-paper">
                <x-contact-form :topic="$topic" compact />
            </div>
        </div>
    </div>
</section>
