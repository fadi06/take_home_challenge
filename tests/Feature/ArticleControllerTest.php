<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to show a single article.
     */
    public function test_can_show_single_article()
    {
        $article = Article::factory()->create([
            'title' => 'Test Article',
            'description' => 'This is a test description.',
            'content' => 'This is test content for the article.',
            'country' => 'us',
            'language' => 'en',
            'author' => 'Test Author',
            'source' => 'Test Source',
            'url' => 'http://test-article.com',
            'feed' => 'newsapi',
            'published_at' => Carbon::now(),
        ]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'title' => 'Test Article',
                        'description' => 'This is a test description.',
                        'content' => 'This is test content for the article.',
                        'country' => 'us',
                        'language' => 'en',
                        'author' => 'Test Author',
                        'source' => 'Test Source',
                        'url' => 'http://test-article.com',
                        'feed' => 'newsapi',
                        'published_at' => $article->published_at->toDateTimeString(),
                    ]
                ]);
    }

    /**
     * Test to show all articles with filters for keyword, date, category, and source.
     */
    public function test_can_show_all_articles_with_filters()
    {
        $category = Category::factory()->create(['name' => 'technology']);

        // Create articles with specific attributes for filtering
        $article1 = Article::factory()->create([
            'title' => 'Tech News',
            'category_id' => $category->id,
            'source' => 'techSource',
            'published_at' => '2023-01-01',
        ]);

        $article2 = Article::factory()->create([
            'title' => 'Business News',
            'category_id' => $category->id,
            'source' => 'BizSource',
            'published_at' => '2023-02-01',
        ]);

        // Request with filters that should match only one article
        $response = $this->getJson('/api/articles?keyword=news&date=2023-01-01&category=' . $category->id . '&source=techSource');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data.data');
    }

    public function test_can_show_all_articles_with_out_filters()
    {
        $category = Category::factory()->create(['name' => 'technology']);

        // Create articles with specific attributes for filtering
        $article1 = Article::factory()->create([
            'title' => 'Tech News',
            'category_id' => $category->id,
            'source' => 'techSource',
            'published_at' => '2023-01-01',
        ]);

        $article2 = Article::factory()->create([
            'title' => 'Business News',
            'category_id' => $category->id,
            'source' => 'BizSource',
            'published_at' => '2023-02-01',
        ]);

        // Request with filters that should match only one article
        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data.data');
    }
}

