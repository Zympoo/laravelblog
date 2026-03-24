@props([
    'post' => collect()
])
<div class="container mt-4" style="max-width: 720px;">
    {{-- Titel --}}
    <h1 class="h3 fw-bold mb-2">{{ $post->title }}</h1>

    {{-- Auteur --}}
    <p class="text-muted small mb-1">{{ $post->user->name }}</p>

    {{-- Datum & categorieën --}}
    <div class="mb-3 text-muted small">
        {{ $post->published_at->format('d-m-Y H:i') }}

        @foreach($post->categories as $category)
            <a href="{{ route('frontend.categories.show', $category) }}" class="badge badge-danger ms-2">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Afbeelding --}}
    @if($post->media)
        <img
            src="{{ asset('storage/' . $post->media->path()) }}"
            alt="{{ $post->title }}"
            class="img-fluid rounded mb-4"
        >
    @endif

    {{-- Body --}}
    <div class="mb-4">
        {!! $post->body !!}
    </div>

    {{-- Terug link --}}
    <a href="{{ route('frontend.posts.index') }}" class="text-primary d-inline-block mt-3">← Terug naar overzicht</a>
</div>
