<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/v1/cart*',
        'api/v1/checkout*',
        'api/v1/wishlist*',
        'api/v1/orders*',
        'api/v1/auth*',
        'api/v1/addresses*',
        'api/v1/demo*',
    ];
}