var availableBalanceBTC = $('#availableBalanceBTC').text();
var saleExchangeRateElem = $("#saleExchangeRate");
var feeElem = $("#fee");
var merchantProfit = $("#merchantProfit");
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

    var premiumVal = premium.val().substr(0, premium.val().indexOf(' '));

    premium.on("change", function (ev) {
        var premiumVal = $(this).val().substr(0, $(this).val().indexOf(' ')).trim();
        var percentage = 100 + parseInt(premiumVal);
        percentage = percentage / 100;
        var newExchangeRate = percentage * saleExchangeRateElem.text();
        saleExchangeRateElem.text(parseFloat(newExchangeRate.toFixed(2)));
        calculateSendFiatAmount($("#amountBTC").val());
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
    });

    $("#premiumInput").on("change paste keyup", function (ev) {

    });

    $("#amountBTC").on("change paste keyup", function (ev) {
        calculateSendFiatAmount($(this).val());
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
        if ($(this).val() > availableBalanceBTC || !$.isNumeric($(this).val())) {
            $(this).closest("div.form-group").addClass("has-error");
            $('#send-payment-btn').prop('disabled', true);
        } else {
            if ($(this).closest("div.form-group").hasClass("has-error")) {
                $(this).closest("div.form-group").removeClass("has-error");
                $('#send-payment-btn').prop('disabled', false);
            }
        }
    });
    $("#amountCurrency").on("change paste keyup", function (ev) {
        calculateSendBtcAmount($(this).val());
        calculateFee();
        calculateProfit();
        updateCollectAndToSend();
        if ($("#amountBTC").val() > availableBalanceBTC || !$.isNumeric($(this).val())) {
            $(this).closest("div.form-group").addClass("has-error");
            $('#send-payment-btn').prop('disabled', true);
        } else {
            $("div.form-group").removeClass("has-error");
            $('#send-payment-btn').prop('disabled', false);
        }
    });
    $("#sendBitcoinAddress").on("change paste keyup", function (ev) {
        if ($(this).val()) {
            if ($(this).closest("div.form-group").hasClass("has-error")) {
                $(this).closest("div.form-group").removeClass("has-error");
                $('#send-payment-btn').prop('disabled', false);
            }
        }
    });

});

function calculateSendFiatAmount(btcAmount) {
    var newFiatAmount = btcAmount * currencyRate;
    $("#amountCurrency").val(parseFloat(newFiatAmount.toFixed(2)));
}
function calculateSendBtcAmount(fiatAmount) {
    var newBtcAmount = fiatAmount / currencyRate;
    $("#amountBTC").val(parseFloat(newBtcAmount.toFixed(8)));
}

function calculateProfit() {
    var amount = $("#amountCurrency").val();
    var profit = amount - feeElem.text();
    merchantProfit.text(parseFloat(profit.toFixed(2)));
}

function calculateFee() {
    var feeAmount = $("#amountCurrency").val() * 0.01;
    feeElem.text(parseFloat(feeAmount.toFixed(2)));
}

function updateCollectAndToSend() {
    $("#toCollect").text($("#amountCurrency").val());
    $("#toSendBtc").text($("#amountBTC").val());
}