<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare input data before validation.
     *
     * - Checkbox 'verified' omzetten naar email_verified_at datetime/null
     */
    protected function prepareForValidation(): void
    {
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
        /**
         * Huidige user id uit route halen.
         * Bij resource routes is de parameter meestal {user}.
         */
        $userId = $this->route('user')?->id;

        return [
            // Basisvelden
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            /**
             * Email moet uniek blijven, behalve voor de huidige user.
             * Zonder ignore() zou "zelfde email bewaren" altijd falen.
             */
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            // Foreign key exists
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],

            // Boolean value komt binnen als "1"/"0"
            'is_active' => [
                'required',
                'boolean',
            ],

            // Checkbox is optioneel
            'verified' => [
                'nullable',
                'boolean',
            ],

            // Genormaliseerd veld dat we effectief opslaan
            'email_verified_at' => [
                'nullable',
                'date',
            ],

            /**
             * Password is bij update meestal optioneel.
             * Als het leeg is, mogen we het niet wijzigen.
             */
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
            ],

            /**
             * Confirmation alleen verplicht als password ingevuld is.
             * 'confirmed' verwacht veld password_confirmation.
             */
            'password_confirmation' => [
                'required_with:password',
                'same:password',
            ],
        ];
    }
}
