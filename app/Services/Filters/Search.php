<?php

namespace App\Services\Filters;

use Closure;
use Illuminate\Support\Facades\Log;

class Search
{
    public function handle($model, Closure $next)
    {
        $request = request();
        if ($request->has('search_query')) {
            //scout search
            if (method_exists($model, 'search')) {
                $model::search($request->search_query);
            } else if ($model::searchable && $request->search_query) {
                //sql search
                $tables = implode(',', $model::searchable);
                $search_query = str_replace(' ', '* ', $request->search_query);
                $query = $model::whereRaw("MATCH ($tables) AGAINST(? IN BOOLEAN MODE)", $search_query);
                return $next($query);
            } else {
                //make a simple query
                return $next($model::query());
            }
        }
        return $next($model);
    }
}
