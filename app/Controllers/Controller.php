<?php

namespace App\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Kint;
use Session;
use URL;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;

        $previousUrl = URL::previous();
        $backUrlInProgress = Session::get('backUrlInProgress');

        if ($backUrlInProgress) {
            Session::remove('backUrlInProgress');
        }
        else if (($previousUrl !== $request->fullUrl()) && (Route::getCurrentRoute()->getName() != 'main.back')) {
            $backUrlList = Session::get('backUrlList');

            if (!$backUrlList) {
                $backUrlList = [];
            }

            array_push($backUrlList, $previousUrl);

            Session::put('backUrlList', $backUrlList);
        }

        Kint::enabled(env('APP_DEBUG'));
    }

    public function goBack()
    {
        $backUrlList = Session::get('backUrlList');

        if ($backUrlList && count($backUrlList) > 0) {
            $lastUrl = array_pop($backUrlList);

            Session::put('backUrlList', $backUrlList);
            Session::put('backUrlInProgress', true);

            return $lastUrl;
        }
        else {
            return route('main.home');
        }
    }
}
