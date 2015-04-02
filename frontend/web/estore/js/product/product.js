jQuery(window).load(function() {
    // The slider being synced must be initialized first
    jQuery('#carouselslider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 60,
        itemMargin:0,
        asNavFor: '#imageitems'
    });

    jQuery('#imageitems').flexslider({
        animation: "fade",
        directionNav: false,
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carouselslider"
    });

    $('#search_dropdown').on('change', function(){
        $('#search_by').submit();
    });
    $('#show_dropdown').on('change', function(){
        $('#search_by').submit();
    });
});
