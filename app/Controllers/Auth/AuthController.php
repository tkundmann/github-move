<?php

namespace App\Controllers\Auth;

use App\Controllers\ContextController;
use App\Data\Models\Role;
use App\Data\Models\User;
use App\Helpers\ContextHelper;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
        
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            return $this->sendLockoutResponse($request);
        }
    
        $credentials = array_merge($this->getCredentials($request), ['site_id' => $this->getSiteId()]);
        
        if (!Auth::validate($credentials)) {
            $credentials = array_merge($this->getCredentials($request), ['site_id' => null]);
            if (!Auth::validate($credentials)) {
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
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }
        
        return $this->sendFailedLoginResponse($request);
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
        
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'lockout' => $this->getLockoutErrorMessage($seconds),
            ]);
    }
    
    /**
     * Get the login lockout error message.
     *
     * @param  int $seconds
     *
     * @return string
     */
    protected function getLockoutErrorMessage($seconds)
    {
        return Lang::has('auth.login.throttle') ? Lang::get('auth.login.throttle', ['seconds' => $seconds]) : 'Too many login attempts. Please try again in ' . $seconds . ' seconds.';
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
