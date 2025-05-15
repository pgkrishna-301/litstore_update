<?php

namespace App\Http\Middleware;

use Closure;

class CorsForImages
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only add CORS headers for image requests from /uploads
        if (strpos($request->getPathInfo(), '/uploads/') === 0) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept');
        }

        return $response;
    }
}
