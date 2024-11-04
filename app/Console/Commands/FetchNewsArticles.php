<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\GuardiansApi\GuardiansApiService;
use App\Services\NewsApi\NewsApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        // public NewsApiService $newsApiService,
        public GuardiansApiService $guardianService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $this->newsApiService->fetchAndSave();
        $this->guardianService->fetchAndSave();
    }


}
