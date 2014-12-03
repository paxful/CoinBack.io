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