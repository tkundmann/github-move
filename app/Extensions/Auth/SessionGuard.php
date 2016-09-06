<?php

namespace App\Extensions\Auth;

use App\Data\Constants;
use App\Helpers\ContextHelper;
use Illuminate\Auth\SessionGuard as BaseSessionGuard;
use Route;

class SessionGuard extends BaseSessionGuard
{
    public function getName()
    {
        $route = Route::current();
        $context = ContextHelper::getContextByRoute($route);
        
        if (ContextHelper::isAdminContext($context)) {
            $contextName = 'admin';
        } else {
            $contextName = 'site';
        }
        
        return 'login_' . $contextName . '_' . $this->name . '_' . sha1(static::class);
    }
    
    public function getRecallerName()
    {
        $route = Route::current();
        $context = ContextHelper::getContextByRoute($route);
        
        if (ContextHelper::isAdminContext($context)) {
            $contextName = 'admin';
        } else {
            $contextName = 'site';
        }
        
        return 'remember_' . $contextName . '_' . $this->name . '_' . sha1(static::class);
    }

    /**
     * Create a "remember me" cookie for a given ID.
     *
     * @param  string  $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function createRecaller($value)
    {
        return $this->getCookieJar()->make($this->getRecallerName(), $value, (Constants::REMEMBER_ME_COOKIE_LONGEVITY * 24 * 60));
    }
}
