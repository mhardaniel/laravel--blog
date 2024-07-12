<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticlesCollection extends ResourceCollection
{
    public static $wrap = 'articles';

    public $collects = ArticleResource::class;

    public function with($request)
    {
        return [
            'articlesCount' => $this->collection->count(),
        ];
    }
}
