(function(){

    var $hero = $( '.c-hero__wrapper' ),
        $document = $( document ),
        keysBound = false;

    function positionHeroContent( e ) {
        switch( e.which ) {
            case 37: // left
                if ( $hero.hasClass( 'c-hero__wrapper--right' ) ) {
                    $hero.removeClass( 'c-hero__wrapper--right' );
                } else {
                    $hero.addClass( 'c-hero__wrapper--left' );
                }
            break;

            case 38: // up
                if ( $hero.hasClass( 'c-hero__wrapper--bottom' ) ) {
                    $hero.removeClass( 'c-hero__wrapper--bottom' );
                } else {
                    $hero.addClass( 'c-hero__wrapper--top' );
                }
            break;

            case 39: // right
                if ( $hero.hasClass( 'c-hero__wrapper--left' ) ) {
                    $hero.removeClass( 'c-hero__wrapper--left' );
                } else {
                    $hero.addClass( 'c-hero__wrapper--right' );
                }
            break;

            case 40: // down
                if ( $hero.hasClass( 'c-hero__wrapper--top' ) ) {
                    $hero.removeClass( 'c-hero__wrapper--top' );
                } else {
                    $hero.addClass( 'c-hero__wrapper--bottom' );
                }
            break;

            default: return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    }

    function bindArrowKeys( e ) {
        if ( keysBound ) return;
        switch( e.which ) {
            case 37:
            case 39:
                // positionHeroContent( e );
                // $document.off( 'keydown', bindArrowKeys );
                // keysBound = true;
                // $document.on( 'keydown', positionHeroContent );
            break;
            case 40:
                var currentScroll = $( window ).scrollTop(),
                    windowHeight = window.innerHeight;

                if ( $( 'body' ).hasClass( 'has-hero' ) && 0 === currentScroll ) {
                    TweenMax.to( window, .5, { scrollTo: windowHeight } );
                }
            break;
            default: return;
        }
    }

     $document.on( 'keydown', bindArrowKeys );

})();