<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Aturan validasi sama, tetapi pastikan field yang di-update bisa diterima
        return [
            'title' => 'sometimes|string|max:255', // Gunakan 'sometimes' agar user tidak wajib kirim semua data
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ];
    }
}
