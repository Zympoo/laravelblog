<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Naam is verplicht.',
            'email.required' => 'E-mail is verplicht.',
            'email.email' => 'Geef een geldig e-mailadres op.',
            'message.required' => 'Bericht is verplicht.',
        ];
    }
}
