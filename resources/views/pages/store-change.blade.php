@php
    $bag = 'store-change';
    $failed = $errors->getBag($bag)->any();
@endphp
<x-layout title="Change your E-box pick-up store" description="Move your E-box package collection between Mail Boxes Etc. Camana Bay and Harbour Walk.">
    <x-page-hero eyebrow="E-box" title="Store change request for E-box package collection."
        lead="Conveniently located in Camana Bay and Harbour Walk. Choose where you would like to collect your E-box packages and keep using the same CBY#."
        :crumb="['E-box', route('ebox')]" />

    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7" data-reveal>
                <form id="form-store-change" method="POST" action="{{ route('enquiries.store', $bag) }}" class="card scroll-mt-28">
                    @csrf
                    <x-form-status :type="$bag" title="Request received." message="The change will take effect in two business days. You can keep using the same CBY#." />

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-field name="name" label="Customer name (as registered on E-box)" :bag="$bag" required autocomplete="name" class="sm:col-span-2" />
                        <x-field name="email" type="email" label="Email address" :bag="$bag" required autocomplete="email" />
                        <x-field name="cby_number" label="CBY number" :bag="$bag" required placeholder="CBY 12345" />
                    </div>

                    <fieldset class="mt-5">
                        <legend class="label">Choose your preferred location for E-box package collection <span class="text-brand-600">*</span></legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach (config('mbe.locations') as $key => $location)
                                <label class="option">
                                    <input type="radio" name="location" value="{{ $key }}" class="sr-only" required @checked($failed && old('location') === $key)>
                                    <span>
                                        <strong class="font-display text-lg font-bold">{{ $location['name'] }}</strong>
                                        <span class="text-muted">{{ $location['address'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @if ($message = $errors->getBag($bag)->first('location'))
                            <p class="field-error">{{ $message }}</p>
                        @endif
                    </fieldset>

                    <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    <button type="submit" class="btn btn-primary mt-6 !px-6 !py-3.5 !text-base">Submit request <x-icon name="arrow-right" class="size-4" /></button>
                </form>
            </div>
            <aside class="space-y-4 lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                @foreach ([
                    ['calendar-clock', 'Takes effect in two business days', 'After that, begin collecting E-box packages from your new store.'],
                    ['repeat', 'Limit of two changes per calendar year', 'Choose the store you visit most often.'],
                    ['ship', 'Ocean shipments', 'Ocean shipping packages continue to be collected from our Camana Bay store or via home delivery only.'],
                ] as [$icon, $title, $text])
                    <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-line/70">
                        <span class="icon-tile"><x-icon :name="$icon" /></span>
                        <div><h2 class="font-display text-lg font-bold">{{ $title }}</h2><p class="mt-1 text-sm text-muted">{{ $text }}</p></div>
                    </div>
                @endforeach
            </aside>
        </div>
    </section>
</x-layout>
