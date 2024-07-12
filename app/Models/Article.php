<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'description',

    ];

    /*     public function favoredBy(int $user): bool
        {
            return $this->favoritedByUsers()->where('favorites.user', $user)->exists();
        } */

    public function favoredBy(User $user): bool
    {
        return $this->favoritedByUsers()->whereKey($user->getKey())
            ->exists();
    }

    public function scopeList(Builder $query, int $take, int $skip): Builder
    {
        return $query->latest()
            ->limit($take)
            ->offset($skip);
    }

    public function scopeHavingTag(Builder $query, string $tag): Builder
    {
        return $query->whereHas('tags', fn (Builder $builder) => $builder->where('name', $tag)
        );
    }

    public function scopeOfAuthor(Builder $query, string $username): Builder
    {
        return $query->whereHas('author', fn (Builder $builder) => $builder->where('username', $username)
        );
    }

    public function scopeFavoredByUser(Builder $query, string $username): Builder
    {
        return $query->whereHas('favoritedByUsers', fn (Builder $builder) => $builder->where('username', $username)
        );
    }

    public function scopeFollowedAuthorsOf(Builder $query, User $user): Builder
    {
        return $query->whereHas('author', fn (Builder $builder) => $builder->whereIn('id', $user->authors->pluck('id'))
        );
    }

    public function attachTags(array $tags): void
    {
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName,
            ]);

            $this->tags()->syncWithoutDetaching($tag);
        }
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article');
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'article', 'user');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article', 'tag');
    }
}
