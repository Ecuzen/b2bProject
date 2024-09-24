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
        //
    ];
    
    protected function shouldSkip($request)
{
    // Exclude API routes from CSRF verification
    if ($request->is('api/*')) {
        return true;
    }

    return false;
}

}
