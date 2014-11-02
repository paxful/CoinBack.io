@extends('layouts.master')

@section('includes')
@parent
{{ HTML::style('/css/intlTelInput.css') }}
{{ HTML::style('/plugins/select2/select2.css') }}
{{ HTML::style('/plugins/select2/select2-custom.css') }}
@stop

@section('content')

    @include('session-message')

    <!-- CONTAINER -->
    <article class="container type-product">
        <div class="col-sm-6 magnific-wrap">
            <div class="img-medium text-center">
                <div class="medium-slider">
                    <a href="{{URL::to(Auth::user()->qr_code_path)}}" title="{{Auth::user()->bitcoin_address}}" class="magnific"><img src="{{URL::to(Auth::user()->qr_code_path)}}" alt=""></a>
                </div>
                <div class="col-xs-12">
                    <p>{{Auth::user()->bitcoin_address}}<br />
                    <a href="" class="btn btn-default btn-block" data-toggle="modal" data-target="#billCards">Print bill cards</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 m-center">
            <h3>Get started</h3>
            <span class="price">
                <span class="amount">$ 59,00</span>
            </span>
            <p>Acquire your bitcoins</p>
            <table class="table cart-total">
                <tr>
                    <th>SKU</th>
                    <td class="text-muted">345 678 0001</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><a href="catalog_grid_sidebar_2.html">Bar Stool</a>, <a href="catalog_list_sidebar_2.html">Armchair</a></td>
                </tr>
                <tr>
                    <th>Tags</th>
                    <td><a href="catalog_grid.html">Contemporary</a>, <a href="catalog_list_sidebar_1.html">Plastic</a>, <a href="catalog_list_sidebar_2.html">Outdoor</a></td>
                </tr>
            </table>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container text-center">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="countup extra color" data-increment="1" data-num="100" data-sign="&percnt;">0</div>
            <h3>— satisfaction —</h3>
            <p>Ea nec enim accumsan, ut prima blandit mel, labores nonumes detraxit an sed. Omnis malis propriae an sed, eu mea erat utinam meliore, inciderint philosophia usune. Laudem labores eu sed.</p>
        </div>
    </article>
    <!-- /.container -->

    <hr/>

    <!-- CONTAINER -->
    <article class="container text-center inforow">
        <div class="col-md-3 col-sm-6">
            <div class="countup" data-increment="3" data-num="324">0</div>
            <h4>clients</h4>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup" data-increment="69" data-num="6980">0</div>
            <h4>burgers</h4>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup" data-increment="8" data-num="780">0</div>
            <h4>projects</h4>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup" data-increment="24" data-num="2450">0</div>
            <h4>sketches</h4>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container">
        <h3 class="text-center">— Ledger —</h3>
        <div class="table-responsive">
            <table class="table shop_table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Price</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="order-number"><a href="">#80</a></td>
                        <td class="order-date">August 29, 2014</td>
                        <td class="order-status">Pending</td>
                        <td class="order-total">$35 for 1 item</td>
                        <td class="order-actions text-right">
                            <a href="">Pay</a>
                            <a href="">Cancel</a>
                            <a href="">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-number"><a href="">#79</a></td>
                        <td class="order-date">August 9, 2014</td>
                        <td class="order-status">Pending</td>
                        <td class="order-total">$38.95 for 2 items</td>
                        <td class="order-actions text-right">
                            <a href="">Pay</a>
                            <a href="">Cancel</a>
                            <a href="">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-number"><a href="">#62</a></td>
                        <td class="order-date">May 13, 2013</td>
                        <td class="order-status color">Cancelled</td>
                        <td class="order-total">$184 for 6 items</td>
                        <td class="order-actions text-right">
                            <a href="">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-number"><a href="">#61</a></td>
                        <td class="order-date">April 3, 2013</td>
                        <td class="order-status color">Cancelled</td>
                        <td class="order-total">$35 for 1 item</td>
                        <td class="order-actions text-right">
                            <a href="">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-number"><a href="">#60</a></td>
                        <td class="order-date">December 29, 2012 </td>
                        <td class="order-status color">Cancelled</td>
                        <td class="order-total">$3 500 for 1 item</td>
                        <td class="order-actions text-right">
                            <a href="">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container text-center">
        <h2>— services —</h2>
        <div class="col-sm-4">
            <div class="block bg-info">
                <div class="icon icon-norm color" data-icon="*"></div>
                <h4>consulting</h4>
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
                <h4>web design </h4>
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
                <h4>sound art</h4>
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

    <!-- Modal -->
    <div class="modal fade bill-cards-modal" id="billCards">
        <a href="" class="pclose" data-dismiss="modal"></a>
        <div class="divtable">
            <div class="divcell">
                <article class="container text-center">
                    <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2 bill-cards-container">
                        <p class="jumbotron">These cards come with your QR code attached. <strong>Download, print and place</strong> them on your counter. Whenever you receive a payment, you will get text message on your phone.</p>
                        <div class="row portrait-row">
                            <div class="col-sm-6">
                                <img src="{{URL::to('images/poster811.png')}}" alt="CoinBack portrait color bill-card" title="Click below to download bill-card"/>
                                <p class="text-center">
                                    <a href="{{URL::to('control/bill-card?type=portrait-color');}}" title="Download portrait type color bill-card" class="btn btn-primary">Download</a>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <img src="{{URL::to('images/poster811bw.png')}}" alt="CoinBack portrait black and white bill-card" title="Click below to download bill-card"/>
                                <p class="text-center">
                                    <a href="{{URL::to('control/bill-card?type=portrait-bw');}}" title="Download portrait type black and white bill-card" class="btn btn-primary">Download</a>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="{{URL::to('images/poster118.png')}}" alt="CoinBack landscape color bill-card" title="Click below to download bill-card"/>
                                <p class="text-center">
                                    <a href="{{URL::to('control/bill-card?type=landscape-color');}}" title="Download landscape type color bill-card" class="btn btn-primary">Download</a>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <img src="{{URL::to('images/poster118bw.png')}}" alt="CoinBack landscape black and white bill-card" title="Click below to download bill-card" />
                                <p class="text-center">
                                    <a href="{{URL::to('control/bill-card?type=landscape-bw');}}" title="Download landscape type black and white bill-card" class="btn btn-primary">Download</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@stop

@section('footer-includes')
@parent
{{ HTML::script('js/intlTelInput.min.js'); }}
{{ HTML::script('plugins/select2/select2.min.js'); }}
{{ HTML::script('js/custom.js'); }}
@stop