@php
    $mbe = config('mbe');
    $bag = 'print-quote';
    $failed = $errors->getBag($bag)->any();
    $old = fn (string $key, $default = null) => $failed ? old($key, $default) : $default;
    // Reopen the step that holds the first invalid field.
    $stepFields = [
        ['document', 'colour_single', 'colour_double', 'bw_single', 'bw_double', 'sets'],
        ['paper_size', 'paper_size_other', 'stock', 'stock_other'],
        ['binding', 'cover', 'cover_colour', 'full_bleed', 'folding', 'proofing'],
        ['first_name', 'last_name', 'company', 'phone', 'email', 'turnaround', 'delivery', 'instructions', 'attachment'],
    ];
    $startStep = 0;
    foreach ($stepFields as $index => $fields) {
        if ($errors->getBag($bag)->hasAny($fields)) {
            $startStep = $index;
            break;
        }
    }
    $steps = ['Your document', 'Paper', 'Finishing', 'Your details'];
@endphp
<x-layout title="Printing Services" description="Digital printing, binding, laminating and finishing in Grand Cayman. Request a print quote online from Mail Boxes Etc.">
    <x-page-hero eyebrow="Printing services" title="Print that leaves a lasting impression."
        lead="From business cards and brochures to postcards and presentation booklets, what you leave with your prospect or customer leaves a lasting impression. Trust Mail Boxes Etc. to deliver quality printing with a fast turnaround."
        image="print-centre.jpg" image-alt="The MBE print centre with digital presses">
        <a href="#form-print-quote" class="btn btn-primary">Get a quote</a>
        <span class="text-sm text-white/70">Colour printing from <strong class="text-white">CI${{ number_format($mbe['print_from'], 2) }}</strong> per page</span>
    </x-page-hero>

    {{-- Products --}}
    <section class="section">
        <div class="wrap">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="eyebrow">What we print</p>
                    <h2 class="h-section mt-4">Whether you need a few copies or a large presentation, we can handle the project.</h2>
                </div>
                <a href="{{ route('graphic-design') }}" class="link-arrow">Need it designed too? <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <ul class="mt-10 flex flex-wrap gap-2.5" data-reveal>
                @foreach ([...$mbe['print_products'], 'Laminating', 'Binding', 'Custom journals'] as $product)
                    <li class="rounded-full bg-white px-4 py-2 text-sm font-semibold ring-1 ring-line">{{ $product }}</li>
                @endforeach
            </ul>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['zap', 'Fast turnaround', '2–3 business days as standard, with next-day and same-day options when you are up against a deadline.'],
                    ['layers', 'Finished properly', 'Coil, double-wire, saddle-stitch and thermal binding, folding, full bleed and a choice of covers.'],
                    ['badge-check', 'Proof before print', 'Ask for an electronic proof by email or view a printed sample before the full run.'],
                ] as $i => [$icon, $title, $text])
                    <div class="card" data-reveal style="--reveal-delay: {{ $i * 80 }}ms">
                        <span class="icon-tile"><x-icon :name="$icon" /></span>
                        <h3 class="mt-5 font-display text-lg font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Quote form --}}
    <section class="section bg-white">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">Request a quote</p>
                <h2 class="h-section mt-4">Tell us what you need.</h2>
                <p class="lead mt-4">Answer a few questions and we&rsquo;ll get back to you with a quote.</p>
                <dl class="mt-8 space-y-4 text-sm">
                    <div class="flex items-center gap-4">
                        <span class="icon-tile"><x-icon name="mail" /></span>
                        <div><dt class="text-muted">Print team</dt><dd><a href="mailto:{{ $mbe['emails']['print'] }}" class="font-semibold">{{ $mbe['emails']['print'] }}</a></dd></div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="icon-tile"><x-icon name="phone" /></span>
                        <div><dt class="text-muted">Call</dt><dd><a href="tel:{{ $mbe['phone_href'] }}" class="font-semibold">{{ $mbe['phone'] }}</a></dd></div>
                    </div>
                </dl>
            </div>

            <div class="lg:col-span-8" data-reveal style="--reveal-delay: 120ms">
                <form id="form-print-quote" method="POST" action="{{ route('enquiries.store', $bag) }}" enctype="multipart/form-data" class="card scroll-mt-28 bg-paper"
                    x-data="stepper({{ count($steps) }}, {{ $startStep }})" novalidate @submit="if (! $el.checkValidity()) { $event.preventDefault(); $el.reportValidity(); }">
                    @csrf
                    <x-form-status :type="$bag" title="Quote request received." message="Our print team will review your project and email your quote." />

                    {{-- Progress --}}
                    <ol class="mb-8 grid grid-cols-4 gap-2">
                        @foreach ($steps as $index => $label)
                            <li>
                                <button type="button" class="block w-full text-left" @click="if ({{ $index }} < step) go({{ $index }})" :aria-current="step === {{ $index }} ? 'step' : null">
                                    <span class="block h-1.5 rounded-full transition" :class="step >= {{ $index }} ? 'bg-brand-600' : 'bg-line'"></span>
                                    <span class="mt-2 hidden text-xs font-semibold sm:block" :class="step >= {{ $index }} ? 'text-ink' : 'text-muted'">{{ $index + 1 }}. {{ $label }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ol>

                    {{-- Step 1 --}}
                    <div data-step="0" x-show="step === 0">
                        <h3 class="font-display text-2xl font-bold">Your digital printing</h3>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <x-field name="document" label="Describe your document" :bag="$bag" required class="sm:col-span-2" placeholder="e.g. CV, newsletter, menu, business stationery" />
                            <fieldset class="sm:col-span-2">
                                <legend class="label">How many pages?</legend>
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                    <x-field name="colour_single" type="number" :bag="$bag" min="0" hint="Colour, single sided" placeholder="0" />
                                    <x-field name="colour_double" type="number" :bag="$bag" min="0" hint="Colour, double sided" placeholder="0" />
                                    <x-field name="bw_single" type="number" :bag="$bag" min="0" hint="Black & white, single sided" placeholder="0" />
                                    <x-field name="bw_double" type="number" :bag="$bag" min="0" hint="Black & white, double sided" placeholder="0" />
                                </div>
                            </fieldset>
                            <x-field name="sets" type="number" label="How many sets would you like?" :bag="$bag" required min="1" :value="1" />
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div data-step="1" x-show="step === 1" x-cloak x-data="{ size: @js($old('paper_size', 'Letter')), stock: @js($old('stock', 'Standard copy (20lb)')) }">
                        <h3 class="font-display text-2xl font-bold">Paper and card</h3>
                        <fieldset class="mt-5">
                            <legend class="label">What is the paper/card size? <span class="text-brand-600">*</span></legend>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                                @foreach ([
                                    'Half Letter' => ['5.5 × 8.5 in', 'h-9 w-7'],
                                    'Letter' => ['8.5 × 11 in', 'h-11 w-[2.1rem]'],
                                    'Legal' => ['8.5 × 14 in', 'h-14 w-[2.1rem]'],
                                    'Ledger' => ['11 × 17 in', 'h-14 w-11'],
                                    'Other' => ['Custom size', 'h-11 w-9 border-dashed'],
                                ] as $size => [$dims, $shape])
                                    <label class="option">
                                        <input type="radio" name="paper_size" value="{{ $size }}" class="sr-only" x-model="size">
                                        <span class="items-center text-center">
                                            <span class="flex h-16 items-end"><span class="block rounded-sm border-2 border-ink/70 bg-white {{ $shape }}"></span></span>
                                            <strong class="mt-2 font-semibold">{{ $size }}</strong>
                                            <span class="text-xs text-muted">{{ $dims }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                        <div class="mt-4" x-show="size === 'Other'" x-cloak>
                            <x-field name="paper_size_other" label="Other size, please specify" :bag="$bag" x-bind:required="size === 'Other'" />
                        </div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <x-field name="stock" type="select" label="What type of paper/card is required?" :bag="$bag" x-model="stock" :value="'Standard copy (20lb)'"
                                :options="['Standard copy (20lb)', 'Premium Copy (28lb)', 'Gloss (100lb)', '60Lb light cardstock matte', '100Lb Heavy cardstock gloss', '100Lb Heavy cardstock matte', 'Other']" />
                            <div x-show="stock === 'Other'" x-cloak>
                                <x-field name="stock_other" label="Other stock, please specify" :bag="$bag" />
                            </div>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div data-step="2" x-show="step === 2" x-cloak>
                        <h3 class="font-display text-2xl font-bold">Binding and finishing</h3>
                        <div class="mt-5 grid gap-4 sm:grid-cols-3">
                            <x-field name="binding" type="select" label="Binding type" :bag="$bag" :options="['N/A', 'Coil/Spiral', 'Double-wire', 'Saddle-stitch', 'Thermal (Uni-bind)', 'Thermal Steel Binding']" />
                            <x-field name="cover" type="select" label="Covers" :bag="$bag" :options="['N/A', 'Clear', 'Frosted', 'Printed']" />
                            <x-field name="cover_colour" type="select" label="Cover colour" :bag="$bag" :options="['N/A', 'White', 'White linen', 'Black (back only)', 'Black linen (back only)']" />
                        </div>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            @foreach ([
                                'full_bleed' => ['Full bleed?', ['No', 'Yes'], 'No'],
                                'folding' => ['Folding', ['None', 'Bi-fold', 'Tri-fold'], 'None'],
                            ] as $name => [$legend, $choices, $default])
                                <fieldset>
                                    <legend class="label">{{ $legend }}</legend>
                                    <div class="flex w-fit gap-1 rounded-full bg-white p-1 ring-1 ring-line">
                                        @foreach ($choices as $choice)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="{{ $name }}" value="{{ $choice }}" class="peer sr-only" @checked($old($name, $default) === $choice)>
                                                <span class="block rounded-full px-4 py-2 text-sm font-semibold text-muted transition peer-checked:bg-ink peer-checked:text-white">{{ $choice }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endforeach
                        </div>
                        <fieldset class="mt-5">
                            <legend class="label">Proofing</legend>
                            <div class="grid gap-3 sm:grid-cols-3">
                                @foreach ([
                                    'I would like an electronic proof emailed to me' => 'Electronic proof',
                                    'I would like to view a printed sample (fee applies)' => 'Printed sample',
                                    'No, I do not need a proof' => 'No proof needed',
                                ] as $value => $short)
                                    <label class="option">
                                        <input type="radio" name="proofing" value="{{ $value }}" class="sr-only" @checked($old('proofing', 'I would like an electronic proof emailed to me') === $value)>
                                        <span><strong class="font-semibold">{{ $short }}</strong><span class="text-xs text-muted">{{ $value }}</span></span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>

                    {{-- Step 4 --}}
                    <div data-step="3" x-show="step === 3" x-cloak>
                        <h3 class="font-display text-2xl font-bold">Your information</h3>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <x-field name="first_name" label="First name(s)" :bag="$bag" required autocomplete="given-name" />
                            <x-field name="last_name" label="Last name" :bag="$bag" required autocomplete="family-name" />
                            <x-field name="company" label="Company name" :bag="$bag" autocomplete="organization" />
                            <x-field name="phone" type="tel" label="Mobile" :bag="$bag" autocomplete="tel" />
                            <x-field name="email" type="email" label="Email" :bag="$bag" required autocomplete="email" class="sm:col-span-2" />
                            <x-field name="turnaround" type="select" label="How soon do you need it?" :bag="$bag"
                                :options="['2-3 Business days', 'Next business day (surcharge may apply)', 'Same business day (surcharge applies)', 'Other']" />
                            <x-field name="delivery" type="select" label="Do you need delivery?" :bag="$bag" :options="['No' => 'No', 'Yes' => 'Yes, delivery instructions below']" />
                            <x-field name="instructions" type="textarea" label="Additional instructions" :bag="$bag" class="sm:col-span-2" placeholder="Please tell us anything extra or special requests you may have" />
                            <div class="sm:col-span-2" x-data="fileField">
                                <span class="label">Upload your file <span class="font-normal text-muted">(helps us quote accurately)</span></span>
                                <label for="print-attachment" class="flex cursor-pointer flex-col items-center gap-2 rounded-2xl border-2 border-dashed border-line bg-white p-6 text-center text-sm text-muted transition hover:border-brand-400">
                                    <x-icon name="upload" class="size-6 text-brand-600" />
                                    <span class="font-semibold text-ink" x-text="name || 'Choose a file'">Choose a file</span>
                                    <span class="text-xs">PDF, TIF, JPEG, PNG, DOCX or EPUB, up to {{ \App\Support\Content::uploadLimitMb() }} MB</span>
                                </label>
                                <input id="print-attachment" type="file" name="attachment" accept=".pdf,.tif,.tiff,.jpg,.jpeg,.png,.docx,.epub" class="sr-only" @change="pick">
                                @if ($message = $errors->getBag($bag)->first('attachment'))
                                    <p class="field-error">{{ $message }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    <div class="mt-8 flex items-center justify-between gap-4 border-t border-line pt-6">
                        <button type="button" class="btn btn-ghost" @click="go(step - 1)" x-show="step > 0" x-cloak><x-icon name="arrow-left" class="size-4" /> Back</button>
                        <span x-show="step === 0" class="text-xs text-muted">Step 1 of {{ count($steps) }}</span>
                        <button type="button" class="btn btn-dark" @click="next()" x-show="step < steps - 1">Continue <x-icon name="arrow-right" class="size-4" /></button>
                        <button type="submit" class="btn btn-primary !px-6" x-show="step === steps - 1" x-cloak>Request my quote <x-icon name="arrow-right" class="size-4" /></button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-cta-band title="Not sure what you need?" text="Pop in with your file, or even just an idea. Our in-house design specialist will work with you from layout through to print.">
        <a href="{{ route('graphic-design') }}" class="btn bg-white !px-6 !py-3.5 !text-base text-ink hover:bg-ink hover:text-white">Graphic design</a>
        <a href="{{ route('contact') }}#locations" class="btn bg-brand-800/60 !px-6 !py-3.5 !text-base text-white ring-1 ring-white/25 hover:bg-brand-800">Find a store</a>
    </x-cta-band>
</x-layout>
