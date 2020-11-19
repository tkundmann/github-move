<?php

namespace App\Traits;

/**
 * Trait Throttle
 * @package App\Traits
 */
trait Throttle {

	/**
	 * @param \Illuminate\Http\Request $request
	 * @return string
	 */
	protected function getThrottleKey(\Illuminate\Http\Request $request)
	{
		$key = mb_strtolower($this->context . '|' . $request->input($this->loginUsername()));
		$ipAddress = $request->ip;
		if ($ipAddress != '') {
			$key .= '|' . $ipAddress;
		}
		return $key;
	}

}