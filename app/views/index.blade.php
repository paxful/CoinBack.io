@extends('layouts.master')

@section('includes')
@parent
{{ HTML::style('/css/intlTelInput.css') }}
{{ HTML::style('/plugins/select2/select2.css') }}
{{ HTML::style('/plugins/select2/select2-custom.css') }}
@stop

@section('content')
<!-- .page-header -->
    <header class="page-header container text-center">
        <div class="col-sm-8 col-sm-offset-2">
            <h1>— Sell Bitcoin —</h1>
        </div>
    </header>
    <!-- /.page-header -->



    <!-- CONTAINER -->
    <article class="container text-center">
        <div class="col-sm-4">
            <div class="block bg-info">
                <div class="icon icon-norm color" data-icon="*"></div>
                <h4>Accept Bitcoin</h4>
                <ul>
                    <li>Project Scoping</li>
                    <li>System Design</li>
                    <li>Process Planning</li>
                    <li>Project management</li>
                    <li>Support</li>
                </ul>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="block">
                <div class="icon icon-norm" data-icon="+"></div>
                <h4>Sell Bitcoin</h4>
                <ul>
                    <li>Graphic design</li>
                    <li>Interface design</li>
                    <li>User Experience design</li>
                    <li>Search Engine Optimization</li>
                    <li>Authoring</li>
                </ul>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="block">
                <div class="icon icon-norm" data-icon="&#xe000;"></div>
                <h4>Track Your Profit</h4>
                <ul>
                    <li>Filmmaking</li>
                    <li>Television Production</li>
                    <li>Sound Recording</li>
                    <li>Sound Reproduction</li>
                    <li>Performance</li>
                </ul>
            </div>
        </div>
    </article>
    <!-- /.container -->


    <!-- CONTAINER -->
    <article class="container m-center">
        <div class="col-sm-4 col-sm-offset-1">
            <h3>About Company —</h3>
        </div>
        <div class="col-sm-6">
            <p>Ea nec enim accumsan, ut prima blandit mel, labores nonumes detraxit an sed. Omnis malis propriae an sed, eu mea erat utinam meliore, inciderint philosophia usu ne. Laudem labores eu sed, vix in omnis habemus omnesque.</p>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container m-center">
        <div class="col-sm-4 col-sm-offset-1">
            <h3>Merchant sign up —</h3>
        </div>
        <div class="signup-container col-sm-6">
            @include('session-message')
            @if ( $errors->count() > 0 )
            <div class="alert alert-warning">
                <p>The following errors have occurred:</p>
                <ul>
                    @foreach( $errors->all() as $message )
                    <li>{{{ $message }}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            {{ Form::open(array('url' => 'home/register', 'role' => 'form', 'id' => 'registerForm')); }}
                <div class="form-group">
                    {{ Form::text('business_name', null, array('class' => 'form-control', 'id' => 'business_name', 'placeholder' => 'Business name')) }}
                </div>
                <div class="form-group">
                    {{ Form::email('email', null, array('class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Phone number')) }}
                </div>
                {{ Form::hidden('location_id', null, array('id' => 'location_id')) }}
                <div class="form-group">
                    {{ Form::text('address', null, array('class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address')) }}
                </div>
                <div class="form-group country-container">
                    {{ Form::select('country', $country, null, array('id' => 'country', 'class' => 'form-control select2')) }}
                </div>
                <div class="form-group state-container">
                    {{ Form::hidden('state', null, array('id' => 'state', 'class' => 'form-control select2')) }}
                </div>
                <div class="form-group city-container">
                    {{ Form::hidden('city', null, array('id' => 'city', 'class' => 'form-control select2')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('post_code', null, array('class' => 'form-control', 'id' => 'post_code', 'placeholder' => 'Postal code')) }}
                </div>
                <input class="btn btn-default btn-block" type="submit" value="Register">
            {{ Form::close(); }}
        </div>
    </article>
    <!-- /.container -->


    <!-- CONTAINER -->
    <div class="container team text-center">
        <div class="col-sm-3 person">
            <a href="" data-toggle="modal" data-target="#person-1"><img alt="" src="http://placehold.it/240" class="img-circle"></a>
            <h5>MIKE Jhonson <small>Founder</small></h5>
        </div>
        <div class="col-sm-3 person">
            <a href="" data-toggle="modal" data-target="#person-2"><img alt="" src="http://placehold.it/240" class="img-circle"></a>
            <h5>ROBERT PLANT <small>designer</small></h5>
        </div>
        <div class="col-sm-3 person vacancy">
            <a href="" data-toggle="modal" data-target="#vacancy" class="img-circle gag"></a>
            <h5>Vacancy <small>project manager</small></h5>
        </div>
    </div>
    <!-- /.container -->
@stop

@section('footer-includes')
@parent
{{ HTML::script('js/intlTelInput.min.js'); }}
{{ HTML::script('plugins/select2/select2.min.js'); }}
{{ HTML::script('js/custom.js'); }}
@stop