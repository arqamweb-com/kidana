<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records every Partner API call made with a valid token, including the rejected ones.
 */
class LogApiRequest
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = hrtime(true);

        $response = $next($request);

        $client = $request->user();

        if ($client instanceof ApiClient) {
            ApiRequestLog::create([
                'api_client_id' => $client->id,
                'method' => $request->method(),
                'path' => Str::limit('/'.$request->path(), 250, ''),
                'status' => $response->getStatusCode(),
                'ip' => $request->ip(),
                'duration_ms' => (int) ((hrtime(true) - $startedAt) / 1_000_000),
            ]);
        }

        return $response;
    }
}
