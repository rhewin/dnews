<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Article;
use App\Models\Comment;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'article_id' => 'required',
            'content' => 'required',
        ]);

        try {
            $article = Article::where('status', 'published')->findOrFail($request->article_id);

            $comment = new Comment($validatedData);
            $comment->user_id = Auth::id();
            $comment->save();

            $result = respOk('get article details', $article);
            return Inertia::render('ArticleDetail', ['article' => $result]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }
}
