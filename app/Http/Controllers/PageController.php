<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Sailing;
use App\Support\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /** Static pages searchable from the header: [route, title, description, keywords]. */
    protected const PAGES = [
        ['mailboxes', 'Mailbox Services', 'Virtual and physical mailboxes with a Cayman Islands street address.', 'mailbox po box address on-demand street address customs'],
        ['virtual', 'Virtual Mailbox', 'View and manage your postal mail online, anytime, anywhere.', 'virtual digital mail scan forward shred anytime mailbox app'],
        ['physical', 'Physical Mailbox', 'Private mailboxes with a key from CI$185 a year.', 'physical mailbox key personal small business corporate price application'],
        ['ebox', 'E-box', 'Your own U.S. address for online shopping, delivered to Cayman in 5–7 business days.', 'e-box ebox us address amazon ebay online shopping air freight miami rates cby points duty customs faq'],
        ['ocean', 'Ocean Ship', 'Cost-effective ocean freight from Miami for large and over-sized cargo.', 'ocean ship freight cargo sailing dates cubic feet pre-alert consolidate furniture'],
        ['pack-ship', 'Pack & Ship', 'Expert packing and worldwide shipping with UPS, FedEx and DHL.', 'pack ship courier ups fedex dhl parcel postal packaging boxes international'],
        ['printing', 'Printing Services', 'Digital printing, binding and finishing with a fast turnaround.', 'printing print business cards brochures flyers booklets binding quote copies'],
        ['services', 'Additional Services', 'Print from Phone, document shredding and photo booth rental.', 'print from phone photos shredding shred documents photo booth rental events'],
        ['graphic-design', 'Graphic Design', 'In-house design from layout through to print.', 'graphic design designer layout tickets flyers brochures'],
        ['blog', 'News & Offers', 'Updates, offers and tips from MBE Cayman.', 'blog news offers refer friend'],
        ['contact', 'Contact & Locations', 'Camana Bay and Harbour Walk store details, hours and contacts.', 'contact phone email hours location map camana bay harbour walk'],
        ['store-change', 'Change e-box pick-up store', 'Move your e-box package collection between Camana Bay and Harbour Walk.', 'store change pick up location e-box collection'],
    ];

    public function home(): View
    {
        return view('pages.home', [
            'posts' => Post::latest('published_at')->take(3)->get(),
            'nextSailing' => Sailing::upcoming()->get()->first(fn (Sailing $s) => ! $s->cutoffPassed())
                ?? Sailing::upcoming()->first(),
        ]);
    }

    public function show(string $view): View
    {
        return view("pages.{$view}");
    }

    public function ebox(): View
    {
        return view('pages.ebox', ['faq' => Content::faq()]);
    }

    public function ocean(): View
    {
        return view('pages.ocean', ['sailings' => Sailing::upcoming()->get()]);
    }

    public function legal(string $slug): View
    {
        abort_unless($meta = config("mbe.legal.{$slug}"), 404);

        return view('pages.legal', ['slug' => $slug, 'meta' => $meta, 'body' => Content::legal($slug)]);
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->query('q'));
        $terms = collect(preg_split('/\s+/', Str::lower($query)))->filter(fn ($t) => strlen($t) > 1);
        $results = collect();

        if ($terms->isNotEmpty()) {
            $matches = fn (string $haystack) => $terms->every(fn ($t) => str_contains(Str::lower($haystack), $t));

            foreach (self::PAGES as [$route, $title, $description, $keywords]) {
                if ($matches("$title $description $keywords")) {
                    $results->push(['type' => 'Page', 'title' => $title, 'text' => $description, 'url' => route($route)]);
                }
            }

            foreach (config('mbe.legal') as $slug => $doc) {
                if ($matches($doc['title'].' '.$doc['summary'])) {
                    $results->push(['type' => 'Guide', 'title' => $doc['title'], 'text' => $doc['summary'], 'url' => route('legal', $slug)]);
                }
            }

            foreach (Content::faq() as $group) {
                foreach ($group['items'] as $item) {
                    if ($matches($item['q'].' '.strip_tags($item['a']))) {
                        $results->push(['type' => 'FAQ', 'title' => $item['q'], 'text' => Str::limit(strip_tags($item['a']), 160), 'url' => route('ebox').'?tab=faq#details']);
                    }
                }
            }

            foreach (Post::latest('published_at')->get() as $post) {
                if ($matches($post->title.' '.strip_tags($post->body))) {
                    $results->push(['type' => 'News', 'title' => $post->title, 'text' => $post->excerpt(), 'url' => route('blog.show', $post)]);
                }
            }
        }

        return view('pages.search', ['query' => $query, 'results' => $results]);
    }
}
