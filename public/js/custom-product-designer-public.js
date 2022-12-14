(function( $ ) {
	'use strict';

    $(document).ready(function () {
        $('.fan_product_carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            dots: false,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:7
                }
            }
        });

        if($('.single-product .hasCustomSelect').length > 0){
            $(".single-product .hasCustomSelect").find("option[value="+fan_design.variation_id+"]").attr('selected', 'selected');
        }

        $('body').on('click', '.wp-post-image', function () {
            let designImg = $('.design_single_image').attr('src');
            let imgStyle = $('.design_single_image').attr('style');
            if($('.custom_design_light_box_wrapper').length < 1) {
                $('.pswp__scroll-wrap').append('<div class="custom_design_light_box_wrapper"><img src="'+designImg+'"></div>');
            }
            $('.custom_design_light_box_wrapper img').attr("style", imgStyle);

            let transform = $('.pswp__zoom-wrap').attr("style");
            var lightbox_width= $(".pswp__zoom-wrap img").width();
            var lightbox_height = $(".pswp__zoom-wrap img").height();
            $(".custom_design_light_box_wrapper").attr("style", ""+transform+"; height: "+lightbox_height+"px; width: "+lightbox_width+"px");
        });


        $('body').on('click', 'a.woocommerce-product-gallery__trigger', function() {

            let designImg = $('.design_single_image').attr('src');
            let imgStyle = $('.design_single_image').attr('style');
            if($('.custom_design_light_box_wrapper').length < 1) {
                $('.pswp__scroll-wrap').append('<div class="custom_design_light_box_wrapper"><img src="'+designImg+'"></div>');
            }
            $('.custom_design_light_box_wrapper img').attr("style", imgStyle);

            let transform = $('.pswp__zoom-wrap').attr("style");
            var lightbox_width= $(".pswp__zoom-wrap img").width();
            var lightbox_height = $(".pswp__zoom-wrap img").height();
            $(".custom_design_light_box_wrapper").attr("style", ""+transform+"; height: "+lightbox_height+"px; width: "+lightbox_width+"px");

            $('.pswp__button--fs').css('display', 'none');
            $('.pswp__button--zoom').css('display', 'none');
            $('.pswp__button--arrow--left').css('z-index', 99);
            $('.pswp__button--arrow--right').css('z-index', 99);
        });


        $(window).on('resize', function(){
            // $( ".custom_design_light_box_wrapper" )..remove();
            let designImg = $('.design_single_image').attr('src');
            let imgStyle = $('.design_single_image').attr('style');
            if($('.custom_design_light_box_wrapper').length < 1) {
                $('.pswp__scroll-wrap').append('<div class="custom_design_light_box_wrapper"><img src="'+designImg+'"></div>');
            }
            $('.custom_design_light_box_wrapper img').attr("style", imgStyle);

            let transform = $('.pswp__zoom-wrap').attr("style");
            var lightbox_width= $(".pswp__zoom-wrap img").width();
            var lightbox_height = $(".pswp__zoom-wrap img").height();
            $(".custom_design_light_box_wrapper").attr("style", ""+transform+"; height: "+lightbox_height+"px; width: "+lightbox_width+"px");
        });

        //link disable
        $('.button-ghost').on('click', function(e) {
            e.preventDefault();
            $(this).off("click").attr('href', "javascript: void(0);");
        });

        $('.cart_item .product-name a').on('click', function(e) {
            e.preventDefault();
            $(this).off("click").attr('href', "javascript: void(0);");
        });

    });

})( jQuery );