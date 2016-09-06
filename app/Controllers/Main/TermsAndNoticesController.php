<?php

namespace App\Controllers\Main;

use App\Controllers\ContextController;
use Illuminate\Http\Request;

class TermsAndNoticesController extends ContextController
{
    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Show the Terms and Notices.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        return view('main.termsAndNotices');
    }
}
