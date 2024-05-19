<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Like;

class LikeController extends Controller
{
    public function like($article_id)
    {
        $this->toggleLike($article_id, true);
    }

    public function dislike($article_id)
    {
        $this->toggleLike($article_id, false);
    }

    private function toggleLike($article_id, $isLike)
    {
        $like = Like::updateOrCreate(
            ['user_id' => Auth::id(), 'article_id' => $article_id],
            ['is_like' => $isLike]
        );
    }
}
