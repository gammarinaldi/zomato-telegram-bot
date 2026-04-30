<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    protected $apiLogRepository;

    public function __construct(ApiLogRepositoryInterface $apiLogRepository)
    {
        $this->apiLogRepository = $apiLogRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $this->apiLogRepository->create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'response_status' => $response->getStatusCode(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log API request: ' . $e->getMessage());
        }

        return $response;
    }
}
