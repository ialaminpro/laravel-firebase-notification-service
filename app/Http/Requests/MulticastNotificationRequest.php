<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MulticastNotificationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tokens' => 'required|array',
            'tokens.*' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'extraData' => 'nullable|array',
        ];
    }
}
