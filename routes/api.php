<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserPreferenceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('password/email', [PasswordResetController::class, 'sendResetLink']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('authors', [UserPreferenceController::class, 'authors']);
Route::get('sources', [UserPreferenceController::class, 'sources']);
Route::get('categories', [UserPreferenceController::class, 'categories']);

Route::apiResource('articles', ArticleController::class);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('fetch_news_by_preferences', [ArticleController::class, 'fetchNewsByPreferences']);
    Route::post('add_preferences', [UserPreferenceController::class, 'addPreferences']);

    Route::post('logout', [AuthController::class, 'logout']);
});
