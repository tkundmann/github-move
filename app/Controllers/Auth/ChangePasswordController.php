<?php

namespace App\Controllers\Auth;

use App\Controllers\ContextController;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Lang;
use Validator;

class ChangePasswordController extends ContextController
{
    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->middleware('context.permissions:' . $this->context);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getChangePassword()
    {
        return view('auth.changePassword');
    }

    public function postChangePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => '
                required|
                min:8|
                symbols|
                confirmed',
        ];

        $validator = Validator::make(Input::all(), $rules);

        $credentials = $request->only(
            'old_password', 'new_password', 'new_password_confirmation'
        );
        $credentials = array_merge($credentials, ['site_id' => $this->getSiteId()]);

        $user = Auth::user();

        $validator->after(function ($validator) use ($credentials, $user) {
            if (!Hash::check($credentials['old_password'], $user->password)) {
                $validator->errors()->add('old_password', Lang::get('auth.password.old_password_not_match'));
            }
        });

        if ($validator->fails()) {
            return redirect()->route('password.change')->withErrors($validator)
                ->withInput(['old_password', $credentials['old_password']]);
        }

        $user->password = Hash::make($credentials['new_password']);
        $user->confirmed = true;
        $user->save();

        return redirect()->route('main.home');
    }
}
