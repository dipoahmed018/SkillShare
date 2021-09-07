<?php

namespace App\Services\Filters;

use Closure;
use Illuminate\Support\Facades\Log;

class Sort
{
    public function handle($builder, Closure $next)
    {
        $request = request();
        if ($request->has('sort_by')) {
            return $next($builder->orderBy($request->sort_by,'asc'));
        }
        return $next($builder);
    }
}
