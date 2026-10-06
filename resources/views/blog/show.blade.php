<x-layout :title="$post->title" :description="$post->excerpt(155)">
    <article>
        <header class="bg-ink text-white">
            <div class="wrap max-w-4xl py-14 sm:py-20">
                <nav class="mb-6 flex items-center gap-2 text-xs font-medium text-white/50" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span>
                    <a href="{{ route('blog') }}" class="hover:text-white">News</a>
                </nav>
                <p class="flex flex-wrap items-center gap-3 text-xs font-semibold text-white/60">
                    <span class="rounded-full bg-brand-600 px-2.5 py-1 text-white">{{ $post->category }}</span>
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j F Y') }}</time>
                    <span>&middot; {{ $post->readingMinutes() }} min read</span>
                </p>
                <h1 class="h-display mt-5">{{ $post->title }}</h1>
            </div>
        </header>

        <div class="wrap max-w-4xl pb-16">
            @if ($post->image)
                <img src="{{ asset('images/'.$post->image) }}" alt="" class="-mt-8 aspect-[16/9] w-full rounded-3xl object-cover shadow-lift ring-1 ring-line">
            @endif
            <div class="prose-mbe mx-auto mt-10 max-w-2xl text-[17px]">{!! $post->body !!}</div>
            <div class="mx-auto mt-10 flex max-w-2xl flex-wrap gap-3 border-t border-line pt-8">
                <a href="{{ route('blog') }}" class="btn btn-light"><x-icon name="arrow-left" class="size-4" /> All news</a>
                <a href="{{ route('contact') }}" class="btn btn-dark">Ask us about this</a>
            </div>
        </div>
    </article>

    @if ($more->isNotEmpty())
        <section class="section border-t border-line bg-white">
            <div class="wrap">
                <h2 class="h-section">More from MBE Cayman</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($more as $other)
                        <x-post-card :post="$other" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout>
