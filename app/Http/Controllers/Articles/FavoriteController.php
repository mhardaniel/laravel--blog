<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function add(Request $request, string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        $user = $request->user();

        $user->favoritedArticles()->syncWithoutDetaching($article);

        return new ArticleResource($article);
    }

    public function remove(Request $request, string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        $user = $request->user();

        $user->favoritedArticles()->detach($article);

        return new ArticleResource($article);
    }
}
