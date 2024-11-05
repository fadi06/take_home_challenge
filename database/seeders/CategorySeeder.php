<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'business',
            'general',
            'technology',
            'sports',
            'entertainment',
            'health',
            'science',
            'guardian News',
            'arts',
            'automobiles',
            'books/review',
            'business',
            'fashion',
            'food',
            'health',
            'home',
            'insider',
            'magazine',
            'movies',
            'nyregion',
            'obituaries',
            'opinion',
            'politics',
            'realestate',
            'science',
            'sports',
            'sundayreview',
            'technology',
            'theater',
            't-magazine',
            'travel',
            'upshot',
            'us',
            'world'
        ];

        foreach ($categories as $key => $categoy) {
            Category::updateOrCreate(['name' => $categoy], ['name' => $categoy]);
        }
    }
}
