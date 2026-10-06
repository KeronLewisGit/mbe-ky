<x-layout title="Graphic Design" description="In-house graphic design at Mail Boxes Etc. Cayman Islands. From design and layout all the way through printing.">
    <x-page-hero eyebrow="Graphic design" title="Designed and printed under one roof."
        lead="Our in-house design specialist will work with you from the design and layout stages all the way through printing. You’ll definitely get more attention with a smart, professional product."
        :crumb="['Printing', route('printing')]">
        <a href="#form-contact" class="btn btn-primary">Start a design project</a>
        <a href="{{ route('printing') }}#form-print-quote" class="btn btn-onDark">Request a print quote</a>
    </x-page-hero>

    <section class="section">
        <div class="wrap grid items-center gap-12 lg:grid-cols-2">
            <div data-reveal>
                <p class="eyebrow">How we can help</p>
                <h2 class="h-section mt-4">Do you have high quality brochures to print? Holding an event?</h2>
                <div class="prose-mbe mt-5">
                    <p>Perhaps you are holding an event and could use an insightful eye to have your entry tickets or flyers designed for event promotion.</p>
                    <p>Contact us today to find the solution that best matches your needs: graphic services, offset or digital printing for low or high print runs, and professional finishing.</p>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2" data-reveal style="--reveal-delay: 120ms">
                @foreach ([
                    ['pen-tool', 'Graphic services', 'Design and layout by our in-house specialist.'],
                    ['ticket', 'Event materials', 'Entry tickets, flyers and invitations.'],
                    ['printer', 'Offset or digital printing', 'For low or high print runs.'],
                    ['layers', 'Professional finishing', 'Binding, folding and laminating.'],
                ] as [$icon, $title, $text])
                    <div class="card !p-6">
                        <span class="icon-tile"><x-icon :name="$icon" /></span>
                        <h3 class="mt-4 font-display text-lg font-bold">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm text-muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">The process</p>
                <h2 class="h-section mt-4">From idea to finished print.</h2>
            </div>
            <ol class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['Brief', 'Tell us what you need and share any logos, text or examples you like.'],
                    ['Design & proof', 'We lay out your piece and send a proof for your approval.'],
                    ['Print & finish', 'Once approved, we print and finish in store, ready for collection.'],
                ] as $i => [$title, $text])
                    <li class="card bg-paper" data-reveal style="--reveal-delay: {{ $i * 90 }}ms">
                        <span class="font-display text-5xl font-extrabold text-brand-600/15">0{{ $i + 1 }}</span>
                        <h3 class="mt-2 font-display text-xl font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <x-help-band topic="Graphic design" title="Tell us about your project." text="Share what you are promoting, the sizes and quantities you have in mind and your deadline." />
</x-layout>
