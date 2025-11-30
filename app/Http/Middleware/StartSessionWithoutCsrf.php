<?php

namespace App\Http\Middleware;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class StartSessionWithoutCsrf extends StartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Start session without CSRF protection
        return parent::handle($request, $next);
    }
}
