<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'qr_code_hash' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'qr_code_hash.required' => 'QR code hash is required.',
            'notes.max' => 'Notes cannot exceed 500 characters.',
        ];
    }
}
