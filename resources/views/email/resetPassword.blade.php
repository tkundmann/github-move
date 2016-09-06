@extends('email.emailLayout')

@section('content')
    <table>
        <tr>
            <td>
                <h1>@lang('email.reset_password.welcome', ['user' => $user->name])</h1>
                <p>@lang('email.reset_password.info')</p>
                <p>@lang('email.reset_password.click_to_reset_password')</p>
                <br />
                <table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <a href="{{ route('password.reset', ['context' => ($user->site) ? $user->site->code : Constants::CONTEXT_ADMIN, 'token' => $token]).'?email='.urlencode($user->getEmailForPasswordReset()) }}">@lang('common.reset')</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection