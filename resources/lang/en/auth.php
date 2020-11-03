<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication/Password Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'login'                        => [
        'shipments_assets_access_login' => 'Shipments/Assets Access Login',
        'admin_login'                   => 'Admin Login',
        'form_to_gain_access'           => 'Please use the form below to gain access to',
        'shipments_and_assets_data'     => 'Shipments and Assets Data',
        'username'                      => 'Username',
        'email_address'                 => 'E-mail address',
        'password'                      => 'Password',
        'remember_me'                   => 'Remember me',
        'login'                         => 'Login',
        'forgot_password'               => 'Forgot your password?',
        'near_lock_out_threshold_minutes' => 'Please note. One more failed login attempt will result in your account being locked out for :minutes minutes.',
        'throttle'                      => 'Due to too many failed login attempts, your account has been locked out.  Please try again in :seconds seconds.',
        'throttle_minutes'              => 'Due to too many failed login attempts, your account has been locked out.  Please try again in :minutes minutes.',
        'failed'                        => 'These credentials are invalid.',
        'user_disabled'                 => 'User disabled.',
        'access_denied'                 => 'Access denied.',
        'initial_login_reset_password'  => 'As a newly created account holder, you are required to reset your password before proceeding.'
    ],
    'password' => [
        'invalid_password'            => 'Password invalid! Passwords must meet the criteria list above and match the confirmation.',
        'invalid_token'               => 'This password reset token is invalid.',
        'invalid_user'                => 'We were unable to find a user with that e-mail address.',
        'reset'                       => 'Your password has been reset!',
        'reset_link_sent'             => 'We have e-mailed your password reset link!',
        'reset_password'              => 'Reset password',
        'email_address'               => 'E-mail address',
        'send_password_reset_link'    => 'Send password reset link',
        'password'                    => 'Password',
        'confirm_password'            => 'Confirm password',
        'change_password'             => 'Change password',
        'change_later'                => 'Change later',
        'password_expired'            => 'Password Expired',
        'password_expiring_in_x_days' => 'Your password expires in :daysTilExpiration day(s).  You will be required to change your password soon.  You may do so now, if you wish.',
        'change_expired_password'     => 'Your password is expired. You must change your password before proceeding.',
        'expired_password_changed'    => 'Password changed successfully. You may now login using your new password.',
        'current_password_not_match'  => 'Current password provided does not match the password associated with your account.',
        'new_current_password_match'  => 'New Password cannot be the same as your current password. Please choose a different password.',
        'new_password_matches_prev'   => 'Your new password can not be same as any of your recent passwords. Please choose a new password.',
        'current_password'            => 'Current password',
        'old_password'                => 'Old password',
        'new_password'                => 'New password',
        'confirm_new_password'        => 'Confirm new password',
        'old_password_not_match'      => 'Old password does not match.',
        'valid_password_criteria'     => '
            <p>Password must meet the following criteria:</p>
            <ul class="margin-bottom-lg">
                <li class="min-num-chars-criteria">Must have a minimum length of <span class="default-min-num-chars">:defaultMinNumChars</span><span class="admin-min-num-chars">:adminMinNumChars</span> characters in length</li>
                <li>Contain at least 1 uppercase letter</li>
                <li>Contain at least 1 lowercase letter</li>
                <li>Contain at least 1 number</li>
                <li>Contain at least 1 symbol or special character (e.g., @, #, &, etc.)</li>
            </ul>
        '
    ]

];
