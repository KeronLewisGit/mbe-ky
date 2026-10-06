<x-layout title="Search">
    <section class="bg-ink text-white">
        <div class="wrap py-14 sm:py-20">
            <p class="eyebrow !text-brand-300">Search</p>
            <h1 class="h-display mt-4">{{ $query !== '' ? 'Results for “'.$query.'”' : 'What are you looking for?' }}</h1>
            <form action="{{ route('search') }}" class="mt-8 flex max-w-2xl items-center gap-2 rounded-full bg-white p-1.5 pl-5">
                <x-icon name="search" class="size-5 shrink-0 text-muted" />
                <input type="search" name="q" value="{{ $query }}" placeholder="Search services, rates, FAQs…" class="w-full border-0 bg-transparent py-2 text-ink outline-none placeholder:text-muted/60" required>
                <button class="btn btn-primary">Search</button>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="wrap max-w-4xl">
            @if ($query === '')
                <p class="text-muted">Try “sailing dates”, “passport photos”, “duty” or “business cards”.</p>
            @elseif ($results->isEmpty())
                <div class="card text-center">
                    <span class="icon-tile mx-auto"><x-icon name="search-x" /></span>
                    <h2 class="mt-4 font-display text-2xl font-bold">Nothing matched “{{ $query }}”.</h2>
                    <p class="mt-2 text-muted">Check the spelling or try a broader term. You can also <a href="{{ route('contact') }}" class="link">ask us directly</a>.</p>
                </div>
            @else
                <p class="text-sm text-muted">{{ $results->count() }} {{ Str::plural('result', $results->count()) }}</p>
                <ul class="mt-6 space-y-3">
                    @foreach ($results as $result)
                        <li>
                            <a href="{{ $result['url'] }}" class="group flex items-start justify-between gap-6 rounded-2xl bg-white p-5 ring-1 ring-line/70 transition hover:shadow-card">
                                <div>
                                    <span class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700">{{ $result['type'] }}</span>
                                    <h2 class="mt-2 font-display text-lg font-bold group-hover:text-brand-700">{{ $result['title'] }}</h2>
                                    <p class="mt-1 text-sm text-muted">{{ $result['text'] }}</p>
                                </div>
                                <x-icon name="arrow-right" class="mt-1 size-5 shrink-0 text-muted transition group-hover:translate-x-1 group-hover:text-brand-700" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-layout>
