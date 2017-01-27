var pixelgradeTheme = function() {

    var _this = this;

    _this.ev = $({});

    _this.onScroll = function() {
        _this.latestKnownScrollY = $( window ).scrollTop();
    };

    _this.onResize = function() {
        _this.windowWidth = window.innerWidth;
        _this.windowHeight = window.innerHeight;
        _this.orientation = _this.windowWidth > _this.windowHeight ? 'landscape' : 'portrait';

        var newOrientation = _this.windowWidth > _this.windowHeight ? 'landscape' : 'portrait';

        if ( Util.isTouch && newOrientation == _this.orientation ) {
            return;
        }

        _this.orientation = newOrientation;
    };

    _this.renderLoop = function() {
        requestAnimationFrame( function() {
            _this.ev.trigger( 'beforeRender' );
            _this.renderLoop();
            _this.ev.trigger( 'afterRender' );
        });
    };

    _this.eventHandlers = function() {
        $( document ).ready( _this.onReady );
        $( window )
            .scroll( _this.onScroll )
            .resize( _this.onResize )
            .smartresize( _this.refresh )
            .load( _this.onLoad );
    };

    _this.onReady = function() {
        $( 'html' ).addClass( 'is-ready' );
    };

    _this.onLoad = function() {
        $( 'html' ).addClass( 'is-loaded' );
    };

    _this.onResize();
    _this.onScroll();
    _this.renderLoop();
};

pixelgradeTheme.prototype = {
    init: function() {
        this.ev.trigger( 'beforeInit' );

        this.onResize();
        this.eventHandlers();
        this.ev.trigger( 'eventHandlers' );
    }
};