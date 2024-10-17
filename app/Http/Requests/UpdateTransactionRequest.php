<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            "created_at" => "nullable"| "date",
            "updated_at" => "nullable"| "date",

            "products" => "required"| "array",
            "products.*.name" => "required"| "string",
            "products.*.quantity" => "required"| "integer",
            "products.*.valor" => "required"| "numeric",

            "users" => "required"| "array",
            "users.*.name" => "required"| "string",
            "users.*.email" => "required"| "string",
            "users.*.birthday" => "required"| "date",

            "payment_method" => "required"| "in:CREDIT, DEBIT, MONEY",

            "blocked" => "required"| "boolean",
        ];
    }
}
