@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.accounts.edit.editing_user', ['user' => $account->name])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            {{ Form::open(['route' => ['admin.account.edit', $account->id], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'account_edit_form']) }}
                            {{ csrf_field() }}

                            <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                                {{ Form::label('name', trans('admin.accounts.user.name'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('name', Input::get('name') ? Input::get('name') : (old('name') ? old('name') : $account->name), ['class' => 'form-control']) }}
                                @if ($errors->has('name'))
                                    {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('email')) has-error @endif">
                                {{ Form::label('email', trans('admin.accounts.user.email'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('email', Input::get('email') ? Input::get('email') : (old('email') ? old('email') : $account->email), ['class' => 'form-control']) }}
                                    @if ($errors->has('email'))
                                        {!! $errors->first('email', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('roles')) has-error @endif">
                                {{ Form::label('roles', trans('admin.accounts.user.role'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('roles', $roles, Input::get('roles') ? Input::get('roles') : (old('roles') ? old('roles') : $account->roles()->lists('name')->toArray()), ['class' => 'selectpicker form-control']) }}
                                </div>
                            </div>

                            <div id="siteGroup" class="form-group @if($errors->has('site')) has-error @endif">
                                {{ Form::label('site', trans('admin.accounts.user.site'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('site', $sites, Input::get('site') ? Input::get('site') : (old('site') ? old('site') : $account->site_id), ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-field alert-info text-center">
                                        @lang('admin.accounts.create.site_reload_warning')
                                    </div>
                                </div>
                            </div>

                            @if ($vendorClients)
                                <div id="vendorClientsGroup"
                                     class="form-group @if($errors->has('vendor_clients')) has-error @endif">
                                    {{ Form::label('vendor_clients', trans('admin.accounts.user.vendor_clients'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                    <div class="col-sm-6">
                                        {{ Form::select('vendor_clients[]', $vendorClients, Input::get('vendor_clients') ? Input::get('vendor_clients') : (old('vendor_clients') ? old('vendor_clients') : $account->vendorClients()->lists('id')->toArray()), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple' => 'true']) }}
                                    </div>
                                </div>
                            @endif

                            @if ($lotNumbers)
                                <div id="lotNumbersGroup"
                                     class="form-group @if($errors->has('lot_numbers')) has-error @endif">
                                    {{ Form::label('lot_numbers', trans('admin.accounts.user.lot_numbers'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                    <div class="col-sm-6">
                                        {{ Form::select('lot_numbers[]', $lotNumbers, Input::get('lot_numbers') ? Input::get('lot_numbers') : (old('lot_numbers') ? old('lot_numbers') : $account->lotNumbers()->lists('id')->toArray()), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple' => 'true']) }}
                                    </div>
                                </div>
                            @endif

                            @if ($pages)
                                <div id="pagesGroup"
                                     class="form-group @if($errors->has('pages')) has-error @endif">
                                    {{ Form::label('pages', trans('admin.accounts.user.pages'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                    <div class="col-sm-6">
                                        {{ Form::select('pages[]', $pages, Input::get('pages') ? Input::get('pages') : (old('pages') ? old('pages') : $account->pages()->lists('id')->toArray()), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple' => 'true']) }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group @if($errors->has('confirmed')) has-error @endif">
                                {{ Form::label('confirmed', trans('admin.accounts.user.confirmed'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6 ">
                                    <label class="radio-inline">
                                        {{ Form::radio('confirmed', 1, Input::get('confirmed') !== null ? Input::get('confirmed') : (old('confirmed') !== null ? old('confirmed') : $account->confirmed)) }}
                                        @lang('common.true')
                                    </label>
                                    <label class="radio-inline">
                                        {{ Form::radio('confirmed', 0, Input::get('confirmed') !== null ? !Input::get('confirmed') : (old('confirmed') !== null ? !old('confirmed') : !$account->confirmed)) }}
                                        @lang('common.false')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('disabled')) has-error @endif">
                                {{ Form::label('disabled', trans('admin.accounts.user.enabled'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <label class="radio-inline">
                                        {{ Form::radio('disabled', 0, Input::get('disabled') !== null ? !Input::get('disabled') : (old('disabled') !== null ? !old('disabled') : !$account->disabled)) }}
                                        @lang('common.true')
                                    </label>
                                    <label class="radio-inline">
                                        {{ Form::radio('disabled', 1, Input::get('disabled') !== null ? Input::get('disabled') : (old('disabled') !== null ? old('disabled') : $account->disabled)) }}
                                        @lang('common.false')
                                    </label>
                                </div>
                            </div>

                            <hr />

                            <div class="text-center">
                                <button type="submit" class="btn btn-success single-click"><i
                                            class="fa fa-btn fa-floppy-o"></i>@lang('common.save')</button>
                                @if (Auth::user()->id == $account->id)
                                <a href="{{ route('password.change') }}" class="btn btn-primary margin-left-md"><i class="fa fa-btn fa-refresh"></i>@lang('auth.password.change_password')</a>
                                @endif
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $('#site').on('change', function (e) {
                $('#site').append('<input type="hidden" name="site_change" value="1"/>');
                $('#account_edit_form').submit();
            });

            var roleSelect = $('select[name="roles"]');
            var siteSelect = $('select[name="site"]');
            var lotNumbersSelect = $('select[name="lot_numbers[]"]');
            var vendorClientsSelect = $('select[name="vendor_clients[]"]');
            var pagesSelect = $('select[name="pages[]"]');

            var roleSelectValueChanged = false;

            roleSelect.on('change', function (event) {
                var value = $(this).val();
                roleSelectValueChanged = true;
                determineSiteSelectVisibility(value);
            });

            function determineSiteSelectVisibility(value) {
                var siteGroup = $('#siteGroup');
                var lotNumbersGroup = $('#lotNumbersGroup');
                var vendorClientsGroup = $('#vendorClientsGroup');
                var pagesGroup = $('#pagesGroup');

                var canChangeVisibility = !siteGroup.hasClass('has-error') || (siteGroup.hasClass('has-error') && roleSelectValueChanged);
                if (canChangeVisibility) {
                    if (value == '{{ head(array_where($roles, function ($key, $value) { return ($value == \App\Data\Models\Role::USER); })) }}') {
                        siteGroup.show();

                        var siteSelectValue = siteSelect.selectpicker('val');

                        if (siteSelectValue) {
                            if (lotNumbersGroup.length > 0) {
                                lotNumbersGroup.show();
                            }
                            if (vendorClientsGroup.length > 0) {
                                vendorClientsGroup.show();
                            }
                            if (pagesGroup.length > 0) {
                                pagesGroup.show();
                            }
                        }
                    }
                    else {
                        siteGroup.hide();
                        siteSelect.selectpicker('val', null);

                        if (vendorClientsGroup.length > 0) {
                            vendorClientsGroup.hide();
                            vendorClientsSelect.selectpicker('val', null);
                        }

                        if (lotNumbersGroup.length > 0) {
                            lotNumbersGroup.hide();
                            lotNumbersSelect.selectpicker('val', null);
                        }

                        if (pagesGroup.length > 0) {
                            pagesGroup.hide();
                            pagesSelect.selectpicker('val', null);
                        }
                    }
                }
            }

            roleSelect.on('loaded.bs.select', function (event) {
                determineSiteSelectVisibility(roleSelect.val());
            });
        });
    </script>
@endsection
