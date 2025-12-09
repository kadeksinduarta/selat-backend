<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // app/Http/Requests/ArticleUpdateRequest.php
    public function rules(): array
    {
        // Ambil ID artikel dari route
        $articleId = $this->route('article'); 

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // Saat update, 'slug' harus unik KECUALI untuk artikel yang sedang diupdate
            'slug' => 'required|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:articles,slug,' . $articleId,
            'is_published' => 'sometimes|boolean',
        ];
    }
}
