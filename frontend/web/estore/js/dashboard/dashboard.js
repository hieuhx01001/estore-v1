jQuery(window).load(function() {
    jQuery('#carousel').elastislide({
        imageW 	: 135,
        margin      : 12
    });

    jQuery('#layerslider.slideritems').layerSlider({
        skinsPath :  $('#baseUrl').val()+'/estore/images/layerslider-skins/',
        skin : 'lastore',
        autoStart : true
    });
    jQuery('.ls-nav-prev').fadeOut();
    jQuery('.ls-nav-next').fadeOut();
    jQuery('#layerslider.slideritems').mouseleave(function(){
        jQuery('.ls-nav-prev').fadeOut();
        jQuery('.ls-nav-next').fadeOut();
    });
    jQuery('#layerslider.slideritems').mouseenter(function(){
        jQuery('.ls-nav-prev').fadeIn();
        jQuery('.ls-nav-next').fadeIn();
    });
});
