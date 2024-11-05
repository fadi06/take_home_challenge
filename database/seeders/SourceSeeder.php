<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'The Guardian',
                'url' => 'https://content.guardianapis.com',
            ],
            [
                'name' => 'The New York Times',
                'url' => 'https://api.nytimes.com',
            ],
            [
                'name' => 'NewsApi',
                'url' => 'https://newsapi.org',
            ]
        ];

        Source::truncate();
        Source::insert($sources);
    }
}
