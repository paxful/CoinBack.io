@extends('layouts.master')

@section('includes')
@parent
@stop

@section('content')
    <!-- .page-header -->
    <header class="page-header container text-center" style="margin-bottom:0;">
        <div class="col-sm-10 col-sm-offset-1">
            <h1>{{trans('web.submit_new_password')}}</h1>
        </div>
    </header>
    <!-- /.page-header -->

   <!-- CONTAINER -->
    <article class="container text-center">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">@include('session-message')</div>
            </div>
            <form action="{{ action('RemindersController@postReset') }}" class="form-horizontal" role="form" method="POST">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">{{trans('web.email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">{{trans('web.password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">{{trans('web.confirm_password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button class="btn btn-default btn-lg btn-block ladda-button" data-style="zoom-in" type="submit">
                            <span class="ladda-label">{{trans('web.reset_password')}}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </article>
    <!-- /.container -->
@stop

@section('footer-includes')
@parent
{{ HTML::script('js/custom.js'); }}
<script type="text/javascript">
    $(document).ready(function() {
        $('button[type=submit]').ladda('bind');
    });
</script>
@stop
