@props(['post'])
<a href="{{ route('blog.show', $post) }}" {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card ring-1 ring-line/70 transition hover:-translate-y-1 hover:shadow-lift']) }}>
    <div class="aspect-[16/10] overflow-hidden bg-paper-deep">
        @if ($post->image)
            <img src="{{ asset('images/'.$post->image) }}" alt="" class="size-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
        @endif
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="flex items-center gap-2 text-xs font-semibold text-muted">
            <span class="rounded-full bg-brand-50 px-2.5 py-1 text-brand-700">{{ $post->category }}</span>
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j M Y') }}</time>
        </p>
        <h3 class="mt-3 font-display text-xl leading-snug font-bold">{{ $post->title }}</h3>
        <p class="mt-2 text-sm text-muted">{{ $post->excerpt(120) }}</p>
        <span class="link-arrow mt-auto pt-5">Read more <x-icon name="arrow-right" class="size-4" /></span>
    </div>
</a>
