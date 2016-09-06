<?php

namespace App\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Kint;
use Session;
use URL;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    
        if (URL::previous() !== $request->fullUrl()) {
            Session::put('backUrl', URL::previous());
        }

        Kint::enabled(env('APP_DEBUG'));
    }
}
