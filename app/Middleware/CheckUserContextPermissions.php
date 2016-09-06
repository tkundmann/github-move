<?php

namespace App\Middleware;

use App\Helpers\ContextHelper;
use App\Data\Models\Role;
use Auth;
use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckUserContextPermissions
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
        $user = Auth::user();

        if ($user->hasRole([Role::ADMIN, Role::SUPERADMIN]) && !ContextHelper::isAdminContext($context)) {
            throw new AccessDeniedHttpException();
        }
        else if ($user->hasRole(Role::SUPERUSER) && !ContextHelper::isSiteContext($context)) {
            throw new AccessDeniedHttpException();
        }
        else if ($user->hasRole(Role::USER) && !ContextHelper::doesContextMatchUserSite($user, $context)) {
            throw new AccessDeniedHttpException();
        }
        else if ($user->roles->count() == 0) {
            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}
