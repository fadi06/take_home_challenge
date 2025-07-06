<?php

namespace App\Services\GuardiansApi\GuardianRepository;

use Carbon\Carbon;
use App\Models\Article;
use App\Models\Author;
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
            info($pageNumber. " is started... \n");
            $response = $this->getResponse($pageNumber);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody());

                $this->setAndSaveData($responseData->response->results, $pageNumber);

            } else {
                $response_body = json_decode($response->getBody());
                $message = $response_body->message ?? 'Unknown error';
                Log::error("API Request failed with message: $message");
            }

            info($pageNumber. " is completed... \n");
        }
    }

    private function getResponse($pageNumber = 1)
    {
        $apiKey = config('services.guardian-api.key');
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
        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
    }

    private function setAndSaveData($articles, $category): void {
        $finalData = [];

        $categoryId = 7;

        foreach ($articles as $article) {
            $author = Author::firstOrCreate(
                ['name' => $article->fields->byline ?? 'un-known'],
                ['name' => $article->fields->byline ?? 'un-known', 'bio' => '']
            );
            if(
                empty($authorId) ||
                empty($article->fields->body) ||
                Article::whereUrl($article->webUrl)->exists()
            ) {
                continue;
            }

            $finalData[] = [
                'title' => $article->webTitle,
                'description' => $article->fields->standfirst ?? null,
                'content' => $article->fields->body ?? null,
                'url' => $article->webUrl ?? null,
                'image' => $article->fields->thumbnail ?? null,
                'source_id' => 1,
                'category_id' => $categoryId,
                'author_id' => $author->id,
                'feed' => 'guardian',
                'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->webPublicationDate) ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Article::insert($finalData);
    }
}
