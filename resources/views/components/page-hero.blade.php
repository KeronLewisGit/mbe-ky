@props(['eyebrow' => null, 'title', 'lead' => null, 'image' => null, 'imageAlt' => '', 'crumb' => null])
<section class="relative overflow-hidden bg-ink text-white">
    @if ($image)
        <img src="{{ asset('images/'.$image) }}" alt="" aria-hidden="true" class="absolute inset-0 size-full object-cover opacity-30 blur-sm">
    @endif
    <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/50"></div>
    <div class="wrap relative grid items-center gap-10 py-14 sm:py-20 lg:grid-cols-12">
        <div class="{{ $image ? 'lg:col-span-7' : 'lg:col-span-9' }}">
            <nav class="mb-6 flex items-center gap-2 text-xs font-medium text-white/50" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                @if ($crumb)
                    <span>/</span>
                    <a href="{{ $crumb[1] }}" class="transition hover:text-white">{{ $crumb[0] }}</a>
                @endif
                <span>/</span>
                <span class="text-white/80">{{ $title }}</span>
            </nav>
            @if ($eyebrow)
                <p class="eyebrow !text-brand-300">{{ $eyebrow }}</p>
            @endif
            <h1 class="h-display mt-4">{{ $title }}</h1>
            <span class="brand-rule"></span>
            @if ($lead)
                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-white/70">{{ $lead }}</p>
            @endif
            @if ($slot->isNotEmpty())
                <div class="mt-8 flex flex-wrap items-center gap-3">{{ $slot }}</div>
            @endif
        </div>
        @if ($image)
            <div class="relative lg:col-span-5">
                <div class="relative mx-auto aspect-[4/3] max-w-md overflow-hidden rounded-sm shadow-lift lg:ml-auto">
                    <img src="{{ asset('images/'.$image) }}" alt="{{ $imageAlt }}" class="size-full object-cover">
                    <div class="airmail absolute inset-x-0 bottom-0 h-1.5"></div>
                </div>
            </div>
        @endif
    </div>
</section>
