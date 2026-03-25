<x-frontend.shell
    title="Categorie - {{ $category->name }}"
    meta-description="Ontdek de nieuwste artikels en categorieën op onze blog."
>
    {{-- Laatste nieuws marquee --}}
    <x-frontend.home.latest-news-marquee :latest-posts="$latestPosts" />

    <div class="container mt-4">
        <h1 class="h3 fw-bold mb-3">Categorie: {{ $category->name }}</h1>

        @if($category->description)
            <p class="mb-4">{{ $category->description }}</p>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        @if($post->media)
                            <img
                                src="{{ asset('storage/' . $post->media->path()) }}"
                                class="card-img-top"
                                alt="{{ $post->title }}"
                                style="height: 200px; object-fit: cover;"
                            >
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
                                @foreach($post->categories as $cat)
                                    <a href="{{ route('frontend.categories.show', $cat) }}" class="badge badge-danger me-1 mb-1">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>Er zijn nog geen posts in deze categorie.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

        <a href="{{ route('frontend.posts.index') }}" class="text-primary d-inline-block mt-3">← Terug naar posts overzicht</a>
    </div>
</x-frontend.shell>
