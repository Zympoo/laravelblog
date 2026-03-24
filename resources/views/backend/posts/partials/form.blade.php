@php
    /**
     * Deze partial wordt gedeeld tussen create en edit.
     *
     * old() wint altijd na validatiefouten.
     * $post kan null zijn bij create.
     */
@endphp

<div class="row g-3">

    {{-- ========================= TITLE ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Title</label>

        <input
            type="text"
            name="title"
            value="{{ old('title', $post?->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
        >

        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= SLUG ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Slug</label>

        <input
            type="text"
            name="slug"
            value="{{ old('slug', $post?->slug ?? '') }}"
            class="form-control @error('slug') is-invalid @enderror"
        >

        @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="form-text">
            Leave blank to generate automatically from the title.
        </div>
    </div>


    {{-- ========================= AUTHOR ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Author</label>

        <select
            name="user_id"
            class="form-select @error('user_id') is-invalid @enderror"
        >
            <option value="">No author</option>

            @foreach($authors as $author)
                <option
                    value="{{ $author->id }}"
                    @selected((string) old('user_id', $post?->user_id ?? '') === (string) $author->id)
                >
                    {{ $author->name }}
                </option>
            @endforeach
        </select>

        @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= STATUS ========================= --}}
    <div class="col-12 col-md-3">
        <label class="form-label">Status</label>

        <select
            name="is_published"
            class="form-select @error('is_published') is-invalid @enderror"
        >
            <option
                value="1"
                @selected((string) old('is_published', $post?->is_published ?? '0') === '1')
            >
                Published
            </option>

            <option
                value="0"
                @selected((string) old('is_published', $post?->is_published ?? '0') === '0')
            >
                Draft
            </option>
        </select>

        @error('is_published')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= PUBLISHED AT ========================= --}}
    <div class="col-12 col-md-3">
        <label class="form-label">Published at</label>

        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', optional($post?->published_at)->format('Y-m-d\TH:i')) }}"
            class="form-control @error('published_at') is-invalid @enderror"
        >

        @error('published_at')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="form-text">
            Leave blank to use the current moment when publishing.
        </div>
    </div>


    {{-- ========================= EXCERPT ========================= --}}
    <div class="col-12">
        <label class="form-label">Excerpt</label>

        <textarea
            name="excerpt"
            rows="3"
            class="form-control @error('excerpt') is-invalid @enderror"
        >{{ old('excerpt', $post?->excerpt ?? '') }}</textarea>

        @error('excerpt')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= BODY ========================= --}}
    <div class="col-12">
        <label class="form-label">Body</label>

        <textarea
            name="body"
            rows="10"
            class="form-control @error('body') is-invalid @enderror"
        >{{ old('body', $post?->body ?? '') }}</textarea>

        @error('body')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= IMAGE ========================= --}}
    <div class="col-12">
        <label class="form-label">Featured image</label>
        <input
            type="file"
            name="image"
            class="form-control @error('image') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.webp"
        >
        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            Allowed formats: jpg, jpeg, png, webp (max 4MB)
        </div>
        @if($post?->media)
            <div class="mt-3">
                <div class="small text-muted mb-2">
                    Current image
                </div>
                <img
                    src="{{ $post->media->url() }}"
                    class="img-thumbnail"
                    style="max-width:200px;"
                >
            </div>
        @endif
    </div>

    {{-- ========================= CATEGORIES ========================= --}}
    <div class="col-12">
        <label class="form-label d-block">Categories</label>

        @php
            $selectedCategories = old(
                'categories',
                isset($post) && $post
                    ? $post->categories
                        ->pluck('id')
                        ->map(fn ($id) => (string) $id)
                        ->all()
                    : []
            );
        @endphp

        <div class="row g-2">
            @foreach($categories as $category)
                <div class="col-12 col-md-4">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            id="category_{{ $category->id }}"
                            class="form-check-input @error('categories') is-invalid @enderror"
                            @checked(in_array((string) $category->id, array_map('strval', $selectedCategories), true))
                        >

                        <label
                            for="category_{{ $category->id }}"
                            class="form-check-label"
                        >
                            {{ $category->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        @error('categories')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @error('categories.*')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= FEATURED ========================= --}}
    <div class="col-12 col-md-3">
        <div class="form-check mt-4">
            <input
                type="checkbox"
                name="is_featured"
                id="is_featured"
                value="1"
                class="form-check-input @error('is_featured') is-invalid @enderror"
                @checked(old('is_featured', $post?->is_featured ?? false))
            >
            <label class="form-check-label" for="is_featured">
                Featured
            </label>
            @error('is_featured')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ========================= ACTIONS ========================= --}}
    <div class="col-12 d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">
            {{ $submitLabel ?? 'Save' }}
        </button>

        <a
            href="{{ route('backend.posts.index') }}"
            class="btn btn-outline-secondary"
        >
            Cancel
        </a>
    </div>

</div>
