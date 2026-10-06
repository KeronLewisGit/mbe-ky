@php
    $mbe = config('mbe');
    $ebox = $mbe['ebox'];
    $tab = in_array(request('tab'), ['overview', 'pricing', 'faq', 'points']) ? request('tab') : 'overview';
@endphp
<x-layout title="E-box: your U.S. address" description="E-box gives you a U.S. address for online shopping. Orders arrive at your MBE store in Grand Cayman in 5–7 business days, Customs cleared.">
    <x-page-hero eyebrow="E-box" title="Need to order something online?"
        lead="E-box provides you with a U.S. address so you can shop online and have your orders delivered directly to your nearest MBE store."
        image="zero-sales-tax.jpg" image-alt="E-box customers pay 0% sales tax on Amazon and eBay purchases">
        <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn btn-primary">Sign up for E-box <x-icon name="arrow-up-right" class="size-4" /></a>
        <a href="{{ $mbe['links']['ebox_login'] }}" target="_blank" rel="noopener" class="btn btn-onDark">Already a customer? Log in</a>
    </x-page-hero>

    {{-- Key facts --}}
    <section class="border-b border-line bg-white">
        <div class="wrap">
        <dl class="grid grid-cols-2 gap-px bg-line lg:grid-cols-4">
            @foreach ([
                ['plane', $ebox['transit'], 'from Miami to your MBE store'],
                ['badge-percent', '0% Florida sales tax', 'on Amazon and eBay orders'],
                ['shield-check', 'We clear Customs', 'on your behalf'],
                ['gift', 'MBE Points', 'on every shipment'],
            ] as [$icon, $title, $text])
                <div class="flex items-center gap-4 bg-white px-2 py-6 sm:px-6">
                    <x-icon :name="$icon" class="size-6 shrink-0 text-brand-600" />
                    <div><dt class="font-display font-bold">{{ $title }}</dt><dd class="text-xs text-muted">{{ $text }}</dd></div>
                </div>
            @endforeach
        </dl>
        </div>
    </section>

    {{-- How it works --}}
    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">How does E-box work?</p>
                <h2 class="h-section mt-4">Five steps from cart to collection.</h2>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn btn-primary">Sign up for E-box <x-icon name="arrow-up-right" class="size-4" /></a>
                    <a href="{{ route('store-change') }}" class="btn btn-light">Change my store pick-up location</a>
                </div>
                <a href="{{ route('legal', 'user-guide') }}" class="link-arrow mt-6">Read the full user guide <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <ol class="relative space-y-4 lg:col-span-8">
                @foreach ([
                    ['user-plus', 'Register', 'Create your E-box account online.'],
                    ['map-pin', 'Get your U.S. address', 'After registering, you’ll get access to our web-based tracking software and mobile app and acquire your U.S. address.'],
                    ['file-check', 'Appoint us as your Customs agent', 'Complete the Customs Appointment of Agent so that we can clear packages on your behalf.', '#customs'],
                    ['package-search', 'Watch your packages arrive', 'When your packages arrive at our Miami facility, they will appear in your account and you can view the status updates.'],
                    ['store', 'Pick up or get it delivered', 'You will receive a notification to pick up from your MBE centre or have them delivered to your door. Secure payment via the app is available for your convenience.'],
                ] as $i => $step)
                    <li class="card flex gap-5 !p-5 sm:!p-6" data-reveal style="--reveal-delay: {{ $i * 60 }}ms">
                        <span class="icon-tile relative">
                            <x-icon :name="$step[0]" />
                            <span class="absolute -top-2 -right-2 grid size-6 place-items-center rounded-full bg-ink text-[11px] font-bold text-white">{{ $i + 1 }}</span>
                        </span>
                        <div>
                            <h3 class="font-display text-lg font-bold">{{ $step[1] }}</h3>
                            <p class="mt-1 text-sm text-muted">{{ $step[2] }} @isset($step[3])<a href="{{ $step[3] }}" class="link">See how</a>@endisset</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Calculator --}}
    <section class="section relative overflow-hidden bg-ink text-white" id="calculator">
        <div class="grid-ink absolute inset-0 opacity-60"></div>
        <div class="wrap relative grid items-start gap-12 lg:grid-cols-12" x-data="eboxCalculator(@js($ebox))">
            <div class="lg:col-span-5" data-reveal>
                <p class="eyebrow !text-brand-300">Shipping estimator</p>
                <h2 class="h-section mt-4">Know the cost before you check out.</h2>
                <p class="mt-4 text-lg text-white/70">Shipping rates are applied to the greater of actual weight and dimensional weight. Enter what you know and we will compare both plans.</p>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="col-span-3">
                        <label class="mb-1.5 block text-sm font-medium text-white/80" for="calc-weight">Actual weight (lbs)</label>
                        <input id="calc-weight" type="number" min="0" step="0.1" inputmode="decimal" class="input" placeholder="e.g. 6.5" x-model="weight">
                    </div>
                    @foreach (['length' => 'Length', 'width' => 'Width', 'height' => 'Height'] as $model => $label)
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-white/80" for="calc-{{ $model }}">{{ $label }} (in)</label>
                            <input id="calc-{{ $model }}" type="number" min="0" inputmode="decimal" class="input" x-model="{{ $model }}">
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-white/50">Dimensional weight = L × W × H ÷ {{ $ebox['dim_divisor'] }}. A 10&Prime; × 10&Prime; × 10&Prime; box has a dimensional weight of 6.02 lbs.</p>
            </div>

            <div class="lg:col-span-7" data-reveal style="--reveal-delay: 120ms">
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                        <p class="text-xs text-white/50">Actual</p>
                        <p class="mt-1 font-display text-xl font-bold tabular-nums" x-text="(Number(weight) || 0).toFixed(1) + ' lb'">0.0 lb</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                        <p class="text-xs text-white/50">Dimensional</p>
                        <p class="mt-1 font-display text-xl font-bold tabular-nums" x-text="dimensional.toFixed(1) + ' lb'">0.0 lb</p>
                    </div>
                    <div class="rounded-2xl bg-brand-600 p-4">
                        <p class="text-xs text-white/70">Chargeable</p>
                        <p class="mt-1 font-display text-xl font-bold tabular-nums" x-text="chargeable + ' lb'">0 lb</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($ebox['plans'] as $key => $plan)
                        <div class="rounded-3xl bg-white p-6 text-ink">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display text-xl font-bold">{{ $plan['name'] }}</h3>
                                @if ($key === 'pro')
                                    <span class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700">Best value</span>
                                @endif
                            </div>
                            <p class="mt-4 font-display text-4xl font-extrabold tabular-nums" x-text="chargeable ? money(cost('{{ $key }}')) : 'CI$ –'">CI$ –</p>
                            <p class="mt-1 text-xs text-muted">CI${{ number_format($plan['first_lb'], 2) }} first lb, then CI${{ number_format($plan['per_lb'], 2) }}/lb</p>
                            <p class="mt-3 border-t border-line pt-3 text-xs text-muted">{{ $plan['membership'] ? 'Plus CI$'.$plan['membership'].'/year membership' : 'No membership fee' }}</p>
                        </div>
                    @endforeach
                </div>

                <p class="mt-4 rounded-2xl bg-white/5 p-4 text-sm text-white/70 ring-1 ring-white/10" x-show="chargeable && saving > 0" x-cloak>
                    <x-icon name="sparkles" class="mr-1 inline size-4 -translate-y-px text-brand-400" />
                    Pro saves <strong class="text-white" x-text="money(saving)"></strong> on this package. The CI${{ $ebox['plans']['pro']['membership'] }} membership pays for itself after
                    <strong class="text-white" x-text="Math.ceil({{ $ebox['plans']['pro']['membership'] }} / saving)"></strong> packages like it.
                </p>
                <p class="mt-4 text-xs text-white/45">Estimate only, with chargeable weight rounded up to the next pound. All rates in C.I. dollars. Imported goods are subject to duties and tax assessed by Customs and Border Control.</p>
            </div>
        </div>
    </section>

    {{-- Detail tabs --}}
    <section class="section" id="details" x-data="{ tab: @js($tab), q: '' }">
        <div class="wrap">
            <div class="flex gap-1 overflow-x-auto rounded-full bg-white p-1.5 ring-1 ring-line sm:w-fit" role="tablist">
                @foreach (['overview' => 'Description', 'pricing' => 'Pricing', 'faq' => 'FAQ', 'points' => 'MBE Points'] as $key => $label)
                    <button type="button" role="tab" @click="tab = '{{ $key }}'" :aria-selected="tab === '{{ $key }}'"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold whitespace-nowrap transition"
                        :class="tab === '{{ $key }}' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink'">{{ $label }}</button>
                @endforeach
            </div>

            {{-- Description --}}
            <div x-show="tab === 'overview'" @if ($tab !== 'overview') x-cloak @endif class="mt-10 grid gap-10 lg:grid-cols-12" role="tabpanel">
                <div class="lg:col-span-7">
                    <h2 class="h-section">Your own U.S. address for online purchases.</h2>
                    <div class="prose-mbe mt-6">
                        <p>E-box service by Mail Boxes Etc. is a reliable and efficient service for shopping online. E-box provides you with a U.S. address that you can use for online purchases. Through our Miami warehouse you can receive packages and documents and have them delivered to your closest Mail Boxes Etc. centre. With E-box, you will have access to thousands of online stores with the best offers, latest styles, trends, modern technology, and much more.</p>
                        <p>Packages typically take {{ $ebox['transit'] }} from the day they are ready to leave Miami to be delivered to your MBE centre. Once delivered, our system will automatically notify you via email that the package is ready for pickup. You can also track your packages via our E-box Web system online.</p>
                        <p>With E-box you can receive clothing, sports gear, cooking equipment, gadgets, computers and much more.</p>
                    </div>

                    <h3 class="mt-10 font-display text-2xl font-bold">Online tools &amp; resources</h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['monitor-smartphone', 'E-box website', 'Track packages, upload invoices, set up email notifications, search for missing packages, make payments.'],
                            ['smartphone', 'Mobile app', 'Available for download from the App Store and Google Play.'],
                            ['gift', 'MBE Points', 'Earn points and redeem the coupons on future shipments or any MBE service.'],
                            ['bell', 'Pre-alerts', 'Let us know that your orders are on the way.'],
                            ['zap', 'Express Pick-Up', 'Pay for your shipping and duties online and use the Express pick-up line in store.'],
                            ['truck', 'Delivery', 'We will deliver your packages right to your door.'],
                        ] as [$icon, $title, $text])
                            <div class="flex gap-3 rounded-2xl bg-white p-4 ring-1 ring-line/70">
                                <x-icon :name="$icon" class="mt-0.5 size-5 shrink-0 text-brand-600" />
                                <div><p class="text-sm font-semibold">{{ $title }}</p><p class="mt-0.5 text-xs text-muted">{{ $text }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <aside class="space-y-5 lg:col-span-5">
                    <div class="card">
                        <h3 class="font-display text-xl font-bold">Benefits</h3>
                        <ul class="mt-4 space-y-3 text-sm">
                            @foreach ([
                                'Highly advanced sorting facility in Miami',
                                'Web and mobile app-based tracking system: E-box Web',
                                'Invoice upload & package pre-alert',
                                'We clear Customs for you',
                                'Loyalty programme: MBE Points are easily converted to redeemable vouchers',
                                'Amazon and eBay orders sent to your E-box address are not subject to Florida sales tax',
                            ] as $benefit)
                                <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="rounded-3xl bg-ink p-6 text-white sm:p-8">
                        <p class="text-xs font-semibold tracking-[0.16em] text-white/50 uppercase">Your E-box address looks like this</p>
                        <address class="mt-4 font-mono text-sm leading-relaxed not-italic">
                            @foreach ($ebox['address'] as $line){{ $line }}<br>@endforeach
                        </address>
                        <p class="mt-4 text-xs text-white/55">Your CBY account number is provided by email when you register. It may appear anywhere in the Ship To address. For large items, <a href="{{ route('ocean') }}" class="underline hover:text-white">Ocean Ship</a> uses a different address.</p>
                    </div>
                </aside>
            </div>

            {{-- Pricing --}}
            <div x-show="tab === 'pricing'" @if ($tab !== 'pricing') x-cloak @endif class="mt-10" role="tabpanel">
                <h2 class="h-section max-w-3xl">Two great E-box plans, with and without annual membership.</h2>
                <div class="mt-8 overflow-x-auto rounded-3xl bg-white shadow-card ring-1 ring-line/70">
                    <table class="table-clean min-w-[36rem]">
                        <thead>
                            <tr>
                                <th class="w-2/5"></th>
                                @foreach ($ebox['plans'] as $plan)
                                    <th class="!text-ink"><span class="font-display text-lg font-bold normal-case tracking-normal">{{ $plan['name'] }}</span></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="font-medium">Membership fee</td>@foreach ($ebox['plans'] as $plan)<td>{{ $plan['membership'] ? 'CI$'.$plan['membership'].'/year' : 'CI$0' }}</td>@endforeach</tr>
                            <tr><td class="font-medium">Flat fee, first pound</td>@foreach ($ebox['plans'] as $plan)<td class="font-semibold">CI${{ number_format($plan['first_lb'], 2) }}</td>@endforeach</tr>
                            <tr><td class="font-medium">Package shipping rate<br><span class="text-xs font-normal text-muted">Each additional pound</span></td>@foreach ($ebox['plans'] as $plan)<td class="font-semibold">CI${{ number_format($plan['per_lb'], 2) }}/lb</td>@endforeach</tr>
                            <tr><td class="font-medium">Mail &amp; documents, 0–16 oz<br><span class="text-xs font-normal text-muted">Standard package rates apply above 16 oz</span></td>@foreach ($ebox['plans'] as $plan)<td>CI${{ number_format($ebox['document_per_oz'], 2) }}/oz</td>@endforeach</tr>
                            <tr><td class="font-medium">Earns MBE Points?</td>@foreach ($ebox['plans'] as $plan)<td><x-icon name="check" class="size-5 text-emerald-600" /></td>@endforeach</tr>
                            <tr><td class="font-medium">Choosing a plan</td>@foreach ($ebox['plans'] as $plan)<td class="text-muted">{{ $plan['blurb'] }}</td>@endforeach</tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex gap-3 rounded-2xl bg-brand-50 p-5 text-sm text-brand-900 ring-1 ring-brand-100">
                    <x-icon name="info" class="mt-0.5 size-5 shrink-0 text-brand-600" />
                    <p><strong class="font-semibold">Important.</strong> All rates in C.I. dollars. Imported goods are subject to duties and tax assessed by Customs and Border Control in accordance with the Cayman Islands Customs Law. The shipping rates are applied to the greater of actual weight and dimensional weight. See the FAQ for more information.</p>
                </div>
            </div>

            {{-- FAQ --}}
            <div x-show="tab === 'faq'" @if ($tab !== 'faq') x-cloak @endif class="mt-10 grid gap-10 lg:grid-cols-12" role="tabpanel">
                <div class="lg:col-span-4">
                    <h2 class="h-section">Frequently asked questions</h2>
                    <p class="mt-4 text-muted">The package journey from your vendor to you is a multi-step process. These shipping tips cover the most common bottlenecks.</p>
                    <label class="relative mt-6 block">
                        <span class="sr-only">Filter questions</span>
                        <x-icon name="search" class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-muted" />
                        <input type="search" x-model="q" placeholder="Filter questions…" class="input !pl-10">
                    </label>
                </div>
                <div class="space-y-10 lg:col-span-8">
                    @foreach ($faq as $group)
                        <div x-data="{ get any() { return {{ Js::from(collect($group['items'])->map(fn ($i) => Str::lower($i['q'].' '.strip_tags($i['a'])))) }}.some(t => t.includes(q.toLowerCase())) } }" x-show="any">
                            <h3 class="font-display text-xl font-bold">{{ $group['category'] }}</h3>
                            <div class="mt-4 divide-y divide-line overflow-hidden rounded-2xl bg-white ring-1 ring-line/70">
                                @foreach ($group['items'] as $item)
                                    <details class="group" x-show="@js(Str::lower($item['q'].' '.strip_tags($item['a']))).includes(q.toLowerCase())">
                                        <summary class="flex list-none items-center justify-between gap-4 p-5 font-semibold transition hover:bg-paper [&::-webkit-details-marker]:hidden">
                                            {{ $item['q'] }}
                                            <x-icon name="plus" class="size-5 shrink-0 text-brand-600 transition group-open:rotate-45" />
                                        </summary>
                                        <div class="prose-mbe px-5 pb-6">{!! $item['a'] !!}</div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Points --}}
            <div x-show="tab === 'points'" @if ($tab !== 'points') x-cloak @endif class="mt-10 grid gap-10 lg:grid-cols-12" role="tabpanel">
                <div class="lg:col-span-7">
                    <h2 class="h-section">MBE Points</h2>
                    <div class="prose-mbe mt-6">
                        <p>The MBE Points programme is our way of thanking you, our valued E-box customer, by giving you the opportunity to generate points every time you use the E-box service. You can then redeem your points for vouchers to purchase any of the services we offer at MBE.</p>
                        <h3>Points exchange</h3>
                        <p>The minimum amount of points required to generate a voucher is 1,000 points. <strong>Each 1,000 points is equivalent to CI$10.00.</strong> Vouchers can be requested by logging in to your E-box web account and are valid for 30 days, so generate them only once you are ready to use them.</p>
                        <h3>With your MBE Points you can</h3>
                        <ul>
                            <li>Pay for packages via the E-box web payment portal</li>
                            <li>Pay for international shipments, digital printing and copying, passport photos, packaging and packaging materials, and the E-box service</li>
                        </ul>
                        <h3>Terms &amp; conditions</h3>
                        <ul>
                            <li>Points earned by pre-alerts are applied once the pre-alert is validated.</li>
                            <li>Points are valid for 12 months from the month in which they were first generated.</li>
                            <li>Vouchers may only be redeemed by the account holder at the MBE centre where their account is registered. MBE Points are not transferable and vouchers cannot be exchanged for cash.</li>
                            <li>Vouchers may not be used to purchase postage stamps or to pay for import duties and taxes.</li>
                        </ul>
                    </div>
                </div>
                <aside class="lg:col-span-5">
                    <div class="card">
                        <h3 class="font-display text-xl font-bold">How do I earn MBE Points?</h3>
                        <ul class="mt-4 divide-y divide-line">
                            @foreach ($ebox['points'] as [$activity, $points])
                                <li class="flex items-center justify-between gap-4 py-3.5 text-sm">
                                    {{ $activity }}
                                    <span class="shrink-0 rounded-full bg-brand-50 px-3 py-1 font-display font-bold text-brand-700">{{ $points }} pts</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="hint">Birthday points are generated provided that the birthdate appears correctly on your E-box web profile.</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Customs --}}
    <section class="section bg-white" id="customs">
        <div class="wrap">
            <div class="max-w-3xl" data-reveal>
                <p class="eyebrow">Customs Appointment of Agent</p>
                <h2 class="h-section mt-4">Register with Customs once, and we clear every package for you.</h2>
                <p class="lead mt-4">Customs requires that all persons importing goods into the Cayman Islands register in the Customs OnLine System (COLS) and appoint Mail Boxes Etc. as their agent.</p>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                <div class="card bg-paper" data-reveal>
                    <span class="inline-flex rounded-full bg-ink px-3 py-1 text-xs font-semibold text-white">Step 1</span>
                    <h3 class="mt-4 font-display text-xl font-bold">Register in COLS</h3>
                    <p class="mt-2 text-sm text-muted">Select applicant type Individual, fill in the form, upload one identification document then press Send Request. You will be notified by Customs via email within 3 business days once your submission is approved.</p>
                    <p class="mt-2 text-sm text-muted">Already have a TIN (Trader Identification Number)? Go directly to step 2.</p>
                    <a href="{{ $mbe['links']['cols_register'] }}" target="_blank" rel="noopener" class="btn btn-dark mt-6">Customs registration <x-icon name="arrow-up-right" class="size-4" /></a>
                </div>
                <div class="card bg-paper" data-reveal style="--reveal-delay: 100ms">
                    <span class="inline-flex rounded-full bg-ink px-3 py-1 text-xs font-semibold text-white">Step 2</span>
                    <h3 class="mt-4 font-display text-xl font-bold">Appoint Mail Boxes Etc. as your agent</h3>
                    <ol class="mt-3 list-decimal space-y-1.5 pl-5 text-sm text-muted marker:font-semibold marker:text-brand-700">
                        <li>Log into COLS and click &ldquo;Declarations&rdquo;.</li>
                        <li>In the left menu click &ldquo;Agent Authorization&rdquo;.</li>
                        <li>Select Mail Boxes Etc. in &ldquo;Available Agents&rdquo; and move it to &ldquo;Authorized Agent&rdquo;.</li>
                        <li>Click &ldquo;Submit&rdquo;. Your Trader Name and TIN are now available to us to clear on your behalf.</li>
                    </ol>
                    <a href="{{ $mbe['links']['cols_login'] }}" target="_blank" rel="noopener" class="btn btn-dark mt-6">COLS login <x-icon name="arrow-up-right" class="size-4" /></a>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band title="Start shopping today." text="When you open your E-box you can immediately start shopping using the address and account number provided by email.">
        <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="btn bg-white !px-6 !py-3.5 !text-base text-ink hover:bg-ink hover:text-white">Sign up for E-box <x-icon name="arrow-up-right" class="size-4" /></a>
        <a href="mailto:{{ $mbe['emails']['ebox'] }}" class="btn bg-brand-800/60 !px-6 !py-3.5 !text-base text-white ring-1 ring-white/25 hover:bg-brand-800">{{ $mbe['emails']['ebox'] }}</a>
    </x-cta-band>
</x-layout>
