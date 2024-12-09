<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Http\Requests\FeedRequest;
use App\Http\Requests\NewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlesCollection;
use App\Models\Article;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    protected const FILTER_LIMIT = 20;

    protected const FILTER_OFFSET = 0;

    public function list(ArticleListRequest $request)
    {
        $filter = $request->validated();

        $q = Article::list($filter);
        $articleCount = $q->count();

        $q->limit($filter['limit'] ?? static::FILTER_LIMIT)
            ->offset($filters['offset'] ?? static::FILTER_OFFSET);

        return (new ArticlesCollection($q->get()))->additional(['articlesCount' => $articleCount]);
    }

    public function feed(FeedRequest $request)
    {
        return new ArticlesCollection(Article::list($request->validated())->followedAuthorsOf($request->user())->get());
    }

    public function create(NewArticleRequest $request)
    {
        $user = $request->user();

        $attributes = $request->validated();
        $attributes['author_id'] = $user->getKey();

        $tags = Arr::pull($attributes, 'tagList');
        $article = Article::create($attributes);

        if (is_array($tags)) {
            $article->attachTags($tags);
        }

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        return new ArticleResource($article);
    }

    public function update(UpdateArticleRequest $request, string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        Gate::authorize('update', $article);

        $article->update($request->validated());

        return new ArticleResource($article);
    }

    public function delete(string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        Gate::authorize('delete', $article);

        $article->delete(); // cascade

        return response()->json([
            'message' => trans('models.article.deleted'),
        ]);
    }
}
