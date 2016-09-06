<?php

namespace App\Extensions\Routing;

use Illuminate\Http\Request;
use App\Data\Constants;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use InvalidArgumentException;
use Route;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Create a new URL Generator instance.
     *
     * @param  \Illuminate\Routing\RouteCollection  $routes
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(RouteCollection $routes, Request $request)
    {
        parent::__construct($routes, $request);
        // $this->forcedRoot = env('APP_URL');  // not fully tested yet
    }

    /**
     * Get the URL to a named route.
     *
     * @param  string  $name
     * @param  mixed   $parameters
     * @param  bool  $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $routeParameters = $parameters;

        // Context
        $route = Route::current();

        if ($route) {
            $currentRouteParameters = Route::current()->parameters();

            if ($currentRouteParameters && array_key_exists(Constants::CONTEXT_PARAMETER, $currentRouteParameters)) {
                $routeParameters[Constants::CONTEXT_PARAMETER] = $currentRouteParameters[Constants::CONTEXT_PARAMETER];
            }
        }

        if (! is_null($route = $this->routes->getByName($name))) {
            return $this->toRoute($route, $routeParameters, $absolute);
        }
        
        throw new InvalidArgumentException("Route [{$name}] not defined.");
    }

    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool|null  $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        // First we will check if the URL is already a valid URL. If it is we will not
        // try to generate a new one but will simply return the URL as is, which is
        // convenient since developers do not always have to check if it's valid.
        if ($this->isValidUrl($path)) {
            return $path;
        }

        $scheme = $this->getScheme($secure);

        $extra = $this->formatParameters($extra);

        $tail = implode('/', array_map(
                'rawurlencode', (array) $extra)
        );

        // Once we have the scheme we will compile the "tail" by collapsing the values
        // into a single string delimited by slashes. This just makes it convenient
        // for passing the array of parameters to this URL as a list of segments.
        $root = $this->getRootUrl($scheme);
    
        // Context
        $route = Route::current();
        if ($route) {
            $currentRouteParameters = Route::current()->parameters();
            if ($currentRouteParameters && array_key_exists(Constants::CONTEXT_PARAMETER, $currentRouteParameters)) {
                $context = $currentRouteParameters[Constants::CONTEXT_PARAMETER];
                $root = $root . '/' . $context;
                // Remove context if already doubled (can happen when external tools try to create the link of current url and yet still use to() method, i.e. @sortablelink)
                $path = preg_replace('/^' . $context . '\//', '', $path);
            }
        }
    
        if (($queryPosition = strpos($path, '?')) !== false) {
            $query = mb_substr($path, $queryPosition);
            $path = mb_substr($path, 0, $queryPosition);
        } else {
            $query = '';
        }

        return $this->trimUrl($root, $path, $tail).$query;
    }
}