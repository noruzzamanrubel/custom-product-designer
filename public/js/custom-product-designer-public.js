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
    });

})( jQuery );