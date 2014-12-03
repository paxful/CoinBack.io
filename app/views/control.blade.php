@extends('layouts.master')

@section('includes')
@parent
{{ HTML::style('/css/intlTelInput.css') }}
{{ HTML::style('/plugins/select2/select2.css') }}
{{ HTML::style('/css/select2-custom.css') }}
{{ HTML::style('/css/jquery.nouislider.min.css') }}
@stop

@section('content')

    @include('session-message')

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

    <article class="container text-center inforow bg-info">
        <div class="row bg-info">
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip">$<span id="merchantAverage">{{Auth::user()->average_rate}}</span></div>
                <h6>Your Bitcoin Average</h6>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="countup-skip currentExchangeRateContainer">
                    $<span id="currentExchangeRate">{{ApiHelper::getBitcoinPrice()}}</span>
                    <img class="rateAjaxLoader" style="display: none;" src="{{URL::to('images/ajax_loader_big.gif');}}" />
                    </div>
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
                <div class="row calculatorContainer">
                    <div class="col-sm-6 calculatorInputs">
                        <div class="form-group amountBTC-container">
                            <label class="col-xs-4 control-label">Bitcoin</label>
                            <div class="col-xs-6">
                                {{ Form::number('amountBTC', null, array('class' => 'form-control', 'id' => 'amountBTC', 'placeholder' => '0', 'step' => '0.00000001')) }}
                            </div>
                            <div class="col-xs-2"><a id="btn-max-btc" href="#" class="btn btn-info btn-xs">MAX</a></div>
                        </div>
                        <div class="form-group amountCurrency-container">
                            <label class="col-xs-4 control-label">USD Amount</label>
                            <div class="col-xs-6">
                                {{ Form::number('amountCurrency', null, array('class' => 'form-control', 'id' => 'amountCurrency', 'step' => '0.01', 'placeholder' => '0')) }}
                            </div>
                            <div class="col-xs-2">&nbsp;</div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-5">
                                <span>Premium</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2" id="premium"></div><div class="col-sm-2"><input name="premiumInput" id="premiumInput"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <table class="table cart-total">
                            <tbody>
                                <tr>
                                    <th>Final Exchange Rate</th>
                                    <td class="text-primary text-left">$ <span id="finalExchangeRate">{{ApiHelper::getBitcoinPrice()}}</span></td>
                                </tr>
                                <tr>
                                    <th>Fee 1%</th>
                                    <td class="text-primary text-left"><span id="fee">0</span> BTC</td>
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
                </div>
                <div class="col-sm-4">
                    <a href="" class="btn btn-info col-sm-11 disabled">Scan</a>
                </div>
                <div class="col-sm-4">
                    {{ Form::text('bitcoinAddress', null, array('class' => 'form-control col-xs-10 col-sm-offset-1', 'id' => 'bitcoinAddress', 'placeholder' => 'bitcoin address')) }}
                </div>
                <div class="col-sm-4">
                    {{ Form::email('bitcoinAddress', null, array('class' => 'form-control col-xs-10 col-sm-offset-1', 'id' => 'email', 'placeholder' => 'email')) }}
                </div>
                <div class="col-xs-12 send-payment-btn-container">
                    <button id="send-payment-btn" type="submit" class="btn btn-success col-sm-6 col-sm-offset-3" onclick="return confirm('Confirm send out bitcoins?');">Confirm & Send</button>
                </div>
            {{ Form::close() }}
        </div>
    </article>

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
    @include('modals.bill-cards')
    @include('modals.account')
@stop

@section('footer-includes')
@parent
{{ HTML::script('js/intlTelInput.min.js'); }}
{{ HTML::script('plugins/select2/select2.min.js'); }}
{{ HTML::script('js/jquery.nouislider.all.min.js'); }}
{{ HTML::script('js/merchant.js'); }}
{{ HTML::script('js/custom.js'); }}
{{ HTML::script('js/locationHelper.js'); }}
@stop