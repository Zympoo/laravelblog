<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleIndexRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:100'],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],
            'sort' => ['nullable', Rule::in(['id', 'name', 'created_at'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),
            'trashed' => $v['trashed'] ?? null,
            'sort' => (string) ($v['sort'] ?? 'created_at'),
            'dir' => (string) ($v['dir'] ?? 'desc'),
            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}
