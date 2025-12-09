<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleStoreRequest; 
use App\Http\Requests\ArticleUpdateRequest; 
use Illuminate\Http\JsonResponse; 


class ArticleController extends Controller
{
    // READ - LIST (GET /api/articles)
    public function index(): JsonResponse
    {
        $articles = Article::latest()->paginate(10); // Ambil data dengan paginasi
        return response()->json($articles);
    }

    // CREATE (POST /api/articles)
    public function store(ArticleStoreRequest $request): JsonResponse
    {
        $article = Article::create($request->validated()); // Mass assignment yang aman
        
        return response()->json([
            'message' => 'Article created successfully!',
            'article' => $article
        ], 201); // Status 201 Created
    }

    // READ - SINGLE (GET /api/articles/{article})
    public function show(Article $article): JsonResponse
    {
        // Berkat Route Model Binding, Article sudah diambil secara otomatis
        return response()->json($article);
    }

    // UPDATE (PUT/PATCH /api/articles/{article})
    public function update(ArticleUpdateRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());
        
        return response()->json([
            'message' => 'Article updated successfully!',
            'article' => $article
        ]);
    }

    // DELETE (DELETE /api/articles/{article})
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        
        return response()->json([
            'message' => 'Article deleted successfully!'
        ], 204); // Status 204 No Content
    }
}