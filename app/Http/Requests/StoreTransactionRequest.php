<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            
            "user_id" => "required|exists:users,id",
            
            "payment_method" => "required|in:CREDIT,DEBIT,MONEY",
            "blocked" => "required|boolean",
            "products" => "required|array",
            "products.*.product_id" => "required|exists:products,id",
            "products.*.quantity" => "required|integer|min:1",
        ];
    }
}
