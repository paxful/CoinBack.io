@extends('layouts.master')

@section('includes')
@parent
{{ HTML::style('/css/intlTelInput.css') }}
{{ HTML::style('/plugins/select2/select2.css') }}
{{ HTML::style('/plugins/select2/select2-custom.css') }}
@stop

@section('content')
    <!-- .page-header -->
    <header class="page-header container text-center" style="margin-bottom:0;">
        <div class="col-sm-10 col-sm-offset-1">
            <h2>Be Your Own Bitcoin Exchange</h2>
        </div>
    </header>
    <!-- /.page-header -->

    <!-- CONTAINER -->
    <article class="container text-center">
        <div class="col-sm-4">
            <div class="block bg-info">
                <div class="icon icon-norm color" data-icon="b"></div>
                <h4>1. Accept Bitcoin</h4>
                <ul>
                    <li>To sell bitcoin</li>
                    <li>Zero clicks</li>
                    <li>No internet needed</li>
                    <li>No smart phone needed</li>
                </ul>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="block">
                <div class="icon icon-norm" data-icon="j"></div>
                <h4>2. Sell Bitcoin</h4>
                <ul>
                    <li>Get new customers</li>
                    <li>Always at a profit</li>
                    <li>Potential for extreme profit</li>
                    <li>1% of your profit, no other fees</li>
                </ul>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="block">
                <div class="icon icon-norm" data-icon="s"></div>
                <h4>Track Your Profit</h4>
                <ul>
                    <li>Be your own exchange</li>
                    <li>Set your own profit</li>
                    <li>Super simple tools</li>
                    <li>Profit is addictive</li>
                </ul>
            </div>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container m-center">
        <div class="col-sm-12 col-sm-offset-2">
            <div class="signup-container col-sm-8">
                <h3>— Merchant sign up —</h3>
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
                    <input class="btn btn-default btn-block" type="submit" value="Start">
                    <span class="succs-msg">message was sent</span>
                {{ Form::close(); }}
            </div>

        </div>

    </article>
    <!-- /.container -->

    <!-- POST: Video -->
    <article class="post format-video">

        <header class="container text-center">
            <div class="col-sm-8 col-sm-offset-2">
                <h2>Meet your Future Customers</h2>
            </div>
        </header>

        <section class="entry-content">
            <article class="video-wrap">
                <div class="container">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="560" height="315" src="//www.youtube.com/embed/VzWIwy68dvo" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </article>
    <!-- /.post -->

   <!-- CONTAINER -->
    <article class="container text-center">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="countup extra color" data-increment="1" data-num="100" data-sign="&percnt;">0</div>
            <h3>— satisfaction —</h3>
            <p>The bitcoin community is passionate and will bring new life to your local network. Profit flows naturally where there is energy and you will be giving your entire </p>
        </div>
    </article>
    <!-- /.container -->

    <header class="container text-center">
        <div class="col-sm-8 col-sm-offset-2">
            <h2>Small Business on bitcoin</h2>
        </div>
    </header>

    <article class="quote-wrap bg-default text-center">
        <div class="container">
            <div class="col-sm-8 col-sm-offset-2">
                <blockquote>
                    <p>“ I only make 57 cents on a pack of cigarettes. With bitcoin I make around $7.00 in profit. A few sales a day really makes the difference between being in the black and the red.  ”</p>
                    <footer>Kay's NewsStand on 35th n 2nd</footer>
                </blockquote>
            </div>
        </div>
    </article>

    <!-- CONTAINER -->
    <article class="container m-center">
        <div class="col-sm-4 col-sm-offset-1">
            <h3>Regulation —</h3>
        </div>
        <div class="col-sm-6">
            <p>The regulatory landscape in crypto currency is still developing but a few things are clear.</p>
                
            <p>Bitcoin is a financial instrument and no state in the union taxes financial instruments. Capital gains however, are outlined clearly by the IRS as a tax on the profit made by exchanging commodities or currencies. Capital gains tax is automatically handled by our system. You, the merchant, bear the duty of paying the government.</p>

            <p>Merchants in the United States will be limited to selling only $50.00 worth of Bitcoin at a time. This allows merchants to operate without concern for AML (anti money launder) issues. International merchants have no such restrictions. Merchants are still encouraged to practice KYC ( know your customer). </p>
        </div>
    </article>
    <!-- /.container -->
@stop

@section('footer-includes')
@parent
{{ HTML::script('js/intlTelInput.min.js'); }}
{{ HTML::script('plugins/select2/select2.min.js'); }}
{{ HTML::script('js/custom.js'); }}
@stop