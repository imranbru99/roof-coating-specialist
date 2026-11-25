@extends('layouts.app')
@section('title', 'Roof Coating Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">SolaraShield designs and applies elastomeric cool roofs, silicone restorations, metal coatings, leak repairs, and maintenance programs across Austin and Central Texas.</p>
        <div class="grid-3">
            @foreach([
                ['elastomeric-cool-roofs','Elastomeric Cool Roofs','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80'],
                ['silicone-membrane-restoration','Silicone Membrane Restoration','https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80'],
                ['commercial-flat-roof-coating','Commercial Flat Roof Coating','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
                ['metal-roof-coating','Metal Roof Coating','https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1200&q=80'],
                ['leak-repair-seam-reinforcement','Leak Repair & Seam Reinforcement','https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1200&q=80'],
                ['roof-maintenance-programs','Roof Maintenance Programs','https://images.unsplash.com/photo-1464146072230-91cabc968266?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
