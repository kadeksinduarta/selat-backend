<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Terapkan logika otorisasi jika diperlukan (misalnya: harus admin)
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Harga harus angka, minimal 0, dan wajib
            'price' => 'required|numeric|min:0', 
            // Stok harus angka, minimal 0, dan wajib
            'stock' => 'required|integer|min:0', 
        ];
    }
}
