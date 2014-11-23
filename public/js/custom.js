(function ($, window, document) {
    'use strict';
    var pluginName = "Aisconverse",
        defaults = {
            sliderFx: 'crossfade',		// Slider effect. Can be 'slide',
                                        // 'fade', 'crossfade', 'directscroll'
            sliderInterval: 6000,		// Interval
            speedAnimation: 600,        // Default speed of the animation
            scrollTopButtonOffset: 500, // when scrollTop Button will show
            locations: [51.5134476, -0.1159143],    // contact map center coords
            mapZoom: 11                             // map zoom
        },
        $win = $(window),
        $html = $('html'),
        onMobile = false;

    // The plugin constructor
    function Plugin(element, options) {
        var that = this;
        that.element = $(element);
        that.options = $.extend({}, defaults, options);

        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            onMobile = true;
            $(document.body).addClass('mobile');
        } else {
            $(document.body).addClass('no-mobile');
        }

        that.init();

        // onLoad function
        $win.load(function(){
            $('#status').fadeOut(defaults.speedAnimation);
            $('#preloader').delay(defaults.speedAnimation)
                .fadeOut(defaults.speedAnimation/2);

            if (that.body.hasClass('error404')){
                $html.addClass('html404');
            }

            that.activate();
            that.sliders();
            that.countUp();

        }).scroll(function(){  // onScroll function
            that.countUp();

            ($win.scrollTop() > defaults.scrollTopButtonOffset) ?
            (that.scrTop.fadeIn(defaults.speedAnimation)) :
            (that.scrTop.fadeOut(defaults.speedAnimation));

        }).resize(function(){  // onResize function
            setTimeout(function() {
                that.mask.each(function () {
                    var $this = $(this),
                        realHeight;
                    $this.parent().attr('maskheight', $(this).parent().height());
                    realHeight = +$this.parent().attr('maskheight') + 1;
                    $this.height(realHeight);
                });
            }, 100);

            if (that.modal.length === 1) {
                that.modal.height($win.height());
            }

            if (that.slider.hasClass('msnr-container')){
                setTimeout(function(){
                    that.slider.find('.caroufredsel_wrapper').height(that.slider.find('li:first').height());
                }, defaults.speedAnimation);
            }

        });
    }

    Plugin.prototype = {
        init: function () {
            this.body = $(document.body);
            this.wrapper = $('.wrapper');
            this.slider = $('.slider');
            this.oneslider = $('.oneslider');
            this.gallery = $('.gallery');
            this.internalLinks = $('.internal');
            this.history = $('.history');
            this.audio = $('audio');
            this.num = $('[data-num]');
            this.select = $('chosen-select');
            this.scrTop = $('.scrolltop');
            this.mask = $('.mask');
            this.map = $('#map');
            this.mapPopup = $('#map-popup');
            this.modal = $('.modal');
            this.magnific = $('.magnific');
            this.magnificWrap = $('.magnific-wrap');
            this.magnificGallery = $('.magnific-gallery');
            this.magnificVideo = $('.magnific-video');
            this.addCart = $('.add-cart');
            this.jslider = $('.jslider');
            this.rating = $('.raty');
            this.thumbs = $('.thumbs');
            this.mediumSlider = $('.medium-slider');
            this.counting = $('.counting');
            this.minus = $('.minus');
            this.plus = $('.plus');
            this.remove = $('.remove');
            this.navCategory = $('.nav-category');
            this.countup = $('.countup');
            this.mixList = $('.mix-list');
            this.masonryWrap = $('.masonry-wrap');
            this.tabLink = $('.tab-link');
            this.emailValidationRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        },
        activate: function () {
            var instance = this;

            setTimeout(function(){
                instance.mask.each(function(){
                    var $this = $(this),
                        realHeight;
                    $this.parent().attr('maskheight', $(this).parent().height());
                    realHeight = +$this.parent().attr('maskheight') + 1;
                    $this.height(realHeight);

                });
            }, 100);

            if (instance.audio.length > 0){
                instance.audio.mediaelementplayer();
            }

            instance.internalLinks.on('click', function(e){
                e.preventDefault();
                var $this = $(this),
                    url = $this.attr('href'),
                    urlTop = $(url).offset().top;

                $('body, html').stop(true, true)
                    .animate({ scrollTop: urlTop },
                    instance.options.speedAnimation);
            });

            // Custom Select
            if (instance.select.length > 0){
                instance.select.each(function(){
                    var self = $(this);

                    self.chosen({width: '100%'});
                });
            }

            // Custom input[type=range]
            if (instance.jslider.length > 0) {
                instance.jslider.slider({
                    from: 0,
                    to: 1000,
                    step: 1,
                    limits: false,
                    scale: [0, 1000],
                    dimension: "$&nbsp;"
                });
            }

            // RATING
            if (instance.rating.length > 0){
                instance.rating.raty({
                    half: true,
                    starType: 'i',
                    readOnly   : function() {
                        return $(this).data('readonly');
                    },
                    score: function() {
                        return $(this).data('score');
                    },
                    starOff: 'fa fa-star-o',
                    starOn: 'fa fa-star',
                    starHalf: 'fa fa-star-half-o'
                });
            }

            instance.tabLink.on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    hrf = $this.attr("href"),
                    top = $(hrf).parent().offset().top;
                $this.tab('show');
                $('.nav li').removeClass('active');
                setTimeout(function() {
                    $('.nav li a[href="' + $this.attr("href") + '"]').parent().addClass('active');
                }, 300);
                $('html, body').animate({scrollTop: top}, instance.options.speedAnimation/2);
            });

            instance.addCart.on('click', function(e){
                e.preventDefault();
                var self = $(this);
                if (self.hasClass('btn-primary')){
                    self.removeClass('btn-primary').addClass('btn-default').text('Remove');
                } else {
                    self.removeClass('btn-default').addClass('btn-primary').text('Add to Cart');
                }
            });

            // scrollTop function
            if (instance.scrTop.length === 1) {
                instance.scrTop.click(function(e){
                    $('html, body').stop(true,true).animate({ scrollTop: 0 }, instance.options.speedAnimation);
                    e.preventDefault();
                });
            }

            if (instance.navCategory.length > 0){
                var hsh = window.location.hash.replace('#','.'),
                    worksNavArr = [];

                if (hsh == '.all' ) {
                    hsh = 'all';
                }

                instance.navCategory.find('li').each(function(){
                    var $this = $(this);
                    worksNavArr.push($this.children().data('filter'));
                });

                if (instance.mixList.length > 0){
                    instance.mixList.mixItUp({
                        load: {
                            filter: hsh !== '' ? hsh : 'all'
                        }
                    });
                }
            }

            instance.magnificWrap.each(function() {
                $(this).find(instance.magnific).magnificPopup({
                    type: 'image',
                    tLoading: '',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true
                    },
                    image: {
                        titleSrc: function (item) {
                            return item.el.attr('title');
                        }
                    }
                });
            });

            instance.magnificVideo.magnificPopup({
                type: 'iframe',
                fixedContentPos: true
            });

            instance.magnificGallery.on('click', function(e) {
                e.preventDefault();

                var $this = $(this),
                    items = [],
                    im = $this.data('gallery'),
                    imA = im.split(','),
                    imL = imA.length,
                    titl = $this.attr('title');

                for (var i = 0; i < imL; i++){
                    items.push({
                        src: imA[i]
                    });
                }

                $.magnificPopup.open({
                    items: items,
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    image: {
                        titleSrc: function () {
                            return titl;
                        }
                    }
                });
            });

            // Masonry
            if (instance.masonryWrap.length > 0){

               setTimeout(function(){
                    instance.masonryWrap.masonry({
                        itemSelector: '.msnr'
                    });
                    var msnry = instance.masonryWrap.data('masonry');
                }, 0);

            }

            // Product Thumbs
            if (instance.thumbs.length > 0) {

                instance.thumbs.find('a').each(function(i) {
                    $(this).addClass( 'itm'+i );
                    $(this).click(function(e) {
                        e.preventDefault();
                        instance.mediumSlider.trigger( 'slideTo', [i, 0, true] );
                    });
                });
                instance.thumbs.find('.itm0').addClass( 'selected' );

                instance.mediumSlider.carouFredSel({
                    responsive: true,
                    circular: false,
                    infinite: false,
                    items : {
                        visible : 1,
                        height : 'auto',
                        width : 870
                    },
                    auto: false,
                    onCreate: function() {
                        $(this).trigger("currentPosition", function() {
                            var txt = "1&#47;"+$(this).children().length;
                            $('.product-count').html(txt);
                        });
                    },
                    scroll: {
                        fx: 'crossfade',
                        onBefore: function() {
                            var pos = $(this).triggerHandler( 'currentPosition' );
                            instance.thumbs.find('a').removeClass( 'selected' );
                            instance.thumbs.find('a.itm'+pos).addClass('selected');

                            $(this).trigger("currentPosition", function( pos ) {
                                var txt = Math.ceil((pos+1))+"&#47;"+$(this).children().length;
                                $('.product-count').html(txt);
                            });
                        }
                    }
                });
            }

            // Product counting more
            instance.plus.on('click',function(e){
                e.preventDefault();
                var $this = $(this),
                    valIn = $this.parent().find('input').val();
                valIn++;
                $this.parent().find('input').val(valIn);

                if ($this.parent().find('input').val() <= 1){
                    $this.parent().find(instance.minus).addClass('disabled');
                } else {
                    $this.parent().find(instance.minus).removeClass('disabled');
                }
            });

            instance.counting.find('input').on('change', function(){
                var $this = $(this);
                if ($this.parent().find('input').val() <= 1){
                    $this.parent().find(instance.minus).addClass('disabled');
                } else {
                    $this.parent().find(instance.minus).removeClass('disabled');
                }
            });

            // Product counting less
            instance.minus.on('click',function(e){
                e.preventDefault();
                var $this = $(this),
                    valIn = $this.parent().find('input').val();
                if($this.parent().find('input').val() != 1){
                    valIn--;
                    $this.parent().find('input').val(valIn);
                    $this.removeClass('disabled');
                } else{
                    $this.addClass('disabled');
                    return false;
                }
                if ($this.parent().find('input').val() <= 1){
                    $this.addClass('disabled');
                }
            });

            instance.remove.on('click', function(e){
                e.preventDefault();
                var $this = $(this);

                $this.parents('tr').fadeOut(instance.options.speedAnimation/2, function(){
                    $(this).remove();
                });
            });

            if (instance.history.length === 1){
                instance.historical();
            }

            if (instance.map.length === 1){
                instance.contactMap();
                $('[data-target="#contact-modal"]').on('click', function(){
                    setTimeout(function(){
                        instance.contactMap()
                    }, instance.options.speedAnimation/2);
                });
            }

        },
        sliders: function(){
            var instance = this;

            if (instance.slider.length > 0){
                instance.slider.each(function(e){
                    var $this = $(this),
                        slidewrap = $this.find('ul:first'),
                        sliderFx = slidewrap.data('fx'),
                        sliderAuto = slidewrap.data('auto'),
                        sliderCircular = slidewrap.data('circular'),
                        sliderPrefix = '#slider-';

                    $this.attr('id', 'slider-'+e);

                    slidewrap.carouFredSel({
                        responsive:true,
                        infinite: (typeof sliderCircular) ? sliderCircular : true,
                        circular: (typeof sliderCircular) ? sliderCircular : true,
                        auto : sliderAuto ? sliderAuto : false,
                        scroll : {
                            fx : sliderFx ? sliderFx : 'crossfade',
                            duration : instance.options.speedAnimation,
                            timeoutDuration : instance.options.sliderInterval,
                            items: 'page'
                        },
                        items: {
                            width: 400,
                            height: 'variable',
                            visible : {
                                min : 1,
                        	    max : 5
                    	    }
                        },
                        swipe : {
                            onTouch : true,
                            onMouse : false
                        },
                        prev : $(sliderPrefix + e).find('.prev'),
                        next : $(sliderPrefix + e).find('.next'),
                        pagination : {
                            container: $(sliderPrefix + e).find('.nav-pages')
                        }
                    }).parent().css('margin', 'auto');

                });
            }

            if (instance.oneslider.length > 0){
                instance.oneslider.each(function(e){
                    var $this = $(this),
                        slidewrap = $this.find('ul'),
                        slidesSize = slidewrap.children().length,
                        sliderFx = slidewrap.data('fx'),
                        sliderAuto = slidewrap.data('auto'),
                        onesliderPrefix = '#oneslider-';

                    $this.attr('id', 'oneslider-'+e);

                    slidewrap.carouFredSel({
                        responsive: true,
                        auto : sliderAuto ? sliderAuto : false,
                        scroll : {
                            fx : sliderFx ? sliderFx : 'crossfade',
                            duration : instance.options.speedAnimation,
                            timeoutDuration : instance.options.sliderInterval,
                            onBefore: function() {
                                $(this).trigger("currentPosition", function( pos ) {
                                    var txt = Math.ceil((pos+1))+"&#47;"+slidesSize;
                                    $this.find('.count').html(txt);
                                });

                            }
                        },
                        onCreate: function() {
                            $(this).trigger("currentPosition", function() {
                                var txt = "1&#47;"+slidesSize;
                                $this.find('.count').html(txt);
                            });
                        },
                        items : {
                            visible     : 1,
                            width       : 870,
                            height      : "variable"
                    	},
                        swipe : {
                            onTouch : true,
                            onMouse : false
                        },
                        prev : $(onesliderPrefix + e).find('.prev'),
                        next : $(onesliderPrefix + e).find('.next'),
                        pagination : {
                            container: $(onesliderPrefix + e).find('.nav-pages'),
                            anchorBuilder: function () {
                                if ($(this).parents(instance.slider.hasClass('pricing'))) {
                                    var per = $(this).data('period');
                                    return '<a href="#">' + per + '</a>';
                                }
                            }
                        }
                    }).parent().css('margin', 'auto');
                    setTimeout(function(){
                        $this.find('.caroufredsel_wrapper')
                            .height($this.find('li').height());
                    }, 100);
                });
            }
        },
        historical: function(){
            var instance = this,
                histContent = $('.histcontent'),
                hcItems = histContent.children(),
                histLine = $('.histline ul'),
                hItems = histLine.children(),
                hItemsWidth = hItems.width(),
                hItemsLength = hItems.length,
                hItemsActive = histLine.find('.active'),
                hYears = $('.hist-years'),
                actIndx,
                hItemFirst = hItems.filter(':first'),
                hItemLast = hItems.filter(':last'),
                fYear = hItemFirst.text(),
                lYear = hItemLast.text();

            hYears.html("(" + fYear + " - " + lYear + ")");

            if (actIndx < 0) {
                actIndx = 0;
            }

            hItems.removeClass().addClass('farfar');

            if (hItemsLength%2 === 0) {
                histLine.parent().css({
                    'width': hItemsLength * hItemsWidth,
                    'marginLeft': -hItemsWidth / 2 * hItemsLength + hItemsWidth / 2
                });

                hItems.eq(Math.floor(hItemsLength/2)).removeClass().addClass('far');
                hItems.eq(Math.floor(hItemsLength/2)-2).removeClass().addClass('far');
                hItems.eq(Math.floor(hItemsLength/2)-1).removeClass().addClass('active');

            } else {
                histLine.parent().css({
                    'width': hItemsLength * hItemsWidth,
                    'marginLeft': -hItemsWidth / 2 * hItemsLength
                });

                hItems.eq(Math.floor(hItemsLength/2)+1).removeClass().addClass('far');
                hItems.eq(Math.floor(hItemsLength/2)-1).removeClass().addClass('far');
                hItems.eq(Math.floor(hItemsLength/2)).removeClass().addClass('active');
            }

            histLine.css({
                'width': hItemsLength*hItemsWidth
            });

            actIndx = histLine.find('.active').index();

            hcItems.eq(actIndx).show();

            hItems.on('click', function(){
                var $this = $(this),
                    indx = $this.index();

                hItemsActive = histLine.find('.active');

                histLine.animate({'left': parseInt((-indx)*hItemsWidth) - parseInt(histLine.parent().css('marginLeft')) + -hItemsWidth / 2}, instance.options.speedAnimation);

                if ( !$this.hasClass('active')){
                    hcItems.fadeOut(instance.options.speedAnimation/2);
                    hcItems.eq(indx).delay(instance.options.speedAnimation/1.8).fadeIn(instance.options.speedAnimation/2);
                }

                hItems.removeClass().addClass('farfar');
                $this.prev().removeClass().addClass('far');
                $this.next().removeClass().addClass('far');
                $this.removeClass().addClass('active');
            });
        },
        countUp: function(){
            var instance = this,
                obj = {
                signPos: 'after',
                delay: 30,
                orderSeparator: ' ',
                decimalSeparator: ','
            };

            if (instance.countup.length > 0){
                this.countup.hsCounter(obj);
            }
        },
        contactMap: function() {
            var instance = this,
                x = instance.options.locations[0],
                y = instance.options.locations[1],
                cmyLatlng = new google.maps.LatLng(x, y),
                cmapOptions = {
                    zoom: instance.options.mapZoom,
                    scrollwheel: false,
                    navigationControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    draggable: true,
                    center: cmyLatlng
                },
                cmap = new google.maps.Map(document.getElementById('map'), cmapOptions),
                cmapPopup = new google.maps.Map(document.getElementById('map-popup'), cmapOptions);

            new google.maps.Marker({
                position: cmyLatlng,
                map: cmap
            });

            instance.mapPopup.height($win.height());

            new google.maps.Marker({
                position: cmyLatlng,
                map: cmapPopup
            });
        }
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };
})(jQuery, window, document);

var countryDropdown = $("#country");
var stateDropdown   = $("#state");
var cityDropdown    = $("#city");
countryDropdown.select2({
    placeholder: "Select your country",
    allowClear: true
});
(function ($) {
    $(document.body).Aisconverse();

    $.post(basePath+"/home/location-by-ip", function(response) {
        var location = $.parseJSON(response.location);
        $("#phone").intlTelInput({
            defaultCountry: location.country_iso_code.toLowerCase(),
            responsiveDropdown: true
        });

        $("#location_id").val(location.id);
        // load the state for that location
        populateStates(location.country_id, location);
        // load cities for that location
        populateCities(location.subdivision_iso_code, location.country_id, location, "Select...");
    }, "json");

})(jQuery);

countryDropdown.on("change", function (e) {
    populateStates(e.val, null);
    stateDropdown.select2('val', null);
    cityDropdown.select2({
        data: {},
        placeholder: "Select a state first"
    });
    cityDropdown.select2("enable", false);
});

stateDropdown.on("change", function (e) {
    populateCities(e.val, $("#country").val(), null, "Select...");
    cityDropdown.select2("enable", true);
    cityDropdown.select2('val', null);
});

cityDropdown.on("change", function (e) {
    $("#location_id").val(e.val);
});

function populateStates(country_id, location)
{
    return $.post(basePath + '/home/states-list', {country_id: country_id}).done(function (data) {
        var states = [];
        $.each(data, function(text, id) {
            if (text === "") {
                return; // same as 'continue'
            }
            states.push({id:id, text:text});
        });

        // init select2 states and set current location state as default
        stateDropdown.select2({
            placeholder: "Select...",
            data: states
        });
        if (location !== null) {
            stateDropdown.select2("val", location.subdivision_iso_code);
        }
    }, 'json'); // loaded states
}

function populateCities(state_iso_code, country_id, location, placeholder)
{
    $.post(basePath + '/home/cities-list', {state: state_iso_code, country_id: country_id}).done(function (data) {
        var cities = [];
        $.each(data, function(id, object) {
            if (object.city_name === "") {
                return; // same as 'continue'
            }
            cities.push({id:object.id, text:object.city_name});
        });

        // init select2 for cities list and set current location id as default city
        cityDropdown.select2({
            placeholder: placeholder,
            data: cities
        });
        if (location !== null) {
            cityDropdown.select2("val", location.id);
        }
    }, 'json');
}