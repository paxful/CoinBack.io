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
            <div class="countup" data-increment="3" data-num="{{Auth::user()->average_rate}}" data-fractional="2" data-sign="$">0</div>
            <h6>Your Bitcoin Average</h6>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup" data-increment="69" data-num="{{ApiHelper::getBitcoinPrice()}}" data-fractional="2" data-sign="$">0</div>
            <h6>Current market price</h6>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup-skip">{{BitcoinHelper::satoshiToBtc(Auth::user()->bitcoin_balance)}}</div>
            <h6>Your Bitcoins</h6>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="countup color" data-increment="24" data-num="2450" data-sign="$">0</div>
            <h6><strong>Your Total profit</strong></h6>
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
                    @foreach ($transactions as $t)
                        <tr>
                            <td class="transaction-type">{{ $t->type == 'received' ? '<i class="fa fa-download"></i>' : '<i class="fa fa-external-link"></i>' }}</td>
                            <td class="transaction-bitcoin-amount">{{BitcoinHelper::satoshiToBtc($t->remaining_bitcoin)}} BTC</td>
                            <td class="transaction-rate">${{$t->bitcoin_current_rate_usd}}</td>
                            <td class="transaction-worth">${{$t->fiat_amount}}</td>
                            <td class="order-actions text-right">
                                <a href="">Pay</a>
                                <a href="">Cancel</a>
                                <a href="">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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