@extends('email.emailLayout')

@section('content')
    <table>
        <tr>
            <td>
                <h1>@lang('email.account_created.welcome')</h1>
                <p>@lang('email.account_created.info')</p>
                <br />
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>@lang('email.account_created.account_login')</td>
                        <td>{{ $account->email }}</td>
                    </tr>
                    <tr>
                        <td>@lang('email.account_created.account_name')</td>
                        <td>{{ $account->name }}</td>
                    </tr>
                </table>
                <br />
                <p>@lang('email.account_created.ask_admin_for_password')</p>
                <br />
                <p>@lang('email.account_created.login_info')</p>
                <a href="{{ route('login', ['context' => ($account->site) ? $account->site->code : Constants::CONTEXT_ADMIN]) }}">{{ route('login', ['context' => ($account->site) ? $account->site->code : Constants::CONTEXT_ADMIN]) }}</a>
                <br />
                <br />
                <table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <a href="{{ route('login', ['context' => ($account->site) ? $account->site->code : Constants::CONTEXT_ADMIN]) }}">@lang('email.account_created.login')</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection