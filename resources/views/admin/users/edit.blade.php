@extends('admin.layouts.app')

@section('header')
    <h1>
        {{trans('list.update')}} {{trans('list.manager')}}
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header text-center">
                    <h3 class="box-title">{{trans('list.update')}} ({!! $company_name !!}) {{trans('list.manager')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-8 align-center">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('storeUser') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{!! (old('id')) ? old('id') : $userData->id !!}" />
                            <input type="hidden" name="company_id" value="{!! (old('company_id')) ? old('company_id') : $userData->company_id !!}" />
                            <input type="hidden" name="user_type" value="{!! (old('user_type')) ? old('user_type') : $userData->user_type !!}" />

                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-md-4 control-label">{{trans('list.firstName')}}</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ (old('first_name')) ? old('first_name') : $userData->first_name }}" required>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-md-4 control-label">{{trans('list.lastName')}}</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ (old('last_name')) ? old('last_name') : $userData->last_name }}" required>

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">{{trans('list.emailAddress')}}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" readonly="readonly" name="email" value="{{ (old('email')) ? old('email') : $userData->email }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-center text-clr">{{trans('list.optional')}}</p>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">{{trans('list.password')}}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">{{trans('list.confirmPassword')}}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{trans('list.submit')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

@endsection
