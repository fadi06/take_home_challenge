<?php

namespace App\Services\GuardiansApi;

use App\Services\NewsApi\NewsApiService;
use App\Services\GuardiansApi\GuardianRepository\GuardianRepository;

class GuardiansApiService implements NewsApiService
{
    protected $repository;

    public function __construct(GuardianRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchAndSave()
    {
        // Call the fetchAndSave method from GuardianRepository
        return $this->repository->fetchAndSave();
    }
}
