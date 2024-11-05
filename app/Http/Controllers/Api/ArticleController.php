<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Article; // Assume you have an Article model
use App\Models\UserPreference;
use App\Traits\Response;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    use Response;
    // Fetch Articles with Pagination
    public function index(Request $request)
    {
        $query = Article::query()
            ->searchByKeyword($request->input('keyword'))
            ->filterByDate($request->input('date'))
            ->filterByCategory($request->preferredCategoryIds)
            ->filterBySource($request->preferredSourceIds)
            ->filterByAuthor($request->preferredAuthorIds);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $query->paginate(10));

    }

    // Retrieve a single article
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $article);
    }

    public function fetchNewsByPreferences(Request $request) {
        $userPreferences = UserPreference::where('user_id', Auth::id())->all();

        $request->request->add([
            'category_ids' => $userPreferences ? $userPreferences->category_id : [],
            'author_ids' => $userPreferences ? $userPreferences->author_id : [],
            'source_ids' => $userPreferences ? $userPreferences->source_id : [],
        ]);

        return $this->index($request);

    }
}
