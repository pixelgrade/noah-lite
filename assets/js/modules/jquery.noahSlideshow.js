// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;(function ( $, window, document, undefined ) {

    var $window = $( window ),
        windowHeight = window.innerHeight,
        latestKnownScrollY = $window.scrollTop(),
        $body = $( 'body' );

    $( window ).on( 'resize', function() {
        windowHeight = window.innerHeight;
    });

    $( window ).on( 'scroll', function() {
        latestKnownScrollY = $window.scrollTop();
    });

    function NoahSlideshow( element, options ) {
        var self = this;

        self.$gallery = $( element );
        self.$slider = $( '<div class="c-slider">' );
        self.$slides = $();
        self.$counter = null;
        self.$currentView = null;

        self.renderSlider_();
        self.bindEvents_();
        self.finishInit_();

    }

    NoahSlideshow.prototype = {
        constructor: NoahSlideshow,
        finishInit_: function() {
            var self = this;

            this.disable = false;

            if ( this.$gallery.is( '.gallery-columns-0' ) ) {
                this.$gallery.removeClass( 'gallery-columns-0' );
                this.$gallery.addClass( 'gallery-columns-3' );
                this.showSlider_( 0, true );
                this.resizeToFit_();
                this.$slides.first().imagesLoaded(function() {
                    self.$el.addClass( 'is-loaded' );
                });
            } else {
                this.showThumbs_( 0, true );
                this.$gallery.imagesLoaded(function() {
                    self.$el.addClass( 'is-loaded' );
                });
            }
        },
        renderSlider_: function() {
            this.renderSlides_();
            this.renderHeader_();
            this.renderControls_();

            this.$el = this.$gallery;
            this.$el = this.$el.wrap( '<div class="c-slideshow u-content-background">' ).parent();
            this.$el.wrap( '<div class="u-full-width u-container-sides-spacings">' ).parent();
            this.$el.wrap( '<div class="o-wrapper u-container-width">' ).parent();
            this.$slider.appendTo( this.$el );
        },
        bindEvents_: function() {
            var self = this,
                resizeEvent = ( 'onorientationchange' in window && isTouch ) ? 'orientationchange' : 'resize';

            $(document).on( 'keydown', function(e) {
                self.onKeyDown(e);
            });

            $window.on( resizeEvent + ' noah:project-view-change', function() {

                if ( self.$currentView == null ) {
                    return;
                }

                // resize gallery
                if ( self.$currentView == self.$gallery ) {
                    self.$gallery.imagesLoaded(function() {
                        self.$gallery.masonry({ isAnimated: false });
                        self.$el.css( 'height', self.$gallery.outerHeight() );
                    });
                // resize slider
                } else {
                    self.resizeToFit_();
                    self.$el.css( 'height', self.$slider.outerHeight() );
                }
            });

            self.$gallery.find( '.gallery-item' ).on( 'click', 'a img', function( e ) {
                self.showSlider_( $( this ).closest( '.gallery-item' ).index() );
            });

            self.controls.close
                .click( function( e ) {
                    self.showThumbs_( self.current );
                })
                .on( 'mouseenter', function() {
                    self.controls[ 'cursor' ].text( "" ).addClass( "c-controls__cursor--remove" );
                });

            self.controls.prev
                .click( function(e) {
                    self.onPrevClick(e);
                })
                .on('mouseenter', function(e) {
                    self.onPrevEnter(e);
                });

            self.controls.next
                .click( function(e) {
                    self.onNextClick(e);
                })
                .on( 'mouseenter', function(e) {
                    self.onNextEnter(e);
                });

            $( self.$controls )
                .mouseenter( function() {
                    self.controls[ 'cursor' ].show();
                })
                .mousemove( function( e ) {
                    self.controls[ 'cursor' ].css({
                        top: e.clientY,
                        left: e.clientX
                    });
                })
                .mouseleave( function( e ) {
                    self.controls[ 'cursor' ].hide();
                });

        },

        isInViewport: function() {
            var y1 = this.$el.offset().top,
                y2 = y1 + this.$el.outerHeight(),
                y3 = latestKnownScrollY,
                y4 = y3 + windowHeight,
                intersection;


            intersection = Math.max(y1,y3) <= Math.min(y2,y4);

            return intersection;
         },

        onKeyDown: function( e ) {

            if ( ! this.isInViewport() || this.$currentView !== this.$slider ) {
                return;
            }

            switch( e.which ) {

                case 37:
                    this.onPrevClick(e);
                    break; // left

                case 39:
                    this.onNextClick(e);
                    break; // right

                default:
                    return;
            }
        },

        onPrevClick: function( e ) {
            e.preventDefault();

            var self = this;

            if ( self.disable ) {
                return;
            }
            self.disable = true;

            var $current = $( self.$slides[self.current] ),
                $prev = $current.loopPrev( '.c-slider__slide' );

            self.current = self.current == 0 ? self.$slides.length - 1 : self.current - 1;

            TweenMax.to( $current, .5, {
                x: 50,
                opacity: 0,
                ease: Power2.easeInOut
            });

            TweenMax.fromTo( $prev, .5, {
                x: -50,
                opacity: 0
            }, {
                x: 0,
                opacity: 1,
                delay: .3,
                ease: Power2.easeInOut,
                onComplete: function() {
                    self.disable = false;
                }
            });

            self.$counter.html( $prev.data( 'slide-number' ) );
        },

        onPrevEnter: function() {
            this.controls[ 'cursor' ].removeClass( "c-controls__cursor--remove" ).text( noah_js_strings.prev_slide );
        },

        onNextClick: function( e ) {
            e.preventDefault();

            var self = this;

            if ( this.disable ) {
                return;
            }
            this.disable = true;

            var $current = $( this.$slides[this.current] ),
                $next = $current.loopNext( '.c-slider__slide' );

            this.current = this.current + 1 == this.$slides.length ? 0 : this.current + 1;

            TweenMax.fromTo($current, .5, {}, {
                x: -50,
                opacity: 0,
                ease: Power2.easeInOut
            });

            TweenMax.fromTo($next, .5, {
                x: 50,
                opacity: 0
            }, {
                x: 0,
                opacity: 1,
                delay: .3,
                ease: Power2.easeInOut,
                onComplete: function() {
                    self.disable = false;
                }
            });

            this.$counter.html( $next.data( 'slide-number' ) );
        },

        onNextEnter: function() {
            this.controls[ 'cursor' ].removeClass( "c-controls__cursor--remove" ).text( noah_js_strings.next_slide );
        },

        renderSlides_: function() {
            var self = this;

            this.$gallery.children().each( function( i, obj ) {
                var src = $( obj ).find( 'img' ).data( 'orig-file' ),
                    $img = $( '<img class="c-slider__image" src="' + src + '">'),
                    $slide = $( '<div class="c-slider__slide" data-slide-number="' + ( i + 1 ) + '">' );

                $img.appendTo( $slide );
                $slide.appendTo( self.$slider );
                self.$slides = self.$slides.add( $slide );
            });
        },
        renderHeader_: function() {
            var $header, $counter, $pageHeader, $meta;

            // create the header element
            $header = $( '<div class="c-slider__header">' );

            // if we're in the media-only view add the page header inside the slider
            $pageHeader = $( '.c-page-header' );

            if ( $( 'body.has-media-only' ).length ) {
                $pageHeader = $pageHeader.clone();
                $header.append( $pageHeader );

                // delete the empty content wrapper
                // $( '.c-project__content' ).remove();
            }

            if ( $( 'body[class*="has-media"]' ).length ) {
                var $shareContainer = $('<div class="c-page-header__side">'),
                    $shareLink = $pageHeader.find( '.c-meta__share-link' ).first().removeClass( 'h7' ).addClass( 'h5' );

                $shareLink.prependTo( $shareContainer );
                $shareContainer.prependTo( $header );
            }

            $meta = $pageHeader.find( '.c-page-header__meta' );

            // if there is no meta information remove the unneeded element
            if ( $.trim( $meta.html() ) == '' ) {
                $meta.remove();
            }

            // create the slider counter
            $counter = $( '<div class="c-page-header__side  c-counter h5">' );
            this.$counter = $( '<span class="c-counter__current js-current-slide">1</span>' );
            $counter.append( this.$counter );
            $counter.append( '<span class="c-counter__total">' + this.$slides.length + '</span>' );

            // add the counter to the slider header
            $header.append( $counter );

            // append the header we created to the slider
            this.$slider.append( $header );
        },
        morph: function( $source, $target, cb ) {
            var $clone = $source.clone().addClass( 'u-no-transition' ),
                $cloneWrap = $clone.wrap( '<div class="c-clone">' ).parent(),
                self = this,
                newScroll = latestKnownScrollY,
                source, target;

            cb = typeof cb !== "undefined" ? cb : noop;

            source = {
                width: $source.outerWidth(),
                height: $source.outerHeight(),
                offset: $source.offset()
            };

            TweenMax.to( $cloneWrap, 0, {y: 0} );

            $cloneWrap.css({
                position: 'fixed',
                top: source.offset.top - latestKnownScrollY,
                left: source.offset.left,
                width: source.width,
                height: source.height
            }).appendTo( 'body' );

            $window.one( 'noah:project-view-change', function() {
                var $adminBar = $( '#wpadminbar' ),
                    adminBarheight = ($adminBar.length && self.$currentView === self.$slider) ? $adminBar.outerHeight() : 0;

                target = {
                    width: $target.outerWidth(),
                    height: $target.outerHeight(),
                    offset: $target.offset()
                };

                if ( $target.closest( '.gallery-item' ).length ) {
                    newScroll = self.getThumbnailScrollPosition( self.current );
                } else {
                    var $header = below( 'lap' ) ? $( '.c-navbar__middle' ) : $( '.c-navbar__content' );
                    newScroll = self.$slider.offset().top - $header.outerHeight();
                }

                TweenMax.to( window, .8, {
                    scrollTo: newScroll - adminBarheight,
                    ease: Power2.easeInOut
                });

                var scale = target.width / source.width;

                TweenMax.to( $cloneWrap, .8, {
                    x: target.offset.left - source.offset.left,
                    y: latestKnownScrollY - newScroll + target.offset.top - source.offset.top + adminBarheight,
                    force3D: true,
                    ease: Power2.easeInOut,
                    onComplete: function() {
                        cb();
                        $cloneWrap.remove();
                    }
                });

                TweenMax.to( $clone, .8, {
                    scale: scale,
                    force3D: true,
                    ease: Power2.easeInOut
                });
            });
        },
        showThumbs_: function( idx, instant ) {
            var self = this;

            idx = typeof idx !== "undefined" ? idx : 0;
            instant = typeof instant !== "undefined" ? instant : false;

            if ( ! instant ) {
                var $source = self.$slides.eq( idx ).find( 'img' ),
                    $target = self.$gallery.children().eq( idx ).find( 'img' );

                self.morph($source, $target, function () {
                    self.$gallery.children().eq(idx).css({
                        opacity: '',
                        pointerEvents: 'auto'
                    });
                    self.$slider.hide();
                });

                // staggering fade in for the thumbnails
                self.$gallery.children().css({
                    opacity: 0,
                    pointerEvents: 'none'
                }).not(':eq(' + idx + ')').each(function (i, obj) {
                    var delay = Math.floor(( Math.random() * 3 ) + 3) / 10,
                        random = Math.floor(Math.random() * 10) / 100 - 0.05;

                    // set timeout with the desired delay
                    setTimeout(function() {
                        // make sure image is loaded before fading in
                        $(obj).imagesLoaded(function() {
                            // fade in
                            TweenMax.to(obj, .5, {
                                opacity: 1,
                                pointerEvents: 'auto',
                                ease: Power2.easeInOut
                            });
                        });
                    }, (delay + random) * 1000);

                });

                self.$slides.css('opacity', 0);

                var $fadeOut = self.$slider.find('.c-slider__header, .c-slider__counter');

                TweenMax.fromTo($fadeOut, .3, {
                    opacity: 1
                }, {
                    opacity: 0,
                    ease: Power2.easeInOut
                });
            } else {
                self.$gallery.children().css( 'opacity', '' );
                self.$slider.hide();
            }

            self.$currentView = self.$gallery;

            $window.trigger( 'noah:project-view-change' );
        },
        showSlider_: function( idx, instant ) {
            var self = this;

            idx = typeof idx !== "undefined" ? idx : 0;
            instant = typeof instant !== "undefined" ? instant : false;
            self.current = idx;

            if ( ! instant ) {
                var $source = self.$gallery.children().eq( idx ).find( 'img' ),
                    $target = self.$slides.eq( idx ).find( 'img' ),
                    timeline = new TimelineMax({ paused: true }),
                    $adminBar = $( '#wpadminbar' ),
                    adminBarheight = $adminBar.length ? $adminBar.outerHeight() : 0;

                TweenMax.to( self.$slides.eq( idx ), 0, {x: 0} );

                self.morph( $source, $target, function() {
                    self.$slides.eq( idx ).css( 'opacity', 1 );

                    if ( $body.is( '.has-media-only' ) ) {
                        $( '.c-project__content' ).hide();
                        TweenMax.to( window, 0, {scrollTo: self.$slider.offset().top - $( '.c-navbar' ).outerHeight() - adminBarheight } );
                    }
                });

                if ( $body.is( '.has-media-only' ) ) {
                    TweenMax.to( '.c-project__content', .3, { opacity: 0 } );
                }

                self.$gallery.children().eq( idx ).css( 'opacity', 0 );
                self.$gallery.children().not( ':eq(' + idx + ')').each( function( i, obj ) {
                    TweenMax.fromTo( obj, .6, {
                        opacity: 1
                    }, {
                        opacity: 0,
                        ease: Power2.easeInOut
                    });
                });

                var $fadeIn = self.$slider.find( '.c-slider__header, .c-slider__counter' );
                TweenMax.fromTo( $fadeIn, .3, {
                    opacity: 0
                }, {
                    opacity: 1,
                    delay: .6,
                    ease: Power2.easeInOut
                });

                self.$slider.show();
                self.$slides.css( 'opacity', 0 );
            } else {
                self.$gallery.children().css( 'opacity', 0 );
                self.$slides.css( 'opacity', 0 ).eq( idx ).css( 'opacity', 1 );

                if ( $body.is( '.has-media-only' ) ) {
                    $( '.c-project__content' ).hide();
                }
            }

            self.$counter.html( idx + 1 );
            self.$currentView = self.$slider;

            $window.trigger( 'noah:project-view-change' );

        },
        getThumbnailScrollPosition: function( idx ) {
            var $target = this.$gallery.children().eq( idx ),
                targetCenter,
                maxTarget;

            if ( ! $target.length ) {
                return latestKnownScrollY;
            }

            maxTarget = this.$gallery.offset().top + this.$gallery.outerHeight();
            targetCenter = $target.offset().top + $target.height() / 2 - windowHeight / 2;
            targetCenter = targetCenter > maxTarget ? maxTarget : targetCenter;
            targetCenter = targetCenter > 0 ? targetCenter : 0;
            return targetCenter;
        },
        renderControls_: function() {
            var self = this;

            if ( self.$controls ) {
                return;
            }

            self.controls = {};

            var $controls = $( '<div class="c-controls h4">' ),
                controls = [ 'prev', 'close', 'next' ];

            for ( var i = 0; i < controls.length; i++ ) {
                var controlName = controls[ i ],
                    $div = $( '<div class="c-controls__area c-controls__area--' + controlName + '">' );

                $controls.append( $div );
                self.controls[ controlName ] = $div;
            }

            var $cursor = $( '<div class="c-controls__cursor">' );
            $controls.append( $cursor );
            self.controls[ 'cursor' ] = $cursor;

            self.$controls = $controls;
            self.$controls.appendTo( self.$slider );
        },
        resizeToFit_: function() {
            var headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight(),
                $contentPaddingTop = $( '.u-content_container_padding_top' ),
                contentPaddingTop = $contentPaddingTop.length ? parseInt( $contentPaddingTop.css( 'paddingTop' ), 10 ) : 0,
                $adminBar = $( '#wpadminbar' ),
                adminBarheight = $adminBar.length ? $adminBar.outerHeight() : 0;

            // ensure proper fit for sliders in viewport
            if ( $( 'body.has-media-only' ).length ) {
                this.$slider.css( 'height', windowHeight - 2 * headerHeight - adminBarheight - contentPaddingTop );
            }
        }
    }


    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn.noahSlideshow = function ( options ) {
        return this.each( function () {

            if ( typeof options === 'string' ) {

                var self = $.data( this, "plugin_" + NoahSlideshow );

                if ( ! $.data( this, "plugin_" + NoahSlideshow ) ) {
                    return;
                }

                switch ( options ) {
                    case 'destroy':
                        self.showThumbs_( 0, true );
                        self.$gallery.find( '.gallery-item' ).off( 'click a img' );
                        self.$slider.remove();
                        self.$gallery.unwrap();
                        $.removeData( this, "plugin_" + NoahSlideshow );
                        break;
                    default:
                        break;
                }

                return;
            }

            if ( ! $.data( this, "plugin_" + NoahSlideshow ) ) {
                $.data( this, "plugin_" + NoahSlideshow, new NoahSlideshow( this, options ) );
            }
        });
    }

})( jQuery, window, document );
