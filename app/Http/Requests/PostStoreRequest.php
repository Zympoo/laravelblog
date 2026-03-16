<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Input normaliseren vóór validatie.
     *
     * Wat doen we hier?
     * - title trimmen
     * - slug automatisch genereren als die leeg is
     * - categories altijd als array doorgeven
     * - published_at automatisch invullen als post gepubliceerd is
     * maar er nog geen datum werd meegegeven
     */
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        $slug = trim((string) $this->input('slug', ''));
        $isPublished = $this->boolean('is_published');
        $publishedAt = $this->input('published_at');

        $this->merge([
            'title' => $title,
            'slug' => $slug !== '' ? Str::slug($slug) : Str::slug($title),
            'is_published' => $isPublished,
            'categories' => $this->input('categories', []),
            'published_at' => $isPublished
                ? ($publishedAt ?: now())
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['array'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
