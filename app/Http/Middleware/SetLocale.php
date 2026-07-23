<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $allowed = ['id', 'ms', 'jawi'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('app_locale', 'id');

        if (!in_array($locale, $this->allowed)) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
