@extends('layouts.app')

@section('title', 'SolaraShield Coatings')
@section('meta_description', 'Reflective elastomeric and silicone roof coatings for commercial and residential flat roofs across Austin and Central Texas by SolaraShield.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>SolaraShield<br>Coatings</h1>
        <p>Reflective elastomeric, silicone, and acrylic roof coating systems that cool buildings, stop leaks, and extend flat-roof life across Austin and Central Texas.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a free roof survey</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">Explore coating systems</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Coating systems</h2>
        <p class="lede">From cool-roof acrylics to high-solids silicone — every SolaraShield system is matched to substrate, ponding risk, and Texas heat load.</p>
        <div class="grid-3">
            @foreach([
                ['elastomeric-cool-roofs','Elastomeric Cool Roofs','High-reflectivity acrylics that cut attic heat and energy bills.','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80'],
                ['silicone-membrane-restoration','Silicone Membrane Restoration','Seamless silicone for ponding water and aging single-ply roofs.','https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80'],
                ['commercial-flat-roof-coating','Commercial Flat Roof Coating','Multi-building campuses, warehouses, and retail roofs.','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why SolaraShield</h2>
        <p class="lede">Austin crews who treat substrate prep, adhesion testing, and mil thickness as one system — not a paint-over-leak shortcut.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Cool-roof science</h3>
                <p>High solar reflectance and thermal emittance coatings that keep Central Texas interiors cooler through peak summer.</p>
            </div>
            <div class="panel">
                <h3>Leak-first diagnostics</h3>
                <p>Moisture scans, core cuts when needed, and seam reinforcement before a gallon of coating leaves the truck.</p>
            </div>
            <div class="panel">
                <h3>ERP-run projects</h3>
                <p>Surveys, coating jobs, inventory drums, and invoicing live in one stack — clear timelines and clean paperwork.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the SolaraShield ERP used to schedule surveys, track coating inventory, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>22</strong> Active roofs</div>
                    <div class="metric"><strong>$386k</strong> Pipeline</div>
                    <div class="metric"><strong>14</strong> Roof surveys</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:92%"></span>
                    <span style="height:68%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & scheduling</li>
                    <li>Coating / primer inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the coating yard</h2>
        <p class="lede">Guides and field notes for property managers planning a roof coating project.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
