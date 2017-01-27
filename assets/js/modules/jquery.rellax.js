/* ========================================================== */
/* ================= noah modified version ================= */
/* ========================================================== */

/*!
 * jQuery Rellax Plugin v0.2.1
 * Examples and documentation at http://pixelgrade.github.io/rellax/
 * Copyright (c) 2016 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
;( function ( $, window, document, undefined ) {

    if ( !window.requestAnimationFrame ) return;

    var $window = $( window ),
        elements = [],
        lastScrollY = -1,
        latestScrollY = window.scrollY,
        requestTick = false,
        windowWidth = window.innerWidth,
        windowHeight = window.innerHeight,
        orientation,
        isTouch = !! ( ( "ontouchstart" in window ) || window.DocumentTouch && document instanceof DocumentTouch );

    function getOrientation() {
        return windowWidth > windowHeight ? "landscape" : "portrait";
    }

    function updateAll() {
        if ( requestTick ) {
            $.each( elements, function( i, element ) {
                element._updatePosition();
            });
            requestTick = false;
        }
        window.requestAnimationFrame( updateAll );
    }

    function reloadAll() {
        $.each( elements, function( i, element ) {
            element._reloadElement();
        });
    }

    $( window ).on("load", function() {

        reloadAll();
        requestTick = true;
        window.requestAnimationFrame( updateAll );

        $window.on( 'resize', function() {
            windowWidth = window.innerWidth;
            windowHeight = window.innerHeight;

            if ( isTouch && getOrientation() === orientation ) {
                return;
            }

            orientation = getOrientation();
            reloadAll();
            requestTick = true;
        });

        $window.on( 'scroll', function() {
            latestScrollY = window.scrollY;

            if ( lastScrollY !== latestScrollY ) {
                requestTick = true;
                lastScrollY = latestScrollY;
            }
        });

    });

    function Rellax( element, options ) {
        this.element = element;
        this.options = $.extend( $.fn.rellax.defaults, options );

        var self = this,
            $el = $( this.element ),
            myAmount = $el.data( 'rellax-amount' ),
            myBleed = $el.data( 'rellax-bleed' ),
            fill = $el.data( 'rellax-fill' ),
            myScale = $el.data( 'rellax-scale' );

        this.isContainer = $el.is( self.options.container );
        this.$parent = $el.parent().closest( self.options.container );

        this.options.amount = myAmount !== undefined ? parseFloat(myAmount) : this.options.amount;
        this.options.bleed = myBleed !== undefined ? parseFloat(myBleed) : this.options.bleed;
        this.options.scale = myScale !== undefined ? parseFloat(myScale) : this.options.scale;
        this.options.fill = fill !== undefined ;

        if ( self.options.amount == 0 ) return;

        self._reloadElement();
        self._bindEvents();

        $el.addClass( 'rellax-active' );

        elements.push( self );
    }

    $.extend( Rellax.prototype, {
        constructor: Rellax,
        _bindEvents: function() {
        },
        _scaleElement: function() {
            this.width = this.$parent.outerWidth();
            this.height = this.$parent.outerHeight() + ( windowHeight - this.$parent.outerHeight() ) * this.options.amount;
        },
        _reloadElement: function() {
            var self = this,
                parent,
                $el = $( this.element ).removeAttr( 'style' );

            if ( self.$parent.length ) {
                self.$parent.css( 'position', 'static' );
            }

            self.width = $el.width();
            self.height = $el.height();
            self.offset = $el.offset();

            if ( self.$parent.length || self.options.fill ) {
                self._scaleElement();
            }

            if ( self.isContainer ) {
                self.height += 2 * self.options.bleed;
                self.offset.top -= self.options.bleed;
            }

            if ( self.$parent.length ) {
                parent = self.$parent.data( "plugin_" + Rellax );
                self.offset.left = self.offset.left - self.$parent.offset().left;
            }

            var style = {
                position: self.$parent.length ? 'absolute' : 'fixed',
                top: self.offset.top,
                left: self.offset.left,
                width: self.width,
                height: self.height,
                marginTop: self.$parent.length ? ( parent.height - self.height ) / 2 : 0,
                marginLeft: self.$parent.length ? ( parent.width - self.width ) / 2 : 0
            };

            if ( self.isContainer ) $.extend( style, {zIndex: -1} );

            if ( self.$parent.length ) {

                $.extend( style, {
                    top: self.offset.top - self.$parent.offset().top + self.options.bleed
                } );

                self.$parent.css( {
                    position: 'fixed',
                    overflow: 'hidden',
                    zIndex: -1
                } );

            }

            $el.css( style );
        },
        _isInViewport: function() {
            var offset = this.isContainer ? this.options.bleed : 0;
            return latestScrollY > this.offset.top - windowHeight - offset &&
                   latestScrollY < this.offset.top + this.height + offset;
        },
        _updatePosition: function() {
            var self = this,
                _this = this.$parent.length ? this.$parent.data( "plugin_" + Rellax ) : this,
                progress = ( latestScrollY - _this.offset.top + windowHeight ) / ( windowHeight + _this.height ),
                move = ( windowHeight + _this.height ) * ( progress - 0.5 ) * self.options.amount,
                scale = 1 + ( self.options.scale - 1 ) * ( progress - 0.5 );

            if ( this.isContainer ) {
                $( self.element ).css( 'transform', 'translate3d(0,' + ( -latestScrollY ) + 'px,0) scale(' + scale + ')' );
            } else {
                if ( ! this.$parent.length ) {
                    move -= latestScrollY;
                } else {
                    move -= self.options.bleed;
                }

                $( self.element ).css( 'transform', 'translate3d(0,' + move + 'px,0) scale(' + scale + ')' );
            }

            if ( _this._isInViewport() && ! _this.isVisible ) {
                $( self.element ).addClass( 'rellax-visible' );
                self.isVisible = true;
            }

            if ( ! _this._isInViewport() && _this.isVisible ) {
                $( self.element ).removeClass( 'rellax-visible' );
                self.isVisible = false;
            }
        }
    });

    $.fn.rellax = function ( options ) {
        return this.each(function () {
            if ( ! $.data( this, "plugin_" + Rellax ) ) {
                $.data( this, "plugin_" + Rellax, new Rellax( this, options ) );
            } else {
                var self = $.data( this, "plugin_" + Rellax );
                if ( options && typeof options == 'string' ) {
                    if ( options == "refresh" ) {
                        self._reloadElement();
                    }
                }
            }
        });
    };

    $.fn.rellax.defaults = {
        amount: 0.5,
        bleed: 0,
        scale: 1,
        container: '[data-rellax-container]'
    };

})( jQuery, window, document );
