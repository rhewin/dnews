<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\ArticleController;
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

            $articleController = new ArticleController();
            return $articleController->show($article->id);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }
}
