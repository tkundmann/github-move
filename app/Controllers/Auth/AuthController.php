<?php

namespace App\Controllers\Auth;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Role;
use App\Data\Models\User;
use App\Data\Models\PasswordSecurity;
use Carbon\Carbon;
use App\Helpers\ContextHelper;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Arr;

class AuthController extends ContextController
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $maxAttempts = 4;
    protected $maxLoginAttempts = Constants::DEFAULT_MAX_FAILED_LOGIN_ATTEMPTS;
    protected $lockoutTime = Constants::DEFAULT_ACCOUNT_LOCKOUT_TIME;
    protected $decayMinutes = Constants::MAX_FAILED_LOGIN_ATTEMPTS_TIME_PERIOD;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->redirectPath = route('main.home');
        $this->redirectAfterLogout = route('login');
        $this->middleware($this->guestMiddleware(), ['except' => ['logout']]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        $lockedOut = $this->hasTooManyLoginAttempts($request);
        if ($throttles && $lockedOut) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = array_merge($this->getCredentials($request), ['site_id' => $this->getSiteId()]);

        if (!Auth::validate($credentials)) {
            $credentials = array_merge($this->getCredentials($request), ['site_id' => null]);
            if (!Auth::validate($credentials)) {

				// If the login attempt was unsuccessful we will increment the number of attempts
				// to login and redirect the user back to the login form. Of course, when this
				// user surpasses their maximum number of attempts they will get locked out.
				if ($throttles && !$lockedOut) {
					$this->incrementLoginAttempts($request);

					if (! $this->retriesLeft($request)) {
                        // User only has one login attempt try left before risking being locked out.
						return $this->sendNearLockoutResponse($request);
					}
				}
                return $this->sendFailedLoginResponse($request);
            }
        }

        if (Auth::validate(array_merge($credentials, ['disabled' => true]))) {
            return $this->sendUserDisabledResponse($request);
        }

        $user = User::where($this->loginUsername(), $credentials[$this->loginUsername()])->where('site_id', $this->getSiteId())->first();
        if (!$user) {
            $user = User::where($this->loginUsername(), $credentials[$this->loginUsername()])->where('site_id', null)->first();
        }

        if (!$this->checkUserContextPermissions($user, $this->context)) {
            return $this->sendAccessDeniedResponse($request);
        }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

    }

    protected function incrementLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleKey($request), $this->decayMinutes
        );
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->passwordSecurity) {
            $request->session()->forget('password_expired_id');

            $password_updated_at = $user->passwordSecurity->password_updated_at;
            $password_expiry_days = $user->passwordSecurity->password_expiry_days;
            $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);
            $now = Carbon::now();
            $daysTilExpiration = $password_expiry_at->diffInDays($now);

            if($password_expiry_at->lt($now)){
                $request->session()->put('password_expired_id',$user->id);
                auth()->logout();
                return redirect()->route('password.expiration')->with('message', Lang::get('auth.password.change_expired_password'));
            }
            elseif ($daysTilExpiration <= Constants::NUM_DAYS_NOTFIY_PASSWORD_EXPIRING_SOON) {
                return redirect()->route('password.change')->with('message', Lang::get('auth.password.password_expiring_in_x_days', ['daysTilExpiration' => $daysTilExpiration]))->with('expiringSoon', 'yes');
            }
        }
        else {
            // The authenticated account does not have a password expiration record setup.
            // This may happened for existing accounts post launch of this feature.
            // Set up password expiration record.
            $passwordExpiryDays = Constants::DEFAULT_PASSWORD_EXPIRY_DAYS;
            if ($user->hasRole(Role::USER)) {
                // This is an account associated with a specific portal site, set the password
                // expiry days per site setting.
                if ($site = Site::find($user->siteId)) {
                    $passwordExpiryDays = $site->passwordExpiryDays;
                }
            }
            $passwordSecurity = PasswordSecurity::create([
                'user_id' => $user->id,
                'password_expiry_days' => $passwordExpiryDays,
                'password_updated_at' => Carbon::now()
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }

    protected function getThrottleKey(Request $request)
    {
        return mb_strtolower($this->context . '|' . $request->input($this->loginUsername())) . '|' . $request->ip();
    }

    /**
     * Redirect the user after determining they are near lock out.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendNearLockoutResponse(Request $request)
    {

        $minutes = ceil($this->lockoutTime / 60);

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'nearlockout' => $this->getNearLockoutErrorMessage($minutes),
            ]);
    }

    /**
     * Get the login near lockout error message.
     *
     * @param  int $minutes
     *
     * @return string
     */
    protected function getNearLockoutErrorMessage($minutes)
    {
        return Lang::has('auth.login.near_lock_out_threshold_minutes') ? Lang::get('auth.login.near_lock_out_threshold_minutes', ['minutes' => $minutes]) : 'You are nearing the account lockout threshold for faile login attempts.  Further failed login will result in your account being locked out for ' . $minutes . ' minutes.';
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

        return redirect()->back()
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
        return Lang::has('auth.login.throttle_minutes') ? Lang::get('auth.login.throttle_minutes', ['minutes' => $minutes]) : 'Too many login attempts. Your account has been locked out. Please try again in ' . $minutes . ' minutes.';
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'failed' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.login.failed') ? Lang::get('auth.login.failed') : 'These credentials are invalid.';
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendUserDisabledResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'disabled' => $this->getUserDisabledMessage()
            ]);
    }

    /**
     * Get the user disabled message.
     *
     * @return string
     */
    protected function getUserDisabledMessage()
    {
        return Lang::has('auth.login.user_disabled') ? Lang::get('auth.login.user_disabled') : 'User disabled.';
    }

    protected function checkUserContextPermissions(User $user, $context)
    {
        if ($user->hasRole([Role::ADMIN, Role::SUPERADMIN]) && !ContextHelper::isAdminContext($context)) {
            return false;
        }
        else if ($user->hasRole(Role::SUPERUSER) && !ContextHelper::isSiteContext($context)) {
            return false;
        }
        else if ($user->hasRole(Role::USER) && !ContextHelper::doesContextMatchUserSite($user, $context)) {
            return false;
        }
        else if ($user->roles->count() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendAccessDeniedResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'access_denied' => $this->getAccessDeniedMessage()
            ]);
    }

    /**
     * Get the user access denied.
     *
     * @return string
     */
    protected function getAccessDeniedMessage()
    {
        return Lang::has('auth.login.access_denied') ? Lang::get('auth.login.access_denied') : 'Access denied.';
    }
}
