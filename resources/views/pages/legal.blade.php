<x-layout :title="$meta['title']" :description="$meta['summary']">
    <x-page-hero :eyebrow="$meta['group']" :title="$meta['title']" :lead="$meta['summary']" />

    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <nav class="top-28 space-y-6 lg:sticky" aria-label="Guides and policies">
                    @foreach (collect(config('mbe.legal'))->groupBy('group', preserveKeys: true) as $group => $docs)
                        <div>
                            <p class="mb-2 text-xs font-semibold tracking-[0.16em] text-muted uppercase">{{ $group }}</p>
                            <ul class="space-y-1">
                                @foreach ($docs as $docSlug => $doc)
                                    <li>
                                        <a href="{{ route('legal', $docSlug) }}" @class([
                                            'flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition',
                                            'bg-ink text-white' => $docSlug === $slug,
                                            'text-ink hover:bg-white hover:ring-1 hover:ring-line' => $docSlug !== $slug,
                                        ])>
                                            {{ $doc['title'] }}
                                            <x-icon name="arrow-right" class="size-4 shrink-0 opacity-50" />
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                    <button type="button" onclick="window.print()" class="btn btn-light w-full"><x-icon name="printer" class="size-4" /> Print this page</button>
                </nav>
            </aside>

            <article class="card lg:col-span-8">
                <div class="prose-mbe">{!! $body !!}</div>
                <p class="mt-10 border-t border-line pt-6 text-sm text-muted">
                    Questions about this document? <a href="{{ route('contact') }}" class="link">Contact us</a> or call <a href="tel:{{ config('mbe.phone_href') }}" class="link">{{ config('mbe.phone') }}</a>.
                </p>
            </article>
        </div>
    </section>
</x-layout>
