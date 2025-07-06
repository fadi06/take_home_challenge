<?php

namespace App\Services\NewyorkTimes;

use App\Services\NewsApi\NewsApiService;
use App\Services\NewyorkTimes\NewyorkTimesRepository\NewyorkTimesRepository;

class NewyorkTimesApiService implements NewsApiService
{
    protected $repository;

    public function __construct(NewyorkTimesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchAndSave()
    {
        // Call the fetchAndSave method from GuardianRepository
        return $this->repository->fetchAndSave();
    }
}
