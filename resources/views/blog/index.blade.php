@php
    $featured = $posts->first();
@endphp
<x-layout title="News & Offers" description="News, offers and tips from Mail Boxes Etc. Cayman Islands.">
    <x-page-hero eyebrow="News &amp; offers" title="The latest from MBE Cayman."
        lead="New services, seasonal offers and tips for getting the most from E-box and your mailbox." />

    <section class="section">
        <div class="wrap">
            @if ($featured)
                <a href="{{ route('blog.show', $featured) }}" class="group grid overflow-hidden rounded-[2rem] bg-white shadow-card ring-1 ring-line/70 transition hover:shadow-lift lg:grid-cols-2" data-reveal>
                    <div class="aspect-[16/10] overflow-hidden bg-paper-deep lg:aspect-auto">
                        <img src="{{ asset('images/'.$featured->image) }}" alt="" class="size-full object-cover transition duration-700 group-hover:scale-105">
                    </div>
                    <div class="flex flex-col justify-center p-8 sm:p-12">
                        <p class="flex items-center gap-2 text-xs font-semibold text-muted">
                            <span class="rounded-full bg-brand-600 px-2.5 py-1 text-white">Latest</span>
                            <time datetime="{{ $featured->published_at->toDateString() }}">{{ $featured->published_at->format('j F Y') }}</time>
                        </p>
                        <h2 class="mt-4 font-display text-3xl font-bold sm:text-4xl">{{ $featured->title }}</h2>
                        <p class="mt-4 text-muted">{{ $featured->excerpt(220) }}</p>
                        <span class="link-arrow mt-6">Read more <x-icon name="arrow-right" class="size-4" /></span>
                    </div>
                </a>
            @endif

            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @foreach ($posts->skip(1) as $post)
                    <x-post-card :post="$post" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms" />
                @endforeach
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-ink p-6 text-white sm:p-8" data-reveal>
                <div>
                    <p class="font-display text-xl font-bold">Want offers as they happen?</p>
                    <p class="mt-1 text-sm text-white/60">We post seasonal E-box deals, holiday hours and new services on Facebook first.</p>
                </div>
                <a href="{{ config('mbe.links.facebook') }}" target="_blank" rel="noopener" class="btn btn-primary">Follow MBE Cayman <x-icon name="arrow-up-right" class="size-4" /></a>
            </div>
        </div>
    </section>
</x-layout>
