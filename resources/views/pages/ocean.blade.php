@php
    $mbe = config('mbe');
    $ocean = $mbe['ocean'];
    $bag = 'ocean-pre-alert';
    $failed = $errors->getBag($bag)->any();
    $next = $sailings->first(fn ($sailing) => ! $sailing->hasSailed());
@endphp
<x-layout title="Ocean Ship" description="Ocean freight from Miami to Grand Cayman for large and over-sized cargo. See sailing dates, estimate your rate and pre-alert your shipment online.">
    <x-page-hero eyebrow="Ocean Ship" title="Large cargo shipping via ocean freight."
        lead="Ideal for large cargo and over-sized packages. With bi-weekly sailings from Miami, our ocean freight service is a cost-effective choice for large purchases. Service includes insurance and Customs clearance to give you peace of mind.">
        <a href="#form-ocean-pre-alert" class="btn btn-primary">Ocean Ship pre-alert</a>
        <a href="#calculator" class="btn btn-onDark">Estimate your rate</a>
    </x-page-hero>

    {{-- Sailings --}}
    <section class="section" id="sailings">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">Upcoming sailing dates</p>
                <h2 class="h-section mt-4">Bi-weekly sailings from Miami.</h2>
                <p class="lead mt-4">Free 30 day consolidation on multiple packages is available to save you money.</p>
                <p class="mt-4 text-sm text-muted">If you need to receive your orders sooner, we recommend our <a href="{{ route('ebox') }}" class="link">E-box air cargo service</a>, which has twice weekly shipments and a {{ $mbe['ebox']['transit'] }} turnaround.</p>
            </div>
            <div class="lg:col-span-8" data-reveal style="--reveal-delay: 120ms">
                <div class="overflow-x-auto rounded-3xl bg-white shadow-card ring-1 ring-line/70">
                    <table class="table-clean min-w-[34rem]">
                        <thead>
                            <tr>
                                <th>Cut-off to instruct to ship*</th>
                                <th>Sails from Miami</th>
                                <th>In hand in Cayman**</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sailings as $sailing)
                                <tr @class(['bg-brand-50/60' => $next?->is($sailing)])>
                                    <td class="font-medium">
                                        <span @class(['text-muted line-through decoration-muted/40' => $sailing->cutoffPassed()])>{{ $sailing->cutoff_date->format('D j M Y') }}</span>
                                        @if ($sailing->cutoffPassed() && ! $sailing->hasSailed())
                                            <span class="block text-xs font-normal text-muted">Cut-off passed</span>
                                        @endif
                                    </td>
                                    <td class="font-semibold">{{ $sailing->sailing_date->format('D j M Y') }}</td>
                                    <td>{{ $sailing->in_hand_date->format('D j M Y') }}</td>
                                    <td class="text-right">
                                        @if ($next?->is($sailing))
                                            <span class="rounded-full bg-brand-600 px-2.5 py-1 text-xs font-semibold text-white">Next sailing</span>
                                        @elseif ($sailing->hasSailed())
                                            <span class="rounded-full bg-paper px-2.5 py-1 text-xs font-semibold text-muted ring-1 ring-line">At sea</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="!py-10 text-center text-muted">New sailing dates will be posted here shortly.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 space-y-1.5 text-xs text-muted">
                    <p>Future sailing dates will be posted as they become available. Holidays may affect sailing dates and cut-offs.</p>
                    <p>*Only packages that have been pre-alerted with the correct invoice are permitted to be released for shipping.</p>
                    <p>**We endeavour to have your orders to you by the date shown. Time frames may be affected by unexpected delays beyond our control including but not limited to weather, Customs, and Port Authority.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Rates + calculator --}}
    <section class="section bg-white" id="calculator">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="eyebrow">Ocean cargo shipping rates</p>
                <h2 class="h-section mt-4">Priced by the cubic foot.</h2>
                <p class="mt-4 text-muted">Rates apply to the combined dimensional weight of all your packages that ship together in a consolidated shipment, and are inclusive of freight, import documentation, and Customs clearance service.</p>

                <div class="mt-8 overflow-hidden rounded-2xl ring-1 ring-line">
                    <table class="table-clean">
                        <thead><tr class="bg-paper"><th>Cubic feet</th><th class="text-right">Rate (CI$)</th></tr></thead>
                        <tbody>
                            <tr><td>Up to {{ $ocean['flat_cf'] }} c.f.</td><td class="text-right font-semibold">${{ $ocean['flat_rate'] }} flat rate</td></tr>
                            <tr><td>Each additional c.f. between {{ $ocean['flat_cf'] }} and {{ $ocean['mid_cf'] }} c.f.</td><td class="text-right font-semibold">${{ number_format($ocean['mid_rate'], 2) }}/c.f.</td></tr>
                            <tr><td>Each additional c.f. over {{ $ocean['mid_cf'] }} c.f.</td><td class="text-right font-semibold">${{ number_format($ocean['high_rate'], 2) }}/c.f.</td></tr>
                        </tbody>
                    </table>
                </div>

                <ul class="mt-6 space-y-2.5 text-sm text-muted">
                    <li class="flex gap-2.5"><x-icon name="info" class="mt-0.5 size-4 shrink-0 text-brand-600" /> 10 free deliveries (dock receipts) per consolidation. Each additional dock receipt is CI$10, to a maximum of CI$500.</li>
                    <li class="flex gap-2.5"><x-icon name="info" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Rates do not include Customs duty, Port Authority fee, freight insurance or other Government fees as applicable.</li>
                    <li class="flex gap-2.5"><x-icon name="info" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Vehicles, self-propelled scooters and dangerous goods are quoted by category.</li>
                    <li class="flex gap-2.5"><x-icon name="clock" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Storage: CI${{ number_format($ocean['storage_per_day'], 2) }} per day, per package, after 30 days in Miami or five business days after invoicing in Cayman.</li>
                </ul>
            </div>

            <div class="lg:col-span-7" data-reveal style="--reveal-delay: 120ms">
                <div class="card bg-paper" x-data="oceanCalculator(@js($ocean))">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-display text-2xl font-bold">Ocean freight estimator</h3>
                            <p class="mt-1 text-sm text-muted">Cubic feet = L × W × H ÷ {{ $ocean['divisor'] }}, measured in inches.</p>
                        </div>
                        <span class="icon-tile !bg-white"><x-icon name="calculator" /></span>
                    </div>

                    <div class="mt-6 space-y-3">
                        <template x-for="(p, index) in packages" :key="index">
                            <div class="grid grid-cols-[repeat(4,minmax(0,1fr))_auto] items-end gap-2 rounded-2xl bg-white p-3 ring-1 ring-line/70">
                                <label class="block"><span class="mb-1 block text-xs font-medium text-muted">Length</span><input type="number" min="0" inputmode="decimal" class="input" placeholder="in" x-model="p.length"></label>
                                <label class="block"><span class="mb-1 block text-xs font-medium text-muted">Width</span><input type="number" min="0" inputmode="decimal" class="input" placeholder="in" x-model="p.width"></label>
                                <label class="block"><span class="mb-1 block text-xs font-medium text-muted">Height</span><input type="number" min="0" inputmode="decimal" class="input" placeholder="in" x-model="p.height"></label>
                                <label class="block"><span class="mb-1 block text-xs font-medium text-muted">Qty</span><input type="number" min="1" inputmode="numeric" class="input" x-model="p.qty"></label>
                                <button type="button" class="grid size-10 place-items-center rounded-xl text-muted transition hover:bg-brand-50 hover:text-brand-700 disabled:opacity-30" @click="remove(index)" :disabled="packages.length === 1" aria-label="Remove package">
                                    <x-icon name="trash-2" class="size-4" />
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn-light mt-3 !py-2.5" @click="add()"><x-icon name="plus" class="size-4" /> Add another package</button>

                    <div class="mt-6 rounded-2xl bg-ink p-6 text-white">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs text-white/50">Consolidated volume</p>
                                <p class="font-display text-2xl font-bold tabular-nums"><span x-text="total.toFixed(1)">0.0</span> c.f.</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-white/50">Estimated freight</p>
                                <p class="font-display text-4xl font-extrabold tabular-nums" x-text="total ? money(cost) : 'CI$ –'">CI$ –</p>
                            </div>
                        </div>
                        <dl class="mt-4 space-y-1.5 border-t border-white/10 pt-4 text-sm text-white/70" x-show="breakdown.length" x-cloak>
                            <template x-for="line in breakdown" :key="line.label">
                                <div class="flex justify-between"><dt x-text="line.label"></dt><dd class="tabular-nums" x-text="money(line.amount)"></dd></div>
                            </template>
                        </dl>
                    </div>
                    <p class="hint">Estimate only. All rates subject to change without notice. Delivery times not guaranteed. Rates in KYD.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Guide + address --}}
    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7" data-reveal>
                <p class="eyebrow">How to use the Ocean Shipping service</p>
                <h2 class="h-section mt-4">Step-by-step guide.</h2>
                <p class="mt-4 text-muted">If you already have an E-box shipping account, you will use the same CBY# for Ocean Shipping. If you have not previously registered with us, <a href="{{ $mbe['links']['ebox_signup'] }}" target="_blank" rel="noopener" class="link">create an E-box account</a> first.</p>
                <ol class="mt-8 space-y-5">
                    @foreach ([
                        ['Place your order', 'Use the ocean shipping address shown here. Remember to include your CBY# and c/o Mail Boxes Etc/GCM in the delivery address at checkout.'],
                        ['Save the invoice', 'It is required for Customs clearance and for pre-alerting your order. You can obtain it from your Order History on the vendor’s website, then Print to PDF.'],
                        ['Pre-alert your incoming order', 'When the vendor emails your tracking number, send us the pre-alert with your invoice using the form below.'],
                        ['We notify you on arrival in Miami', 'You should also track your orders on your vendor’s site to know when it has been delivered.'],
                        ['Ship', 'If you instructed us to Ship, your order ships automatically. You will be notified within 4 weeks that it is ready for pick-up or delivery.'],
                        ['Or consolidate', 'We hold your orders in Miami free of charge for up to 30 days. When they have all arrived, instruct us to ship.'],
                    ] as $i => [$title, $text])
                        <li class="flex gap-4">
                            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-ink font-display text-sm font-bold text-white">{{ $i + 1 }}</span>
                            <div><h3 class="font-display text-lg font-bold">{{ $title }}</h3><p class="mt-1 text-sm text-muted">{{ $text }}</p></div>
                        </li>
                    @endforeach
                </ol>
            </div>
            <aside class="space-y-5 lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                <div class="rounded-3xl bg-ink p-6 text-white sm:p-8" x-data="{ copied: false }">
                    <p class="text-xs font-semibold tracking-[0.16em] text-brand-300 uppercase">Ocean shipping address</p>
                    <address class="mt-4 font-mono text-[15px] leading-relaxed not-italic" x-ref="address">@foreach ($ocean['address'] as $line){{ $line }}<br>@endforeach</address>
                    <p class="mt-2 text-xs text-white/50">(Miami or Doral are accepted)</p>
                    <button type="button" class="btn btn-onDark mt-5 !py-2.5" @click="navigator.clipboard?.writeText($refs.address.innerText.trim()); copied = true; setTimeout(() => copied = false, 2000)">
                        <x-icon name="copy" class="size-4" /> <span x-text="copied ? 'Copied' : 'Copy address'">Copy address</span>
                    </button>
                    <p class="mt-5 flex gap-2.5 border-t border-white/10 pt-5 text-sm text-white/70">
                        <x-icon name="triangle-alert" class="mt-0.5 size-4 shrink-0 text-brand-400" />
                        This address is different from the E-box air shipping address. Failure by the vendor to include the CBY# may cause delays with your shipment.
                    </p>
                </div>
                <div class="card">
                    <h3 class="font-display text-lg font-bold">Acceptable invoice for Customs</h3>
                    <ul class="mt-4 space-y-3 text-sm text-muted">
                        <li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Legible. Low resolution images will not be accepted.</li>
                        <li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Shows vendor name, customer name, itemised descriptions and prices, shipping fee, total paid, order number and order date.</li>
                        <li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" /> Corresponds exactly to the contents of the package.</li>
                    </ul>
                    <p class="mt-4 text-sm text-muted">Importing for the first time? <a href="{{ route('ebox') }}#customs" class="link">Register with Customs and appoint MBE as your agent</a>.</p>
                </div>
            </aside>
        </div>
    </section>

    {{-- Pre-alert form --}}
    <section class="section bg-white">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">Pre-alert &amp; invoice upload</p>
                <h2 class="h-section mt-4">Tell us what is on the way.</h2>
                <p class="lead mt-4">Providing all of the details will help us process your shipment as smoothly as possible. Most importantly, don&rsquo;t forget to attach your invoice.</p>
                <p class="mt-6 text-sm text-muted">Prefer email? Send the same details to <a href="mailto:{{ $mbe['emails']['ocean'] }}" class="link">{{ $mbe['emails']['ocean'] }}</a> or call <a href="tel:{{ $mbe['phone_href'] }}" class="link">{{ $mbe['phone'] }}</a>.</p>
            </div>
            <div class="lg:col-span-8" data-reveal style="--reveal-delay: 120ms">
                <form id="form-ocean-pre-alert" method="POST" action="{{ route('enquiries.store', $bag) }}" enctype="multipart/form-data" class="card scroll-mt-28 bg-paper">
                    @csrf
                    <x-form-status :type="$bag" title="Pre-alert received." message="We will match it to your package when it reaches Miami and email you if we need anything further." />

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-field name="name" label="Name" :bag="$bag" required autocomplete="name" />
                        <x-field name="cby_number" label="CBY#" :bag="$bag" required placeholder="CBY 12345" />
                        <x-field name="email" type="email" label="Email" :bag="$bag" required autocomplete="email" class="sm:col-span-2" />
                        <x-field name="tracking_number" label="Tracking number" :bag="$bag" required />
                        <x-field name="vendor" label="Vendor" :bag="$bag" placeholder="Where did you order from?" />
                        <x-field name="description" type="textarea" label="Description of the item(s)" :bag="$bag" required rows="3" class="sm:col-span-2" />
                        <x-field name="invoice_value" type="number" label="Invoice value (USD)" :bag="$bag" required step="0.01" min="0.01" placeholder="0.00" />

                        <div x-data="fileField">
                            <label class="label" for="ocean-attachment">Invoice <span class="text-brand-600">*</span></label>
                            <label for="ocean-attachment" class="input flex cursor-pointer items-center gap-2 text-muted {{ $errors->getBag($bag)->has('attachment') ? 'input-error' : '' }}">
                                <x-icon name="upload" class="size-4 shrink-0" />
                                <span class="truncate" x-text="name || 'PDF, JPG or PNG, up to {{ \App\Support\Content::uploadLimitMb() }} MB'">PDF, JPG or PNG, up to {{ \App\Support\Content::uploadLimitMb() }} MB</span>
                            </label>
                            <input id="ocean-attachment" type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" @change="pick" required>
                            @if ($message = $errors->getBag($bag)->first('attachment'))
                                <p class="field-error">{{ $message }}</p>
                            @elseif ($failed)
                                <p class="hint">Please re-attach your invoice.</p>
                            @endif
                        </div>
                    </div>

                    <fieldset class="mt-5">
                        <legend class="label">What should we do when it arrives? <span class="text-brand-600">*</span></legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ([
                                'Ship' => 'Send it on the next available sailing.',
                                'Consolidate' => 'Hold in Miami free for up to 30 days while my other orders arrive.',
                            ] as $value => $text)
                                <label class="option">
                                    <input type="radio" name="instruction" value="{{ $value }}" class="sr-only" required @checked(($failed ? old('instruction') : 'Ship') === $value)>
                                    <span><strong class="font-display text-base font-bold">{{ $value }}</strong><span class="text-muted">{{ $text }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    <button type="submit" class="btn btn-primary mt-6 !px-6 !py-3.5 !text-base">Submit pre-alert <x-icon name="arrow-right" class="size-4" /></button>
                </form>
            </div>
        </div>
    </section>
</x-layout>
