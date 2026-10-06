<x-admin title="Sailing dates">
    <div class="grid gap-6 xl:grid-cols-3">
        <section class="card !p-0 xl:col-span-2">
            <div class="p-6 pb-4">
                <h2 class="font-display text-lg font-bold">Published schedule</h2>
                <p class="mt-1 text-sm text-muted">Sailings stay on the <a href="{{ route('ocean') }}#sailings" target="_blank" class="link">Ocean Ship page</a> until their in-hand date has passed.</p>
            </div>
            <div class="overflow-x-auto border-t border-line">
                <table class="table-clean min-w-[40rem]">
                    <thead><tr><th>Cut-off</th><th>Sails from Miami</th><th>In hand</th><th></th><th></th></tr></thead>
                    <tbody>
                        @forelse ($sailings as $sailing)
                            @php($past = $sailing->in_hand_date->lt(today()))
                            <tr @class(['opacity-50' => $past])>
                                <td colspan="3" class="!p-0">
                                    <form id="sailing-{{ $sailing->id }}" method="POST" action="{{ route('admin.sailings.update', $sailing) }}" class="grid grid-cols-3 gap-2 p-2">
                                        @csrf
                                        @method('PATCH')
                                        @foreach (['cutoff_date', 'sailing_date', 'in_hand_date'] as $field)
                                            <input type="date" name="{{ $field }}" value="{{ $sailing->$field->toDateString() }}" class="input !py-2 !text-sm" aria-label="{{ Str::headline($field) }}" required>
                                        @endforeach
                                    </form>
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $past ? 'bg-paper text-muted ring-1 ring-line' : 'bg-emerald-50 text-emerald-800' }}">{{ $past ? 'Archived' : 'Live' }}</span>
                                </td>
                                <td class="text-right whitespace-nowrap">
                                    <button form="sailing-{{ $sailing->id }}" class="btn btn-light !px-3.5 !py-2">Save</button>
                                    <form method="POST" action="{{ route('admin.sailings.destroy', $sailing) }}" class="inline" onsubmit="return confirm('Remove this sailing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-grid size-9 place-items-center rounded-full align-middle text-muted transition hover:bg-brand-50 hover:text-brand-700" aria-label="Remove sailing"><x-icon name="trash-2" class="size-4" /></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="!py-12 text-center text-muted">No sailings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card !p-6">
            <h2 class="font-display text-lg font-bold">Add a sailing</h2>
            <form method="POST" action="{{ route('admin.sailings.store') }}" class="mt-4 space-y-4">
                @csrf
                <label class="block"><span class="label">Cut-off for customer to instruct to ship</span><input type="date" name="cutoff_date" value="{{ old('cutoff_date') }}" class="input" required></label>
                <label class="block"><span class="label">Expected sailing date from Miami</span><input type="date" name="sailing_date" value="{{ old('sailing_date') }}" class="input" required></label>
                <label class="block"><span class="label">In-hand date in Cayman</span><input type="date" name="in_hand_date" value="{{ old('in_hand_date') }}" class="input" required></label>
                <button class="btn btn-primary w-full"><x-icon name="plus" class="size-4" /> Publish sailing</button>
            </form>
        </section>
    </div>
</x-admin>
