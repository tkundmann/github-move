<?php

namespace App\Controllers\Auth;

use App\Controllers\ContextController;
use App\Extensions\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    use ResetsPasswords;

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

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email', 'prevalidateResult'));
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with(compact('token', 'email', 'prevalidateResult'));
        }

        return view('auth.reset')->with(compact('token', 'email', 'prevalidateResult'));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
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
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);
        $credentials = array_merge($credentials, ['site_id' => $this->getSiteId()]);

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
    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:user,email,site_id,' . $this->getSiteId(true),
            'password' => 'required|confirmed|min:8|symbols',
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
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
    }
}
