<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>CoinBack</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    @section('includes')
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
    @show
</head>
<body class="home">

<!-- HEADER -->
<header class="header container">
    <div class="col-xs-3">
        <a href="index.html" class="logo">CoinBack</a>
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
                <!-- Cart-list -->
                <li class="cart-list">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/ico-cart.png" alt=""> cart <ins>3</ins></a>
                    <div class="dropdown-menu">
                        <ul>
                            <li>
                                <a href="product.html" class="fig text-center pull-left"><img src="http://placehold.it/130x150" alt=""></a>
                                <div>
                                    <a href="product.html">Plastic chair</a>
                                        <span class="price">
                                            <span class="amount">1 x $47,50</span>
                                        </span>
                                </div>
                            </li>
                            <li>
                                <a href="product.html" class="fig text-center pull-left"><img src="http://placehold.it/130x150" alt=""></a>
                                <div class="block">
                                    <a href="product.html">Plastic Chair</a>
                                        <span class="price">
                                            <span class="amount">3 x $89,99</span>
                                        </span>
                                </div>
                            </li>
                            <li>
                                <a href="product.html" class="fig text-center pull-left"><img src="http://placehold.it/130x150" alt=""></a>
                                <div class="block">
                                    <a href="product.html">Plastic Chair</a>
                                        <span class="price">
                                            <span class="amount">1 x $ 7 900,99</span>
                                        </span>
                                </div>
                            </li>
                        </ul>
                        <div class="hcart-total">
                            <a href="cart.html" class="btn btn-default btn-sm pull-left">Cart</a>
                            <div class="total pull-left">Total - <ins>$164,50</ins></div>
                        </div>
                    </div>
                </li>
                <!-- /.cart-list -->
            </ul>
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                <button type="button" class="pclose" data-toggle="collapse" data-target="#navbar-collapse"></button>
                <ul class="nav navbar-nav pull-right">
                    <li class="current-menu-item dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                        <ul class="dropdown-menu">
                            <li><a href="start_agency.html">Agency</a></li>
                            <li><a href="start_portfolio.html">Portfolio</a></li>
                            <li><a href="start_ecommerce.html">eCommerce</a></li>
                            <li class="current-menu-item"><a href="start_business.html">Business</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages</a>
                        <ul class="dropdown-menu">
                            <li><a href="about.html">About</a></li>
                            <li><a href="services.html">Services</a></li>
                            <li><a href="team.html">Our Team</a></li>
                            <li><a href="clients.html">Clients</a></li>
                            <li><a href="works.html">Works</a></li>
                            <li><a href="single_project_1.html">Single Work</a></li>
                            <li><a href="pricing.html">Pricing</a></li>
                            <li><a href="faq.html">FAQ</a></li>
                            <li><a href="contact.html">Contact</a></li>
                            <li><a href="404.html">Error 404</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                        <ul class="dropdown-menu">
                            <li><a href="blog_list.html">Blog List</a></li>
                            <li><a href="blog_post.html">Blog Post</a></li>
                            <li><a href="blog_post_sidebar.html">Blog Post with Sidebar</a></li>
                            <li><a href="post_gallery.html">Gallery Post</a></li>
                            <li><a href="post_audio.html">Audio Post</a></li>
                            <li><a href="post_video.html">Video Post</a></li>
                            <li><a href="post_link.html">Link Post</a></li>
                            <li><a href="post_quote.html">Quote Post</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Shop</a>
                        <ul class="dropdown-menu">
                            <li><a href="catalog_grid.html">Catalog Grid </a></li>
                            <li><a href="catalog_grid_sidebar_1.html">Catalog Grid Right Sidebar </a></li>
                            <li><a href="catalog_grid_sidebar_2.html">Catalog Grid Left Sidebar</a></li>
                            <li><a href="catalog_list.html">Catalog List</a></li>
                            <li><a href="catalog_list_sidebar_1.html">Catalog List Right Sidebar</a></li>
                            <li><a href="catalog_list_sidebar_2.html">Catalog List Left Sidebar</a></li>
                            <li><a href="product.html">Product Page</a></li>
                            <li><a href="cart.html">Shopping Cart</a></li>
                            <li><a href="cart_empty.html">Shopping Cart Empty</a></li>
                            <li><a href="checkout.html">Proceed to Checkout</a></li>
                            <li><a href="confirmation.html">Confirmation Page</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account</a>
                        <ul class="dropdown-menu">
                            <li><a href="login.html">Login</a></li>
                            <li><a href="lost_password.html">Lost Password</a></li>
                            <li><a href="change_password.html">Change Password</a></li>
                            <li><a href="account.html">My Account</a></li>
                            <li><a href="shipping_address.html">Edit Shipping Address</a></li>
                            <li><a href="billing_address.html">Edit Billing Address</a></li>
                            <li><a href="order_list.html">Order List</a></li>
                            <li><a href="order_view.html">View Order</a></li>
                        </ul>
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
{{ HTML::script('js/custom.js'); }}
@show
</body>
</html>