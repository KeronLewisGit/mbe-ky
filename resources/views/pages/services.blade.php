<x-layout title="Additional Services" description="Passport and visa photos, Print from Phone, secure document shredding and photo booth rental at Mail Boxes Etc. Cayman Islands.">
    <x-page-hero eyebrow="Other services" title="The little things, handled too."
        lead="Passport photos, photo prints from your phone, secure shredding and everything you need for a photo booth. All at Camana Bay and Harbour Walk.">
        @foreach (['passport-photos' => 'Passport photos', 'print-from-phone' => 'Print from Phone', 'shredding' => 'Shredding', 'photo-booth' => 'Photo Booth Rental'] as $anchor => $label)
            <a href="#{{ $anchor }}" class="btn btn-onDark">{{ $label }}</a>
        @endforeach
    </x-page-hero>

    {{-- Passport photos --}}
    <section class="section scroll-mt-20" id="passport-photos">
        <div class="wrap">
            <div class="grid items-center gap-10 rounded-[2rem] bg-white p-6 shadow-card ring-1 ring-line/70 sm:p-12 lg:grid-cols-12" data-reveal>
                <div class="lg:col-span-7">
                    <p class="eyebrow">Passport &amp; visa photos</p>
                    <h2 class="h-section mt-4">Photos done right, the first time.</h2>
                    <p class="lead mt-4">Travelling, renewing or applying? Have your passport and visa photos taken in store. We take newborn passport photos too.</p>
                    <ul class="mt-6 grid gap-3 text-sm sm:grid-cols-2">
                        @foreach (['Passport photos', 'Visa photos', 'Newborn passport photos', 'Pay with MBE Points vouchers'] as $feature)
                            <li class="flex gap-2.5"><x-icon name="check" class="size-[18px] shrink-0 text-brand-600" /> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('contact') }}#locations" class="btn btn-dark mt-8">Find a store <x-icon name="arrow-right" class="size-4" /></a>
                </div>
                <div class="lg:col-span-5">
                    <div class="mx-auto flex max-w-xs justify-center gap-4">
                        @foreach (['rotate-[-6deg]', 'rotate-[4deg] translate-y-6'] as $tilt)
                            <div class="{{ $tilt }} rounded-xl bg-white p-3 pb-8 shadow-lift ring-1 ring-line">
                                <div class="grid aspect-[7/9] w-28 place-items-center rounded-md bg-paper-deep">
                                    <x-icon name="user-round" class="size-14 text-ink/25" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Print from phone --}}
    <section class="section scroll-mt-20 bg-white" id="print-from-phone">
        <div class="wrap grid items-center gap-12 lg:grid-cols-2">
            <div data-reveal>
                <p class="eyebrow">Print from Phone</p>
                <h2 class="h-section mt-4">Bring your camera roll to life.</h2>
                <div class="prose-mbe mt-5">
                    <p>MBE Print from Phone service lets you turn your favourite pictures from your phone into beautiful, printed photos in a matter of minutes. No more scrolling through your camera roll and never printing those precious memories.</p>
                    <p>All you need to do is bring your phone with the photos you want to print to one of our stores. Our friendly staff will assist you in selecting the perfect shots.</p>
                    <p>Our high-quality printers ensure that your photos are crisp, vibrant, and true to life, so you can cherish them for years to come.</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4" data-reveal style="--reveal-delay: 120ms">
                @foreach ([
                    ['photo-prints.jpg', 'Decorate your home with personalised photo prints'],
                    ['photo-gift.jpg', 'Create a unique gift for a loved one'],
                    ['photo-keepsake.jpg', 'Preserve your memories in a tangible form'],
                    ['print-from-phone.jpg', null],
                ] as [$image, $caption])
                    <figure class="group relative overflow-hidden rounded-2xl {{ $loop->even ? 'translate-y-6' : '' }}">
                        <img src="{{ asset('images/'.$image) }}" alt="{{ $caption ?? 'Print and cherish precious memories' }}" class="aspect-square w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        @if ($caption)
                            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/90 to-transparent p-4 pt-10 text-sm font-semibold text-white">{{ $caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Shredding --}}
    <section class="section relative scroll-mt-20 overflow-hidden bg-ink text-white" id="shredding">
        <div class="grid-ink absolute inset-0 opacity-60"></div>
        <div class="wrap relative grid items-center gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7" data-reveal>
                <p class="eyebrow !text-brand-300">Shredding</p>
                <h2 class="mt-4 font-display text-5xl font-extrabold tracking-tight sm:text-6xl">Don&rsquo;t trash, <span class="text-brand-500">shred.</span></h2>
                <p class="mt-5 max-w-xl text-lg text-white/70">With identity theft on the rise, it&rsquo;s more important than ever to properly dispose of personal and sensitive documents. MBE Shredding offers a convenient solution for individuals and businesses.</p>
                <ul class="mt-8 flex flex-wrap gap-2">
                    @foreach (['Old tax receipts', 'Bank statements', 'Medical records', 'Business archives'] as $tag)
                        <li class="rounded-full bg-white/10 px-3.5 py-1.5 text-sm font-medium ring-1 ring-white/15">{{ $tag }}</li>
                    @endforeach
                </ul>
                <a href="#form-contact" class="btn btn-primary mt-8">Ask about shredding <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <div class="space-y-4 lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                @foreach ([
                    ['folder-input', 'Bring them in', 'Bring in your sensitive documents and trust us to shred them securely and efficiently.'],
                    ['shredder', 'We shred', 'Your information remains confidential from the counter to the shredder.'],
                    ['file-badge', 'Proof of destruction', 'We will even supply a destruction document after the shredding is complete.'],
                ] as [$icon, $title, $text])
                    <div class="flex gap-4 rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-600"><x-icon :name="$icon" /></span>
                        <div><h3 class="font-display text-lg font-bold">{{ $title }}</h3><p class="mt-1 text-sm text-white/65">{{ $text }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Photo booth --}}
    <section class="section scroll-mt-20" id="photo-booth">
        <div class="wrap grid items-center gap-12 lg:grid-cols-2">
            <div class="relative order-last lg:order-first" data-reveal>
                <img src="{{ asset('images/photo-booth.jpg') }}" alt="Two friends pulling faces in a photo booth" class="aspect-square w-full rounded-3xl object-cover shadow-lift" loading="lazy">
                <span class="absolute top-5 left-5 rounded-full bg-brand-600 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-white uppercase">New service</span>
            </div>
            <div data-reveal style="--reveal-delay: 120ms">
                <p class="eyebrow">Photo Booth Rental</p>
                <h2 class="h-section mt-4">Capture the moment at your next event.</h2>
                <p class="lead mt-4">Professional booths can be so expensive, and trying to DIY it can leave you stuck with a bunch of items you won&rsquo;t need again. Rent from MBE and we&rsquo;ll provide everything you&rsquo;ll need.</p>
                <ul class="mt-8 space-y-4">
                    @foreach ([
                        ['sun', 'Ring light', 'For amazing lighting and capturing your good side.'],
                        ['camera', 'Tripod', 'To steady your camera.'],
                        ['party-popper', 'Props', 'For creative fun.'],
                        ['images', 'Prints', 'Prints of photos after the party has ended.'],
                        ['smartphone', 'App cheat sheet', 'A cheat sheet for photo booth apps, or we can help set you up with a subscription.'],
                    ] as [$icon, $title, $text])
                        <li class="flex gap-4">
                            <span class="icon-tile !size-10 !rounded-xl"><x-icon :name="$icon" class="size-[18px]" /></span>
                            <p class="pt-0.5 text-sm"><strong class="font-semibold">{{ $title }}.</strong> <span class="text-muted">{{ $text }}</span></p>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-6 text-sm text-muted">Birthday party, dinner gathering, baptism or any special occasion. We will customise your package according to your needs, and digital copies can be shared by email or social media.</p>
            </div>
        </div>
    </section>

    <x-help-band topic="Photo booth rental" title="Tell us about your event or project." text="Ask about photo booth availability, shredding in bulk or anything else. We will come back with the details." />
</x-layout>
