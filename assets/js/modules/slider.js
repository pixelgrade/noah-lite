var Slider = (function () {

    var options = {
        dots: true,
        infinite: true,
        speed: 600,
        fade: true,
        useTransform: false,
        ease: 'easeInOutCirc'
    };

    return {
        init: function( selector ) {
            $( selector ).each(function() {
                var $this = $(this),
                    autoplay = typeof $this.data( 'autoplay' ) !== "undefined",
                    autoplaySpeed = typeof $this.data( 'autoplay-delay' ) == "number" ? $this.data( 'autoplay-delay' ) : 2000;

                if ( autoplay ) {
                    $.extend(options, {
                        autoplay: autoplay,
                        autoplaySpeed: autoplaySpeed
                    })
                }

                $this.slick( options );
                $this.find( '.slick-slide' ).first().focus();
            });
        }
    }

})();