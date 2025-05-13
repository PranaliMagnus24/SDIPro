<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clearAllCaches()
    {
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');
        Artisan::call('event:clear');

        return response()->json(['message' => 'All caches have been cleared.']);
    }
}
