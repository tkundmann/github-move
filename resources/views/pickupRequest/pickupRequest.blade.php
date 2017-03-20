@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('pickup_request.title', [ 'title' => $data['title']])</div>
                    <div class="panel-body">
                        @if (Session::has('success'))
                            <div class="alert alert-success animate">{{ session('success') }}</div>
                        @endif

                        @if (!$errors->isEmpty())
                            <div class="alert alert-danger animate">
                                <strong>@lang('pickup_request.error')</strong>
                            </div>
                        @endif

                        <form id="pickupRequest" class="form-horizontal" method="POST" enctype="multipart/form-data"
                              action="{{ route('pickupRequest', ['token' => Input::get('token')]) }}">
                            {{ csrf_field() }}

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-btn fa-envelope-o"></i>@lang('common.submit')</button>
                                <button type="button" class="btn btn-primary resetButton">
                                    <i class="fa fa-btn fa-undo"></i> @lang('common.reset')
                                </button>
                            </div>
                            <p>@lang('pickup_request.denotes_required_field')</p>
                            <hr>

                            <div class="container-fluid">
                                @if($site->hasFeature(Feature::PICKUP_REQUEST_EQUIPMENT_LIST))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group margin-bottom-none">
                                                <label for="upload_equipment_list"
                                                       class="control-label colon-after">@lang('pickup_request.equipment_list')</label>
                                                <p class="small">@lang('pickup_request.equipment_list_info')</p>
                                                @foreach($site->getFeature(Feature::PICKUP_REQUEST_EQUIPMENT_LIST)->pivot->data as $file)
                                                    <div class="alert-info margin-bottom-md margin-top-md padding-md">
                                                        <a href="{{ $file['url'] }}" target="_blank"
                                                           class="btn btn-md btn-primary"><i
                                                                    class="fa fa-btn fa-download"></i>@lang('pickup_request.click_here')
                                                        </a>
                                                        <span class="margin-left-md">@lang('pickup_request.to_download') {{ $file['name'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('upload_equipment_list')) has-error @endif">
                                                {{ Form::label('upload_equipment_list', trans('pickup_request.upload_equipment_list'), ['class' => 'control-label colon-after-required']) }}
                                                <div class="">
                                                    <div class="fileinput fileinput-new input-group margin-bottom-none"
                                                         data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">@lang('common.file.select_file')</span>
                                                            <span class="fileinput-exists">@lang('common.file.change')</span>
                                                            <input type="file" id="upload_equipment_list" name="upload_equipment_list">
                                                        </span>
                                                        <span class="input-group-addon btn btn-default fileinput-exists"
                                                              data-dismiss="fileinput">@lang('common.file.remove')</span>
                                                    </div>
                                                    @if ($errors->has('upload_equipment_list'))
                                                        {!! $errors->first('upload_equipment_list', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                @endif

                                @if($site->hasFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK))
                                    @if($site->hasFeature(Feature::PICKUP_REQUEST_EQUIPMENT_LIST))
                                    <hr>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group @if($errors->has('site')) has-error @endif">
                                                <label class="control-label colon-after">{{ $site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['site_address_book_label'] }}</label>
                                                {{ Form::select('site', $addressBook, null, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                @if ($errors->has('site'))
                                                    {!! $errors->first('site', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                            <div class="form-group margin-vertical-none">
                                                <p class="bold margin-top-md">@lang('common.or')</p>
                                                <p>{{ $site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['new_site_text'] }}</p>
                                            </div>
                                            @if($site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['allow_change'])
                                                <div class="form-group margin-vertical-none @if($errors->has('site_name')) has-error @endif">
                                                <label for="site_name"
                                                       class="control-label colon-after">{{ $site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['new_site_address_book_label'] }}</label>
                                                <input id="site_name" type="text" class="form-control"
                                                       name="site_name" value="{{ old('site_name') }}">
                                                    @if ($errors->has('site_name'))
                                                        {!! $errors->first('site_name', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">
                                            <p>{!! $site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['change_text'] !!}</p>
                                            @if($site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['allow_change'])
                                                <div class="checkbox @if($errors->has('allow_change')) has-error @endif">
                                                    <label><input type="checkbox" name="allow_change"
                                                                  @if (old('allow_change')) checked="true" @endif
                                                                  id="allow_change">@lang('pickup_request.change_address_book_checkbox')
                                                    </label>
                                                    @if ($errors->has('allow_change'))
                                                        {!! $errors->first('allow_change', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="container-fluid">
                                <div class="row">
                                    @if($data['use_contact_section_title'])
                                        <p class="bold text-center">{{ $data['contact_section_title'] }}</p>
                                    @endif
                                    <div class="col-md-5">
                                        {{--Left column--}}
                                        <div class="form-group @if($errors->has('company_name')) has-error @endif">
                                            <label for="company_name"
                                                   class="control-label colon-after @if(in_array('company_name',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.company_name')</label>
                                            <input id="company_name" type="text" class="form-control"
                                                   name="company_name" value="{{ old('company_name') }}">
                                            @if ($errors->has('company_name'))
                                                {!! $errors->first('company_name', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('contact_name')) has-error @endif">
                                            <label for="contact_name"
                                                   class="control-label colon-after @if(in_array('contact_name',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.contact_name')</label>
                                            <input id="contact_name" type="text" class="form-control"
                                                   name="contact_name" value="{{ old('contact_name') }}">
                                            @if ($errors->has('contact_name'))
                                                {!! $errors->first('contact_name', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('contact_address_1') || $errors->has('contact_address_2'))has-error @endif">
                                            <label class="control-label colon-after @if(in_array('contact_address_1',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.contact_address')</label>
                                            <input id="contact_address_1" type="text"
                                                   class="form-control"
                                                   name="contact_address_1" value="{{ old('contact_address_1') }}">
                                            @if ($errors->has('contact_address_1'))
                                                {!! $errors->first('contact_address_1', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                            <input id="contact_address_2" type="text"
                                                   class="form-control margin-top-sm"
                                                   name="contact_address_2" value="{{ old('contact_address_2') }}">
                                            @if ($errors->has('contact_address_2'))
                                                {!! $errors->first('contact_address_2', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('contact_city')) has-error @endif">
                                            <label for="contact_city"
                                                   class="control-label colon-after @if(in_array('contact_city',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.city')</label>
                                            <input id="contact_city" type="text" class="form-control"
                                                   name="contact_city"
                                                   value="{{ old('contact_city') }}">
                                            @if ($errors->has('contact_city'))
                                                {!! $errors->first('contact_city', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        @if($data['use_state_as_select'])
                                            <div class="form-group @if($errors->has('contact_state')) has-error @endif">
                                                <label class="control-label colon-after @if(in_array('contact_state',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.state')</label>
                                                {{ Form::select('contact_state', array_flip($data['states']), old('contact_state'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                @if ($errors->has('contact_state'))
                                                    {!! $errors->first('contact_state', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @else
                                            <div class="form-group @if($errors->has('contact_state')) has-error @endif">
                                                <label for="contact_state"
                                                       class="control-label colon-after @if(in_array('contact_state',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.state_province')</label>
                                                <input id="contact_state" type="text" class="form-control"
                                                       name="contact_state" value="{{ old('contact_state') }}">
                                                @if ($errors->has('contact_state'))
                                                    {!! $errors->first('contact_state', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group @if($errors->has('contact_zip')) has-error @endif">
                                            <label for="contact_zip"
                                                   class="control-label colon-after @if(in_array('contact_zip',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.zip_code')</label>
                                            <input id="contact_zip" type="text" class="form-control"
                                                   name="contact_zip" value="{{ old('contact_zip') }}">
                                            @if ($errors->has('contact_zip'))
                                                {!! $errors->first('contact_zip', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">
                                        {{--Right column--}}
                                        @if($data['use_country'])
                                            @if($data['countries'])
                                                <div class="form-group @if($errors->has('contact_country')) has-error @endif">
                                                    <label class="control-label colon-after @if(in_array('contact_country',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.country')</label>
                                                    {{ Form::select('contact_country', $data['countries'], old('contact_country'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                    @if ($errors->has('contact_country'))
                                                        {!! $errors->first('contact_country', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @else
                                                <div class="form-group @if($errors->has('contact_country')) has-error @endif">
                                                    <label for="contact_country"
                                                           class="control-label colon-after @if(in_array('contact_country',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.country')</label>
                                                    <input id="contact_country" type="text" class="form-control"
                                                           name="contact_country"
                                                           value="{{ old('contact_country') }}">
                                                    @if ($errors->has('contact_country'))
                                                        {!! $errors->first('contact_country', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @endif
                                        @endif

                                        @if($data['use_company_division'])
                                            <div class="form-group @if($errors->has('company_division')) has-error @endif">
                                                <label class="control-label colon-after @if(in_array('company_division',$data['required_fields'],true)) colon-after-required @endif">{{ $data['company_division_label'] }}</label>
                                                {{ Form::select('company_division', $data['company_divisions'], old('company_division'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                @if ($errors->has('company_division'))
                                                    {!! $errors->first('company_division', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group @if($errors->has('contact_phone_number')) has-error @endif">
                                            <label for="contact_phone_number"
                                                   class="control-label colon-after @if(in_array('contact_phone_number',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.phone_number')</label>
                                            <input id="contact_phone_number" type="text" class="form-control"
                                                   name="contact_phone_number"
                                                   value="{{ old('contact_phone_number') }}">
                                            @if ($errors->has('contact_phone_number'))
                                                {!! $errors->first('contact_phone_number', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('contact_cell_number')) has-error @endif">
                                            <label for="contact_cell_number"
                                                   class="control-label colon-after @if(in_array('contact_cell_number',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.cell_number')</label>
                                            <input id="contact_cell_number" type="text" class="form-control"
                                                   name="contact_cell_number"
                                                   value="{{ old('contact_cell_number') }}">
                                            @if ($errors->has('contact_cell_number'))
                                                {!! $errors->first('contact_cell_number', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('contact_email_address')) has-error @endif">
                                            <label for="contact_email_address"
                                                   class="control-label colon-after @if(in_array('contact_email_address',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.email_address')</label>
                                            <input id="contact_email_address" type="text" class="form-control"
                                                   name="contact_email_address"
                                                   value="{{ old('contact_email_address') }}">
                                            @if ($errors->has('contact_email_address'))
                                                {!! $errors->first('contact_email_address', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            @if($data['use_reference_number'])
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('reference_number')) has-error @endif">
                                                <label for="reference_number"
                                                       class="control-label colon-after @if(in_array('reference_number',$data['required_fields'],true)) colon-after-required @endif">{{ $data['reference_number_label'] }}</label>
                                                <input id="reference_number" type="text" class="form-control"
                                                       name="reference_number"
                                                       value="{{ old('reference_number') }}">
                                                @if ($errors->has('reference_number'))
                                                    {!! $errors->first('reference_number', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                                <p class="small">@lang('pickup_request.reference_number_note', ['reference_number_label' => $data['reference_number_label']])</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                    </div>
                                </div>
                            <hr>
                            @endif

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-6">
                                        <p class="bold text-center">@lang('pickup_request.provide_piece_counts')</p>
                                        @if($data['use_alternative_piece_count_form'])
                                            <div>
                                                <div class="form-group @if($errors->has('num_internal_hard_drives')) has-error @endif">
                                                    <label for="num_internal_hard_drives"
                                                           class="control-label colon-after">@lang('pickup_request.internal_hard_drives')</label>
                                                    <input id="num_internal_hard_drives" type="text"
                                                           class="form-control"
                                                           name="num_internal_hard_drives"
                                                           value="{{ old('num_internal_hard_drives', 0) }}">
                                                    @if ($errors->has('num_internal_hard_drives'))
                                                        {!! $errors->first('num_internal_hard_drives', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                                <div class="form-group @if($errors->has('internal_hard_drive_encrypted')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.encrypted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('internal_hard_drive_encrypted', 1, old('internal_hard_drive_encrypted')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('internal_hard_drive_encrypted', 0, !old('internal_hard_drive_encrypted')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('internal_hard_drive_encrypted'))
                                                        {!! $errors->first('internal_hard_drive_encrypted', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                                <div class="form-group @if($errors->has('internal_hard_drive_wiped')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.hard_drive_wiped'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('internal_hard_drive_wiped', 1, old('internal_hard_drive_wiped')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('internal_hard_drive_wiped', 0, !old('internal_hard_drive_wiped')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('internal_hard_drive_wiped'))
                                                        {!! $errors->first('internal_hard_drive_wiped', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="form-group @if($errors->has('num_desktops')) has-error @endif">
                                                <label for="num_desktops"
                                                       class="control-label colon-after">@lang('pickup_request.desktop')</label>
                                                <input id="num_desktops" type="text" class="form-control"
                                                       name="num_desktops" value="{{ old('num_desktops', 0) }}">
                                                @if ($errors->has('num_desktops'))
                                                    {!! $errors->first('num_desktops', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                            @if($data['use_alternative_piece_count_form'])
                                                <div class="form-group @if($errors->has('desktop_encrypted')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.encrypted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('desktop_encrypted', 1, old('desktop_encrypted')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('desktop_encrypted', 0, !old('desktop_encrypted')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('desktop_encrypted'))
                                                        {!! $errors->first('desktop_encrypted', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                                <div class="form-group @if($errors->has('desktop_hard_drive_wiped')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.hard_drive_wiped'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('desktop_hard_drive_wiped', 1, old('desktop_hard_drive_wiped')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('desktop_hard_drive_wiped', 0, !old('desktop_hard_drive_wiped')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('desktop_hard_drive_wiped'))
                                                        {!! $errors->first('desktop_hard_drive_wiped', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="form-group @if($errors->has('num_laptops')) has-error @endif">
                                                <label for="num_laptops"
                                                       class="control-label colon-after">@lang('pickup_request.laptop')</label>
                                                <input id="num_laptops" type="text" class="form-control"
                                                       name="num_laptops" value="{{ old('num_laptops', 0) }}">
                                                @if ($errors->has('num_laptops'))
                                                    {!! $errors->first('num_laptops', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                            @if($data['use_alternative_piece_count_form'])
                                                <div class="form-group @if($errors->has('laptop_encrypted')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.encrypted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('laptop_encrypted', 1, old('laptop_encrypted')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('laptop_encrypted', 0, !old('laptop_encrypted')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('laptop_encrypted'))
                                                        {!! $errors->first('laptop_encrypted', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                                <div class="form-group @if($errors->has('laptop_hard_drive_wiped')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.hard_drive_wiped'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('laptop_hard_drive_wiped', 1, old('laptop_hard_drive_wiped')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('laptop_hard_drive_wiped', 0, !old('laptop_hard_drive_wiped')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('laptop_hard_drive_wiped'))
                                                        {!! $errors->first('laptop_hard_drive_wiped', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_monitors')) has-error @endif">
                                            <label for="num_monitors"
                                                   class="control-label colon-after">@lang('pickup_request.monitor')</label>
                                            <input id="num_monitors" type="text" class="form-control"
                                                   name="num_monitors" value="{{ old('num_monitors', 0) }}">
                                            @if ($errors->has('num_monitors'))
                                                {!! $errors->first('num_monitors', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_printers')) has-error @endif">
                                            <label for="num_printers"
                                                   class="control-label colon-after">@lang('pickup_request.printer')</label>
                                            <input id="num_printers" type="text" class="form-control"
                                                   name="num_printers" value="{{ old('num_printers', 0) }}">
                                            @if ($errors->has('num_printers'))
                                                {!! $errors->first('num_printers', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="form-group @if($errors->has('num_servers')) has-error @endif">
                                                <label for="num_servers"
                                                       class="control-label colon-after">@lang('pickup_request.server')</label>
                                                <input id="num_servers" type="text" class="form-control"
                                                       name="num_servers" value="{{ old('num_servers', 0) }}">
                                                @if ($errors->has('num_servers'))
                                                    {!! $errors->first('num_servers', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                            @if($data['use_alternative_piece_count_form'])
                                                <div class="form-group @if($errors->has('server_encrypted')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.encrypted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('server_encrypted', 1, old('server_encrypted')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('server_encrypted', 0, !old('server_encrypted')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('server_encrypted'))
                                                        {!! $errors->first('server_encrypted', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                                <div class="form-group @if($errors->has('server_hard_drive_wiped')) has-error @endif">
                                                    {{ Form::label('disabled', trans('pickup_request.hard_drive_wiped'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline">
                                                            {{ Form::radio('server_hard_drive_wiped', 1, old('server_hard_drive_wiped')) }}
                                                            @lang('common.yes')
                                                        </label>
                                                        <label class="radio-inline">
                                                            {{ Form::radio('server_hard_drive_wiped', 0, !old('server_hard_drive_wiped')) }}
                                                            @lang('common.no')
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('server_hard_drive_wiped'))
                                                        {!! $errors->first('server_hard_drive_wiped', '<small class="text-danger">:message</small>') !!}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_networking')) has-error @endif">
                                            <label for="num_networking"
                                                   class="control-label colon-after">@lang('pickup_request.networking')</label>
                                            <input id="num_networking" type="text" class="form-control"
                                                   name="num_networking" value="{{ old('num_networking', 0) }}">
                                            @if ($errors->has('num_networking'))
                                                {!! $errors->first('num_networking', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_storage_systems')) has-error @endif">
                                            <label for="num_storage_systems"
                                                   class="control-label colon-after">@lang('pickup_request.storage_system')</label>
                                            <input id="num_storage_systems" type="text" class="form-control"
                                                   name="num_storage_systems"
                                                   value="{{ old('num_storage_systems', 0) }}">
                                            @if ($errors->has('num_storage_systems'))
                                                {!! $errors->first('num_storage_systems', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_ups')) has-error @endif">
                                            <label for="num_ups"
                                                   class="control-label colon-after">@lang('pickup_request.ups')</label>
                                            <input id="num_ups" type="text" class="form-control"
                                                   name="num_ups" value="{{ old('num_ups', 0) }}">
                                            @if ($errors->has('num_ups'))
                                                {!! $errors->first('num_ups', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        @if(!$data['use_alternative_piece_count_form'])
                                            <div class="form-group @if($errors->has('num_racks')) has-error @endif">
                                                <label for="num_racks"
                                                       class="control-label colon-after">@lang('pickup_request.racks')</label>
                                                <input id="num_racks" type="text" class="form-control"
                                                       name="num_racks" value="{{ old('num_racks', 0) }}">
                                                @if ($errors->has('num_racks'))
                                                    {!! $errors->first('num_racks', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif
                                        <div class="form-group @if($errors->has('num_other')) has-error @endif">
                                            <label for="num_other"
                                                   class="control-label colon-after">@lang('pickup_request.other')</label>
                                            <input id="num_other" type="text" class="form-control"
                                                   name="num_other" value="{{ old('num_other', 0) }}">
                                            @if ($errors->has('num_other'))
                                                {!! $errors->first('num_other', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                        <div class="form-group @if($errors->has('num_misc')) has-error @endif">
                                            <label for="num_misc"
                                                   class="control-label colon-after">@lang('pickup_request.misc')</label>
                                            <input id="num_misc" type="text" class="form-control"
                                                   name="num_misc" value="{{ old('num_misc', 0) }}">
                                            @if ($errors->has('num_misc'))
                                                {!! $errors->first('num_misc', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>


                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-6">
                                        @if($data['use_preferred_pickup_date'])
                                            <div class="form-group @if($errors->has('preferred_pickup_date')) has-error @endif">
                                                <label for="preferred_pickup_date"
                                                       class="control-label">@lang('pickup_request.preferred_date_pickup')</label>

                                                <div class="form-inline">
                                                    <input id="preferred_pickup_date" name="preferred_pickup_date" value="{{ old('preferred_pickup_date') }}" class="form-control" data-provide="datepicker">
                                                    {{ Form::select('preferred_pickup_date_hour', ['00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12'], old('preferred_pickup_date_hour'), ['class' => 'selectpicker form-control col-md-1', 'title' => trans('common.hour')]) }}
                                                    {{ Form::select('preferred_pickup_date_min', ['00' => '00', '15' => '15', '30' => '30', '45' => '45'], old('preferred_pickup_date_min'), ['class' => 'selectpicker form-control col-md-1', 'title' => trans('common.minute')]) }}
                                                    {{ Form::select('preferred_pickup_date_am_pm', ['AM' => 'AM', 'PM' => 'PM'], old('preferred_pickup_date_am_pm'), ['class' => 'selectpicker form-control col-md-1', 'title' => trans('common.meridiem')]) }}
                                                </div>
                                                @if ($errors->has('preferred_pickup_date'))
                                                    {!! $errors->first('preferred_pickup_date', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif
                                        @if($data['use_preferred_pickup_date_information'])
                                            <div class="form-group @if($errors->has('preferred_pickup_date_information')) has-error @endif">
                                                <label for="preferred_pickup_date_information"
                                                       class="control-label @if(in_array('preferred_pickup_date_information',$data['required_fields'],true)) colon-after-required @endif">@lang('pickup_request.preferred_date_pickup')</label>
                                                <input id="preferred_pickup_date_information"
                                                       value="{{ old('preferred_pickup_date_information') }}"
                                                       name="preferred_pickup_date_information"
                                                       class="form-control">
                                                @if ($errors->has('preferred_pickup_date_information'))
                                                    {!! $errors->first('preferred_pickup_date_information', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group @if($errors->has('units_located_near_dock')) has-error @endif">
                                            {{ Form::label('units_located_near_dock', trans('pickup_request.units_located_near_dock'), ['class' => 'col-sm-8 control-label']) }}
                                            <div class="col-sm-4">
                                                <label class="radio-inline">
                                                    {{ Form::radio('units_located_near_dock', 1, old('units_located_near_dock')) }}
                                                    @lang('common.yes')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('units_located_near_dock', 0, !old('units_located_near_dock')) }}
                                                    @lang('common.no')
                                                </label>
                                            </div>
                                            @if ($errors->has('units_located_near_dock'))
                                                {!! $errors->first('units_located_near_dock', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('units_on_single_floor')) has-error @endif">
                                            {{ Form::label('units_on_single_floor', trans('pickup_request.units_on_single_floor'), ['class' => 'col-sm-8 control-label']) }}
                                            <div class="col-sm-4">
                                                <label class="radio-inline">
                                                    {{ Form::radio('units_on_single_floor', 1, old('units_on_single_floor')) }}
                                                    @lang('common.yes')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('units_on_single_floor', 0, !old('units_on_single_floor')) }}
                                                    @lang('common.no')
                                                </label>
                                            </div>
                                            @if ($errors->has('units_on_single_floor'))
                                                {!! $errors->first('units_on_single_floor', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('is_loading_dock_present')) has-error @endif">
                                            {{ Form::label('is_loading_dock_present', trans('pickup_request.is_loading_dock_present'), ['class' => 'col-sm-8 control-label']) }}
                                            <div class="col-sm-4">
                                                <label class="radio-inline">
                                                    {{ Form::radio('is_loading_dock_present', 1, old('is_loading_dock_present')) }}
                                                    @lang('common.yes')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('is_loading_dock_present', 0, !old('is_loading_dock_present')) }}
                                                    @lang('common.no')
                                                </label>
                                            </div>
                                            @if ($errors->has('is_loading_dock_present'))
                                                {!! $errors->first('is_loading_dock_present', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('dock_appointment_required')) has-error @endif">
                                            {{ Form::label('dock_appointment_required', trans('pickup_request.dock_appointment_required'), ['class' => 'col-sm-8 control-label']) }}
                                            <div class="col-sm-4">
                                                <label class="radio-inline">
                                                    {{ Form::radio('dock_appointment_required', 1, old('dock_appointment_required')) }}
                                                    @lang('common.yes')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('dock_appointment_required', 0, !old('dock_appointment_required')) }}
                                                    @lang('common.no')
                                                </label>
                                            </div>
                                            @if ($errors->has('dock_appointment_required'))
                                                {!! $errors->first('dock_appointment_required', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('assets_need_packaging')) has-error @endif">
                                            {{ Form::label('assets_need_packaging', trans('pickup_request.assets_need_packaging'), ['class' => 'col-sm-8 control-label']) }}
                                            <div class="col-sm-4">
                                                <label class="radio-inline">
                                                    {{ Form::radio('assets_need_packaging', 1, old('assets_need_packaging')) }}
                                                    @lang('common.yes')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('assets_need_packaging', 0, !old('assets_need_packaging')) }}
                                                    @lang('common.no')
                                                </label>
                                            </div>
                                            @if ($errors->has('assets_need_packaging'))
                                                {!! $errors->first('assets_need_packaging', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        @if($data['use_lift_gate'])
                                            <div class="form-group @if($errors->has('is_lift_gate_needed')) has-error @endif">
                                                {{ Form::label('is_lift_gate_needed', trans('pickup_request.lift_gate_needed'), ['class' => 'col-sm-8 control-label']) }}
                                                <div class="col-sm-4">
                                                    <label class="radio-inline">
                                                        {{ Form::radio('is_lift_gate_needed', 1, old('is_lift_gate_needed')) }}
                                                        @lang('common.yes')
                                                    </label>
                                                    <label class="radio-inline">
                                                        {{ Form::radio('is_lift_gate_needed', 0, !old('is_lift_gate_needed')) }}
                                                        @lang('common.no')
                                                    </label>
                                                </div>
                                                @if ($errors->has('is_lift_gate_needed'))
                                                    {!! $errors->first('is_lift_gate_needed', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        @if($data['use_hardware_on_skids'])
                                            <div class="form-group @if($errors->has('hardware_on_skids')) has-error @endif">
                                                {{ Form::label('hardware_on_skids', trans('pickup_request.hardware_on_skids'), ['class' => 'col-sm-8 control-label']) }}
                                                <div class="col-sm-4">
                                                    <label class="radio-inline">
                                                        {{ Form::radio('hardware_on_skids', 1, old('hardware_on_skids')) }}
                                                        @lang('common.yes')
                                                    </label>
                                                    <label class="radio-inline">
                                                        {{ Form::radio('hardware_on_skids', 0, !old('hardware_on_skids')) }}
                                                        @lang('common.no')
                                                    </label>
                                                </div>
                                                @if ($errors->has('hardware_on_skids'))
                                                    {!! $errors->first('hardware_on_skids', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>

                                            <div class="form-group form-inline @if($errors->has('num_skids')) has-error @endif">
                                                <label for="num_skids" class="col-sm-9 control-label"><i class="fa fa-arrow-up"></i> @lang('pickup_request.how_many_skids')</label>
                                                <div class="col-sm-3">
                                                    <input id="num_skids" type="text" class="form-control"
                                                           name="num_skids"
                                                           value="{{ old('num_skids', 0) }}">
                                                </div>
                                                @if ($errors->has('num_skids'))
                                                    {!! $errors->first('num_skids', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="container-fluid">
                                <div class="row">
                                    <p class="bold text-center">@lang('pickup_request.building_manager_info')</p>

                                    <div class="col-md-5">
                                        {{--Left column--}}
                                        <div class="form-group @if($errors->has('bm_company_name')) has-error @endif">
                                            <label for="bm_company_name"
                                                   class="control-label colon-after">@lang('pickup_request.company_name')</label>
                                            <input id="bm_company_name" type="text" class="form-control"
                                                   name="bm_company_name" value="{{ old('bm_company_name') }}">
                                            @if ($errors->has('bm_company_name'))
                                                {!! $errors->first('bm_company_name', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('bm_contact_name')) has-error @endif">
                                            <label for="bm_contact_name"
                                                   class="control-label colon-after">@lang('pickup_request.contact_name')</label>
                                            <input id="bm_contact_name" type="text" class="form-control"
                                                   name="bm_contact_name" value="{{ old('bm_contact_name') }}">
                                            @if ($errors->has('bm_contact_name'))
                                                {!! $errors->first('bm_contact_name', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('bm_address_1') || $errors->has('bm_address_2')) has-error @endif">
                                            <label class="control-label colon-after">@lang('pickup_request.contact_address')</label>
                                            <input id="bm_address_1" type="text"
                                                   class="form-control"
                                                   name="bm_address_1" value="{{ old('bm_address_1') }}">
                                            @if ($errors->has('bm_address_1'))
                                                {!! $errors->first('bm_address_1', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                            <input id="bm_address_2" type="text"
                                                   class="form-control margin-top-sm"
                                                   name="bm_address_2" value="{{ old('bm_address_2') }}">
                                            @if ($errors->has('bm_address_2'))
                                                {!! $errors->first('bm_address_2', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('bm_city')) has-error @endif">
                                            <label for="bm_city"
                                                   class="control-label colon-after">@lang('pickup_request.city')</label>
                                            <input id="bm_city" type="text" class="form-control" name="bm_city"
                                                   value="{{ old('bm_city') }}">
                                            @if ($errors->has('bm_city'))
                                                {!! $errors->first('bm_city', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        @if($data['use_state_as_select'])
                                            <div class="form-group @if($errors->has('bm_state')) has-error @endif">
                                                <label class="control-label colon-after">@lang('pickup_request.state')</label>
                                                {{ Form::select('bm_state', array_flip($data['states']), old('bm_state'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                @if ($errors->has('bm_state'))
                                                    {!! $errors->first('bm_state', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @else
                                            <div class="form-group @if($errors->has('bm_state')) has-error @endif">
                                                <label for="bm_state"
                                                       class="control-label colon-after">@lang('pickup_request.state_province')</label>
                                                <input id="bm_state" type="text" class="form-control"
                                                       name="bm_state" value="{{ old('bm_state') }}">
                                                @if ($errors->has('bm_state'))
                                                    {!! $errors->first('bm_state', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group @if($errors->has('bm_zip')) has-error @endif">
                                            <label for="bm_zip"
                                                   class="control-label colon-after">@lang('pickup_request.zip_code')</label>
                                            <input id="bm_zip" type="text" class="form-control"
                                                   name="bm_zip" value="{{ old('bm_zip') }}">
                                            @if ($errors->has('bm_zip'))
                                                {!! $errors->first('bm_zip', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">
                                        {{--Right column--}}
                                        @if($data['use_country'])
                                            <div class="form-group @if($errors->has('bm_country')) has-error @endif">
                                                <label class="control-label colon-after">@lang('pickup_request.country')</label>
                                                {{ Form::select('bm_country', $data['countries'], old('bm_country'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'title' => trans('common.select')]) }}
                                                @if ($errors->has('bm_country'))
                                                    {!! $errors->first('bm_country', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @else
                                            <div class="form-group @if($errors->has('bm_country')) has-error @endif">
                                                <label for="bm_country"
                                                       class="control-label colon-after">@lang('pickup_request.country')</label>
                                                <input id="bm_country" type="text" class="form-control"
                                                       name="bm_country"
                                                       value="{{ old('bm_country') }}">
                                                @if ($errors->has('bm_country'))
                                                    {!! $errors->first('bm_country', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group @if($errors->has('bm_cell_number')) has-error @endif">
                                            <label for="bm_cell_number"
                                                   class="control-label colon-after">@lang('pickup_request.cell_number')</label>
                                            <input id="bm_cell_number" type="text" class="form-control"
                                                   name="bm_cell_number"
                                                   value="{{ old('bm_cell_number') }}">
                                            @if ($errors->has('bm_cell_number'))
                                                {!! $errors->first('bm_cell_number', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>

                                        <div class="form-group @if($errors->has('bm_email_address')) has-error @endif">
                                            <label for="bm_email_address"
                                                   class="control-label colon-after">@lang('pickup_request.email_address')</label>
                                            <input id="bm_email_address" type="text" class="form-control"
                                                   name="bm_email_address"
                                                   value="{{ old('bm_email_address') }}">
                                            @if ($errors->has('bm_email_address'))
                                                {!! $errors->first('bm_email_address', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('special_instructions')) has-error @endif">
                                            <label for="special_instructions"
                                                   class="control-label colon-after">@lang('pickup_request.special_instructions')</label>
                                            <textarea id="special_instructions" name="special_instructions"
                                                      class="form-control">{{ old('special_instructions') ?old('special_instructions') : null }}</textarea>
                                            @if ($errors->has('special_instructions'))
                                                {!! $errors->first('special_instructions', '<small class="text-danger">:message</small>') !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary single-click"><i
                                            class="fa fa-btn fa-envelope-o"></i>@lang('common.submit')</button>
                                <button type="button" class="btn btn-primary resetButton">
                                    <i class="fa fa-btn fa-undo"></i> @lang('common.reset')
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('.fileinput').on("change.bs.fileinput", function() {
                var value = $('input[name="upload_equipment_list"]').val();
                if (value) {
                    if (value.length > {{ $limit }}) {
                        $('.fileinput-filename').text(value.substr(0, {{ $limit }}) + '...');
                    }
                    else {
                        $('.fileinput-filename').text(value);
                    }
                }
            });

            $('.resetButton').click(function (event) {
                $('#pickupRequest')[0].reset();
                $('select.selectpicker').selectpicker('val', null);
            });

            $('select[name="site"]').on('change', function (event) {
                var value = $(this).val();

                var url = '{{ route('pickupRequest.address.get', ['context' => $context, 'id' => 'SITE_ID']) }}';
                url = url.replace('SITE_ID', value);
                url = url + '?token=' + '{{ Input::get('token') }}';

                $.get(url, function (data) {
                    if (data instanceof Object) {
                        var address = data;

                        $('input[name="company_name"]').val(address.company_name);
                        @if($data['use_company_division'])
                        $('select[name="company_division"]').selectpicker('val', address.company_division);
                        @endif
                        $('input[name="contact_name"]').val(address.contact_name);
                        $('input[name="contact_phone_number"]').val(address.contact_phone_number);
                        $('input[name="contact_address_1"]').val(address.contact_address_1);
                        $('input[name="contact_address_2"]').val(address.contact_address_2);
                        $('input[name="contact_city"]').val(address.contact_city);
                        @if($data['use_state_as_select'])
                        $('select[name="contact_state"]').selectpicker('val', address.contact_state);
                        @else
                          $('input[name="contact_state"]').val(address.contact_state);
                        @endif
                        $('input[name="contact_zip"]').val(address.contact_zip);
                        @if($data['use_country'])
                        $('select[name="contact_country"]').selectpicker('val', address.contact_country);
                        @endif
                        $('input[name="contact_cell_number"]').val(address.contact_cell_number);
                        $('input[name="contact_email_address"]').val(address.contact_email_address);
                    }
                });
            });
        });
    </script>
@endsection
