<?php

namespace App\Extensions\Auth;

use Illuminate\Auth\Passwords\PasswordBroker as IlluminatePasswordBroker;

class PasswordBroker extends IlluminatePasswordBroker
{
    const RESET_LINK_SENT = 'auth.password.reset_link_sent';
    const PASSWORD_RESET = 'auth.password.reset';
    const INVALID_USER = 'auth.password.invalid_user';
    const INVALID_PASSWORD = 'auth.password.invalid_password';
    const INVALID_TOKEN = 'auth.password.invalid_token';
    
    /**
     * Send the password reset link via e-mail in a queue
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @param  \Closure|null  $callback
     * @return int
     */
    public function emailResetLink(\Illuminate\Contracts\Auth\CanResetPassword $user, $token, \Closure $callback = null)
    {
        // We will use the reminder view that was given to the broker to display the
        // password reminder e-mail. We'll pass a "token" variable into the views
        // so that it may be displayed for an user to click for password reset.
        $view = $this->emailView;

        $title = trans('email.reset_password.email_title');

        return $this->mailer->queue($view, compact('title', 'token', 'user'), function ($m) use ($user, $token, $callback) {
            $m->to($user->getEmailForPasswordReset());
            if (! is_null($callback)) {
                call_user_func($callback, $m, $user, $token);
            }
        });
    }

    /**
     * Pre-validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return string
     */
    public function prevalidateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return self::INVALID_USER;
        }

        if (! $this->tokens->exists($user, $credentials['token'])) {
            return self::INVALID_TOKEN;
        }

        return null;
    }
}