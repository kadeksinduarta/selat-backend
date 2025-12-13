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
        return true;
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
        $article = $this->route('article'); 
        $articleId = $article ? $article->id : null;

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // Saat update, 'slug' harus unik KECUALI untuk artikel yang sedang diupdate
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:articles,slug,' . $articleId,
            'image' => 'nullable|image|max:2048',
            'is_published' => 'sometimes|boolean',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ];
    }
}
