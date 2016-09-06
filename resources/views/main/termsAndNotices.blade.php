@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('main.terms_and_notices.terms_and_notices.headline')</div>

                    <div class="panel-body">
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.terms_and_notices.headline')</p>
                            <p>@lang('main.terms_and_notices.terms_and_notices.text', ['website' => '<b>'.trans('main.terms_and_notices.terms_and_notices.website').'</b>', 'company' => '<b>'.trans('main.terms_and_notices.terms_and_notices.company').'</b>'])</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.acceptance_of_terms.headline')</p>
                            <p>@lang('main.terms_and_notices.acceptance_of_terms.text', ['additional_terms' => '<b>'.trans('main.terms_and_notices.acceptance_of_terms.additional_terms').'</b>'])</p>
                            <p>@lang('main.terms_and_notices.acceptance_of_terms.text_2')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.site_license_and_access.headline')</p>
                            <p>@lang('main.terms_and_notices.site_license_and_access.text')</p>
                            <p>@lang('main.terms_and_notices.site_license_and_access.text_2')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.modification_of_these_terms_of_use.headline')</p>
                            <p>@lang('main.terms_and_notices.modification_of_these_terms_of_use.text')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.trademarks.headline')</p>
                            <p>@lang('main.terms_and_notices.trademarks.text', ['trademarks' => '<b>'.trans('main.terms_and_notices.trademarks.trademarks').'</b>'])</p>
                            <p>@lang('main.terms_and_notices.trademarks.text_2')</p>
                            <p>@lang('main.terms_and_notices.trademarks.text_3')</p>
                            <p>@lang('main.terms_and_notices.trademarks.text_4')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.copyright.headline')</p>
                            <p>@lang('main.terms_and_notices.copyright.text')</p>
                            <p>@lang('main.terms_and_notices.copyright.text_2')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.service_descriptions.headline')</p>
                            <p>@lang('main.terms_and_notices.service_descriptions.text')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.disclaimer_of_warranties_and_limitation_of_liability.headline')</p>
                            <p>@lang('main.terms_and_notices.disclaimer_of_warranties_and_limitation_of_liability.text')</p>
                            <p>@lang('main.terms_and_notices.disclaimer_of_warranties_and_limitation_of_liability.text_2')</p>
                            <p>@lang('main.terms_and_notices.disclaimer_of_warranties_and_limitation_of_liability.text_3')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.indemnification.headline')</p>
                            <p>@lang('main.terms_and_notices.indemnification.text')</p>
                        </div>
                        <div class="col-md-10 col-md-push-1">
                            <p class="bold">@lang('main.terms_and_notices.applicable_law.headline')</p>
                            <p>@lang('main.terms_and_notices.applicable_law.text')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
