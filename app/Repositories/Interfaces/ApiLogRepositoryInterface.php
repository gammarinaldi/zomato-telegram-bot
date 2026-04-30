<?php

namespace App\Repositories\Interfaces;

interface ApiLogRepositoryInterface
{
    public function create(array $data);
    public function getAllPaginated(int $perPage = 50);
}
