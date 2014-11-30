var availableBalanceBTC = $('#availableBalanceBTC').text();
var finalExchangeRateElem = $("#finalExchangeRate");
var currentExchangeRate = $("#currentExchangeRate").text();
var merchantAverage = $("#merchantAverage").text();
var feeElem = $("#fee");
var merchantProfit = $("#merchantProfit");
var amountBtc = $("#amountBTC");
$(function() {
    var premium = $("#premium");
    premium.noUiSlider({
        start: [ 0 ],
        step: 1,
        range: {
            'min': [ 0 ],
            'max': [ 100 ]
        },
        format: wNumb({
            thousand: ',',
            postfix: ' %'
        })
    });
    premium.Link('lower').to($('#premiumInput'));

    amountBtc.tooltip({trigger: 'manual', title: 'Insufficient balance'});

    var premiumVal = premium.val().substr(0, premium.val().indexOf(' '));

    premium.on("change", function (ev) {
        var premiumVal = $(this).val().substr(0, $(this).val().indexOf(' ')).trim();
        var percentage = 100 + parseInt(premiumVal);
        percentage = percentage / 100;
        var newExchangeRate = percentage * currentExchangeRate;
        finalExchangeRateElem.text(parseFloat(newExchangeRate.toFixed(2)));
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
    });

    $("#premiumInput").on("change paste keyup", function (ev) {
        var newPremium = $(this).val().substr(0, $(this).val().indexOf(' ')).trim();
        $("#premium").val(newPremium);
        if ( $.isNumeric( newPremium ) ) {
            var percentage = 100 + parseInt(newPremium);
            percentage = percentage / 100;
            var newExchangeRate = percentage * currentExchangeRate;
            finalExchangeRateElem.text(parseFloat(newExchangeRate.toFixed(2)));
            calculateFee();
            calculateProfit();
            updateCollectAndToSend();
        }
    });

    $("#amountBTC").on("change paste keyup", function (ev) {
        calculateSendFiatAmount( $(this).val() );
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
        checkBtcAvailability( $(this).val(), $(this).val() );
    });
    $("#amountCurrency").on("change paste keyup", function (ev) {
        calculateSendBtcAmount( $(this).val() );
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
        checkBtcAvailability( $("#amountBTC").val(), $(this).val() );
    });
    $("#sendBitcoinAddress").on("change paste keyup", function (ev) {
        if ($(this).val()) {
            if ($(this).closest("div.form-group").hasClass("has-error")) {
                $(this).closest("div.form-group").removeClass("has-error");
                $('#send-payment-btn').prop('disabled', false);
            }
        }
    });

    $('#btn-max-btc').click(function(e) {
        e.preventDefault();
        var btcAfterFee = availableBalanceBTC*0.99;
        amountBtc.val( btcAfterFee );
        calculateSendFiatAmount( btcAfterFee );
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
        checkBtcAvailability( btcAfterFee, btcAfterFee );
    });

    setInterval(updateBtcPrice, 60000);
});

// needed because of the 1% fee
function hasEnoughBalance(btcToSend) {
    return btcToSend * 1.01 > availableBalanceBTC;
}

function calculateSendFiatAmount(btcAmount) {
    var newFiatAmount = btcAmount * currentExchangeRate;
    $("#amountCurrency").val(parseFloat(newFiatAmount.toFixed(2)));
}
function calculateSendBtcAmount(fiatAmount) {
    var newBtcAmount = fiatAmount / currentExchangeRate;
    $("#amountBTC").val(parseFloat(newBtcAmount.toFixed(8)));
}

function calculateProfit() {
    var btcAveragePrice = merchantAverage*amountBtc.val(); // initial btc price with merchant's average
    var btcExchangePrice = finalExchangeRateElem.text()*amountBtc.val(); // btc price with added premium by merchant
    merchantProfit.text((parseFloat(btcExchangePrice)-parseFloat(btcAveragePrice)).toFixed(2));
}

function calculateFee() {
    var feeAmount = 0.01 * amountBtc.val();
    feeElem.text(parseFloat(feeAmount.toFixed(8)));
}

function updateCollectAndToSend() {
    $("#toCollect").text((finalExchangeRateElem.text()*amountBtc.val()).toFixed(2));
    $("#toSendBtc").text(parseFloat(parseFloat($("#amountBTC").val()).toFixed(8))); // super ugly, but core javascript doesnt have good api to work with numbers
}

/**
 *
 * @param btcAmount
 * @param currentField field that is being validated
 */
function checkBtcAvailability(btcAmount, currentField) {
    if ( hasEnoughBalance( btcAmount ) || !$.isNumeric( currentField ) ) {
        $(".amountBTC-container, .amountCurrency-container").addClass("has-error");
        $('#send-payment-btn').prop('disabled', true);
        amountBtc.tooltip('show');
    } else {
        $(".amountBTC-container, .amountCurrency-container").removeClass("has-error");
        $('#send-payment-btn').prop('disabled', false);
        amountBtc.tooltip('hide');
    }
}

function updateBtcPrice() {
    $(".rateAjaxLoader").show();
    $.get( basePath+"/home/bitcoin-price", function( data ) {
        if (currentExchangeRate != data) {
            $("#currentExchangeRate").text(data);
            currentExchangeRate = data;
            calculateSendFiatAmount( amountBtc.val() );
            calculateFee();
            calculateProfit();
            updateCollectAndToSend();
        }
        $(".rateAjaxLoader").hide();
    });
}