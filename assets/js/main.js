
(function($){
    "use strict";
    $(document).ready(function(){

        /**-----------------------------
         *  Navbar fix
         * ---------------------------*/
        $(document).on('click','.navbar-area .navbar-nav li.menu-item-has-children>a',function(e){
            e.preventDefault();
        })

        /*-----------------------------
            Side Bar Offcanvas Menu
        -------------------------------*/
        $(document).on('click','.toggle-btn', function(ref){
            ref.preventDefault();
            $(this).toggleClass("active");
            document.getElementById('sidebar').classList.toggle('active');
        })
        /*-----------------------------------
            Cart bar Show
        -------------------------------------*/
        $(document).on('click','.show-cart',function(ref){
            ref.preventDefault();
            $(this).toggleClass("active");
            document.getElementById('menu-cart-open').classList.toggle('active');
        })

        /*------------------------------------
            File Upload
        --------------------------------------*/
        if($('.file-upload').length > 0){
            $('#chooseFile').bind('change', function () {
                var filename = $("#chooseFile").val();
                if (/^\s*$/.test(filename)) {
                    $(".file-upload").removeClass('active');
                    $("#noFile").text("No file chosen...");
                }
                else {
                    $(".file-upload").addClass('active');
                    $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
                }
            });
        }

        /*-------------------------
          Mean Menu For Home Page 04
        ---------------------------*/
        if($('#offcanvas-menu').length > 0){
            $('#offcanvas-menu').meanmenu({
                meanMenuContainer: '.mobile-menu',
                meanScreenWidth: "25000"
            });
        }

        /**-----------------------------
         *     Sticky Icon Popup
         * ---------------------------*/
        $(document).on('click','.contact-popup',function(){
            $(this).removeClass('active');
            $('.contact-popup-content').removeClass('active');
            $("#open-message-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");
            $("#open-location-popup").removeClass("active");
        })
        $(document).on('click','.location-popup',function(){
            $(this).removeClass('active');
            $('.location-popup-content').removeClass('active');
            $("#open-message-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");
            $("#open-location-popup").removeClass("active");
        })
        $(document).on('click','.message-popup',function(){
            $(this).removeClass('active');
            $('.message-popup-content').removeClass('active');
            $("#open-message-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");
            $("#open-location-popup").removeClass("active");
        })
        /*Open Contact Popup*/
        $("#open-contact-popup").on("click", function() {
            $(this).addClass("active");
            $("#open-message-popup").removeClass("active");
            $("#open-location-popup").removeClass("active");

            $(".contact-popup").toggleClass("active");
            $(".contact-popup-content").toggleClass("active");
            $(".location-popup-content").removeClass("active");
            $(".message-popup-content").removeClass("active");
            $(".location-popup").removeClass("active");
            $(".message-popup").removeClass("active");
        });

        $(".contact-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .removeClass("active");
            $('.contact-popup').removeClass('active')

            $("#open-message-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");
            $("#open-location-popup").removeClass("active");
        });
        /*Open location Popup*/
        $("#open-location-popup").on("click", function() {
            $(this).addClass("active");
            $("#open-message-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");

            $(".location-popup").toggleClass("active");
            $(".location-popup-content").toggleClass("active");
            $(".contact-popup-content").removeClass("active");
            $(".message-popup-content").removeClass("active");
            $(".message-popup").removeClass("active");
            $(".contact-popup").removeClass("active");
        });

        $(".location-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .removeClass("active");
                $('.location-popup').removeClass('active')

                $("#open-message-popup").removeClass("active");
                $("#open-contact-popup").removeClass("active");
                $("#open-location-popup").removeClass("active");
        });
        /*Open message Popup*/
        $("#open-message-popup").on("click", function() {
            $(this).addClass("active");
            $("#open-location-popup").removeClass("active");
            $("#open-contact-popup").removeClass("active");

            $(".message-popup").toggleClass("active");
            $(".message-popup-content").toggleClass("active");
            $(".location-popup-content").removeClass("active");
            $(".contact-popup-content").removeClass("active");
            $(".contact-popup").removeClass("active");
            $(".location-popup").removeClass("active");
        });

        $(".message-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .removeClass("active");
                $('.message-popup').removeClass('active');

                $("#open-message-popup").removeClass("active");
                $("#open-contact-popup").removeClass("active");
                $("#open-location-popup").removeClass("active");
        });

        /*------------------
            back to top
        ------------------*/
        $(document).on('click', '.back-to-top', function () {
            $("html,body").animate({ scrollTop: 0 }, 2000);
        });

        /*------------------------
            Nice select active
        --------------------------*/
        if($('.custom-select-box select').length > 0){
            $('.custom-select-box select').niceSelect();
        }

        //Scroll Down
        $('.arrow-down-wrap .arrow-down').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top}, 500, 'linear');
        });

        /*-----------------------------
            Show / Hide password
        -------------------------------*/
        var showPass = 0;
        $(document).on('click','.btn-show-pass',function(){
            if(showPass == 0) {
                $('#password, #confirm_password').attr('type','text');
                $('.show-hide-icon').removeClass('fa-eye');
                $('.show-hide-icon').addClass('fa-eye-slash');
                showPass = 1;
            }
            else {
                $('#password, #confirm_password').attr('type','password');
                $('.show-hide-icon').removeClass('fa-eye-slash');
                $('.show-hide-icon').addClass('fa-eye');
                showPass = 0;
            }
        })

        /*------------------------------
            Cart Close BTN
        --------------------------------*/
        if($('.cart-item-close-btn').length > 0){
            $(document).on('click', '.cart-item-close-btn', function(){
                $(this).parent().hide(200);
            })
        }
        if($('.cart-close-icon').length > 0){
            $(document).on('click', '.cart-close-icon', function(){
                $(this).parent().parent().hide(200);
            })
        }

        /*---------------------------
             Alert Close BTN
        -----------------------------*/
        if($('.basic-alert__close_btn').length > 0){
            $(document).on('click', '.basic-alert__close_btn', function(){
                $(this).parent().hide(200);
            })
        }

        /*------------------------------
            counter section activation
        -------------------------------*/
        var counternumber = $('.count-num');
        if((counternumber).length > 0){
            counternumber.rCounter({
                duration: 40
            });
        }

        /* pricing Table Active */
        var $singlepricing = $('.pricing-plan-inner-columns')
        if(($singlepricing).length > 0){
            $singlepricing.mouseover(function() {
                $singlepricing.removeClass('active');
                $(this).addClass('active');
            });
        }

        /*----------------------------
             price-slider active
        ------------------------------ */
        if($( "#slider-range").length > 0){
            $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 6000,
                values: [ 60, 570 ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                }
            });
            $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
            " - $" + $( "#slider-range" ).slider( "values", 1 ) );
        }

        $(document).on('click','.click-to-top',function(){
            $('body,html').animate({scrollTop: 0}, 1000)
        })

        /*------------------------------------
            Video popup
        --------------------------------------*/
        var $AcHostedPopup = $('.hosted-popup');
        if($AcHostedPopup.length > 0){
            $AcHostedPopup.rPopup({
                'video': {
                    embed: true,
                    autoplay: true,
                },
            });
        }

        /*------------------------------------
            Home-01 Testimonial slider
        --------------------------------------*/
        var $ACtestimonialSliderImage = $('.testimonial-images');
        var $ACtestimonialSliderContent = $('.testimonial-content');

        if($ACtestimonialSliderImage.length > 0 &&  $ACtestimonialSliderContent.length > 0 ){

            $ACtestimonialSliderImage.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                arrows: false,
                fade: true,
                asNavFor: $ACtestimonialSliderContent,
            });

            $ACtestimonialSliderContent.slick({
                dots: true,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                asNavFor: $ACtestimonialSliderImage,
                appendDots: $('.testimonial-dots-02'),
                appendArrows: $('.testimonial-arrow-02'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-right-arrow"></i> </div>',
            });
        }

        /*------------------------------------
             Home-02 Testimonial slider
        --------------------------------------*/
        var $ACtestimonialSliderImage02 = $('.testimonial-images-02');
        var $ACtestimonialSliderContent02 = $('.testimonial-content-02');

        if($ACtestimonialSliderImage02.length > 0 &&  $ACtestimonialSliderContent02.length > 0 ){

            $ACtestimonialSliderImage02.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                fade: true,
                asNavFor: $ACtestimonialSliderContent02,
                appendArrows: $('.testimonial-arrow'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-right-arrow"></i> </div>',
            });

            $ACtestimonialSliderContent02.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                arrows: false,
                asNavFor: $ACtestimonialSliderImage02,
            });
        }


        /*-----------------------------------------
            Home-01 Our Latest News Slider
        -------------------------------------------*/
        var $LNSliderActive = $('.latest-news-slider-active');
        if($LNSliderActive.length > 0){
            $LNSliderActive.slick({
                infinite: true,
                speed: 1000,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                appendArrows: $('.slider-arrow'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-right-arrow"></i> </div>',
                responsive: [
                    {
                      breakpoint: 1280,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                      }
                    },
                    {
                      breakpoint: 769,
                      settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 601,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                  ]
            });

                // On before home slider change
                $LNSliderActive.on('beforeChange', function(event, slick, currentSlide, nextSlide){
                var activeSlide =  $('.latest-news-slider-active .slick-active').length;
                var currentSliderIndex = nextSlide;
                ++currentSliderIndex;
                var progress = 100 / activeSlide;
                var progressWidth = progress * currentSliderIndex;
                LatestSliderProgressbar(progressWidth)
            });
        }


        /*------------------------------------
            Home Slider 01
        --------------------------------------*/
        var $AChomeSliderOne = $('.home-slider-one');
        if($AChomeSliderOne.length > 0){
            $AChomeSliderOne.slick({
                dots: true,
                infinite: true,
                speed: 1600,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                fade: true,
                appendDots: $('.carousel-dots'),
                appendArrows: $('.carousel-dots'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-right-arrow"></i> </div>',
            });
        }

        /*------------------------------------
            Home 01 Vehicle Slider
        --------------------------------------*/
        var $ACvehicleSlider = $('.vehicle-slider-active');
        if($ACvehicleSlider.length > 0){
            $ACvehicleSlider.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                centerMode: true,
                arrows: true,
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow-1"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-arrow-pointing-to-right"></i> </div>',
                responsive: [
                    {
                      breakpoint: 1450,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                      }
                    },
                    {
                      breakpoint: 992,
                      settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 601,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                      }
                    }
                  ]
            });
        }

        /*-----------------------------------------
            Home 01 our team Slider
        -------------------------------------------*/
        var $ACourTeamSlider = $('.h2-our-team-slider-active');
        if($ACourTeamSlider.length > 0){
            $ACourTeamSlider.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                appendArrows: $('.team-slider-arrow'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow-1"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-arrow-pointing-to-right"></i> </div>',
                responsive: [
                    {
                      breakpoint: 1651,
                      settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                      }
                    },
                    {
                      breakpoint: 1201,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 991,
                      settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                      }
                    },
                    {
                        breakpoint: 601,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                    }
                  ]
            });
        }

        /*-------------------------------------
            Home-02 Slider
        ---------------------------------------*/
        var $AChomeSliderTwo = $('.home-slider-two');
        if($AChomeSliderTwo.length > 0){
            $AChomeSliderTwo.slick({
                dots: true,
                infinite: true,
                speed: 1600,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                arrows: true,
                fade: true,
                appendDots: $('.carousel-dots'),
                appendArrows: $('.carousel-dots'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-up-arrow"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-download-arrow"></i> </div>',
            });
        }

        /*-----------------------------------------
            Home 03 Slider
        -------------------------------------------*/
        var $ACh3Slider = $('.home-slider-three');
        if($ACh3Slider.length > 0){
            $ACh3Slider.slick({
                dots: true,
                infinite: true,
                speed: 1600,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                arrows: true,
                appendDots: $('.h3-slider-dots'),
                appendArrows: $('.h3-carousel-dots'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow-1"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-arrow-pointing-to-right"></i> </div>',
            });

            // On before home slider change
            $ACh3Slider.on('beforeChange', function(event, slick, currentSlide, nextSlide){
                var testSlider =  $ACh3Slider.slick('getSlick');
                var totalSlide = testSlider.$slides.length;
                var currentSliderIndex = nextSlide;
                ++currentSliderIndex;
                var progress = 100 / totalSlide;
                var progressWidth = progress * currentSliderIndex;
                homeThreeSliderProgressbar(progressWidth)
            });
        }

        /*-----------------------------------------
            Home 04 Slider
        -------------------------------------------*/
        var $ACh4Slider = $('.home-slider-four');
        if($ACh4Slider.length > 0){
            $ACh4Slider.slick({
                dots: true,
                infinite: true,
                speed: 1600,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                arrows: true,
                appendDots: $('.h4-slider-dots'),
                appendArrows: $('.h4-carousel-dots'),
                prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow-1"></i> </div>',
                nextArrow: '<div class="slick-next"> <i class="flaticon-arrow-pointing-to-right"></i> </div>',
            });

            // On before home slider change
            $ACh4Slider.on('beforeChange', function(event, slick, currentSlide, nextSlide){
                var testSlider =  $ACh4Slider.slick('getSlick');
                var totalSlide = testSlider.$slides.length;
                var currentSliderIndex = nextSlide;
                ++currentSliderIndex;
                var progress = 100 / totalSlide;
                var progressWidth = progress * currentSliderIndex;
                homeFourSliderProgressbar(progressWidth)
            });
        }

        /*-----------------------------------------
             Home 05 Most Popular Slider Tab One
        -------------------------------------------*/

        var $ACh5MostPopularSlider = $('.h5-tab-slider-left-active');
        var $ACh5MostPopularSliderBig = $('.h5-tab-slider-right-active');

        if($ACh5MostPopularSlider.length > 0 &&  $ACh5MostPopularSliderBig.length > 0 ){

            $(document).on('click', '#popular-car-tab',function(){
                var containerHeight = $('.most-popular-tab-content').height();

                $('.most-popular-tab-content').height(containerHeight);
                $('.slider-preloader').height(containerHeight);
                $('.slider-preloader').fadeIn(100);
                setTimeout(function(){
                    $('.slider-preloader').fadeOut(500);
                },5000);
                var contentID = $(this).attr('id');
            })

            $ACh5MostPopularSlider.slick({
                dots: false,
                infinite: true,
                speed: 500,
                slidesToShow: 4,
                slidesToScroll: 1,
                vertical: true,
                autoplay: true,
                arrows: false,
                asNavFor: $ACh5MostPopularSliderBig,
            });

            $ACh5MostPopularSliderBig.slick({
                dots: false,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                arrows: false,
                asNavFor: $ACh5MostPopularSlider,
            });
        }
        $(document).on('click','.h5-tab-slider-left-items',function(e){
            e.preventDefault();
            var el = $(this);
            var slideIndex = el.data('slide-index');
            $ACh5MostPopularSliderBig.slick('slickGoTo',slideIndex);
        });


        /*-----------------------------------------
            Home 05 Most Popular Slider Tab Two
        -------------------------------------------*/

        var $ACh5MostPopularSlider2 = $('.h5-tab-slider-left-active2');
        var $ACh5MostPopularSliderBig2 = $('.h5-tab-slider-right-active2');

        if($ACh5MostPopularSlider2.length > 0 &&  $ACh5MostPopularSliderBig2.length > 0 ){

            $(document).on('click','#newest-car-tab',function(){
                var containerHeight = $('.most-popular-tab-content').height();

                $('.most-popular-tab-content').height(containerHeight);
                $('.slider-preloader').height(containerHeight);
                $('.slider-preloader').fadeIn(100);
                setTimeout(function(){
                    $('.slider-preloader').fadeOut(500);
                },5000);
                var contentID = $(this).attr('id');

                $ACh5MostPopularSlider2.slick({
                    dots: false,
                    infinite: true,
                    speed: 500,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    vertical: true,
                    autoplay: true,
                    arrows: false,
                    asNavFor: $ACh5MostPopularSliderBig2,
                });
                $ACh5MostPopularSliderBig2.slick({
                    dots: false,
                    infinite: true,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    fade: true,
                    autoplay: true,
                    arrows: false,
                    asNavFor: $ACh5MostPopularSlider2,
                });
            });
        }

        $(document).on('click','.h5-tab-slider-left-items',function(e){
            e.preventDefault();
            var el = $(this);
            var slideIndex = el.data('slide-index');
            $ACh5MostPopularSliderBig2.slick('slickGoTo',slideIndex);
        });



      /*-----------------------------------------
            Home 05 Testimonial Slider
       -------------------------------------------*/
       var $ACh5TestimonialSlider = $('.h5-testimonial-active');
       if($ACh5TestimonialSlider.length > 0){
           $ACh5TestimonialSlider.slick({
               dots: false,
               infinite: true,
               speed: 1000,
               slidesToShow: 1,
               slidesToScroll: 1,
               fade: true,
               autoplay: true,
               arrows: true,
               appendArrows: $('.slider-arrow'),
               prevArrow: '<div class="slick-prev"> <i class="flaticon-left-arrow-1"></i> </div>',
               nextArrow: '<div class="slick-next"> <i class="flaticon-arrow-pointing-to-right"></i> </div>',
           });
       }

        /*-----------------------------------------
            Shop Details / Product slider
        -------------------------------------------*/
        var $ACproductTabSmall = $('.Product-tab-small-active');
        var $ACproductTabBig = $('.product-tab-big-active');

        if($ACproductTabSmall.length > 0 &&  $ACproductTabBig.length > 0 ){
            $ACproductTabSmall.slick({
                dots: false,
                infinite: true,
                speed: 1000,
                slidesToShow: 5,
                slidesToScroll: 1,
                centerMode: true,
                autoplay: true,
                arrows: true,
                asNavFor: $ACproductTabBig,
                responsive: [

                    {
                      breakpoint: 1441,
                      settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 1201,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                      }
                    },
                    {
                        breakpoint: 601,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 1,
                          centerMode: false,
                        }
                    }
                  ]
            });

            $('.product-details-tab').imagesLoaded(function () {
                $ACproductTabBig.slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    fade: true,
                    autoplay: true,
                    arrows: false,
                    asNavFor: $ACproductTabSmall,
                });
            });

        }
        $(document).on('click','.product-tab-single-small-item',function(e){
            e.preventDefault();
            var el = $(this);
            var slideIndex = el.data('slide-index');
            $ACproductTabBig.slick('slickGoTo',slideIndex);
        });


        /*-----------------------------
            Date picker
        -------------------------------*/
        var $ACDatePicker = $('.datepicker');
        if($ACDatePicker.length > 0){
            $ACDatePicker.datepicker();
        }

        /*--------------------
            wow js init
        ---------------------*/
        new WOW().init();


        /*---------------------------------------------
            Mixitup Active For Home 05
        ----------------------------------------------*/
        $('.filter-section').imagesLoaded(function () {
            if($('.car-filter-content-active').length > 0){
                var containerEl = document.querySelector('.car-filter-content-active');
                var mixer = mixitup(containerEl);
            }
        })

       /*----------------------
            Search Popup
        -----------------------*/
        var bodyOvrelay =  $('#body-overlay');
        var searchPopup = $('#search-popup');

        $(document).on('click','#body-overlay',function(e){
            e.preventDefault();
            bodyOvrelay.removeClass('active');
            searchPopup.removeClass('active');
        });

        $(document).on('click','#search',function(e){
            e.preventDefault();
            searchPopup.addClass('active');
            bodyOvrelay.addClass('active');
        });


    });

    //define variable for store last scrolltop
    var lastScrollTop = '';

    var floatingIcon = $("#service_info_item");

    $(window).on('scroll', function () {

        //back to top show/hide
       var ScrollTop = $('.back-to-top');
       if ($(window).scrollTop() > 1000) {
           ScrollTop.fadeIn(1000);
       } else {
           ScrollTop.fadeOut(1000);
       }

        /*---------------------------------------
            Sticky Icon Bar
        -----------------------------------------*/
        var st = $(this).scrollTop();
        var mainMenuTop = $(".navbar-area");
        if ($(window).scrollTop() > 1000) {
            if (st > lastScrollTop) {
                // hide sticky menu on scrolldown
                mainMenuTop.removeClass("nav-fixed");
            } else {
                // active sticky menu on scrollup
                mainMenuTop.addClass("nav-fixed");
            }
        } else {
            mainMenuTop.removeClass("nav-fixed ");
        }

        if ($(window).width() < 992) {
            if (st > lastScrollTop) {
                // hide sticky menu on scrolldown
                showFloatingIcon();
            } else {
                // active sticky menu on scrollup
                hideFloatingIcon();
            }
        } else {
            floatingIcon.css({ display: "inline-block" });
        }
        lastScrollTop = st;

    });



   $(window).on('load',function(){

        /*-----------------
            back to top
        ------------------*/
        var backtoTop = $('.back-to-top')
        backtoTop.fadeOut();

        /*-----------------
            preloader
        ------------------*/
        var preLoder = $("#preloader");
        preLoder.fadeOut(0);

        /*---------------------
            Cancel Preloader
        ----------------------*/
        $(document).on('click','.cancel-preloader a',function(e){
            e.preventDefault();
            $("#preloader").fadeOut(2000);
        });

        //floatingIcon
        if ($(window).width() < 992) {
            hideFloatingIcon();
        } else {
            showFloatingIcon();
        }

    });

    $(window).on("resize", function(e) {
        e.preventDefault();
        //floatingIcon
        if ($(window).width() < 768) {
            hideFloatingIcon();
        } else {
            showFloatingIcon();
        }
    });

        function hideFloatingIcon() {
            //floatingIcon
            floatingIcon.hide();
        }

        function showFloatingIcon() {
            //floatingIcon
            floatingIcon.show();
    }

    function check_number(num) {
        var IsInteger = /^[0-9]+$/.test(num);
        return IsInteger ? '0' + num : null;
    }


    function LatestSliderProgressbar(progressWidth){
        $('.latest-news-slider-progressbar .porgress-bar-active').css({'width': progressWidth+'%'});
    }
    function homeThreeSliderProgressbar(progressWidth){
        $('.h3-slider-progress-area .progress-active-line').css({'height': progressWidth+'%'});
    }
    function homeFourSliderProgressbar(progressWidth) {
        $('.slider-progress-inner .progress-active-line').css({'width': progressWidth+'%'});
    }


})(jQuery);
