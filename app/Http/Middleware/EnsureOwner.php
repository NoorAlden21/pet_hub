<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOwner
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *  ->middleware('isOwner:pet')           // pet = route model name
     *  ->middleware('isOwner:pet,user_id')   // custom owner column
     */
    public function handle(Request $request, Closure $next, string $param, string $column = 'user_id')
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $model = $request->route($param);

        if (!$model) {
            abort(404, "Resource not found.");
        }

        if ((int) $model->{$column} !== (int) $user->id) {
            abort(403, 'You do not own this resource.');
        }

        return $next($request);
    }
}
