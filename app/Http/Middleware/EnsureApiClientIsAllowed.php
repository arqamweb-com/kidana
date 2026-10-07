<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets a Partner API request through only for an active client calling from an allowed IP.
 */
class EnsureApiClientIsAllowed
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->user();

        abort_unless($client instanceof ApiClient, 401, 'Unauthenticated.');
        abort_unless($client->is_active, 403, 'This API client is disabled.');
        abort_unless($client->allowsIp($request->ip()), 403, 'This IP address is not allowed for this API client.');

        return $next($request);
    }
}
