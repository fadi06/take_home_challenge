<?php

namespace App\Services\NewsApi\NewsRepository;

use App\Models\Article;
use App\Models\Category;
use App\Services\NewsApi\NewsApiService;
use App\Traits\BuildClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewsApiRepository implements NewsApiService
{
    use BuildClient;

    private const ENDPOINT = 'v2/top-headlines';
    private const COUNTRY = 'us';

    protected function getBaseUrl(): string
    {
        return 'https://newsapi.org';
    }

    public function fetchAndSave()
    {
        foreach ($this->fetchNewsApiCategories() as $category) {
            info($category. " is started");
            $response = $this->getResponse($category);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody());

                $this->setAndSaveData($responseData->articles, $category);

            } else {
                $response_body = json_decode($response->getBody());
                $message = $response_body->message ?? 'Unknown error';
                Log::error("API Request failed with message: $message");
                throw new \Exception($message);
            }

            info($category. " is completed");
        }
    }

    private function getResponse($category = null)
    {
        $apiKey = env('NEWS_API_KEY');
        if (empty($apiKey)) {
            throw new \Exception("NEWS_API_KEY is not set in the environment.");
        }

        $params = [
            'apiKey' => $apiKey,
            'pageSize' => '100',
            'country' => self::COUNTRY,
            'category' => $category
        ];

        return $this->sendRequest(self::ENDPOINT, $params);
    }

    private function fetchNewsApiCategories(): array
    {
        return ['business', 'general', 'technology', 'sports', 'entertainment', 'health', 'science'];
    }

    private function setAndSaveData($articles, $category): void {
        $finalData = [];

        $categoryId = Category::where('name', $category)->pluck('id')->first();

        foreach ($articles as $article) {
            if($article->title === "[Removed]" || Article::where('url', $article->url)->exists()) {
                continue;
            }

            $finalData[] = [
                'title' => $article->title,
                'description' => $article->description,
                'content' => $article->content,
                'url' => $article->url,
                'image' => $article->urlToImage,
                'source' => $article->source->name,
                'category_id' => $categoryId,
                'author' => $article->author,
                'feed' => 'newsapi',
                'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->publishedAt) ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Article::insert($finalData);
    }
}