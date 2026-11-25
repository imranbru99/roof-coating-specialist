@extends('layouts.app')
@section('title', 'Blog')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Blog</h2>
        <p class="lede">Field notes on cool roofs, silicone systems, and coating maintenance for Central Texas property owners.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    @if($post->cover_image)
                        <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" style="width:100%;height:160px;object-fit:cover;margin-bottom:.8rem;">
                    @endif
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>No posts yet.</p></div>
            @endforelse
        </div>
        <div style="margin-top:2rem">{{ $posts->links() }}</div>
    </div>
</section>
@endsection
