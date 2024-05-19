<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Article;
use App\Models\Like;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::select('id', 'title', 'published_date', 'user_id', 'status');
        if (Auth::check()) {
            $articles = $query->with('user')->orderBy('id', 'DESC')->get();
        } else {
            $articles = $query->with('user')->where('status', 'published')->orderBy('id', 'DESC')->get();
        }

        $result = respOk('success get published articles', $articles);
        return Inertia::render('Welcome', ['articles' => $result, 'csrfToken' => csrf_token() ]);
    }

    public function show($id)
    {
        try {
            $article = Article::with(['user','comments.user'])->findOrFail($id);
            $likeCount = Like::where('article_id', $id)->where('is_like', 1)->count();
            $result = respOk('success get article details', $article, ['like_count' => $likeCount]);

            return Inertia::render('ArticleDetail', ['article' => $result, 'csrfToken' => csrf_token()]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }

    public function form($id = 0)
    {
        if ($id == 0) {
            return Inertia::render('ArticleForm');
        }

        try {
            $article = Article::findOrFail($id);
            $result = respOk('success get article details', $article);

            return Inertia::render('ArticleForm', ['article' => $result, 'csrfToken' => csrf_token()]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $article = new Article($validatedData);
        $article->title = ucwords(strtolower($article->title), ".-/ ");
        $article->user_id = Auth::id();
        $article->save();
        $article->refresh();

        return $this->show($article->id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required'
        ]);

        try {
            $article = Article::findOrFail($id);

            if ($request->status == 'draft') {
                $validatedData['published_date'] = null;
            } else if ($article->status != 'published' && $request->status == 'published') {
                $validatedData['published_date'] = now();
            }
            $article->update($validatedData);
            return $this->show($id);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }

    public function archived($id)
    {
        try {
            $article = Article::where('status', 'published')->findOrFail($id);
            $article->update([
                'status' => 'archived',
                'published_date' => null
            ]);

            return response()->json(respOk('success archived the article'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(respError('Article not found', '404'), 404);
        }
    }
}
