<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Enquiry;
use App\Models\Sailing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public static function publicPages(): array
    {
        return [
            'home' => ['/', 'Shop the world.'],
            'mailboxes' => ['/mailboxes', 'On-Demand Address Service'],
            'virtual' => ['/virtual', 'Anytime Mailbox'],
            'physical' => ['/physical', 'Physical Mailbox application'],
            'e-box' => ['/e-box', 'How long does it take to get my orders?'],
            'ocean ship' => ['/ocean-ship', 'Ocean freight estimator'],
            'pack and ship' => ['/pack-and-ship', 'Send a parcel'],
            'printing' => ['/printing-service', 'Request my quote'],
            'additional services' => ['/additional-mbe-services', 'Photo Booth Rental'],
            'graphic design' => ['/graphic-design', 'Designed and printed under one roof.'],
            'contact' => ['/contact-us', 'oceanship@mbe.ky'],
            'store change' => ['/store-change-request-for-e-box-package-collection', 'Limit of two changes per calendar year'],
            'blog' => ['/blog', 'Refer a Friend and Earn $$$'],
            'blog post' => ['/blog/harbour-walk-store-is-now-open', 'The ribbon is cut'],
            'user guide' => ['/user-guide', 'Before you begin'],
            'e-box terms' => ['/terms-and-conditions', 'THE AGREEMENT'],
            'virtual mailbox privacy' => ['/virtual-mailbox-privacy', 'Privacy Policy'],
            'search' => ['/search?q=sailing', 'Ocean Ship'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_page_renders(string $url, string $expected): void
    {
        $this->get($url)->assertOk()->assertSee($expected);
    }

    public function test_unknown_page_returns_not_found(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('Return to sender.');
    }

    public function test_old_wordpress_post_urls_redirect_to_the_blog(): void
    {
        $this->get('/print-from-phone')->assertRedirect('/blog/print-from-phone')->assertStatus(301);
    }

    public function test_contact_form_stores_an_enquiry_and_shows_a_reference(): void
    {
        $response = $this->from('/contact-us')->post('/enquiries/contact', [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'topic' => 'Shredding',
            'message' => 'Do you shred archive boxes?',
        ]);

        $enquiry = Enquiry::where('email', 'test@example.com')->sole();

        $response->assertRedirect('/contact-us#form-contact');
        $this->assertSame('Shredding', $enquiry->summary);
        $this->assertMatchesRegularExpression('/^MSG-\d{5}$/', $enquiry->reference);
        $this->get('/contact-us')->assertSee($enquiry->reference);
    }

    public function test_invalid_submission_is_rejected_with_errors_in_its_own_bag(): void
    {
        $this->from('/contact-us')
            ->post('/enquiries/contact', ['name' => '', 'email' => 'not-an-email', 'message' => ''])
            ->assertRedirect('/contact-us#form-contact')
            ->assertSessionHasErrorsIn('contact', ['name', 'email', 'message']);

        $this->assertSame(0, Enquiry::where('is_sample', false)->count());
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        $this->post('/enquiries/contact', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'message' => 'Buy now', 'website' => 'https://spam.example',
        ])->assertSessionHas('enquiry_success');

        $this->assertDatabaseMissing('enquiries', ['email' => 'bot@example.com']);
    }

    public function test_mailbox_application_is_stored_with_a_plan_summary(): void
    {
        $this->post('/enquiries/mailbox', [
            'plan' => 'corporate', 'location' => 'harbour-walk', 'applicant_type' => 'Business',
            'first_name' => 'Test', 'last_name' => 'Owner', 'company' => 'Test Ltd.',
            'email' => 'owner@example.com', 'phone' => '345-555-0100', 'additional_recipients' => 2, 'terms' => '1',
        ])->assertSessionHas('enquiry_success');

        $enquiry = Enquiry::where('type', 'mailbox')->where('email', 'owner@example.com')->sole();

        $this->assertSame('Test Owner', $enquiry->name);
        $this->assertSame('Corporate mailbox · Harbour Walk', $enquiry->summary);
        $this->assertSame('Test Ltd.', $enquiry->data['company']);
    }

    public function test_business_mailbox_application_requires_a_company_and_terms(): void
    {
        $this->post('/enquiries/mailbox', [
            'plan' => 'personal', 'location' => 'camana-bay', 'applicant_type' => 'Business',
            'first_name' => 'Test', 'last_name' => 'Owner', 'email' => 'owner@example.com', 'phone' => '345-555-0100',
        ])->assertSessionHasErrorsIn('mailbox', ['company', 'terms']);
    }

    public function test_print_quote_request_is_stored(): void
    {
        $this->post('/enquiries/print-quote', [
            'document' => 'Event flyer', 'colour_single' => 1, 'sets' => 250, 'paper_size' => 'Letter',
            'first_name' => 'Test', 'last_name' => 'Planner', 'email' => 'planner@example.com',
        ])->assertSessionHas('enquiry_success');

        $this->assertDatabaseHas('enquiries', ['type' => 'print-quote', 'summary' => 'Event flyer × 250']);
    }

    public function test_ocean_pre_alert_requires_and_stores_the_invoice(): void
    {
        Storage::fake();

        $payload = [
            'name' => 'Test Shipper', 'cby_number' => 'CBY 12345', 'email' => 'shipper@example.com',
            'tracking_number' => '1Z999', 'description' => 'Dining table', 'invoice_value' => '899.50', 'instruction' => 'Consolidate',
        ];

        $this->post('/enquiries/ocean-pre-alert', $payload)->assertSessionHasErrorsIn('ocean-pre-alert', ['attachment']);

        $this->post('/enquiries/ocean-pre-alert', $payload + [
            'attachment' => UploadedFile::fake()->create('invoice.pdf', 120, 'application/pdf'),
        ])->assertSessionHas('enquiry_success');

        $enquiry = Enquiry::where('type', 'ocean-pre-alert')->where('email', 'shipper@example.com')->sole();

        $this->assertSame('invoice.pdf', $enquiry->attachment_name);
        Storage::assertExists($enquiry->attachment_path);
    }

    public function test_store_change_request_validates_the_cby_number(): void
    {
        $this->post('/enquiries/store-change', [
            'name' => 'Test Customer', 'email' => 'cby@example.com', 'cby_number' => 'hello', 'location' => 'harbour-walk',
        ])->assertSessionHasErrorsIn('store-change', ['cby_number']);

        $this->post('/enquiries/store-change', [
            'name' => 'Test Customer', 'email' => 'cby@example.com', 'cby_number' => 'CBY 20931', 'location' => 'harbour-walk',
        ])->assertSessionHas('enquiry_success');

        $this->assertDatabaseHas('enquiries', ['email' => 'cby@example.com', 'summary' => 'Move collection to Harbour Walk']);
    }

    public function test_admin_area_requires_sign_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/enquiries')->assertRedirect('/admin/login');
    }

    public function test_staff_can_sign_in_and_see_the_dashboard(): void
    {
        $this->post('/admin/login', ['email' => 'admin@mbe.ky', 'password' => 'wrong'])->assertSessionHasErrors('email');

        $this->post('/admin/login', ['email' => 'admin@mbe.ky', 'password' => 'mbe-demo-2026'])->assertRedirect('/admin');

        $this->get('/admin')->assertOk()->assertSee('Recent enquiries');
    }

    public function test_staff_can_update_and_delete_an_enquiry(): void
    {
        $enquiry = Enquiry::factory()->create();

        $this->actingAs(User::first())
            ->patch("/admin/enquiries/{$enquiry->id}", ['status' => 'in_progress', 'notes' => 'Called customer.'])
            ->assertSessionHas('status');

        $this->assertSame('in_progress', $enquiry->refresh()->status);
        $this->get("/admin/enquiries/{$enquiry->id}")->assertOk()->assertSee('Called customer.');
        $this->get('/admin/enquiries?status=in_progress')->assertOk()->assertSee($enquiry->reference);

        $this->delete("/admin/enquiries/{$enquiry->id}")->assertRedirect('/admin/enquiries');
        $this->assertModelMissing($enquiry);
    }

    public function test_a_sailing_added_by_staff_appears_on_the_ocean_ship_page(): void
    {
        $this->actingAs(User::first())->post('/admin/sailings', [
            'cutoff_date' => now()->addDays(10)->toDateString(),
            'sailing_date' => now()->addDays(13)->toDateString(),
            'in_hand_date' => now()->addDays(21)->toDateString(),
        ])->assertSessionHas('status');

        $sailing = Sailing::latest('id')->first();

        $this->get('/admin/sailings')->assertOk();
        $this->get('/ocean-ship')->assertSee($sailing->sailing_date->format('D j M Y'))->assertSee('Next sailing');
    }

    public function test_an_announcement_shows_site_wide_until_switched_off(): void
    {
        Announcement::query()->delete();

        $this->actingAs(User::first())->post('/admin/announcements', [
            'message' => 'Both stores close at 3pm on New Year’s Eve.',
            'link_text' => 'Store hours',
            'link_url' => '/contact-us#locations',
        ])->assertSessionHas('status');

        $announcement = Announcement::sole();

        $this->get('/admin/announcements')->assertOk()->assertSee('Showing now');
        $this->get('/pack-and-ship')->assertSee('Both stores close at 3pm on New Year’s Eve.');

        $this->patch("/admin/announcements/{$announcement->id}")->assertSessionHas('status');
        $this->get('/pack-and-ship')->assertDontSee('Both stores close at 3pm on New Year’s Eve.');
    }

    public function test_time_limited_announcements_take_priority_and_expire(): void
    {
        Announcement::query()->delete();
        Announcement::create(['message' => 'Open-ended notice']);
        $promo = Announcement::create(['message' => 'Seasonal promo', 'ends_on' => today(config('mbe.timezone'))->addDay()]);

        $this->assertTrue($promo->is(Announcement::current()));

        $promo->update(['ends_on' => today(config('mbe.timezone'))->subDay()]);

        $this->assertSame('Open-ended notice', Announcement::current()->message);
    }
}
