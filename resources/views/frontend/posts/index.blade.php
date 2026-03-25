<x-frontend.shell
    :filters="$filters"
    title="Posts"
    meta-description="Ontdek de nieuwste artikels en categorieën op onze blog."
>
    {{-- Laatste nieuws marquee --}}
    <x-frontend.home.latest-news-marquee :latest-posts="$latestPosts" />

    <div class="container mt-4">
        <h1 class="mb-4">Alle Artikels</h1>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        @if($post->media)
                            <img src="{{ asset('storage/' . $post->media->path()) }}" class="card-img-top" alt="{{ $post->title }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('frontend.posts.show', $post) }}" class="text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h5>

                            <p class="text-muted small mb-2">{{ $post->published_at->format('d-m-Y H:i') }}</p>

                            <p class="card-text mb-3">{{ Str::limit($post->excerpt ?? $post->body, 150) }}</p>

                            <div class="mt-auto">
                                @foreach($post->categories as $category)
                                    <a href="{{ route('frontend.categories.show', $category) }}" class="badge badge-danger me-1 mb-1">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>Er zijn nog geen artikels beschikbaar.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $posts->withQueryString()->links() }}
        </div>
    </div>
</x-frontend.shell>
