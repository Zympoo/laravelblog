<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        /**
         * Input normalisatie vóór validatie.
         *
         * verified checkbox:
         * - niet aangevinkt => veld ontbreekt => boolean('verified') = false
         * - aangevinkt => 'verified' = "1" => boolean('verified') = true
         *
         * We zetten dit om naar email_verified_at, zodat:
         * - controller enkel 1 veld moet opslaan
         * - view simpel blijft
         */
        $this->merge([
            'email_verified_at' => $this->boolean('verified') ? now() : null,
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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            // Unique email voorkomt duplicates (belangrijk voor login systemen)
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            // exists voorkomt FK errors (role_id moet bestaan)
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],

            // boolean accepteert "1" en "0"
            'is_active' => [
                'required',
                'boolean',
            ],

            // checkbox input (mag ontbreken)
            'verified' => [
                'nullable',
                'boolean',
            ],

            // genormaliseerd veld
            'email_verified_at' => [
                'nullable',
                'date',
            ],

            // password + confirmation
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],

            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ];
    }
}
