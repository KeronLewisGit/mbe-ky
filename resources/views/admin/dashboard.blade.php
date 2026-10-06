<x-admin title="Dashboard">
    <x-slot:actions>
        <a href="{{ route('admin.enquiries', ['status' => 'new']) }}" class="btn btn-dark !py-2.5">Review new enquiries</a>
    </x-slot:actions>

    <dl class="grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['New', $newCount, 'Waiting for a first reply', 'inbox', 'text-brand-600'],
            ['Open', $open, 'New or in progress', 'clock', 'text-amber-600'],
            ['Last 7 days', $thisWeek, 'Submissions received', 'trending-up', 'text-emerald-600'],
        ] as [$label, $value, $hint, $icon, $colour])
            <div class="card !p-6">
                <div class="flex items-center justify-between">
                    <dt class="text-sm font-medium text-muted">{{ $label }}</dt>
                    <x-icon :name="$icon" class="size-5 {{ $colour }}" />
                </div>
                <dd class="mt-2 font-display text-4xl font-extrabold">{{ $value }}</dd>
                <p class="mt-1 text-xs text-muted">{{ $hint }}</p>
            </div>
        @endforeach
    </dl>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="card !p-0 xl:col-span-2">
            <div class="flex items-center justify-between p-6 pb-4">
                <h2 class="font-display text-lg font-bold">Recent enquiries</h2>
                <a href="{{ route('admin.enquiries') }}" class="link-arrow">View all <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <ul class="divide-y divide-line border-t border-line">
                @forelse ($recent as $enquiry)
                    <li>
                        <a href="{{ route('admin.enquiry', $enquiry) }}" class="flex items-center gap-4 px-6 py-4 transition hover:bg-paper">
                            <span class="icon-tile !size-10 !rounded-xl"><x-icon :name="$enquiry->typeIcon()" class="size-[18px]" /></span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold">{{ $enquiry->name }} <span class="font-normal text-muted">&middot; {{ $enquiry->summary }}</span></p>
                                <p class="text-xs text-muted">{{ $enquiry->typeLabel() }} &middot; {{ $enquiry->created_at->diffForHumans() }}</p>
                            </div>
                            <x-status-badge :enquiry="$enquiry" />
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-10 text-center text-sm text-muted">No enquiries yet. Submissions from the website will appear here.</li>
                @endforelse
            </ul>
        </section>

        <div class="space-y-6">
            <section class="card !p-6">
                <h2 class="font-display text-lg font-bold">By type</h2>
                <ul class="mt-4 space-y-1">
                    @foreach ($byType as $type => $row)
                        <li>
                            <a href="{{ route('admin.enquiries', ['type' => $type]) }}" class="flex items-center gap-3 rounded-xl px-2 py-2 text-sm transition hover:bg-paper">
                                <x-icon :name="$row['meta']['icon']" class="size-[18px] text-muted" />
                                <span class="flex-1">{{ $row['meta']['label'] }}</span>
                                @if ($row['new'])
                                    <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-700">{{ $row['new'] }} new</span>
                                @endif
                                <span class="w-6 text-right font-semibold tabular-nums">{{ $row['total'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="card !p-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold">Sailings on the site</h2>
                    <a href="{{ route('admin.sailings') }}" class="link-arrow">Manage</a>
                </div>
                <ul class="mt-4 space-y-3 text-sm">
                    @forelse ($sailings as $sailing)
                        <li class="flex items-center justify-between gap-3">
                            <span class="flex items-center gap-2.5"><x-icon name="ship" class="size-4 text-brand-600" /> Sails {{ $sailing->sailing_date->format('D j M') }}</span>
                            <span class="text-xs text-muted">in hand {{ $sailing->in_hand_date->format('j M') }}</span>
                        </li>
                    @empty
                        <li class="text-muted">No upcoming sailings published. Add the next dates so customers can plan.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</x-admin>
