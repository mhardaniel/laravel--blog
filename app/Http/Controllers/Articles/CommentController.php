<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function list(string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        return new CommentCollection($article->comments);
    }

    public function create(StoreCommentRequest $request, string $slug)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        $user = $request->user();

        $comment = Comment::create([
            'article_id' => $article->id,
            'author_id' => $user->id,
            'body' => $request->input('comment.body'),
        ]);

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }

    public function delete(string $slug, $id)
    {
        $article = Article::whereSlug($slug)
            ->firstOrFail();

        $comment = $article->comments()
            ->findOrFail((int) $id);

        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => trans('models.comment.deleted'),
        ]);
    }
}
