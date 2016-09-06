<?php

namespace App\Helpers;

use App\Data\Constants;
use App\Data\Models\Site;
use App\Data\Models\User;

class ContextHelper
{
    public static function isAdminContext($context)
    {
        return ($context === Constants::CONTEXT_ADMIN);
    }

    public static function isSiteContext($context)
    {
        return Site::doesContextExist($context);    // only site contexts are stored in DB
    }

    public static function isValidContext($context)
    {
        return (self::isAdminContext($context) || self::isSiteContext($context));
    }

    public static function doesContextMatchUserSite(User $user, $context)
    {
        if ($user->site) {
            return ($context === $user->site->code);
        }
        else {
            return false;
        }
    }

    public static function getContextByRoute($route)
    {
        $context = null;
        if ($route) {
            $parameters = $route->parameters();

            if ($parameters && array_key_exists(Constants::CONTEXT_PARAMETER, $parameters)) {
                $context = $parameters[Constants::CONTEXT_PARAMETER];
            }
        }

        return $context;
    }
}
