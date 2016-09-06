<?php

namespace App\Middleware;

use App\Helpers\ContextHelper;
use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param                           $context
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function handle($request, Closure $next, $context)
    {
        if (!ContextHelper::isValidContext($context)) {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}
