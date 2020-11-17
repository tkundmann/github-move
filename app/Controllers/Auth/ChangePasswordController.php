<?php

namespace App\Controllers\Auth;

use Auth;
use App\Controllers\ContextController;
use App\Extensions\Auth\PasswordBroker;
use App\Data\Constants;
use App\Data\Models\Role;
use App\Data\Models\User;
use App\Data\Models\PasswordHistory;
use App\Data\Models\Site;
use Carbon\Carbon;
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
        $applicablePasswordLengthClass = 'default-min-chars-apply';
        if (Auth::user()->passwordRequiredLength() == User::ADMIN_SUPER_USER_PASSWORD_REQUIRED_LENGTH) {
            $applicablePasswordLengthClass = 'admin-min-chars-apply';
        }

        $passwordCriteriaMsgReplacePairs = [
            'applicablePasswordLengthClass' => $applicablePasswordLengthClass,
            'defaultMinNumChars'            => User::PASSWORD_DEFAULT_REQUIRED_LENGTH,
            'adminMinNumChars'              => User::ADMIN_SUPER_USER_PASSWORD_REQUIRED_LENGTH
        ];
        $passwordCriteriaMsg = Lang::get('auth.password.valid_password_criteria', $passwordCriteriaMsgReplacePairs);

        return view('auth.changePassword', [
            'applicablePasswordLengthClass' => $applicablePasswordLengthClass,
            'passwordCriteriaMsg'           => $passwordCriteriaMsg
        ]);
    }

    public function postChangePassword(Request $request)
    {
        $user = Auth::user();

        $credentials = $request->only(
            'current_password', 'new_password', 'new_password_confirmation'
        );
        $credentials = array_merge($credentials, ['site_id' => $this->getSiteId()]);

        $minNumChars = $user->passwordRequiredLength();
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:' . $minNumChars . '|letters|numbers|symbols|case_diff',
        ];

        $validator = Validator::make($credentials, $rules);

        $validator->after(function ($validator) use ($credentials, $user) {
            if (!Hash::check($credentials['current_password'], $user->password)) {
                $validator->errors()->add('current_password', Lang::get('auth.password.current_password_not_match'));
                $credentials['current_password'] = '';
            }
            else if(strcmp($credentials['current_password'], $credentials['new_password']) == 0) {
                $validator->errors()->add('new_password', Lang::get('auth.password.new_current_password_match'));
            }
            else {
                $passwordHistories = $user->passwordHistories()->take(Constants::PASSWORD_HISTORY_NUM)->get();
                foreach($passwordHistories as $passwordHistory){
                    if (Hash::check($credentials['new_password'], $passwordHistory->password)) {
                        $validator->errors()->add('new_password',  Lang::get('auth.password.new_password_matches_prev'));
                        break;
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('password.change')->withErrors($validator)
                ->withInput(['current_password', $credentials['current_password']]);
        }

        $passwordHash = bcrypt($credentials['new_password']);
        $user->password = $passwordHash;
        $user->confirmed = true;
        $user->save();

        $user->passwordSecurity->password_updated_at = Carbon::now();
        if ($site = Site::find($user->siteId)) {
            $user->passwordSecurity->password_expiry_days = $site->passwordExpiryDays;
        }
        $user->passwordSecurity->save();

        $passwordHistory = PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $passwordHash
        ]);

        return redirect()->route('main.home');
    }
}
