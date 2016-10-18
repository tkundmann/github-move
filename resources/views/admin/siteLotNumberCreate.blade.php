@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.site.lot_number.create.create', ['site' => $currentSite->title])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            {{ Form::open(['route' => ['admin.site.lotNumber.create', $currentSite->id], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'site_lot_number_create_form']) }}
                            {{ csrf_field() }}

                            <div id="textGroup" class="form-group{{----}}@if($errors->has('lot_number')) has-error @endif">
                                {{ Form::label('lot_number', trans('admin.site.lot_number.create.lot_number'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::textarea('lot_number', Input::get('lot_number') ? Input::get('lot_number') : old('lot_number'), ['class' => 'form-control']) }}
                                    @if ($errors->has('lot_number'))
                                        {!! $errors->first('lot_number', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-field alert-info text-center">
                                        @lang('admin.site.lot_number.create.info')
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success single-click"><i
                                            class="fa fa-btn fa-share-alt"></i>@lang('admin.site.lot_number.assign')</button>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

