<?php

namespace App\Middleware;

use App\Helpers\ContextHelper;
use App\Traits\Throttle;
use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddIpAddress
{

	use Throttle;

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

		$request->ip = $this->getIp();
		return $next($request);

	}

	/**
	 * Get IP Address
	 *
	 * @return mixed|string
	 */
	public function getIp()
	{
		foreach ([ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ] as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					if (filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}
	}

}
