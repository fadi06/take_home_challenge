<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Article; // Assume you have an Article model
use App\Traits\Response;

class ArticleController extends Controller
{

    use Response;
    // Fetch Articles with Pagination
    public function index(Request $request)
    {
        $query = Article::query()
            ->searchByKeyword($request->input('keyword'))
            ->filterByDate($request->input('date'))
            ->filterByCategory($request->input('category'))
            ->filterBySource($request->input('source'));

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $query->paginate(10));

    }

    // Retrieve a single article
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $article);
    }
}