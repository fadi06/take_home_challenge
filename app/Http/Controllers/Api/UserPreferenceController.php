<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferencesFormRequest;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\UserPreference;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    use Response;

    public function authors()
    {
        return $this->sendSuccessResponse('Authors fetched successfully', Author::all());
    }

    public function sources()
    {
        return $this->sendSuccessResponse('Sources fetched successfully', Source::all());
    }

    public function categories()
    {
        return $this->sendSuccessResponse('Categories fetched successfully', Category::all());
    }

    public function addPreferences(PreferencesFormRequest $request){
        $validated = $request->validated() + ['user_id' => Auth::id()];
        $UserPreference = UserPreference::create($validated);

        return $this->sendSuccessResponse('Preferences added successfully', $UserPreference);
    }
}
