var noop = function() {};

// search every image that is alone in a p tag
// and wrap it in a figure element to behave like images with captions
function unwrapImages( $container ) {

    $container = typeof $container !== "undefined" ? $container : $( 'body' );

    $container.find( 'p > img:first-child:last-child' ).each(function(i, obj) {
        var $image = $(obj),
            className = $image.attr( 'class' ),
            $p = $image.parent();

        if ( $.trim( $p.text() ).length ) {
            return;
        }

        $image
            .removeAttr( 'class' )
            .unwrap()
            .wrap( '<figure />' )
            .parent()
            .attr( 'class', className );
    });

}

// wrap comment actions in the same container
// and append it to the comment body
function wrapCommentActions( $container ) {

    $container = typeof $container !== "undefined" ? $container : $( 'body' );

    $container.find( '.comment' ).each( function(i, obj) {
        var $comment = $(obj),
            $body = $comment.children( '.comment-body' ),
            $reply = $body.find( '.reply' ),
            $edit = $body.find( '.comment-edit-link' ),
            $meta = $( '<div class="comment-links">' );

        $reply.add($edit).appendTo($meta);
        $meta.appendTo($body);
    });
}

function handleVideos( $container ) {
    $container = typeof $container !== "undefined" ? $container : $( 'body' );

    $container.find( '.video-placeholder' ).each( function( i, obj ) {
        var $placeholder = $( obj ),
            video = document.createElement( 'video' ),
            $video = $( video ).addClass( 'c-hero__video' );

        // play as soon as possible
        video.onloadedmetadata = function() {
            video.play();
        };

        video.src       = $placeholder.data( 'src' );
        video.poster    = $placeholder.data( 'poster' );
        video.muted     = true;
        video.loop      = true;

        $placeholder.replaceWith( $video );

        // if ( Modernizr.touchevents ) {

        //     // if autoplay is not allowed play on user gesture
        //     $placeholder.closest('.hero').on( 'touchstart', function() {
        //         video.play();
        //     });

        //     if ( isiPhone ) {
        //         makeVideoPlayableInline( video, /* hasAudio */ false);
        //     }
        // }

    });
}

function handleCustomCSS( $container ) {
    var $elements = typeof $container !== "undefined" ? $container.find( "[data-css]" ) : $( "[data-css]" );

    if ( $elements.length ) {

        $elements.each(function(i, obj) {

            var $element = $( obj ),
                css = $element.data( 'css' );

            if ( typeof css !== "undefined" ) {
                $element.replaceWith( '<style type="text/css">' + css + '</style>' );
            }

        });
    }
}

$.fn.loopNext = function( selector ) {
    selector = selector || '';
    return this.next( selector ).length ? this.next( selector ) : this.siblings( selector ).addBack( selector ).first();
};

$.fn.loopPrev = function( selector ) {
    selector = selector || '';
    return this.prev( selector ).length ? this.prev( selector ) : this.siblings( selector ).addBack( selector ).last();
};

function mq( direction, string ) {
    var $temp = $( '<div class="u-mq-' + direction + '-' + string + '">' ).appendTo( 'body' ),
        response = $temp.is( ':visible' );

    $temp.remove();
    return response;
}

function below( string ) {
    return mq( 'below', string );
}

function above( string ) {
    return mq( 'above', string );
}

function stopEvent( e ) {
    if ( typeof e == "undefined" ) return;
    e.stopPropagation();
    e.preventDefault();
}

// smooth scrolling to anchors on the same page
$( 'a[href*="#"]:not([href="#"])' ).on( 'click touchstart', function() {
    if ( location.pathname.replace( /^\//,'' ) == this.pathname.replace( /^\//,'' ) && location.hostname == this.hostname ) {
        var target = $( this.hash );
        target = target.length ? target : $( '[name=' + this.hash.slice(1) + ']' );
        if ( target.length ) {
            TweenMax.to( window, 1, {scrollTo: target.offset().top} );
            return false;
        }
    }
});

// debouncing function from John Hann
// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
//
// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce( func, threshold, execAsap ) {
    var timeout;

    return function debounced () {
        var obj = this, args = arguments;
        function delayed () {
            if (!execAsap)
                func.apply(obj, args);
            timeout = null;
        };

        if ( timeout )
            clearTimeout( timeout );
        else if ( execAsap )
            func.apply( obj, args );

        timeout = setTimeout( delayed, threshold || 100 );
    };
};

// use the debounce function to create a smartresize event
$.fn[ 'smartresize' ] = function( fn ) {
    return fn ? this.bind( 'resize', debounce( fn, 200 ) ) : this.trigger( 'smartresize' );
};

//similar to PHP's empty function
function empty( data ) {
    if ( typeof( data ) == 'number' || typeof( data ) == 'boolean' ) {
        return false;
    }

    if ( typeof( data ) == 'undefined' || data === null ) {
        return true;
    }

    if ( typeof( data.length ) != 'undefined' ) {
        return data.length === 0;
    }

    var count = 0;

    for ( var i in data ) {
        // if (data.hasOwnProperty(i))
        //
        // This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
        // http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
        //
        // for hosts objects we do this
        if ( Object.prototype.hasOwnProperty.call( data, i ) ) {
            count ++;
        }
    }
    return count === 0;
}
