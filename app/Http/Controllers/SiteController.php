<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! SolaraShield will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'elastomeric-cool-roofs' => [
                'title' => 'Elastomeric Cool Roofs',
                'summary' => 'High-reflectivity acrylic elastomeric systems that cut heat gain and extend membrane life.',
                'body' => 'SolaraShield applies white and light-tint elastomeric coatings engineered for Texas UV. We measure dry film thickness, reinforce seams, and document reflectance so property managers can track cool-roof performance season after season.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80',
            ],
            'silicone-membrane-restoration' => [
                'title' => 'Silicone Membrane Restoration',
                'summary' => 'High-solids silicone for ponding water, aging TPO/EPDM, and roofs that cannot be torn off.',
                'body' => 'When tear-off is disruptive or budget-prohibitive, silicone restoration seals aged membranes with excellent ponding resistance. We clean, prime, flash penetrations, and spray seamless silicone to manufacturer mil specs.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1600&q=80',
            ],
            'commercial-flat-roof-coating' => [
                'title' => 'Commercial Flat Roof Coating',
                'summary' => 'Warehouse, retail, multifamily, and campus roofs coated with sequenced crew logistics.',
                'body' => 'Large decks need phased access, safety, and weather windows. SolaraShield stages primers, base coats, and finish coats so occupied buildings stay online while coatings cure. Night and weekend work available for retail centers.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80',
            ],
            'metal-roof-coating' => [
                'title' => 'Metal Roof Coating',
                'summary' => 'Rust-inhibiting primers and reflective topcoats for standing-seam and screw-down metal.',
                'body' => 'Metal roofs fail at fasteners, seams, and oxidation first. We tighten or replace fasteners, treat rust, apply metal primers, and finish with elastomeric or silicone systems that restore reflectivity without a full panel replacement.',
                'image' => 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1600&q=80',
            ],
            'leak-repair-seam-reinforcement' => [
                'title' => 'Leak Repair & Seam Reinforcement',
                'summary' => 'Targeted repairs at flashings, seams, drains, and penetrations before full coating.',
                'body' => 'Coating over active leaks is a warranty killer. SolaraShield isolates wet areas, reinforces seams with fabric and mastic, rebuilds curb flashings, and only then applies finish systems that lock out water.',
                'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1600&q=80',
            ],
            'roof-maintenance-programs' => [
                'title' => 'Roof Maintenance Programs',
                'summary' => 'Twice-yearly inspections, drain clearing, and touch-up coating for portfolio properties.',
                'body' => 'Coatings last longest when drains stay clear and small breaches get patched early. Our maintenance plans include photo reports, mil checks on aging coats, and priority scheduling after Central Texas hail events.',
                'image' => 'https://images.unsplash.com/photo-1464146072230-91cabc968266?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
