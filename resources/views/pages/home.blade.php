@php
    $mbe = config('mbe');
    $plans = $mbe['mailbox']['plans'];
@endphp
<x-layout>
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="grid-paper absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_85%)]"></div>
        <div class="absolute -top-48 -right-40 size-[38rem] rounded-full bg-brand-200/50 blur-3xl"></div>

        <div class="wrap relative grid items-center gap-12 pt-12 pb-16 sm:pt-20 sm:pb-24 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <p class="chip">
                    <span class="size-1.5 rounded-full bg-brand-600"></span>
                    Camana Bay &middot; Harbour Walk &middot; Serving Cayman for over a decade
                </p>
                <h1 class="mt-6 font-display text-5xl leading-[0.98] font-extrabold tracking-tight sm:text-6xl lg:text-7xl">
                    Shop the world.<br>
                    <span class="text-brand-600">We&rsquo;ll bring it home.</span>
                </h1>
                <p class="lead mt-6 max-w-xl">
                    A U.S. address for online shopping, mailboxes with a real street address, worldwide courier and professional printing. Whatever you need to send, receive or print, the team at Mail Boxes Etc. handles it for you.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('ebox') }}" class="btn btn-primary !px-6 !py-3.5 !text-base">Get a U.S. address <x-icon name="arrow-right" class="size-4" /></a>
                    <a href="{{ route('mailboxes') }}" class="btn btn-light !px-6 !py-3.5 !text-base">Find a mailbox</a>
                </div>
                <ul class="mt-10 grid max-w-xl gap-x-6 gap-y-3 text-sm sm:grid-cols-3">
                    @foreach ([
                        ['plane', $mbe['ebox']['transit'], 'by air from Miami'],
                        ['badge-percent', '0% Florida sales tax', 'on Amazon & eBay orders'],
                        ['shield-check', 'Customs cleared', 'for you, by us'],
                    ] as [$icon, $strong, $text])
                        <li class="flex items-start gap-2.5">
                            <x-icon :name="$icon" class="mt-0.5 size-[18px] shrink-0 text-brand-600" />
                            <span><strong class="block font-semibold">{{ $strong }}</strong><span class="text-muted">{{ $text }}</span></span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Live estimator --}}
            <div class="relative lg:col-span-5" data-reveal style="--reveal-delay: 200ms">
                <div class="absolute -bottom-7 -left-6 z-10 hidden rotate-[-5deg] rounded-2xl bg-ink px-4 py-2.5 text-white shadow-lift sm:block animate-float">
                    <p class="text-[10px] font-semibold tracking-widest text-white/50 uppercase">Miami &rarr; Grand Cayman</p>
                    <p class="font-display text-lg font-bold">{{ $mbe['ebox']['transit'] }}</p>
                </div>
                <div class="card relative !p-0 shadow-lift" x-data="eboxCalculator(@js($mbe['ebox']))">
                    <div class="airmail h-1.5 rounded-t-3xl"></div>
                    <div class="p-6 sm:p-7">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold tracking-[0.16em] text-muted uppercase">E-box estimator</p>
                                <h2 class="mt-1 font-display text-2xl font-bold">What will shipping cost?</h2>
                            </div>
                            <span class="icon-tile"><x-icon name="calculator" /></span>
                        </div>

                        <div class="mt-6 grid grid-cols-4 gap-3">
                            <div class="col-span-4">
                                <label class="label" for="hero-weight">Package weight (lbs)</label>
                                <input id="hero-weight" type="number" min="0" step="0.1" inputmode="decimal" class="input" placeholder="e.g. 4" x-model="weight">
                            </div>
                            <div class="col-span-4 -mb-1 flex items-center justify-between">
                                <span class="label !mb-0">Box size (inches) <span class="font-normal text-muted">optional</span></span>
                            </div>
                            <input type="number" min="0" inputmode="decimal" class="input col-span-1" placeholder="L" aria-label="Length in inches" x-model="length">
                            <input type="number" min="0" inputmode="decimal" class="input col-span-1" placeholder="W" aria-label="Width in inches" x-model="width">
                            <input type="number" min="0" inputmode="decimal" class="input col-span-1" placeholder="H" aria-label="Height in inches" x-model="height">
                            <div class="col-span-1 grid place-items-center rounded-xl bg-paper text-center text-xs text-muted ring-1 ring-line">
                                <span><strong class="block text-sm text-ink" x-text="chargeable || '–'"></strong>lbs</span>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            @foreach ($mbe['ebox']['plans'] as $key => $plan)
                                <div class="rounded-2xl p-4 {{ $key === 'pro' ? 'bg-ink text-white' : 'bg-paper ring-1 ring-line' }}">
                                    <p class="text-xs font-semibold {{ $key === 'pro' ? 'text-white/60' : 'text-muted' }}">{{ $plan['name'] }}</p>
                                    <p class="mt-1 font-display text-2xl font-bold tabular-nums" x-text="chargeable ? money(cost('{{ $key }}')) : 'CI$ –'">CI$ –</p>
                                    <p class="mt-1 text-[11px] {{ $key === 'pro' ? 'text-white/60' : 'text-muted' }}">{{ $plan['membership'] ? 'CI$'.$plan['membership'].'/yr membership' : 'No membership fee' }}</p>
                                </div>
                            @endforeach
                        </div>

                        <p class="mt-4 text-xs text-muted" x-show="usesDimensional" x-cloak>
                            <x-icon name="info" class="mr-0.5 inline size-3.5 -translate-y-px" /> Charged on dimensional weight (L×W×H ÷ {{ $mbe['ebox']['dim_divisor'] }}), which is greater than the actual weight.
                        </p>
                        <div class="mt-4 flex items-center justify-between gap-4 border-t border-line pt-4">
                            <p class="text-xs text-muted">Estimate only. Excludes duty and government fees.</p>
                            <a href="{{ route('ebox') }}#details" class="link-arrow shrink-0">Full rates <x-icon name="arrow-right" class="size-4" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Courier partners --}}
    <section class="border-y border-line bg-white" aria-label="Courier partners">
        <div class="wrap flex flex-col items-center gap-x-10 gap-y-4 py-6 md:flex-row">
            <p class="shrink-0 text-xs font-semibold tracking-[0.16em] text-muted uppercase">Authorised shipping centre</p>
            <div class="relative w-full overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_12%,black_88%,transparent)]">
                <div class="flex w-max animate-marquee items-center gap-14 pr-14">
                    @foreach (array_merge(...array_fill(0, 4, $mbe['couriers'])) as $courier)
                        <span class="font-display text-2xl font-extrabold tracking-tight text-ink/35">{{ $courier }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="section">
        <div class="wrap">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="eyebrow">What we do</p>
                    <h2 class="h-section mt-4">One stop for post, parcels and print.</h2>
                </div>
                <p class="max-w-sm text-muted">Whether you are a business or an individual, last minute or one-off, we&rsquo;re here to help.</p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-6">
                {{-- E-box --}}
                <a href="{{ route('ebox') }}" class="group relative overflow-hidden rounded-3xl bg-ink p-8 text-white md:col-span-4 md:row-span-2" data-reveal>
                    <div class="grid-ink absolute inset-0 opacity-70"></div>
                    <div class="absolute -right-24 -bottom-24 size-80 rounded-full bg-brand-600/50 blur-3xl transition duration-500 group-hover:bg-brand-500/60"></div>
                    <div class="relative flex h-full flex-col">
                        <div class="flex items-center justify-between">
                            <span class="grid size-12 place-items-center rounded-2xl bg-brand-600"><x-icon name="plane" /></span>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/20">Most popular</span>
                        </div>
                        <h3 class="mt-8 font-display text-3xl font-bold sm:text-4xl">E-box: your own U.S. address</h3>
                        <p class="mt-3 max-w-lg text-white/70">Shop thousands of U.S. online stores and have your orders delivered to your nearest MBE store. Track every package from the Miami warehouse to your hands.</p>

                        {{-- Route --}}
                        <div class="mt-10 flex items-center gap-3 text-xs font-semibold text-white/70">
                            <span class="rounded-full bg-white/10 px-3 py-1.5 ring-1 ring-white/15">Your order</span>
                            <span class="h-px flex-1 border-t border-dashed border-white/30"></span>
                            <span class="rounded-full bg-white/10 px-3 py-1.5 ring-1 ring-white/15">Miami</span>
                            <span class="relative h-px flex-1 border-t border-dashed border-white/30">
                                <x-icon name="plane" class="absolute -top-2.5 left-1/2 size-5 -translate-x-1/2 text-brand-400" />
                            </span>
                            <span class="rounded-full bg-brand-600 px-3 py-1.5 text-white">Cayman</span>
                        </div>

                        <div class="mt-auto flex flex-wrap items-end justify-between gap-4 pt-10">
                            <dl class="flex gap-8">
                                <div><dt class="text-xs text-white/50">First pound from</dt><dd class="font-display text-2xl font-bold">CI${{ number_format($mbe['ebox']['plans']['pro']['first_lb'], 2) }}</dd></div>
                                <div><dt class="text-xs text-white/50">Then from</dt><dd class="font-display text-2xl font-bold">CI${{ number_format($mbe['ebox']['plans']['pro']['per_lb'], 2) }}<span class="text-sm font-medium text-white/50">/lb</span></dd></div>
                            </dl>
                            <span class="btn bg-white text-ink transition group-hover:bg-brand-600 group-hover:text-white">Explore E-box <x-icon name="arrow-right" class="size-4" /></span>
                        </div>
                    </div>
                </a>

                {{-- Mailboxes --}}
                <a href="{{ route('mailboxes') }}" class="card group flex flex-col transition hover:-translate-y-1 hover:shadow-lift md:col-span-2" data-reveal style="--reveal-delay: 80ms">
                    <span class="icon-tile"><x-icon name="mailbox" /></span>
                    <h3 class="mt-5 font-display text-xl font-bold">Mailboxes</h3>
                    <p class="mt-2 text-sm text-muted">A Cayman street address that accepts courier deliveries. Choose a virtual mailbox or one with a key.</p>
                    <p class="mt-auto pt-5 text-sm"><span class="text-muted">From</span> <strong class="font-display text-lg">CI${{ $mbe['mailbox']['virtual_month'] }}</strong><span class="text-muted">/month</span></p>
                </a>

                {{-- Ocean --}}
                <a href="{{ route('ocean') }}" class="card group flex flex-col transition hover:-translate-y-1 hover:shadow-lift md:col-span-2" data-reveal style="--reveal-delay: 140ms">
                    <span class="icon-tile"><x-icon name="ship" /></span>
                    <h3 class="mt-5 font-display text-xl font-bold">Ocean Ship</h3>
                    <p class="mt-2 text-sm text-muted">Bi-weekly sailings from Miami for furniture, appliances and other large cargo.</p>
                    <p class="mt-auto pt-5 text-sm">
                        @if ($nextSailing)
                            <span class="text-muted">Next sailing</span> <strong class="font-display text-lg">{{ $nextSailing->sailing_date->format('D j M') }}</strong>
                        @else
                            <span class="text-muted">Up to 12 c.f. for</span> <strong class="font-display text-lg">CI${{ $mbe['ocean']['flat_rate'] }}</strong>
                        @endif
                    </p>
                </a>

                {{-- Pack & Ship --}}
                <a href="{{ route('pack-ship') }}" class="group relative overflow-hidden rounded-3xl md:col-span-2" data-reveal>
                    <img src="{{ asset('images/pack-ship.jpg') }}" alt="An MBE team member taping up a parcel" class="absolute inset-0 size-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/50 to-transparent"></div>
                    <div class="relative flex h-full min-h-72 flex-col justify-end p-6 text-white">
                        <h3 class="font-display text-xl font-bold">Pack &amp; Ship</h3>
                        <p class="mt-1.5 text-sm text-white/75">Expert packing and worldwide courier with UPS, FedEx, DHL and EMS.</p>
                    </div>
                </a>

                {{-- Printing --}}
                <a href="{{ route('printing') }}" class="card group flex flex-col transition hover:-translate-y-1 hover:shadow-lift md:col-span-2" data-reveal style="--reveal-delay: 80ms">
                    <span class="icon-tile"><x-icon name="printer" /></span>
                    <h3 class="mt-5 font-display text-xl font-bold">Printing &amp; design</h3>
                    <p class="mt-2 text-sm text-muted">Business cards, brochures, booklets, binding and laminating, with an in-house designer.</p>
                    <p class="mt-auto pt-5 text-sm"><span class="text-muted">Colour from</span> <strong class="font-display text-lg">CI${{ number_format($mbe['print_from'], 2) }}</strong><span class="text-muted">/page</span></p>
                </a>

                {{-- Photos + more --}}
                <a href="{{ route('services') }}" class="card group flex flex-col !bg-brand-600 !ring-brand-600 text-white transition hover:-translate-y-1 hover:shadow-lift md:col-span-2" data-reveal style="--reveal-delay: 140ms">
                    <span class="grid size-12 place-items-center rounded-2xl bg-white/15"><x-icon name="camera" /></span>
                    <h3 class="mt-5 font-display text-xl font-bold">Passport photos &amp; more</h3>
                    <p class="mt-2 text-sm text-white/80">Passport and visa photos, Print from Phone, secure shredding and photo booth rental.</p>
                    <p class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-semibold">See all services <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" /></p>
                </a>
            </div>
        </div>
    </section>

    {{-- Who it's for --}}
    <section class="section bg-white" x-data="{ tab: 'new' }">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">Made for you</p>
                <h2 class="h-section mt-4">Whatever your needs, we&rsquo;ll personalise our service.</h2>
            </div>

            <div class="mx-auto mt-10 flex w-fit max-w-full gap-1 overflow-x-auto rounded-full bg-paper p-1.5 ring-1 ring-line" role="tablist" data-reveal>
                @foreach (['new' => 'New to Cayman', 'shopper' => 'Online shoppers', 'business' => 'For business'] as $key => $label)
                    <button type="button" role="tab" @click="tab = '{{ $key }}'" :aria-selected="tab === '{{ $key }}'"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold whitespace-nowrap transition"
                        :class="tab === '{{ $key }}' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink'">{{ $label }}</button>
                @endforeach
            </div>

            @php
                $audiences = [
                    'new' => [
                        'title' => 'Just landed? Here are the essentials.',
                        'text' => 'Moving somewhere new comes with a long to-do list. Get your address, your online shopping and your paperwork sorted in one visit.',
                        'image' => 'work-from-home.jpg',
                        'alt' => 'A new resident setting up her mail online',
                        'items' => [
                            ['mailbox', 'A Cayman mailing address', 'A street address that accepts post and FedEx, DHL and UPS deliveries.', 'mailboxes'],
                            ['plane', 'A U.S. address for shopping', 'Keep ordering from your favourite U.S. stores with E-box.', 'ebox'],
                            ['camera', 'Passport & visa photos', 'Taken in store while you wait, including newborn passport photos.', 'services'],
                            ['ship', 'Ship your big items by sea', 'Furniture and appliances on bi-weekly sailings from Miami.', 'ocean'],
                        ],
                    ],
                    'shopper' => [
                        'title' => 'Add to cart. We handle the rest.',
                        'text' => 'Use your E-box address at checkout. We receive your order in Miami, fly it to Cayman, clear Customs and let you know when it is ready.',
                        'image' => 'virtual-mailbox.jpg',
                        'alt' => 'A phone showing a new mail notification',
                        'items' => [
                            ['badge-percent', '0% Florida sales tax', 'Amazon and eBay orders sent to your E-box address are not subject to Florida sales tax.', 'ebox'],
                            ['shield-check', 'We clear Customs for you', 'Upload your invoice in the app and skip the queue.', 'ebox'],
                            ['gift', 'Earn MBE Points', 'Points on every shipment, redeemable for vouchers.', 'ebox'],
                            ['truck', 'Pick up or home delivery', 'Pay in the app and use the Express Pick-Up line.', 'ebox'],
                        ],
                    ],
                    'business' => [
                        'title' => 'Let MBE be your solutions partner.',
                        'text' => 'Services tailored for small businesses: a professional address, mail you can manage remotely, print that makes an impression and courier you can count on.',
                        'image' => 'print-centre.jpg',
                        'alt' => 'The MBE print centre',
                        'items' => [
                            ['building-2', 'A business street address', 'Small Business and Corporate mailboxes for up to four recipients.', 'physical'],
                            ['smartphone', 'Mail you can read anywhere', 'Scan, forward or shred from the Virtual Mailbox app.', 'virtual'],
                            ['printer', 'Print & finishing', 'Stationery, presentations, binding and laminating with a fast turnaround.', 'printing'],
                            ['package', 'Courier & returns', 'Compare UPS, FedEx, DHL and EMS at one counter.', 'pack-ship'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($audiences as $key => $audience)
                <div x-show="tab === '{{ $key }}'" @if (! $loop->first) x-cloak @endif x-transition.opacity.duration.300ms class="mt-10 grid items-stretch gap-6 lg:grid-cols-12" role="tabpanel">
                    <div class="relative overflow-hidden rounded-3xl bg-ink lg:col-span-5">
                        <img src="{{ asset('images/'.$audience['image']) }}" alt="{{ $audience['alt'] }}" class="absolute inset-0 size-full object-cover opacity-60" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-ink/10"></div>
                        <div class="relative flex h-full min-h-80 flex-col justify-end p-8 text-white">
                            <h3 class="font-display text-3xl font-bold">{{ $audience['title'] }}</h3>
                            <p class="mt-3 text-white/75">{{ $audience['text'] }}</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:col-span-7">
                        @foreach ($audience['items'] as [$icon, $title, $text, $route])
                            <a href="{{ route($route) }}" class="group flex flex-col rounded-3xl bg-paper p-6 ring-1 ring-line/70 transition hover:bg-white hover:shadow-card">
                                <span class="grid size-11 place-items-center rounded-xl bg-white text-brand-700 ring-1 ring-line transition group-hover:bg-brand-600 group-hover:text-white group-hover:ring-brand-600"><x-icon :name="$icon" /></span>
                                <h4 class="mt-4 font-display text-lg font-bold">{{ $title }}</h4>
                                <p class="mt-1.5 text-sm text-muted">{{ $text }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How E-box works + stats --}}
    <section class="section">
        <div class="wrap">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-4" data-reveal>
                    <p class="eyebrow">How E-box works</p>
                    <h2 class="h-section mt-4">From checkout to Cayman in four steps.</h2>
                    <p class="lead mt-4">Packages typically take {{ $mbe['ebox']['transit'] }} from the day they leave Miami.</p>
                    <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn btn-dark mt-8">Sign up for E-box <x-icon name="arrow-up-right" class="size-4" /></a>
                </div>
                <ol class="grid gap-4 sm:grid-cols-2 lg:col-span-8">
                    @foreach ([
                        ['Register', 'Create your free E-box account and get your U.S. address and CBY number straight away.'],
                        ['Shop', 'Order online and ship to your E-box address. Include your CBY# anywhere in the address.'],
                        ['Track', 'When your package reaches our Miami facility it appears in your account. Upload your invoice for Customs.'],
                        ['Collect', 'We notify you when it is ready. Pick up at your MBE store or have it delivered to your door.'],
                    ] as $i => [$title, $text])
                        <li class="card relative" data-reveal style="--reveal-delay: {{ $i * 70 }}ms">
                            <span class="font-display text-5xl font-extrabold text-brand-600/15">0{{ $i + 1 }}</span>
                            <h3 class="mt-2 font-display text-xl font-bold">{{ $title }}</h3>
                            <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>

            <dl class="mt-16 grid grid-cols-2 gap-px overflow-hidden rounded-3xl bg-line ring-1 ring-line lg:grid-cols-4" data-reveal>
                @foreach ([
                    [1550, '+', 'MBE stores worldwide'],
                    [2, '', 'stores in Grand Cayman'],
                    [10, '+', 'years serving Cayman'],
                    [24, 'hr', 'mailbox access at Camana Bay'],
                ] as [$number, $suffix, $label])
                    <div class="bg-white p-6 sm:p-8">
                        <dd class="font-display text-4xl font-extrabold tracking-tight sm:text-5xl"><span x-data="countUp({{ $number }})" x-text="value.toLocaleString()">{{ number_format($number) }}</span><span class="text-brand-600">{{ $suffix }}</span></dd>
                        <dt class="mt-2 text-sm text-muted">{{ $label }}</dt>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Mailbox comparison --}}
    <section class="section bg-white">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">Mailbox services</p>
                <h2 class="h-section mt-4">Virtual or physical? Both give you a Cayman street address.</h2>
            </div>
            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                <article class="card flex flex-col gap-6 sm:flex-row" data-reveal>
                    <img src="{{ asset('images/virtual-mailbox.jpg') }}" alt="Virtual mailbox notification on a phone" class="aspect-square w-full rounded-2xl object-cover sm:w-44" loading="lazy">
                    <div class="flex flex-col">
                        <p class="chip w-fit !bg-brand-50 !text-brand-700 !ring-brand-100">Powered by Anytime Mailbox</p>
                        <h3 class="mt-3 font-display text-2xl font-bold">Virtual Mailbox</h3>
                        <p class="mt-2 text-sm text-muted">View images of your mail on any device, then open &amp; scan, forward, shred or hold for pick-up with a few clicks.</p>
                        <p class="mt-4 text-sm"><span class="text-muted">From</span> <strong class="font-display text-xl">CI${{ $mbe['mailbox']['virtual_month'] }}</strong><span class="text-muted">/month or CI${{ $mbe['mailbox']['virtual_year'] }}/year</span></p>
                        <a href="{{ route('virtual') }}" class="link-arrow mt-auto pt-4">Virtual Mailbox <x-icon name="arrow-right" class="size-4" /></a>
                    </div>
                </article>
                <article class="card flex flex-col gap-6 sm:flex-row" data-reveal style="--reveal-delay: 100ms">
                    <img src="{{ asset('images/physical-mailbox.jpg') }}" alt="Brass MBE mailboxes, one open with mail inside" class="aspect-square w-full rounded-2xl object-cover sm:w-44" loading="lazy">
                    <div class="flex flex-col">
                        <p class="chip w-fit">24-hour access at Camana Bay</p>
                        <h3 class="mt-3 font-display text-2xl font-bold">Physical Mailbox</h3>
                        <p class="mt-2 text-sm text-muted">Your own mailbox with a key, email notifications for packages and three sizes: Personal, Small Business and Corporate.</p>
                        <p class="mt-4 text-sm"><span class="text-muted">From</span> <strong class="font-display text-xl">CI${{ $plans['personal']['price'] }}</strong><span class="text-muted">/year plus refundable deposit</span></p>
                        <a href="{{ route('physical') }}" class="link-arrow mt-auto pt-4">Physical Mailbox <x-icon name="arrow-right" class="size-4" /></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Ocean band --}}
    <section class="relative overflow-hidden bg-ink text-white">
        <div class="grid-ink absolute inset-0 opacity-60"></div>
        <div class="absolute -bottom-40 -left-32 size-[30rem] rounded-full bg-brand-600/30 blur-3xl"></div>
        <div class="wrap section relative grid items-center gap-12 lg:grid-cols-2">
            <div data-reveal>
                <p class="eyebrow !text-brand-300">Ocean Ship</p>
                <h2 class="h-section mt-4">Big purchase? Send it by sea.</h2>
                <p class="mt-4 max-w-lg text-lg text-white/70">Our ocean freight service is a cost-effective choice for large and over-sized cargo, with free 30 day consolidation, insurance and Customs clearance.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('ocean') }}#calculator" class="btn btn-primary">Estimate ocean freight</a>
                    <a href="{{ route('ocean') }}#sailings" class="btn btn-onDark">All sailing dates</a>
                </div>
            </div>
            <div class="rounded-3xl bg-white/5 p-6 ring-1 ring-white/10 backdrop-blur sm:p-8" data-reveal style="--reveal-delay: 120ms">
                @if ($nextSailing)
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold tracking-[0.16em] text-white/50 uppercase">{{ $nextSailing->hasSailed() ? 'Latest sailing' : 'Next sailing from Miami' }}</p>
                        <x-icon name="ship" class="size-5 text-brand-400" />
                    </div>
                    <p class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">{{ $nextSailing->sailing_date->format('D j M') }}</p>
                    <dl class="mt-8 grid grid-cols-3 gap-4 border-t border-white/10 pt-6 text-sm">
                        <div><dt class="text-white/50">Instruct to ship by</dt><dd class="mt-1 font-semibold">{{ $nextSailing->cutoff_date->format('D j M') }}</dd></div>
                        <div><dt class="text-white/50">Sails</dt><dd class="mt-1 font-semibold">{{ $nextSailing->sailing_date->format('D j M') }}</dd></div>
                        <div><dt class="text-white/50">In hand</dt><dd class="mt-1 font-semibold">{{ $nextSailing->in_hand_date->format('D j M') }}</dd></div>
                    </dl>
                @else
                    <p class="font-display text-2xl font-bold">Sailing dates coming soon</p>
                    <p class="mt-2 text-white/60">Future sailing dates will be posted as they become available.</p>
                @endif
                <p class="mt-6 text-sm text-white/60">Up to {{ $mbe['ocean']['flat_cf'] }} cubic feet for a flat <strong class="text-white">CI${{ $mbe['ocean']['flat_rate'] }}</strong>, including freight, import documentation and Customs clearance service.</p>
            </div>
        </div>
    </section>

    {{-- News --}}
    <section class="section">
        <div class="wrap">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div>
                    <p class="eyebrow">News &amp; offers</p>
                    <h2 class="h-section mt-4">The latest from MBE Cayman.</h2>
                </div>
                <a href="{{ route('blog') }}" class="link-arrow">All news <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Locations --}}
    <section class="section bg-white" id="locations">
        <div class="wrap">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">Visit us</p>
                <h2 class="h-section mt-4">Two stores, one friendly team.</h2>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @foreach ($mbe['locations'] as $location)
                    <x-location-card :location="$location" data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms" />
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band />
</x-layout>
