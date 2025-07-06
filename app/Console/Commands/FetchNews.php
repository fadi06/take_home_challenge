<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GuardiansApi\GuardiansApiService;
use App\Services\NewsApi\NewsApiService;
use App\Services\NewyorkTimes\NewyorkTimesApiService;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from news apis and insert into database.';

    public function __construct(
        public NewsApiService $newsApiService,
        public GuardiansApiService $guardianService,
        public NewyorkTimesApiService $newyorkTimesApiService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newsApiService->fetchAndSave();
        $this->guardianService->fetchAndSave();
        $this->newyorkTimesApiService->fetchAndSave();
    }
}
