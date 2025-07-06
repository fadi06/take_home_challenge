<?php

namespace App\Services\NewyorkTimes\NewyorkTimesRepository;

use Carbon\Carbon;
use App\Models\Article;
use App\Models\Author;
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
            echo ($category. " is started... \n");

            $response = $this->getResponse($category);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody());

                $this->setAndSaveData($responseData->results, $category);

            } else {
                $response_body = json_decode($response->getBody());
                $message = $response_body->message ?? 'Unknown error';
                Log::error("API Request failed with message: $message");
            }

            echo ($category. " is completed.. \n");
            sleep(10);
        }
    }

    private function getResponse($category = 1)
    {
        $apiKey = config('services.new-york-times.key');
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

    private function setAndSaveData($articles, $category)
    {
        if (!empty($articles)) {

            $finalData = [];

            $categoryId = Category::where('name', $category)->pluck('id')->first();;

            foreach ($articles as $article) {
                $image = isset($article->multimedia[0]) ? $article->multimedia[0]->url : null;
                $authorName = str_replace('By ', '', ($article->byline ?? 'un-known'));

                $author = Author::firstOrCreate(
                    ['name' => $authorName],
                    ['name' => $authorName, 'bio' => '']
                );

                if(
                    empty($image) || Article::where('url', $article->url)->exists()
                ) {
                    continue;
                }

                $finalData[] = [
                    'title' => $article->title ?? null,
                    'description' => $article->abstract ?? null,
                    'content' => $article->abstract ?? null,
                    'url' => $article->url ?? null,
                    'image' => $image,
                    'source_id' => 2,
                    'category_id' => $categoryId,
                    'author_id' => $author->id,
                    'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->published_date) ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Article::insert($finalData);
        }
    }
}
