<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Enquiry;
use App\Models\Post;
use App\Models\Sailing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mbe.ky'],
            ['name' => 'MBE Store Admin', 'password' => 'mbe-demo-2026'],
        );

        $this->posts();
        $this->sailings();
        $this->announcements();
        $this->sampleEnquiries();
    }

    /** Current notices from the MBE Cayman Facebook page (posted 25 Aug and 3 Oct 2026). */
    protected function announcements(): void
    {
        Announcement::updateOrCreate(['message' => 'Harbour Walk has a new opening schedule from 1 October.'], [
            'link_text' => 'See store hours', 'link_url' => '/contact-us#locations',
        ]);
        Announcement::updateOrCreate(['message' => 'Prime Big Deal Days, Oct 6–7: let E-box ship your deals.'], [
            'link_text' => 'Get your U.S. address', 'link_url' => '/e-box', 'starts_on' => '2026-10-03', 'ends_on' => '2026-10-07',
        ]);
    }

    /** News posts carried over from mbe.ky/blog. */
    protected function posts(): void
    {
        $categories = [
            'refer-a-friend-and-earn' => 'Offers',
            'print-from-phone' => 'New service',
            'e-box-customers-pay-0-sales-tax-on-amazon-purchases' => 'E-box',
            'harbour-walk-store-is-now-open' => 'Store news',
        ];
        $images = [
            'refer-a-friend-and-earn' => 'refer-a-friend.jpg',
            'print-from-phone' => 'photo-gift.jpg',
            'e-box-customers-pay-0-sales-tax-on-amazon-purchases' => 'zero-sales-tax.jpg',
            'harbour-walk-store-is-now-open' => 'harbour-walk-opening.jpg',
        ];

        foreach (json_decode(file_get_contents(database_path('data/posts.json')), true) as $post) {
            $body = trim($post['body']);
            $body = str_starts_with($body, '<') ? $body : '<p>'.$body;
            $body = str_ends_with($body, '>') ? $body : $body.'</p>';

            Post::updateOrCreate(['slug' => $post['slug']], [
                'title' => $post['title'],
                'category' => $categories[$post['slug']] ?? 'News',
                'image' => $images[$post['slug']] ?? null,
                'body' => $body,
                'published_at' => $post['published_at'],
            ]);
        }
    }

    /** Sailing schedule as published on mbe.ky/ocean-ship. */
    protected function sailings(): void
    {
        foreach ([
            ['2026-09-07', '2026-09-10', '2026-09-18'],
            ['2026-09-21', '2026-09-24', '2026-10-02'],
            ['2026-10-05', '2026-10-08', '2026-10-16'],
        ] as [$cutoff, $sailing, $inHand]) {
            Sailing::updateOrCreate(['sailing_date' => $sailing], ['cutoff_date' => $cutoff, 'in_hand_date' => $inHand]);
        }
    }

    /** Clearly-labelled sample submissions so the admin inbox is not empty in a demo. */
    protected function sampleEnquiries(): void
    {
        if (Enquiry::where('is_sample', true)->exists()) {
            return;
        }

        $samples = [
            ['contact', 'Sample Customer A', 'sample.a@example.com', '345-555-0101', 'On-Demand Address Service', 'new', 3,
                ['topic' => 'On-Demand Address Service', 'message' => 'I am visiting for two weeks and need to receive one courier delivery. How does the On-Demand Address Service work?']],
            ['mailbox', 'Sample Customer B', 'sample.b@example.com', '345-555-0102', 'Small Business mailbox · Camana Bay', 'in_progress', 20,
                ['plan' => 'small-business', 'location' => 'camana-bay', 'applicant_type' => 'Business', 'company' => 'Sample Trading Ltd.', 'recipients' => 'Two directors, accounts team', 'additional_recipients' => 1]],
            ['print-quote', 'Sample Customer C', 'sample.c@example.com', '345-555-0103', 'Restaurant menu × 150', 'new', 26,
                ['document' => 'Restaurant menu', 'colour_double' => 2, 'sets' => 150, 'paper_size' => 'Legal', 'stock' => '100Lb Heavy cardstock matte', 'binding' => 'N/A', 'full_bleed' => 'Yes', 'folding' => 'Bi-fold', 'proofing' => 'I would like an electronic proof emailed to me', 'turnaround' => '2-3 Business days', 'delivery' => 'No']],
            ['ocean-pre-alert', 'Sample Customer D', 'sample.d@example.com', null, 'Consolidate · 1Z999AA10123456784', 'new', 49,
                ['cby_number' => 'CBY 10482', 'tracking_number' => '1Z999AA10123456784', 'vendor' => 'Online furniture store', 'description' => 'Three-seat sofa, flat packed', 'invoice_value' => '1249.00', 'instruction' => 'Consolidate']],
            ['store-change', 'Sample Customer E', 'sample.e@example.com', null, 'Move collection to Harbour Walk', 'closed', 74,
                ['cby_number' => 'CBY 20931', 'location' => 'harbour-walk']],
            ['contact', 'Sample Customer F', 'sample.f@example.com', '345-555-0106', 'Shredding', 'closed', 120,
                ['topic' => 'Shredding', 'message' => 'We have about ten archive boxes of old statements to shred. Do you provide a destruction certificate?']],
        ];

        foreach ($samples as [$type, $name, $email, $phone, $summary, $status, $hoursAgo, $data]) {
            $enquiry = Enquiry::create([
                'type' => $type, 'name' => $name, 'email' => $email, 'phone' => $phone,
                'summary' => $summary, 'status' => $status, 'data' => $data, 'is_sample' => true,
            ]);
            $enquiry->forceFill(['created_at' => now()->subHours($hoursAgo), 'updated_at' => now()->subHours($hoursAgo)])->saveQuietly();
        }
    }
}
