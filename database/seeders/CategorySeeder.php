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
            'NewYork times',
        ];

        foreach ($categories as $key => $categoy) {
            Category::updateOrCreate(['name' => $categoy], ['name' => $categoy]);
        }
    }
}
