<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend;

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
            'search' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'search' => (string) ($v['search'] ?? ''),
        ];
    }
}
