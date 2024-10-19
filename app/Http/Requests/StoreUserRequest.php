<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreUserRequest extends FormRequest
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
            "name" => "required|string",
            "email" => "required|string",
            "birthday" => "required|date|before:18 years",
            "created_at" => "nullable|date",
            "updated_at" => "nullable|date",
        ];
    }

    public function messages()
    {
        return [
            'birthday.before' => 'You must be at least 18 years old to create an account.',
        ];
    }
    
}
