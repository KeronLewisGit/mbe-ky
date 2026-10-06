@php
    $mbe = config('mbe');
    $mailbox = $mbe['mailbox'];
    $bag = 'mailbox';
    $failed = $errors->getBag($bag)->any();
    $initial = [
        'plan' => $failed ? old('plan', 'personal') : request('plan', 'personal'),
        'extra' => $failed ? (int) old('additional_recipients', 0) : 0,
        'applicant' => $failed ? old('applicant_type', 'Individual') : 'Individual',
    ];
    if (! isset($mailbox['plans'][$initial['plan']])) {
        $initial['plan'] = 'personal';
    }
@endphp
<x-layout title="Physical Mailbox" description="Private mailboxes at Mail Boxes Etc. Camana Bay and Harbour Walk from CI$185 a year. Apply online in minutes.">
    <x-page-hero eyebrow="Physical Mailbox" title="Simplify your life with a private mailbox."
        lead="Convenient and secure Private Mail Boxes for receiving local and international mail. With an MBE mailbox you get a street address, so courier deliveries are no problem."
        image="physical-mailbox.jpg" image-alt="Brass MBE mailboxes, one open with mail inside" :crumb="['Mailboxes', route('mailboxes')]">
        <a href="#form-mailbox" class="btn btn-primary">Apply online</a>
        <a href="#pricing" class="btn btn-onDark">See prices</a>
    </x-page-hero>

    {{-- Features --}}
    <section class="section">
        <div class="wrap">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">Features and benefits</p>
                <h2 class="h-section mt-4">Works like a P.O. Box, with a few added benefits.</h2>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['truck', 'A street address', 'Receive regular mail and courier deliveries from FedEx, DHL & UPS, which do not deliver to P.O. Boxes.'],
                    ['clock', '24hr indoor access', 'Collect your mail whenever it suits you. Camana Bay location only.'],
                    ['shield-check', 'Customs clearance', 'We clear all your postal packages from the Airport Post Office, so no more waiting in line.'],
                    ['forward', 'Holding & forwarding', 'Mail holding and forwarding, plus duplicate keys available for added convenience.'],
                ] as $i => [$icon, $title, $text])
                    <div class="card" data-reveal style="--reveal-delay: {{ $i * 70 }}ms">
                        <span class="icon-tile"><x-icon :name="$icon" /></span>
                        <h3 class="mt-5 font-display text-lg font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-8 text-sm text-muted" data-reveal>Available to all residents of Grand Cayman. We are a one stop shop for all your postal, courier, printing, packaging and U.S. address services too.</p>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="section bg-white" id="pricing">
        <div class="wrap">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="eyebrow">Mailbox prices</p>
                <h2 class="h-section mt-4">Choose the mailbox that is right for you.</h2>
            </div>
            <div class="mt-12 grid gap-6 lg:grid-cols-3">
                @foreach ($mailbox['plans'] as $key => $plan)
                    <article class="card flex flex-col {{ $key === 'small-business' ? '!bg-ink text-white !ring-ink shadow-lift' : '' }}" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <p class="text-sm font-semibold {{ $key === 'small-business' ? 'text-brand-300' : 'text-brand-700' }}">{{ $plan['for'] }}</p>
                        <h3 class="mt-2 font-display text-2xl font-bold">{{ $plan['name'] }}</h3>
                        <p class="mt-6 font-display text-5xl font-extrabold tracking-tight">CI${{ $plan['price'] }}<span class="text-base font-medium {{ $key === 'small-business' ? 'text-white/60' : 'text-muted' }}">/yr</span></p>
                        <ul class="mt-6 space-y-3 text-sm {{ $key === 'small-business' ? 'text-white/85' : '' }}">
                            <li class="flex gap-3"><x-icon name="users" class="size-[18px] shrink-0 {{ $key === 'small-business' ? 'text-brand-400' : 'text-brand-600' }}" /> {{ $plan['recipients'] }} recipients included</li>
                            <li class="flex gap-3"><x-icon name="key-round" class="size-[18px] shrink-0 {{ $key === 'small-business' ? 'text-brand-400' : 'text-brand-600' }}" /> Mailbox key</li>
                            <li class="flex gap-3"><x-icon name="bell" class="size-[18px] shrink-0 {{ $key === 'small-business' ? 'text-brand-400' : 'text-brand-600' }}" /> Email notifications for packages</li>
                        </ul>
                        <a href="{{ route('physical', ['plan' => $key]) }}#form-mailbox" class="btn {{ $key === 'small-business' ? 'btn-primary' : 'btn-dark' }} mt-8">Choose {{ $plan['name'] }}</a>
                    </article>
                @endforeach
            </div>
            <p class="mt-8 text-center text-sm text-muted">Price does not include refundable key deposit of CI${{ $mailbox['key_deposit'] }}. Each additional recipient over and above the allocation is CI${{ $mailbox['extra_recipient'] }}/year.</p>
        </div>
    </section>

    {{-- Application --}}
    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">Getting your mailbox</p>
                <h2 class="h-section mt-4">Signing up is easy.</h2>
                <ol class="mt-8 space-y-6">
                    @foreach ([
                        'Complete and submit the application form.',
                        'You will receive an email confirmation containing a payment link so that you can pay online.',
                        'Within one business day, you will receive an email confirming your new address.',
                        'Collect your mailbox key(s) at your convenience. You will be required to present your photo ID.',
                    ] as $i => $step)
                        <li class="flex gap-4">
                            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-ink font-display text-sm font-bold text-white">{{ $i + 1 }}</span>
                            <p class="pt-1.5 text-sm text-ink/80">{{ $step }}</p>
                        </li>
                    @endforeach
                </ol>
                <div class="mt-8 flex gap-3 rounded-2xl bg-white p-4 text-sm ring-1 ring-line">
                    <x-icon name="building-2" class="mt-0.5 size-5 shrink-0 text-brand-600" />
                    <p><strong class="font-semibold">Business applicants:</strong> you are required to provide a copy of a valid Trade &amp; Business licence in order to open the mailbox in the business&rsquo; name.</p>
                </div>
            </div>

            <div class="lg:col-span-8" data-reveal style="--reveal-delay: 120ms">
                <form id="form-mailbox" method="POST" action="{{ route('enquiries.store', 'mailbox') }}" enctype="multipart/form-data" class="card scroll-mt-28"
                    x-data="mailboxApplication(@js($mailbox), @js($initial))">
                    @csrf
                    <x-form-status type="mailbox" title="Application received." message="Look out for an email confirmation with your payment link. We will confirm your new address within one business day." />

                    <h3 class="font-display text-2xl font-bold">Physical Mailbox application</h3>

                    <fieldset class="mt-6">
                        <legend class="label">Mailbox size <span class="text-brand-600">*</span></legend>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ($mailbox['plans'] as $key => $plan)
                                <label class="option">
                                    <input type="radio" name="plan" value="{{ $key }}" class="sr-only" x-model="plan" required>
                                    <span>
                                        <strong class="font-display text-base font-bold">{{ $plan['name'] }}</strong>
                                        <span class="text-muted">{{ $plan['recipients'] }} recipients</span>
                                        <span class="mt-2 font-semibold">CI${{ $plan['price'] }}/yr</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset class="mt-6">
                        <legend class="label">Store location <span class="text-brand-600">*</span></legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($mbe['locations'] as $key => $location)
                                <label class="option">
                                    <input type="radio" name="location" value="{{ $key }}" class="sr-only" required @checked(($failed ? old('location') : 'camana-bay') === $key)>
                                    <span>
                                        <strong class="font-display text-base font-bold">{{ $location['name'] }}</strong>
                                        <span class="text-muted">{{ $location['address'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @if ($message = $errors->getBag($bag)->first('location'))
                            <p class="field-error">{{ $message }}</p>
                        @endif
                    </fieldset>

                    <fieldset class="mt-6">
                        <legend class="label">This mailbox is for <span class="text-brand-600">*</span></legend>
                        <div class="flex w-fit gap-1 rounded-full bg-paper p-1 ring-1 ring-line">
                            @foreach (['Individual', 'Business'] as $type)
                                <label class="cursor-pointer">
                                    <input type="radio" name="applicant_type" value="{{ $type }}" class="peer sr-only" x-model="applicant">
                                    <span class="block rounded-full px-5 py-2 text-sm font-semibold text-muted transition peer-checked:bg-ink peer-checked:text-white">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <x-field name="first_name" label="First name" :bag="$bag" required autocomplete="given-name" />
                        <x-field name="last_name" label="Last name" :bag="$bag" required autocomplete="family-name" />
                        <div class="sm:col-span-2" x-show="applicant === 'Business'" x-cloak>
                            <x-field name="company" label="Business name" :bag="$bag" hint="As shown on your Trade & Business licence." x-bind:required="applicant === 'Business'" />
                        </div>
                        <x-field name="email" type="email" label="Email" :bag="$bag" required autocomplete="email" />
                        <x-field name="phone" type="tel" label="Mobile" :bag="$bag" required autocomplete="tel" />
                        <x-field name="recipients" type="textarea" label="Names of people receiving mail at this box" :bag="$bag" class="sm:col-span-2" rows="2" placeholder="One name per line" />
                        <x-field name="additional_recipients" type="number" label="Additional recipients" :bag="$bag" :value="0" min="0" max="20" x-model.number="extra"
                            hint="CI${{ $mailbox['extra_recipient'] }}/year each, over the plan allocation." />
                        <div x-data="fileField">
                            <label class="label" for="mailbox-attachment">Trade &amp; Business licence <span class="font-normal text-muted">(business only)</span></label>
                            <label for="mailbox-attachment" class="input flex cursor-pointer items-center gap-2 text-muted">
                                <x-icon name="paperclip" class="size-4 shrink-0" />
                                <span class="truncate" x-text="name || 'Attach PDF, JPG or PNG'">Attach PDF, JPG or PNG</span>
                            </label>
                            <input id="mailbox-attachment" type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" @change="pick">
                            @if ($message = $errors->getBag($bag)->first('attachment'))
                                <p class="field-error">{{ $message }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Live total --}}
                    <div class="mt-8 rounded-2xl bg-paper p-5 ring-1 ring-line">
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between"><dt class="text-muted">Annual subscription</dt><dd class="font-semibold tabular-nums" x-text="money(planPrice)"></dd></div>
                            <div class="flex justify-between" x-show="extraCost > 0"><dt class="text-muted">Additional recipients</dt><dd class="font-semibold tabular-nums" x-text="money(extraCost)"></dd></div>
                            <div class="flex justify-between"><dt class="text-muted">Refundable key deposit</dt><dd class="font-semibold tabular-nums">CI${{ number_format($mailbox['key_deposit'], 2) }}</dd></div>
                            <div class="flex items-baseline justify-between border-t border-line pt-3"><dt class="font-semibold">Due on sign-up</dt><dd class="font-display text-2xl font-extrabold tabular-nums" x-text="money(total)"></dd></div>
                        </dl>
                    </div>

                    <label class="mt-6 flex items-start gap-3 text-sm">
                        <input type="checkbox" name="terms" value="1" required class="mt-0.5 size-4 rounded border-line accent-brand-600">
                        <span>I have read and accept the <a href="{{ route('legal', 'physical-mailbox-terms') }}" target="_blank" class="link">Physical Mailbox terms &amp; conditions</a>.</span>
                    </label>
                    @if ($message = $errors->getBag($bag)->first('terms'))
                        <p class="field-error">{{ $message }}</p>
                    @endif

                    <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    <button type="submit" class="btn btn-primary mt-6 !px-6 !py-3.5 !text-base">Submit application <x-icon name="arrow-right" class="size-4" /></button>
                    <p class="hint">No payment is taken now. We email you a secure payment link.</p>
                </form>
            </div>
        </div>
    </section>

    <x-help-band topic="Mailboxes" />
</x-layout>
