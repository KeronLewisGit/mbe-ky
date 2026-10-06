<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PageController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
| Public site. URLs match the current mbe.ky structure so existing links,
| bookmarks and search rankings carry over.
*/
Route::get('/', [PageController::class, 'home'])->name('home');

Route::controller(PageController::class)->group(function () {
    Route::get('/mailboxes', 'show')->defaults('view', 'mailboxes')->name('mailboxes');
    Route::get('/virtual', 'show')->defaults('view', 'virtual')->name('virtual');
    Route::get('/physical', 'show')->defaults('view', 'physical')->name('physical');
    Route::get('/e-box', 'ebox')->name('ebox');
    Route::get('/ocean-ship', 'ocean')->name('ocean');
    Route::get('/pack-and-ship', 'show')->defaults('view', 'pack-ship')->name('pack-ship');
    Route::get('/printing-service', 'show')->defaults('view', 'printing')->name('printing');
    Route::get('/additional-mbe-services', 'show')->defaults('view', 'services')->name('services');
    Route::get('/graphic-design', 'show')->defaults('view', 'graphic-design')->name('graphic-design');
    Route::get('/contact-us', 'show')->defaults('view', 'contact')->name('contact');
    Route::get('/store-change-request-for-e-box-package-collection', 'show')->defaults('view', 'store-change')->name('store-change');
    Route::get('/search', 'search')->name('search');
    Route::get('/{slug}', 'legal')->whereIn('slug', array_keys(config('mbe.legal')))->name('legal');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/enquiries/{type}', [EnquiryController::class, 'store'])
    ->middleware('throttle:12,1')
    ->name('enquiries.store');

/*
| Staff admin.
*/
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');

Route::middleware('auth')->prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/', 'dashboard')->name('dashboard');
    Route::get('/enquiries', 'enquiries')->name('enquiries');
    Route::get('/enquiries/{enquiry}', 'enquiry')->name('enquiry');
    Route::patch('/enquiries/{enquiry}', 'updateEnquiry')->name('enquiry.update');
    Route::delete('/enquiries/{enquiry}', 'destroyEnquiry')->name('enquiry.destroy');
    Route::get('/enquiries/{enquiry}/attachment', 'attachment')->name('enquiry.attachment');
    Route::get('/sailings', 'sailings')->name('sailings');
    Route::post('/sailings', 'storeSailing')->name('sailings.store');
    Route::patch('/sailings/{sailing}', 'updateSailing')->name('sailings.update');
    Route::delete('/sailings/{sailing}', 'destroySailing')->name('sailings.destroy');
    Route::get('/announcements', 'announcements')->name('announcements');
    Route::post('/announcements', 'storeAnnouncement')->name('announcements.store');
    Route::patch('/announcements/{announcement}', 'toggleAnnouncement')->name('announcements.toggle');
    Route::delete('/announcements/{announcement}', 'destroyAnnouncement')->name('announcements.destroy');
});

// Blog posts used to live at the site root on WordPress (e.g. /print-from-phone/).
Route::fallback(function () {
    $post = Post::where('slug', trim(request()->path(), '/'))->first();

    abort_unless($post, 404);

    return redirect()->route('blog.show', $post, 301);
});
