<?php

namespace App\Controllers\Auth;

use App\Controllers\ContextController;
use App\Extensions\Auth\PasswordBroker;
use App\Data\Constants;
use App\Data\Models\Site;
use App\Traits\Throttle;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Data\Models\User;
use App\Data\Models\PasswordHistory;
use Carbon\Carbon;
use DateTime;
use Lang;
use Validator;

class PasswordController extends ContextController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use AuthenticatesAndRegistersUsers, Throttle, ThrottlesLogins, ResetsPasswords {
        Throttle::getThrottleKey insteadof ThrottlesLogins;
        ResetsPasswords::guestMiddleware insteadof AuthenticatesAndRegistersUsers;
        ResetsPasswords::getGuard insteadof AuthenticatesAndRegistersUsers;
        ResetsPasswords::redirectPath insteadof AuthenticatesAndRegistersUsers;
    }

    protected $linkRequestView = 'auth.resetPasswordRequest';
    protected $resetView = 'auth.resetPassword';
    protected $subject = '';

    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->redirectPath = route('login');
        $this->middleware($this->guestMiddleware());

        $this->subject = trans('email.reset_password.email_title');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $context = null, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

        $credentials = [
            'email' => $email,
            'token' => $token
        ];
        $credentials = array_merge($credentials, ['site_id' => $this->getSiteId()]);

        if (!empty($credentials['email']) || !empty($credentials['token'])) {
            $broker = $this->getBroker();
            $prevalidateResult = Password::broker($broker)->prevalidateReset($credentials);
        }

        $user = User::where('email', $credentials['email'])->where('site_id', $this->getSiteId())->first();
        if (!$user) {
            $user = User::where('email', $credentials['email'])->where('site_id', null)->first();
        }

        $applicablePasswordLengthClass = 'default-min-chars-apply';
        if ($user->passwordRequiredLength() == User::ADMIN_SUPER_USER_PASSWORD_REQUIRED_LENGTH) {
            $applicablePasswordLengthClass = 'admin-min-chars-apply';
        }
        $defaultMinNumChars = User::PASSWORD_DEFAULT_REQUIRED_LENGTH;
        $adminMinNumChars   = User::ADMIN_SUPER_USER_PASSWORD_REQUIRED_LENGTH;

        $passwordCriteriaMsgReplacePairs = [
            'applicablePasswordLengthClass' => $applicablePasswordLengthClass,
            'defaultMinNumChars'            => $defaultMinNumChars,
            'adminMinNumChars'              => $adminMinNumChars
        ];
        $passwordCriteriaMsg = Lang::get('auth.password.valid_password_criteria', $passwordCriteriaMsgReplacePairs);

        $viewData = compact('token', 'email', 'prevalidateResult', 'applicablePasswordLengthClass', 'passwordCriteriaMsg');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with($viewData);
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with($viewData);
        }

        return view('auth.reset')->with($viewData);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $throttles = $this->isUsingThrottlesLoginsTrait();
        $throttleKey = $this->getThrottleKey($request);
        $lockedOut = (Cache::has($throttleKey.':lockout')) ? true : false;
        if ($throttles && $lockedOut) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        // we need to remove Request from the PasswordController, since its Closure is passed here to the sendResetLink method
        // apparently Illuminate\Http\Request cannot be serialized (serialization is done by creating a Mail job), so we have to detach it just for this

        $temp = $this->request;
        $this->request = null;
        $credentials = array_merge($this->getSendResetLinkEmailCredentials($request), ['site_id' => $this->getSiteId()]);
        $response = Password::broker($broker)->sendResetLink(
            $credentials,
            $this->resetEmailBuilder()
        );

        $this->request = $temp;

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse($response);
            case PasswordBroker::INVALID_USER:
            default:
                return $this->getSendResetLinkEmailFailureResponse($response);
        }
    }

    /**
     * Validate the request of sending reset link.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateSendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|exists:user,email,site_id,' . $this->getSiteId(true)]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->where('site_id', $this->getSiteId())->first();
        if (!$user) {
            $user = User::where('email', $email)->where('site_id', null)->first();
        }

		$passwordRequiredLength = User::PASSWORD_DEFAULT_REQUIRED_LENGTH;
		if ($user) {
			$passwordRequiredLength = $user->passwordRequiredLength();
		}
        $rules = $this->getResetValidationRules($passwordRequiredLength);

        $credentials = $this->getResetCredentials($request);
        $credentials = array_merge($credentials, ['site_id' => $this->getSiteId()]);

        $validator = Validator::make($credentials, $rules);

        $validator->after(function ($validator) use ($credentials, $user) {
        	if ($user) {
				$passwordHistories = $user->passwordHistories()->take(Constants::PASSWORD_HISTORY_NUM)->get();
				foreach ($passwordHistories as $passwordHistory) {
					if (Hash::check($credentials['password'], $passwordHistory->password)) {
						$validator->errors()->add('password', Lang::get('auth.password.new_password_matches_prev'));
						break;
					}
				}
			}
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput(['email', $credentials['email']]);
        }

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case PasswordBroker::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);
            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules($passwordRequiredLength)
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email|exists:user,email,site_id,' . $this->getSiteId(true),
            'password' => 'required|confirmed|min:' . $passwordRequiredLength . '|letters|numbers|symbols|case_diff'
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $passwordHash = bcrypt($password);

        $user->forceFill([
            'password' => $passwordHash,
            'remember_token' => Str::random(60),
        ])->save();

        if ($user->passwordSecurity) {
            $user->passwordSecurity->password_updated_at = Carbon::now();
            if ($site = Site::find($user->siteId)) {
                $user->passwordSecurity->password_expiry_days = $site->passwordExpiryDays;
            }
            $user->passwordSecurity->save();
        }

        $passwordHistory = PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $passwordHash
        ]);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        $minutes = ceil($seconds / 60);

        return redirect()->route('login')
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'lockout' => $this->getLockoutErrorMessage($minutes),
            ]);
    }

    /**
     * Get the login lockout error message.
     *
     * @param  int $minutes
     *
     * @return string
     */
    protected function getLockoutErrorMessage($minutes)
    {
        return Lang::has('auth.password.reset_throttle_minutes') ? Lang::get('auth.password.reset_throttle_minutes', ['minutes' => $minutes]) : 'Your account has been locked out. Password resetting not allowed for locked accounts. Please try again in ' . $minutes . ' minutes.';
    }

}
