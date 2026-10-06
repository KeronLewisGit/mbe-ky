@props(['location'])
@php
    $status = \App\Support\Content::storeStatus($location);
@endphp
<article {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-3xl bg-paper p-6 ring-1 ring-line/70 sm:p-8']) }}>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold tracking-[0.16em] text-muted uppercase">{{ $location['label'] }}</p>
            <h3 class="mt-2 font-display text-3xl font-bold">{{ $location['name'] }}</h3>
        </div>
        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold {{ $status['open'] ? 'bg-emerald-100 text-emerald-800' : 'bg-white text-muted ring-1 ring-line' }}">
            <span class="size-1.5 rounded-full {{ $status['open'] ? 'bg-emerald-500' : 'bg-muted/50' }}"></span>
            {{ $status['text'] }}
        </span>
    </div>

    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <div class="space-y-3 text-sm">
            <p class="flex items-start gap-2.5"><x-icon name="map-pin" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $location['address'] }}</p>
            <p class="flex items-start gap-2.5"><x-icon name="phone" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> <a href="tel:{{ config('mbe.phone_href') }}" class="font-semibold">{{ config('mbe.phone') }}</a></p>
            <p class="flex items-start gap-2.5"><x-icon name="sparkles" class="mt-0.5 size-[18px] shrink-0 text-brand-600" /> {{ $location['perk'] }}</p>
        </div>
        <dl class="text-sm">
            @foreach ($location['hours'] as [$days, $hours])
                <div class="flex justify-between gap-4 border-b border-line py-2 first:pt-0 last:border-0">
                    <dt class="text-muted">{{ $days }}</dt>
                    <dd class="font-semibold">{{ $hours }}</dd>
                </div>
            @endforeach
            @isset($location['hours_note'])
                <p class="mt-2 text-xs text-muted">{{ $location['hours_note'] }}</p>
            @endisset
        </dl>
    </div>

    <a href="{{ $location['directions'] }}" target="_blank" rel="noopener" class="btn btn-dark mt-6">Get directions <x-icon name="arrow-up-right" class="size-4" /></a>
</article>
