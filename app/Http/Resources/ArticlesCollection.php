<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticlesCollection extends ResourceCollection
{
    public static $wrap = 'articles';

    public $collects = ArticleResource::class;

    public function with($request)
    {
        return [
            /*'articlesCount' => $this->collection->count(),*/
            /*'articlesCount' => Article::all()->count(),*/
            /*'articlesCount' => $this->count,*/
        ];
    }
}
