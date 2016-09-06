<?php

namespace App\Extensions\Auth;

use App\Data\Constants;
use Illuminate\Auth\SessionGuard as BaseSessionGuard;

class SessionGuard extends BaseSessionGuard
{
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
