<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            SourceSeeder::class,
        ]);

        // first time it will run when no article available in the database
        $articleCount = Article::count();
        if($articleCount == 0) {
            Artisan::call('app:fetch-news-articles');
        }
    }
}
