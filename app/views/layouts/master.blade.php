<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>CoinBack</title>
    <link href="{{ URL::to('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    @section('includes')
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/ladda-themeless.min.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/custom.css') }}
    @show
    <script type="text/javascript">
        var basePath = {{ json_encode(URL::to('/')); }};
    </script>
</head>
<body class="home">
<!-- HEADER -->
<header class="header container">
    <div class="col-xs-3">
        <a href="{{URL::to('/')}}" class="logo">CoinBack</a><br />
        <span style="white-space: nowrap;">project by <a target="_blank" href="https://easybitz.com">easybitz.com</a></span>
    </div>
    <div class="col-xs-9">
        <!-- Mainmenu -->
        <nav class="navbar mainmenu">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                <ul class="pull-right">
                    <li class="cart-list-empty">
                        @if(!Auth::check())
                            <a href="" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in"></i> Login</a>
                        @endif
                    </li>
                </ul>
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                <button type="button" class="pclose" data-toggle="collapse" data-target="#navbar-collapse"></button>
                <ul class="nav navbar-nav pull-right">
                    <li class="current-menu-item">
                        <a href="{{ URL::to('/') }}">Home</a>
                    </li>
                    @if(Auth::check())
                        <li>
                            <a href="#" data-toggle="modal" data-target="#accountModal"><i class="fa fa-user"></i> Account</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- /.mainmenu -->
    </div>
</header>
<!-- /.header -->

<!-- WRAPPER -->
<div class="wrapper">
@yield('content')
</div>
<!-- /.wrapper -->

@if(!Auth::check())
    @include('modals.login')
    @include('modals.forgot-password')
@endif

<!-- FOOTER -->
<footer class="footer">
    <!-- CONTAINER -->
    <div class="container">
        <div class="col-sm-4">
            <h5>About Us</h5>
            <p>CoinBack.io is an open source effort to grow bitcoin and bring stability to the bitcoin price.</p>
            <p>Small business makes up the majority of world commerce and the more small merchants are involved with bitcoin the more things will stabilize.</p>
        </div>
        <div class="col-sm-3">
            <h5>Contact</h5>
            <address>
                <p>+718 790 3303<br>
                <a href="mailto:easybitz@easybitz.com">email us</a></p>
                <p>Made with <i class="fa fa-heart fa-inverse"></i> in Las Vegas</p>
                <p>This project is Open Source. <a target="_blank" href="https://github.com/skyzer/coinback.io">Github link <i class="fa fa-github fa-inverse"></i></a></p>
            </address>
        </div>
        <div class="col-sm-5">
            <h5>Tools</h5>
            <ul>
                <li><a href="http://circle.com" target="_blank">Get bitcoin with a debit card <i class="fa fa-external-link fa-inverse"></i></a></li>
                <li><a href="http://coinbase.com" target="_blank">Get bitcoin with a bank account <i class="fa fa-external-link fa-inverse"></i></a></li>
                <li><a href="http://blockchain.info" target="_blank">Bitcoin Wallet <i class="fa fa-external-link fa-inverse"></i></a></li>
                <li><a href="http://easybitz.com" target="_blank">Bitcoin Point of Sale <i class="fa fa-external-link fa-inverse"></i></a></li>
            </ul>
        </div>
    </div>
    <!-- /.container -->

    <!-- CONTAINER -->
    <div class="container text-center">
        <p>&copy; 2014 All Rights Reserved</p>
    </div>
    <!-- /.container -->
</footer>
<!-- /.footer -->


<!-- ScrollTop Button -->
<a href="#" class="scrolltop"><i></i></a>
@section('footer-includes')
{{ HTML::script('js/jquery-2.1.1.min.js'); }}
{{ HTML::script('js/bootstrap.min.js'); }}
{{ HTML::script('js/jquery.plugins.js'); }}
{{ HTML::script('js/spin.min.js'); }}
{{ HTML::script('js/ladda.min.js'); }}
{{ HTML::script('js/ladda.jquery.min.js'); }}
@show
@if(!Auth::check())
    <script type="text/javascript">
    $(document).ready(function()
    {
        var loginBtn = $( '#btn-login-modal' );
        loginBtn.click(function(e) {
            e.preventDefault();

            loginBtn.ladda();
            loginBtn.ladda('start');

            $("#login-modal-form-message").empty();
            var $form = $('#loginForm');

            var token = $form.find( "input[name='_token']" ).val();
            var email = $form.find( "input[name='loginEmail']" ).val();
            var pass = $form.find( "input[name='password']" ).val();

            $.post($form.attr('action'), { email: email, password: pass, token: token }).done(function( data ) {
                if (data.status == 'success') {
                    $("#login-modal-form-message").html(data.message).fadeIn();
                    window.location.href = data.redirect;
                } else {
                    $("#login-modal-form-message").html(data.message).fadeIn();
                    loginBtn.ladda('stop');
                }
            }, 'json');
        });

        var btnResetPass = $('#btn-reset-pass-modal');
        btnResetPass.click(function(e) {
            e.preventDefault();

            btnResetPass.ladda();
            btnResetPass.ladda('start');

            var resetForm = $('#resetForm');
            var forgotPassEmail = resetForm.find( "#forgotPassEmail" ).val();
            $.post(resetForm.attr('action'), { forgotPassEmail: forgotPassEmail}).done(function( data ) {
                if (data.status == 'success') {
                    resetForm.find("#reminder-modal-form-message").removeClass().addClass('alert alert-success').html(data.message);
                } else {
                    resetForm.find("#reminder-modal-form-message").removeClass().addClass('alert alert-warning').html(data.message);
                }
                btnResetPass.ladda('stop');
            }, 'json')
                .fail(function() {
                resetForm.find("#reminder-modal-form-message").removeClass().addClass('alert alert-warning').html('<p>Unknown error</p>');
                btnResetPass.ladda('stop');
            });
        });
    });
    </script>
    @if(Session::has('password_reset'))
        <script type="text/javascript">
            $('#loginModal').modal('show');
        </script>
    @endif
@endif
</body>
</html>