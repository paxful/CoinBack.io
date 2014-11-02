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
        <a href="{{URL::to('/')}}" class="logo">CoinBack</a>
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
                            <a href="{{ URL::to('logout') }}" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-out"></i> Logout</a>
                        @endif
                    </li>
                </ul>
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                <button type="button" class="pclose" data-toggle="collapse" data-target="#navbar-collapse"></button>
                <ul class="nav navbar-nav pull-right">
                    <li class="current-menu-item">
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">Pages</a>
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
                    </form>
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
            <h5><a href="about.html">About Us</a></h5>
            <p>Mellentesque habitant morbi tristique senectus et netus et malesuada famesac turpis egestas. Ut non enim eleifend felis pretium feugiat.</p>
        </div>
        <div class="col-sm-3">
            <h5><a href="works.html">Latest works</a></h5>
            <ul class="latest-list">
                <li><a href="single_project_1.html"><img src="http://placehold.it/110x75" alt=""></a></li>
                <li><a href="single_project_2.html"><img src="http://placehold.it/110x75" alt=""></a></li>
                <li><a href="single_project_video.html"><img src="http://placehold.it/110x75" alt=""></a></li>
            </ul>
        </div>
        <div class="col-sm-3">
            <h5><a href="contact.html">Contact</a></h5>
            <address>
                <p>+44 20 7734 8000<br> +44 20 7734 8945<br>
                <a href="mailto:info@samplesamp.com">info@samplesamp.com</a></p>
                <p>Main Piccadilly Street London, UK</p>
            </address>
        </div>
        <div class="col-sm-2">
            <h5><a href="start_agency.html">Links</a></h5>
            <ul>
                <li><a href="start_business.html">Presentation</a></li>
                <li><a href="">Documentation</a></li>
                <li><a href="http://samplesamp.com" target="_blank">Support</a></li>
                <li><a href="contact.html">Terms Of Use</a></li>
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

<!-- Modal -->
<div class="modal fade person-modal" id="person-1">
    <a href="" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
                    <img src="http://placehold.it/240" alt="" class="img-circle">
                    <h4>MIKE Jhonson <small>Founder</small></h4>
                    <p>Ea nec enim accumsan, ut prima blandit mel, labores nonumes detraxit an sed. Omnis malis propriae an sed, eu mea erat utinam meliore, inciderint philosophia usu ne. Laudem labores eu sed, vix in omnis habemus omnesque.</p>
                    <p><a href="http://www.samplesamp.com" target="_blank">www.samplesamp.com</a></p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal -->
<div class="modal fade person-modal" id="person-2">
    <a href="" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
                    <img src="http://placehold.it/240" alt="" class="img-circle">
                    <h4>ROBERT PLANT <small>designer</small></h4>
                    <p>Ea nec enim accumsan, ut prima blandit mel, labores nonumes detraxit an sed. Omnis malis propriae an sed, eu mea erat utinam meliore, inciderint philosophia usu ne. Laudem labores eu sed, vix in omnis habemus omnesque.</p>
                    <p><a href="http://www.samplesamp.com" target="_blank">www.samplesamp.com</a></p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal -->
<div class="modal fade person-modal" id="vacancy">
    <a href="" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
                    <div class="img-circle gag"></div>
                    <h4>project manager <small>Vacancy</small></h4>
                    <p>Ea nec enim accumsan, ut prima blandit mel, labores nonumes detraxit an sed. Omnis malis propriae an sed, eu mea erat utinam meliore, inciderint philosophia usu ne. Laudem labores eu sed, vix in omnis habemus omnesque.</p>
                    <p><a href="contact.html" class="btn btn-link">Contact Us</a></p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- ScrollTop Button -->
<a href="#" class="scrolltop"><i></i></a>
@section('footer-includes')
{{ HTML::script('js/jquery-2.1.1.min.js'); }}
{{ HTML::script('js/bootstrap.min.js'); }}
{{ HTML::script('js/jquery.plugins.js'); }}
@show
</body>
</html>