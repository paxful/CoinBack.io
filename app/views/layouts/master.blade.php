<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>CoinBack</title>
    <link href="{{ URL::to('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    @section('includes')
    {{ HTML::style('css/bootstrap.min.css') }}
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
        <span>project by <a href="https://easybitz.com">easybitz.com</a></span>
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
                        @else
                            <a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                        @endif
                    </li>
                </ul>
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                <button type="button" class="pclose" data-toggle="collapse" data-target="#navbar-collapse"></button>
                <ul class="nav navbar-nav pull-right">
                    <li class="current-menu-item">
                        <a href="#">Home</a>
                    </li>
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
<div class="modal fade bill-cards-modal" id="loginModal">
    <a href="" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2 bill-cards-container">
                    <div class="icon" data-icon="Z"></div>
                    <h2>login</h2>
                    {{ Form::open(array('url' => 'home/login', 'role' => 'form', 'id' => 'loginForm')); }}
                        <div class="form-group">
                            <input name="loginEmail" id="loginEmail" class="form-control text-center" type="text" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input name="password" id="password" class="form-control text-center" type="password" placeholder="Password">
                        </div>
                        <input class="btn btn-default" type="submit" value="Login">
                        <p class="form-control-static"><a href="lost_password.html">Lost Password?</a></p>
                    {{Form::close()}}
                </div>
            </article>
        </div>
    </div>
</div>
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
                <a href="mailto:info@easybitz.com">email us</a></p>
                <p>Made with <i class="fa fa-heart fa-inverse"></i> in Las Vegas</p>
                <p>This project is Open Source. <a href="https://github.com/skyzer/coinback.io">Github link <i class="fa fa-github"></i></a></p>
            </address>
        </div>
        <div class="col-sm-5">
            <h5>Tools</h5>
            <ul>
                <li><a href="http://circle.com" target="_blank">Get bitcoin with a debit card <i class="fa fa-external-link"></i></a></li>
                <li><a href="http://coinbase.com" target="_blank">Get bitcoin with a bank account <i class="fa fa-external-link"></i></a></li>
                <li><a href="http://blockchain.info" target="_blank">Bitcoin Wallet <i class="fa fa-external-link"></i></a></li>
                <li><a href="http://easybitz.com" target="_blank">Bitcoin Point of Sale <i class="fa fa-external-link"></i></a></li>
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
@show
</body>
</html>