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

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Get filtered list of articles",
     *     description="Returns a paginated list of articles filtered by keyword, date, category, source, and author.",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category_ids[]",
     *         in="query",
     *         description="Filter by category IDs",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="source_ids[]",
     *         in="query",
     *         description="Filter by source IDs",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="author_ids[]",
     *         in="query",
     *         description="Filter by author IDs",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Articles fetched successfully"),
     *             @OA\Property(property="result", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Get a single article",
     *     description="Returns the details of a single article by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article fetched successfully"),
     *             @OA\Property(property="result", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return $this->sendSuccessResponse(message: __('article.articles_fetched'), result: $article);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/preference",
     *     tags={"Articles"},
     *     summary="Get articles based on user preferences",
     *     description="Returns a paginated list of articles filtered according to the authenticated user's saved preferences.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched by user preferences",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Articles fetched successfully"),
     *             @OA\Property(property="result", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Auth token missing or invalid",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function fetchNewsByPreferences(Request $request)
    {
        $userPreferences = UserPreference::where('user_id', Auth::id())->get();

        $request->merge([
            'category_ids' => $userPreferences->pluck('category_id')->filter()->unique()->values()->all(),
            'author_ids' => $userPreferences->pluck('author_id')->filter()->unique()->values()->all(),
            'source_ids' => $userPreferences->pluck('source_id')->filter()->unique()->values()->all(),
        ]);

        return $this->index($request);
    }
}
