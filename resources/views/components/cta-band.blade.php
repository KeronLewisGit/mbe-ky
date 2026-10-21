@props([
    'title' => 'Ready when you are.',
    'text' => 'Open an E-box account online in minutes, or visit us at Camana Bay or Harbour Walk and we will set you up in person.',
])
<section class="wrap py-16 sm:py-24">
    <div class="relative overflow-hidden rounded-sm bg-brand-600 px-6 py-14 text-center text-white sm:px-16 sm:py-20" data-reveal>
        <div class="relative mx-auto max-w-2xl">
            <p class="font-display text-lg font-medium text-white/80">{{ config('mbe.tagline') }}</p>
            <h2 class="mt-3 font-display text-4xl font-bold sm:text-5xl">{{ $title }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-lg text-white/85">{{ $text }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    <a href="{{ config('mbe.links.ebox_signup') }}" target="_blank" rel="noopener" class="btn btn-lg btn-white">Sign up for E-box <x-icon name="arrow-up-right" class="size-4" /></a>
                    <a href="{{ route('contact') }}" class="btn btn-lg btn-dark">Contact a store</a>
                @endif
            </div>
        </div>
    </div>
</section>
