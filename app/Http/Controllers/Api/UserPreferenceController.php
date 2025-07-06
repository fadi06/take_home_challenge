<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferencesFormRequest;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\UserPreference;
use App\Traits\Response;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    use Response;

    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"User Preferences"},
     *     summary="Get list of all authors",
     *     description="Returns all authors to choose from for preferences.",
     *     @OA\Response(
     *         response=200,
     *         description="Authors fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Authors fetched successfully"),
     *             @OA\Property(property="result", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function authors()
    {
        return $this->sendSuccessResponse('Authors fetched successfully', Author::all());
    }

    /**
     * @OA\Get(
     *     path="/api/sources",
     *     tags={"User Preferences"},
     *     summary="Get list of all sources",
     *     description="Returns all news sources for user preferences.",
     *     @OA\Response(
     *         response=200,
     *         description="Sources fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sources fetched successfully"),
     *             @OA\Property(property="result", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function sources()
    {
        return $this->sendSuccessResponse('Sources fetched successfully', Source::all());
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"User Preferences"},
     *     summary="Get list of all categories",
     *     description="Returns all categories to allow users to set preferences.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Categories fetched successfully"),
     *             @OA\Property(property="result", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function categories()
    {
        return $this->sendSuccessResponse('Categories fetched successfully', Category::all());
    }

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     tags={"User Preferences"},
     *     summary="Add user preferences",
     *     description="Save user's selected preferences (category, source, author).",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id","source_id","author_id"},
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="source_id", type="integer", example=2),
     *             @OA\Property(property="author_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Preferences added successfully"),
     *             @OA\Property(property="result", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function addPreferences(PreferencesFormRequest $request)
    {
        $validated = $request->validated() + ['user_id' => Auth::id()];
        $UserPreference = UserPreference::create($validated);

        return $this->sendSuccessResponse('Preferences added successfully', $UserPreference);
    }
}
