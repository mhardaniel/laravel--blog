<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected const FILTER_LIMIT = 20;

    protected const FILTER_OFFSET = 0;

    protected $fillable = [
        'author_id',
        'slug',
        'title',
        'body',
        'description',

    ];

    public function scopeList(Builder $query, array $filters): Builder
    {
        return $query->latest()
            ->limit($filters['limit'] ?? static::FILTER_LIMIT)
            ->offset($filters['offset'] ?? static::FILTER_OFFSET)->filter($filters, 'tag', 'tags', 'name')
            ->filter($filters, 'author', 'author', 'username')
            ->filter($filters, 'favorited', 'favoritedByUsers', 'username')
            ->with('author.followers', 'favoritedByUsers', 'tags');
    }

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column): Builder
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
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
        return $this->hasMany(Comment::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'article', 'user');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article', 'tag');
    }

    public function setTitleAttribute(string $title): void
    {
        $this->attributes['title'] = $title;

        $this->attributes['slug'] = Str::slug($title);
    }
}
