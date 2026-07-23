<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleController extends Controller
{
    protected array $allowed = ['id', 'ms', 'jawi'];

    public function __invoke(Request $request, string $locale)
    {
        if (!in_array($locale, $this->allowed)) {
            $locale = 'id';
        }

        Session::put('app_locale', $locale);
        App::setLocale($locale);

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
