<x-frontend.shell
    title="Post - {{ $post->title }}"
    meta-description="Ontdek de nieuwste artikels en categorieën op onze blog."
>
    {{-- Laatste nieuws marquee --}}
    <x-frontend.home.latest-news-marquee :latest-posts="$latestPosts" />

    <x-frontend.posts.single-content-blade :post="$post"/>
</x-frontend.shell>
