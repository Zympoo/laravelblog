<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Vrije zoekterm
            'q' => ['nullable', 'string', 'max:100'],

            // Filters
            'author' => ['nullable', 'integer', 'min:1'],
            'category' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],

            // Sortering
            'sort' => ['nullable', Rule::in(['id', 'title', 'slug', 'created_at', 'published_at', 'is_published'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],

            // Pagination
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),
            'author' => $v['author'] ?? null,
            'category' => $v['category'] ?? null,
            'status' => $v['status'] ?? null,
            'trashed' => $this->input('trashed', ''),
            'sort' => (string) ($v['sort'] ?? 'created_at'),
            'dir' => (string) ($v['dir'] ?? 'desc'),
            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}
