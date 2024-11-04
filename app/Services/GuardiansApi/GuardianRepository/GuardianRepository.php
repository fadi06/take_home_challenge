<?php

namespace App\Services\GuardiansApi\GuardianRepository;

use Carbon\Carbon;
use App\Models\Article;
use App\Traits\BuildClient;
use Illuminate\Support\Facades\Log;

class GuardianRepository
{
    use BuildClient;

    private const ENDPOINT = 'search';

    protected function getBaseUrl(): string
    {
        return 'https://content.guardianapis.com';
    }

    public function fetchAndSave()
    {
        foreach ($this->fetchGuardiansApiCategories() as $pageNumber) {
            $response = $this->getResponse($pageNumber);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody());

                $this->setAndSaveData($responseData->response->results, $pageNumber);

            } else {
                $response_body = json_decode($response->getBody());
                $message = $response_body->message ?? 'Unknown error';
                Log::error("API Request failed with message: $message");
                throw new \Exception($message);
            }

            info($pageNumber. " is completed");
        }
    }

    private function getResponse($pageNumber = 1)
    {
        $apiKey = env('GUARDIAN_API_KEY');
        if (empty($apiKey)) {
            throw new \Exception("GUARDIAN_API_KEY is not set in the environment.");
        }

        $params = [
            'api-key' => $apiKey,
            'page' => $pageNumber,
            'show-fields' => 'all'
        ];

        return $this->sendRequest(self::ENDPOINT, $params);
    }

    private function fetchGuardiansApiCategories(): array
    {
        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }

    private function setAndSaveData($articles, $category): void {
        $finalData = [];

        $categoryId = 7;
dd($articles);
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
