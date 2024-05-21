<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Like;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::select('id', 'title', 'summary', 'content', 'published_date', 'user_id', 'status');

        if ($request->has('keyword')) {
            $searchTerm = $request->query('keyword');
            $query
                ->where('title', 'LIKE', "%{$searchTerm}%")
                ->orWhere('content', 'LIKE', "%{$searchTerm}%");
        }

        if (Auth::check()) {
            $query = $query->where(function ($query) {
                $query
                    ->where('status', 'published')
                    ->orWhere('user_id', Auth::id());
            });

            $articles = $query->with('user')->orderBy('id', 'DESC')->paginate(5);
        } else {
            $articles = $query->with('user')->where('status', 'published')->orderBy('id', 'DESC')->paginate(5);
        }

        $arrArticles = $articles->toArray();
        $mapArticles = [];
        foreach ($arrArticles['data'] as $key => $article) {
            $mapArticles['list'][$key]['id'] = $article['id'];
            $mapArticles['list'][$key]['title'] = $article['title'];
            $mapArticles['list'][$key]['summary'] = $article['summary'];
            $mapArticles['list'][$key]['published_date'] = $article['published_date'];
            $mapArticles['list'][$key]['user_id'] = $article['user_id'];
            $mapArticles['list'][$key]['status'] = $article['status'];
            $mapArticles['list'][$key]['user'] = $article['user'];
        }
        $mapArticles['last_page'] = $arrArticles['last_page'];
        $mapArticles['current_page'] = $arrArticles['current_page'];

        $result = respOk('success get published articles', $mapArticles);
        return Inertia::render('Welcome', ['articles' => $result, 'csrfToken' => csrf_token() ]);
    }

    public function show($id)
    {
        try {
            $article = Article::with(['user','comments.user'])->findOrFail($id);
            $likeQuery = Like::where('article_id', $id)->where('is_like', 1);
            $likeCount = $likeQuery->count();
            $isLike = 0;
            if (Auth::check()) {
                $isLike = $likeQuery->where('user_id', Auth::id())->count();
            }
            $result = respOk('success get article details', $article, ['like_count' => $likeCount, 'is_like' => $isLike]);

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
        $article->content = preg_replace('/(<[^>]+) style=("|\').*?\2/i', '$1', $request->content);
        $article->summary = Str::words(htmlspecialchars_decode(strip_tags($article->content)), 25, '...');
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
            $validatedData['title'] = ucwords(strtolower($request->title), ".-/ ");
            $validatedData['content'] = preg_replace('/(<[^>]+) style=("|\').*?\2/i', '$1', $request->content);
            $article->summary = Str::words(htmlspecialchars_decode(strip_tags($request->content)), 25, '...');
            $article->update($validatedData);
            return $this->show($id);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }

    public function archived($id)
    {
        try {
            $article = Article::where(function ($query) use ($id) {
                $query->where('status', 'published')->orWhere('status', 'draft');
            })->findOrFail($id);

            $article->update([
                'status' => 'archived',
                'published_date' => null
            ]);
            return to_route('article.index');
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Error', ['status' => 404]);
        }
    }
}
