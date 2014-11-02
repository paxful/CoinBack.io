@extends('layouts.master')

@section('includes')
@parent
{{ HTML::style('/css/intlTelInput.css') }}
{{ HTML::style('/plugins/select2/select2.css') }}
{{ HTML::style('/plugins/select2/select2-custom.css') }}
{{ HTML::style('/css/jquery.nouislider.min.css') }}
<script type="text/javascript">
    var currencyRate = <?php echo ApiHelper::getBitcoinPrice(); ?>;
</script>
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
            <p>Acquire your first bitcoins bitcoins by selling your goods or services or use one of our following partners</p>
            <table class="table cart-total">
                <tr>
                    <th>With Credit Card</th>
                    <td><a href="https://www.circle.com" target="_blank">Circle</a></td>
                </tr>
                <tr>
                    <th>With Your Bank</th>
                    <td><a href="https://www.coinbase.com" target="_blank">Coinbase</a></td>
                </tr>
            </table>
        </div>
    </article>
    <!-- /.container -->

    <!-- CONTAINER -->
    <article class="container text-center inforow bg-info">
        <div class="row bg-info">
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip">${{Auth::user()->average_rate}}</div>
                <h6>Your Bitcoin Average</h6>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip">${{ApiHelper::getBitcoinPrice()}}</div>
                <h6>Current market price</h6>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip" id="availableBalanceBTC">{{BitcoinHelper::satoshiToBtc(Auth::user()->bitcoin_balance)}}</div>
                <h6>Your Bitcoins</h6>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip color">${{Auth::user()->total_profit}}</div>
                <h6><strong>Your Total profit</strong></h6>
            </div>
        </div>
        <div class="row bg-info selling-container">
        <h3 class="text-center">— Sell —</h3>
            {{ Form::open(array('url' => 'control/send-payment', 'class' => 'form-horizontal', 'id' => 'sendBitcoinsForm', 'role' => 'form')) }}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Bitcoin</label>
                        <div class="col-xs-6">
                            <input name="amountBTC" id="amountBTC" class="form-control" type="number" placeholder="0" step="0.00000001">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">USD Amount</label>
                        <div class="col-xs-6">
                            <input name="amountCurrency" id="amountCurrency" class="form-control" type="number" step="0.01" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-5">
                            <span>Premium</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2" id="premium"></div><div class="col-sm-2"><input id="premiumInput"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <table class="table cart-total">
                        <tbody>
                            <tr>
                                <th>Sale Exchange Rate</th>
                                <td class="text-primary text-left">$ <span id="saleExchangeRate">{{ApiHelper::getBitcoinPrice()}}</span></td>
                            </tr>
                            <tr>
                                <th>Fee 1%</th>
                                <td class="text-primary text-left">$ <span id="fee">0</span></td>
                            </tr>
                            <tr>
                                <th>Your profit</th>
                                <td class="text-primary text-left"><strong>$ <span id="merchantProfit">0</span></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="jumbotron collect-info">
                        <table class="table cart-total">
                            <tbody>
                                <tr>
                                    <th>Collect</th>
                                    <td class="text-primary text-left">$ <span id="toCollect">0</span></td>
                                </tr>
                                <tr>
                                    <th>To Send</th>
                                    <td class="text-primary text-left"><span id="toSendBtc">0</span> BTC</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-4">
                    <a href="" class="btn btn-info col-sm-11 disabled">Scan</a>
                </div>
                <div class="col-sm-4">
                    <input class="form-control col-xs-10 col-sm-offset-1" type="text" placeholder="Bitcoin Address" disabled>
                </div>
                <div class="col-sm-4">
                    <input class="form-control col-xs-10 col-sm-offset-1" name="email" id="emai" type="email" placeholder="Email">
                </div>
                <div class="col-xs-12 send-payment-btn-container">
                    <button id="send-payment-btn" type="submit" class="btn btn-success col-sm-6 col-sm-offset-3" onclick="return confirm('Confirm send out bitcoins?');">Confirm & Send</button>
                </div>
            {{ Form::close() }}
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
                            <td class="order-actions text-right">{{!empty($t->sale_profit) ? $t->sale_profit : ''}}</td>
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
{{ HTML::script('js/jquery.nouislider.all.min.js'); }}
{{ HTML::script('js/merchant.js'); }}
{{ HTML::script('js/custom.js'); }}
@stop