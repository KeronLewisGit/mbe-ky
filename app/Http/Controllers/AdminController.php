<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Enquiry;
use App\Models\Sailing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        return Auth::check() ? redirect()->route('admin.dashboard') : view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those details do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard(): View
    {
        $counts = Enquiry::selectRaw('type, status, count(*) as total')->groupBy('type', 'status')->get();

        return view('admin.dashboard', [
            'byType' => collect(Enquiry::TYPES)->map(fn ($meta, $type) => [
                'meta' => $meta,
                'total' => $counts->where('type', $type)->sum('total'),
                'new' => $counts->where('type', $type)->where('status', 'new')->sum('total'),
            ]),
            'open' => $counts->where('status', '!=', 'closed')->sum('total'),
            'newCount' => $counts->where('status', 'new')->sum('total'),
            'thisWeek' => Enquiry::where('created_at', '>=', now()->subDays(7))->count(),
            'recent' => Enquiry::latest()->take(8)->get(),
            'sailings' => Sailing::upcoming()->take(3)->get(),
        ]);
    }

    public function enquiries(Request $request): View
    {
        $enquiries = Enquiry::query()
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('reference', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.enquiries', ['enquiries' => $enquiries]);
    }

    public function enquiry(Enquiry $enquiry): View
    {
        return view('admin.enquiry', ['enquiry' => $enquiry]);
    }

    public function updateEnquiry(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $enquiry->update($request->validate([
            'status' => ['required', Rule::in(array_keys(Enquiry::STATUSES))],
            'notes' => ['nullable', 'string', 'max:4000'],
        ]));

        return back()->with('status', 'Enquiry updated.');
    }

    public function destroyEnquiry(Enquiry $enquiry): RedirectResponse
    {
        if ($enquiry->attachment_path) {
            Storage::delete($enquiry->attachment_path);
        }

        $enquiry->delete();

        return redirect()->route('admin.enquiries')->with('status', "Enquiry {$enquiry->reference} deleted.");
    }

    public function attachment(Enquiry $enquiry): StreamedResponse
    {
        abort_unless($enquiry->attachment_path && Storage::exists($enquiry->attachment_path), 404);

        return Storage::download($enquiry->attachment_path, $enquiry->attachment_name);
    }

    public function sailings(): View
    {
        return view('admin.sailings', ['sailings' => Sailing::orderByDesc('sailing_date')->get()]);
    }

    public function storeSailing(Request $request): RedirectResponse
    {
        Sailing::create($this->sailingData($request));

        return back()->with('status', 'Sailing added. It is now live on the Ocean Ship page.');
    }

    public function updateSailing(Request $request, Sailing $sailing): RedirectResponse
    {
        $sailing->update($this->sailingData($request));

        return back()->with('status', 'Sailing updated.');
    }

    public function destroySailing(Sailing $sailing): RedirectResponse
    {
        $sailing->delete();

        return back()->with('status', 'Sailing removed.');
    }

    public function announcements(): View
    {
        return view('admin.announcements', [
            'announcements' => Announcement::latest('id')->get(),
            'live' => Announcement::current(),
        ]);
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        Announcement::create($request->validate([
            'message' => ['required', 'string', 'max:140'],
            'link_text' => ['nullable', 'string', 'max:40', 'required_with:link_url'],
            'link_url' => ['nullable', 'string', 'max:255', 'regex:/^(\/|https:\/\/)/'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
        ], ['link_url.regex' => 'Links must start with / (a page on this site) or https://.']));

        return back()->with('status', 'Announcement published.');
    }

    public function toggleAnnouncement(Announcement $announcement): RedirectResponse
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        return back()->with('status', $announcement->is_active ? 'Announcement switched on.' : 'Announcement switched off.');
    }

    public function destroyAnnouncement(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return back()->with('status', 'Announcement removed.');
    }

    protected function sailingData(Request $request): array
    {
        return $request->validate([
            'cutoff_date' => ['required', 'date'],
            'sailing_date' => ['required', 'date', 'after_or_equal:cutoff_date'],
            'in_hand_date' => ['required', 'date', 'after_or_equal:sailing_date'],
        ]);
    }
}
