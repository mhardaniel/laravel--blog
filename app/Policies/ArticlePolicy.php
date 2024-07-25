<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Article $article): bool
    {
        return $user->getKey() === $article->author->getKey();
    }

    public function delete(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }
}
