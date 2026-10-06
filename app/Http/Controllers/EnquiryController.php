<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Support\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EnquiryController extends Controller
{
    public const TOPICS = [
        'General enquiry', 'Mailboxes', 'On-Demand Address Service', 'E-box shipping', 'Ocean shipping',
        'Pack & Ship', 'Printing', 'Shredding', 'Photo booth rental', 'Graphic design',
    ];

    public function store(Request $request, string $type): RedirectResponse
    {
        abort_unless(isset(Enquiry::TYPES[$type]), 404);

        $back = url()->previous(route('contact')).'#form-'.$type;

        // Honeypot: bots fill the hidden "website" field. Pretend it worked.
        if ($request->filled('website')) {
            return redirect()->to($back)->with('enquiry_success', ['type' => $type, 'reference' => null]);
        }

        $validator = Validator::make($request->all(), $this->rules($type), [
            'terms.accepted' => 'Please accept the terms & conditions to continue.',
            'cby_number.regex' => 'Enter your CBY number, for example CBY 12345.',
            'attachment.required' => 'Please attach your invoice.',
        ], [
            'cby_number' => 'CBY number',
            'bw_single' => 'black & white single sided',
            'bw_double' => 'black & white double sided',
        ]);

        if ($validator->fails()) {
            return redirect()->to($back)->withErrors($validator, $type)->withInput($request->except('attachment'));
        }

        $data = $validator->validated();
        $name = $data['name'] ?? trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? ''));

        $attributes = [
            'type' => $type,
            'name' => $name,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'summary' => $this->summary($type, $data),
            'data' => Arr::except($data, ['name', 'first_name', 'last_name', 'email', 'phone', 'attachment', 'terms']),
        ];

        if ($file = $request->file('attachment')) {
            $attributes['attachment_path'] = $file->store('enquiries');
            $attributes['attachment_name'] = $file->getClientOriginalName();
        }

        $enquiry = Enquiry::create($attributes);

        return redirect()->to($back)->with('enquiry_success', [
            'type' => $type,
            'reference' => $enquiry->reference,
            'email' => $enquiry->email,
        ]);
    }

    protected function rules(string $type): array
    {
        $locations = Rule::in(array_keys(config('mbe.locations')));
        $count = ['nullable', 'integer', 'min:0', 'max:100000'];
        $maxUpload = 'max:'.(Content::uploadLimitMb() * 1024);
        $cby = ['required', 'string', 'max:20', 'regex:/^(CBY)?[\s#-]*\d{3,8}$/i'];

        return match ($type) {
            'contact' => [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:160'],
                'phone' => ['nullable', 'string', 'max:40'],
                'topic' => ['nullable', Rule::in(self::TOPICS)],
                'message' => ['required', 'string', 'min:5', 'max:4000'],
            ],
            'mailbox' => [
                'plan' => ['required', Rule::in(array_keys(config('mbe.mailbox.plans')))],
                'location' => ['required', $locations],
                'applicant_type' => ['required', Rule::in(['Individual', 'Business'])],
                'first_name' => ['required', 'string', 'max:80'],
                'last_name' => ['required', 'string', 'max:80'],
                'company' => ['nullable', 'required_if:applicant_type,Business', 'string', 'max:160'],
                'email' => ['required', 'email', 'max:160'],
                'phone' => ['required', 'string', 'max:40'],
                'recipients' => ['nullable', 'string', 'max:600'],
                'additional_recipients' => ['nullable', 'integer', 'min:0', 'max:20'],
                'attachment' => ['nullable', 'file', $maxUpload, 'mimes:pdf,jpg,jpeg,png'],
                'terms' => ['accepted'],
            ],
            'print-quote' => [
                'document' => ['required', 'string', 'max:200'],
                'colour_single' => $count,
                'colour_double' => $count,
                'bw_single' => $count,
                'bw_double' => $count,
                'sets' => ['required', 'integer', 'min:1', 'max:100000'],
                'paper_size' => ['required', Rule::in(['Half Letter', 'Letter', 'Legal', 'Ledger', 'Other'])],
                'paper_size_other' => ['nullable', 'required_if:paper_size,Other', 'string', 'max:120'],
                'stock' => ['nullable', 'string', 'max:80'],
                'stock_other' => ['nullable', 'string', 'max:120'],
                'binding' => ['nullable', 'string', 'max:80'],
                'cover' => ['nullable', 'string', 'max:80'],
                'cover_colour' => ['nullable', 'string', 'max:80'],
                'full_bleed' => ['nullable', Rule::in(['Yes', 'No'])],
                'folding' => ['nullable', Rule::in(['None', 'Bi-fold', 'Tri-fold'])],
                'proofing' => ['nullable', 'string', 'max:120'],
                'first_name' => ['required', 'string', 'max:80'],
                'last_name' => ['required', 'string', 'max:80'],
                'company' => ['nullable', 'string', 'max:160'],
                'phone' => ['nullable', 'string', 'max:40'],
                'email' => ['required', 'email', 'max:160'],
                'turnaround' => ['nullable', 'string', 'max:80'],
                'delivery' => ['nullable', Rule::in(['No', 'Yes'])],
                'instructions' => ['nullable', 'string', 'max:4000'],
                'attachment' => ['nullable', 'file', $maxUpload, 'mimes:pdf,tif,tiff,jpg,jpeg,png,docx,epub'],
            ],
            'ocean-pre-alert' => [
                'name' => ['required', 'string', 'max:120'],
                'cby_number' => $cby,
                'email' => ['required', 'email', 'max:160'],
                'tracking_number' => ['required', 'string', 'max:120'],
                'vendor' => ['nullable', 'string', 'max:120'],
                'description' => ['required', 'string', 'max:1000'],
                'invoice_value' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
                'instruction' => ['required', Rule::in(['Ship', 'Consolidate'])],
                'attachment' => ['required', 'file', $maxUpload, 'mimes:pdf,jpg,jpeg,png'],
            ],
            'store-change' => [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:160'],
                'cby_number' => $cby,
                'location' => ['required', $locations],
            ],
        };
    }

    protected function summary(string $type, array $data): string
    {
        $location = fn () => config('mbe.locations.'.($data['location'] ?? '').'.name', '');

        return match ($type) {
            'contact' => $data['topic'] ?? 'General enquiry',
            'mailbox' => config("mbe.mailbox.plans.{$data['plan']}.name").' mailbox · '.$location(),
            'print-quote' => $data['document'].' × '.$data['sets'],
            'ocean-pre-alert' => $data['instruction'].' · '.$data['tracking_number'],
            'store-change' => 'Move collection to '.$location(),
        };
    }
}
