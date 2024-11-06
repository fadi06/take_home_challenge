<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\UserPreference;
use App\Traits\Response;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    use Response;

    public function index(Request $request)
    {
        $query = Article::query()
            ->searchByKeyword($request->input('keyword'))
            ->filterByDate($request->input('date'))
            ->filterByCategory($request->category_ids)
            ->filterBySource($request->source_ids)
            ->filterByAuthor($request->author_ids);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $query->paginate(10));
    }

    // Retrieve a single article
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $article);
    }

    public function fetchNewsByPreferences(Request $request)
    {
        $userPreferences = UserPreference::where('user_id', Auth::id())->get();

        $request->merge([
            'category_ids' => $userPreferences->pluck('category_id')->filter()->unique()->values()->all(),
            'author_ids' => $userPreferences->pluck('author_id')->filter()->unique()->values()->all(),
            'source_ids' => $userPreferences->pluck('source_id')->filter()->unique()->values()->all(),
        ]);

        return $this->index($request);
    }}
