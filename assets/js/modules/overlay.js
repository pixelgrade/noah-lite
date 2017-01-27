var Overlay = ( function () {
    var $overlay,
        $content,
        $close;

    init();

    function init() {

        $close = $( '<div class="c-overlay__close"></div>' );

        $overlay = $( '<div/>', {
            class: 'c-overlay u-container-sides-spacings js-header-height-padding-top'
        });

        $content = $( '<div/>', {
            class: 'c-overlay__content u-content-width u-content-bottom-spacing entry-content content-area'
        });

        $content.appendTo( $overlay );
        $overlay.appendTo( 'body' );
        $close.appendTo( 'body' );

        bindEvents();
    }

    function bindEvents() {

        $close.on( 'click', function() {
            hide();
        });

        $content.on( 'click', function(e) {
            e.stopPropagation();
        });

        $overlay.on( 'click', function(e) {
            hide();
        });

        $(document).on( 'keydown', function( evt ) {
            evt = evt || window.event;
            var isEscape = false;
            if ( "key" in evt ) {
                isEscape = ( evt.key == "Escape" || evt.key == "Esc" );
            } else {
                isEscape = ( evt.keyCode == 27 );
            }
            if ( isEscape ) {
                hide();
            }
        });
    }

    function setContent( html ) {
        $content.empty().html( html );
        $content.addClass( 'is-visible' );
    }

    function show() {
        $( 'body' ).addClass( 'has-overlay' ).css( 'overflow', 'hidden' );

        $overlay.addClass( 'is-visible' );
    }

    function hide() {
        $( 'body' ).removeClass( 'has-overlay' ).css( 'overflow', '' );

        $overlay.removeClass( 'is-visible' );
        $content.removeClass( 'is-visible' );
    }

    function load( url ) {
        Overlay.show();

        $.ajax({
            url: noah_js_strings.ajaxurl,
            type: 'post',
            data: {
                action: 'noah_ajax_get_content',
                page_url: url
            },
            success: function( result ) {
                Overlay.setContent( result );
            }
        });
    }

    return {
        show: show,
        hide: hide,
        load: load,
        setContent: setContent
    };

})();
