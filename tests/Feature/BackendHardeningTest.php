<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Enquiry;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackendHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_forms_never_redirect_back_to_another_site(): void
    {
        $this->from('https://evil.example/landing')->post('/enquiries/contact', [
            'name' => 'Test Customer', 'email' => 'test@example.com', 'message' => 'Hello there',
        ])->assertRedirect(route('contact').'#form-contact');
    }

    public function test_an_unknown_enquiry_type_is_not_found(): void
    {
        $this->post('/enquiries/made-up', ['name' => 'x'])->assertNotFound();
    }

    public function test_announcement_links_must_be_site_paths_or_https(): void
    {
        $this->actingAs(User::first());

        $this->post('/admin/announcements', ['message' => 'Hi', 'link_text' => 'Go', 'link_url' => 'javascript:alert(1)'])
            ->assertSessionHasErrors('link_url');

        $this->post('/admin/announcements', ['message' => 'Hi', 'link_url' => '/contact-us'])
            ->assertSessionHasErrors('link_text');

        $this->post('/admin/announcements', ['message' => 'Safe', 'link_text' => 'Go', 'link_url' => 'https://example.com'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Announcement::where('message', 'Safe')->exists());
    }

    public function test_a_sailing_cannot_depart_before_its_cutoff(): void
    {
        $this->actingAs(User::first())->post('/admin/sailings', [
            'cutoff_date' => '2026-11-10', 'sailing_date' => '2026-11-05', 'in_hand_date' => '2026-11-20',
        ])->assertSessionHasErrors('sailing_date');
    }

    public function test_attachments_are_only_downloadable_by_signed_in_staff(): void
    {
        Storage::fake();
        Storage::put('enquiries/invoice.pdf', 'pdf');
        $enquiry = Enquiry::factory()->create(['attachment_path' => 'enquiries/invoice.pdf', 'attachment_name' => 'invoice.pdf']);

        $this->get("/admin/enquiries/{$enquiry->id}/attachment")->assertRedirect('/admin/login');

        $this->actingAs(User::first())->get("/admin/enquiries/{$enquiry->id}/attachment")->assertOk()->assertDownload('invoice.pdf');
    }

    public function test_an_enquiry_without_an_attachment_returns_not_found_for_staff(): void
    {
        $enquiry = Enquiry::factory()->create();

        $this->actingAs(User::first())->get("/admin/enquiries/{$enquiry->id}/attachment")->assertNotFound();
    }

    public function test_deleting_an_enquiry_removes_its_uploaded_file(): void
    {
        Storage::fake();
        $file = UploadedFile::fake()->create('invoice.pdf', 10, 'application/pdf');
        $path = $file->store('enquiries');
        $enquiry = Enquiry::factory()->create(['attachment_path' => $path]);

        $this->actingAs(User::first())->delete("/admin/enquiries/{$enquiry->id}");

        Storage::assertMissing($path);
    }

    public function test_reseeding_does_not_reset_a_changed_staff_password(): void
    {
        $user = User::where('email', 'admin@mbe.ky')->sole();
        $user->update(['password' => 'a-new-password']);

        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(Hash::check('a-new-password', $user->refresh()->password));
        $this->assertSame(1, User::where('email', 'admin@mbe.ky')->count());
    }

    public function test_admin_enquiry_search_matches_references_and_names(): void
    {
        $enquiry = Enquiry::factory()->create(['name' => 'Zebediah Quill']);

        $this->actingAs(User::first())
            ->get('/admin/enquiries?q=Zebediah')->assertOk()->assertSee($enquiry->reference);
        $this->get('/admin/enquiries?q=no-such-person-anywhere')->assertOk()->assertDontSee($enquiry->reference);
    }

    public function test_search_ignores_empty_and_single_character_queries(): void
    {
        $this->get('/search?q=')->assertOk();
        $this->get('/search?q=a')->assertOk();
        $this->get('/search?q=ocean+ship')->assertOk()->assertSee('Ocean Ship');
    }

    public function test_an_expired_session_returns_to_the_form_with_the_input_kept(): void
    {
        Route::middleware('web')->post('/_expired', fn () => throw new TokenMismatchException);

        $this->from('/contact-us')
            ->post('/_expired', ['name' => 'Kept Name', 'message' => 'Still here', 'attachment' => 'x', '_token' => 'old'])
            ->assertRedirect('/contact-us')
            ->assertSessionHas('session_expired')
            ->assertSessionHasInput('name', 'Kept Name')
            ->assertSessionMissing('_old_input._token');
    }
}
