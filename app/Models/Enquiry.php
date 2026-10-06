<?php

namespace App\Models;

use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    public const TYPES = [
        'contact' => ['label' => 'Contact message', 'prefix' => 'MSG', 'inbox' => 'general', 'icon' => 'message-square'],
        'mailbox' => ['label' => 'Mailbox application', 'prefix' => 'MBX', 'inbox' => 'general', 'icon' => 'mailbox'],
        'print-quote' => ['label' => 'Print quote request', 'prefix' => 'PRT', 'inbox' => 'print', 'icon' => 'printer'],
        'ocean-pre-alert' => ['label' => 'Ocean pre-alert', 'prefix' => 'OCN', 'inbox' => 'ocean', 'icon' => 'ship'],
        'store-change' => ['label' => 'E-box store change', 'prefix' => 'CBY', 'inbox' => 'ebox', 'icon' => 'map-pin'],
    ];

    public const STATUSES = [
        'new' => 'New',
        'in_progress' => 'In progress',
        'closed' => 'Closed',
    ];

    protected $fillable = [
        'type', 'reference', 'name', 'email', 'phone', 'summary', 'data',
        'attachment_path', 'attachment_name', 'status', 'notes', 'is_sample',
    ];

    protected function casts(): array
    {
        return ['data' => 'array', 'is_sample' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::created(function (Enquiry $enquiry) {
            $prefix = self::TYPES[$enquiry->type]['prefix'] ?? 'MBE';
            $enquiry->updateQuietly(['reference' => sprintf('%s-%05d', $prefix, 1000 + $enquiry->id)]);
        });
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type]['label'] ?? Str::headline($this->type);
    }

    public function typeIcon(): string
    {
        return self::TYPES[$this->type]['icon'] ?? 'inbox';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? Str::headline($this->status);
    }

    /** Store inbox this enquiry would be routed to. */
    public function inbox(): string
    {
        return config('mbe.emails.'.(self::TYPES[$this->type]['inbox'] ?? 'general'));
    }

    /** Submitted fields as label => value pairs for display. */
    public function details(): array
    {
        return collect($this->data ?? [])
            ->reject(fn ($value) => $value === null || $value === '' || $value === [])
            ->mapWithKeys(fn ($value, $key) => [
                Str::of($key)->replace('_', ' ')->ucfirst()->replace('Cby', 'CBY')->replace('Bw ', 'B&W ')->toString() => is_array($value) ? implode(', ', $value) : $value,
            ])
            ->all();
    }
}
