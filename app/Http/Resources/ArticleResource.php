<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public static $wrap = 'article';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'tagList' => new TagsCollection($this->tags),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'favorited' => $this->when($user !== null, fn () => $this->favoritedByUsers->contains($user->id)),
            'favoritesCount' => $this->favoritedByUsers->count(),
            'author' => new ProfileResource($this->author),
        ];
    }
}
