<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ThrottleOrderApi extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        return sha1($request->method() . '|' . $request->route()->getDomain() . '|' . $request->ip());
    }

    protected function buildResponse($key, $maxAttempts)
    {
        $response = parent::buildResponse($key, $maxAttempts);

        $retryAfter = setting('orderRetryAfter', 20); //default 20 seconds
        //wwrite a message to show user when trying ordering multiple times
        $errorMsg = __("You have reached the maximum number of orders you can place at a time. Please try again after") . " " . $retryAfter . " " . __("seconds.");

        return $response->setContent([
            'error' => $errorMsg
        ])->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS)
            ->header('Retry-After', $retryAfter);
    }
}
