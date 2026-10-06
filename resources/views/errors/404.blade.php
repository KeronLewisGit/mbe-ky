<x-layout title="Page not found">
    <section class="relative overflow-hidden">
        <div class="grid-paper absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>
        <div class="wrap relative py-24 text-center sm:py-32">
            <p class="font-display text-8xl font-extrabold text-brand-600 sm:text-9xl">404</p>
            <h1 class="h-section mt-4">Return to sender.</h1>
            <p class="lead mx-auto mt-4 max-w-md">We couldn&rsquo;t find that page. It may have moved when we rebuilt the site.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
                <a href="{{ route('search') }}" class="btn btn-light"><x-icon name="search" class="size-4" /> Search the site</a>
            </div>
        </div>
    </section>
</x-layout>
