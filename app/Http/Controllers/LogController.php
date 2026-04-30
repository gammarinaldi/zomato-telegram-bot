<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;

class LogController extends Controller
{
    protected $apiLogRepository;

    public function __construct(ApiLogRepositoryInterface $apiLogRepository)
    {
        $this->apiLogRepository = $apiLogRepository;
    }

    public function index()
    {
        $logs = $this->apiLogRepository->getAllPaginated(50);
        return view('logs.index', compact('logs'));
    }
}
