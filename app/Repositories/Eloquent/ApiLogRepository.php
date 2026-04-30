<?php

namespace App\Repositories\Eloquent;

use App\Models\ApiLog;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;

class ApiLogRepository implements ApiLogRepositoryInterface
{
    public function create(array $data)
    {
        return ApiLog::create($data);
    }

    public function getAllPaginated(int $perPage = 50)
    {
        return ApiLog::orderBy('created_at', 'desc')->paginate($perPage);
    }
}
