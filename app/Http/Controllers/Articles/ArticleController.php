<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Http\Resources\ArticlesCollection;
use App\Models\Article;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    protected const FILTER_LIMIT = 20;

    protected const FILTER_OFFSET = 0;

    public function list(ArticleListRequest $request)
    {
        $filter = collect($request->validated());

        $limit = $this->getLimit($filter);
        $offset = $this->getOffset($filter);

        $list = Article::list($limit, $offset);

        if ($tag = $filter->get('tag')) {
            $list->havingTag($tag);
        }

        if ($authorName = $filter->get('author')) {
            $list->ofAuthor($authorName);
        }

        if ($userName = $filter->get('favorited')) {
            $list->favoredByUser($userName);
        }

        return new ArticlesCollection($list->get()->load('tags', 'author', 'favoritedByUsers'));
    }

    public function feed(FeedRequest $request)
    {
        $filter = collect($request->validated());

        $limit = $this->getLimit($filter);
        $offset = $this->getOffset($filter);

        $feed = Article::list($limit, $offset)
            ->followedAuthorsOf($request->user());

        return new ArticlesCollection($feed->get());
    }

    private function getLimit(Collection $filter): int
    {
        return (int) ($filter['limit'] ?? static::FILTER_LIMIT);
    }

    private function getOffset(Collection $filter): int
    {
        return (int) ($filter['offset'] ?? static::FILTER_OFFSET);
    }
}
