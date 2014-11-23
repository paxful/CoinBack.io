@extends('layouts.master')

@section('includes')
@parent
@stop

@section('navigation')

@stop

@section('content')

<div class="gap-40"></div>

<section id="features">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="text-right">Submit your new password</h1>
                <div class="row">
                    <div class="col-xs-12">
                        @include('session-message')
                        <form action="{{ action('RemindersController@postReset') }}" class="form-horizontal" role="form" method="POST">
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Confirm password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Reset Password">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('footer-includes')
@stop
<script type="text/javascript">
    $( 'input[type=submit]' ).ladda( 'bind' );
</script>