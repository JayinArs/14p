@extends('layouts.single')

@section('style')
    <style>
        body {
            background: #2C3A47;
        }
    </style>
@endsection

@section('content')
    <div class="block-center wd-xl">
        <div class="text-center mb-xl">
            <img src="img/logo.png" height="200"/>
        </div>
        <!-- START panel-->
        <div class="panel panel-flat">
            <div class="panel-body">
                <form role="form" data-parsley-validate="" novalidate="" class="mb-lg" method="POST"
                      action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="login_email" name="email" type="text" placeholder="Username"
                               autocomplete="off" required class="form-control">
                        <span class="fa fa-user form-control-feedback text-muted"></span>
                        @if ($errors->has('email'))
                            <ul class="parsley-errors-list filled">
                                <li class="parsley-required">{{ $errors->first('email') }}</li>
                            </ul>
                        @endif
                    </div>
                    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="login_password" name="password" type="password" placeholder="Password" required
                               class="form-control">
                        <span class="fa fa-lock form-control-feedback text-muted"></span>
                        @if ($errors->has('password'))
                            <ul class="parsley-errors-list filled">
                                <li class="parsley-required">{{ $errors->first('password') }}</li>
                            </ul>
                        @endif
                    </div>
                    <div class="clearfix">
                        <div class="checkbox c-checkbox pull-left mt0">
                            <label>
                                <input type="checkbox" value="1" name="remember">
                                <span class="fa fa-check"></span>Remember Me
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary mt-lg">Login</button>
                </form>
            </div>
        </div>
        <!-- END panel-->
    </div>
@endsection
