<?php

namespace App\Services\NewyorkTimes\NewyorkTimesRepository;

use Carbon\Carbon;
use App\Models\Article;
use App\Models\Category;
use App\Traits\BuildClient;
use Illuminate\Support\Facades\Log;

class NewyorkTimesRepository
{
    use BuildClient;

    private const ENDPOINT = '/svc/topstories/v2/';

    protected function getBaseUrl(): string
    {
        return 'https://api.nytimes.com';
    }

    public function fetchAndSave()
    {
        foreach ($this->fetchNewYorkApiCategories() as $category) {
            info($category. " is started...");

            $response = $this->getResponse($category);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody());

                $this->setAndSaveData($responseData->results, $category);

            } else {
                $response_body = json_decode($response->getBody());
                $message = $response_body->message ?? 'Unknown error';
                Log::error("API Request failed with message: $message");
            }

            info($category. " is completed");
            sleep(30);
        }
    }

    private function getResponse($category = 1)
    {
        $apiKey = env('NYT_API_KEY');
        if (empty($apiKey)) {
            throw new \Exception("NYT_API_KEY is not set in the environment.");
        }

        $params = [
            'api-key' => $apiKey
        ];

        return $this->sendRequest(self::ENDPOINT.$category.'.json', $params);
    }

    private function fetchNewYorkApiCategories(): array
    {
        return ["movies", "nyregion", "obituaries", "opinion", "politics", "realestate", "science", "sports", "upshot", "us", "world"];
    }

    private function setAndSaveData($articles, $category): void {
        $finalData = [];

        $categoryId = Category::where('name', $category)->pluck('id')->first();;

        foreach ($articles as $article) {
            info(json_encode($article));
            $image = isset($article->multimedia[0]) ? $article->multimedia[0]->url : null;
            if(!is_null($image) && Article::where('url', $article->url)->exists()) {
                continue;
            }

            $finalData[] = [
                'title' => $article->title,
                'description' => $article->abstract ?? null,
                'content' => $article->abstract,
                'url' => $article->url,
                'image' => $image,
                'source' => 'The Newyork Times',
                'category_id' => $categoryId,
                'author' => str_replace('By ', '', $article->byline) ?? null,
                'feed' => 'newyork times',
                'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->published_date) ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Article::insert($finalData);
    }
}
