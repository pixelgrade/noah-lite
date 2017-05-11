(function( $, window, document, undefined ) {

var Hero = function() {

	this.refresh = function() {
		this.scrolled = null;
		this.hasHero = $( 'body' ).is( '.has-hero' );
		this.adminBarHeight = $( '#wpadminbar' ).height();
		this.heroHeight = $( '.c-hero' ).outerHeight() || 0;
		this.borderWidth = parseInt( $( '.c-border' ).css( 'borderTopWidth' ), 10 );

		$( '.site-header, .c-navbar__label, .c-navbar__zone--middle' ).css( 'top', this.heroHeight );
	};

	this.refresh();
};

Hero.prototype.update = function( lastScroll ) {

	if ( ! this.hasHero || typeof lastScroll === "undefined" ) {
		return;
	}

	if ( this.scrolled == null || ! this.scrolled && lastScroll > this.heroHeight ) {
		this.scrolled = true;
		$( 'body' ).addClass( 'is-scrolled' );
		return;
	}

	if ( this.scrolled == null || this.scrolled && lastScroll <= this.heroHeight ) {
		this.scrolled = false;
		$( 'body' ).removeClass( 'is-scrolled' );
	}
};

var Navbar = function() {

	var _this = this;

	_this.initialized = false;

	_this.bindEvents = function() {
		_this.$handle.on( 'change', function( e ) {
			_this.onChange();
		} );

		var $targets = _this.$navbar.find( ".menu-item-has-children > a, .page_item_has_children > a" ),
			toggleClass = 'is-toggled';

		$targets.parent().addClass( toggleClass );

		$targets.on( 'click', function( e ) {

			var $parent = $( this ).parent();

			if ( $parent.hasClass( toggleClass ) ) {
				e.stopPropagation();
				e.preventDefault();

				$parent.removeClass( toggleClass );

				return false;
			}

		} );
	};

	_this.unbindEvents = function() {
		_this.$handle.off( 'change' );
		_this.$navbar.find( ".menu-item-has-children > a, .page_item_has_children > a" ).off( 'click' );
	};

	_this.open = function() {
		_this.$handle.prop( 'checked', true );
		_this.$handle.trigger( 'change' );
	};

	_this.close = function() {
		_this.$handle.prop( 'checked', false );
		_this.$handle.trigger( 'change' );
	};

	_this.init();
};

Navbar.prototype.onChange = function() {
	var $body = $( 'body' );
	$body.attr( 'style', '' );

	if ( this.$handle.prop( 'checked' ) ) {
		$body.width( $body.width() );
		$body.css( 'overflow', 'hidden' );
	} else {
		$body.attr( 'style', '' );
	}
};

Navbar.prototype.init = function() {

	this.$handle = $( '#menu-toggle' );

	if ( this.initialized ) {
		this.headerHeight = this.$clone.outerHeight();
		return;
	}

	$( '.js-share-clone' ).remove();

	this.$navbar = $( '.c-navbar' );
	this.$logo = $( '.header.nav' ).parent().addClass( 'has-logo' );
	this.$share = $( '.c-meta__share-link:not(.c-meta__share-link--desktop)' );
	this.$clone = this.$logo.clone().css( 'overflow', 'hidden' ).addClass( 'mobile-logo-clone' );
	this.$clone.find( 'img' ).addClass( 'is-loaded' );

	if ( ! $( '.c-navbar__zone' ).filter( function() {
		var $obj = $( this );
		return ! $obj.hasClass( 'c-navbar__zone--branding' ) && !! $obj.children().length;
	} ).length ) {
		$( '.c-navbar__label' ).hide();
	}

	if ( Util.below( 'pad' ) || (
            Util.below( 'lap' ) && Util.isTouch && window.innerWidth > window.innerHeight
        ) && this.$share.length ) {
        this.$target = this.$clone.wrapInner( "<div class='c-navbar__slide'></div>" ).children();
        this.$share.clone().addClass( 'js-share-clone' ).appendTo( this.$target );
    }

	this.$share.clone().addClass( 'js-share-clone h5' ).appendTo( '.js-share-target' );

	this.$clone.appendTo( this.$navbar );

	this.headerHeight = this.$clone.outerHeight();

	this.unbindEvents();
	this.bindEvents();

	this.initialized = true;
};

Navbar.prototype.update = function( lastScroll ) {

	lastScroll = typeof lastScroll === "undefined" ? $( window ).scrollTop() : lastScroll;

	if ( ! this.initialized || typeof this.$target === "undefined" || lastScroll < 0 || ! $( 'body' ).hasClass( 'single-jetpack-portfolio' ) ) {
		return;
	}

	if ( lastScroll < this.headerHeight ) {
		this.$target.css( 'transform', 'translate3d(0,' + - lastScroll + 'px,0)' );
	} else {
		this.$target.css( 'transform', 'translate3d(0,' + - this.headerHeight + 'px,0)' );
	}
};

Navbar.prototype.destroy = function() {
	if ( ! this.initialized ) {
		return;
	}

	if ( typeof this.$clone !== "undefined" ) {
		this.$clone.remove();
	}

	this.unbindEvents();
	this.initialized = false;
};

var Parallax = function( selector, options ) {
	this.disabled = false;
	this.selector = selector;
	this.options = options;
};

Parallax.prototype.init = function( $container ) {
	$container = $container || $( 'body' );

	if ( this.disabled === false ) {
		$container.find( this.selector ).rellax( this.options );
		$( window ).trigger( 'rellax' );
	}
};

Parallax.prototype.disable = function() {
	this.disabled = true;
	$( this.selector ).rellax( "destroy" );
};

Parallax.prototype.destroy = function() {
	$( this.selector ).rellax( "destroy" );
};

Parallax.prototype.enable = function() {
	this.disabled = false;
	$( this.selector ).rellax( this.options );
};

/*!
 * pixelgradeTheme v1.0.3
 * Copyright (c) 2017 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
var pixelgradeTheme = function() {

	var _this = this,
		windowWidth = window.innerWidth,
		windowHeight = window.innerHeight,
		lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0),
		orientation = windowWidth > windowHeight ? 'landscape' : 'portrait';

	_this.ev = $( {} );
	_this.frameRendered = false;
	_this.debug = false;

	_this.log = function() {
		if ( _this.debug ) {
			console.log.apply( this, arguments );
		}
	};

	_this.update = function() {

	};

	_this.getScroll = function() {
		return lastScrollY;
	};

	_this.getWindowWidth = function() {
		return windowWidth;
	};

	_this.getWindowHeight = function() {
		return windowHeight;
	};

	_this.getOrientation = function() {
		return orientation;
	};

	_this.onScroll = function() {
		if ( _this.frameRendered === false ) {
			return;
		}
		lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
		_this.frameRendered = false;
	};

	_this.onResize = function() {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;

		var newOrientation = windowWidth > windowHeight ? 'landscape' : 'portrait';

		_this.debouncedResize();

		if ( orientation !== newOrientation ) {
			_this.debouncedOrientationChange();
		}

		orientation = newOrientation;
	};

	_this.debouncedResize = Util.debounce(function() {
		$( window ).trigger( 'pxg:resize' );
	}, 300);

	_this.debouncedOrientationChange = Util.debounce(function() {
		$( window ).trigger( 'pxg:orientationchange' );
	}, 300);

	_this.renderLoop = function() {
		if ( _this.frameRendered === false ) {
			_this.ev.trigger( 'render' );
		}
		requestAnimationFrame( function() {
			_this.update();
			_this.renderLoop();
			_this.frameRendered = true;
			_this.ev.trigger( 'afterRender' );
		} );
	};

	_this.eventHandlers = function() {
		$( document ).ready( _this.onReady );
		$( window )
		.on( 'scroll', _this.onScroll )
		.on( 'resize', _this.onResize )
		.on( 'load', _this.onLoad );
	};

	_this.eventHandlers();
	_this.renderLoop();
};

pixelgradeTheme.prototype.onReady = function() {
	$( 'html' ).addClass( 'is-ready' );
};

pixelgradeTheme.prototype.onLoad = function() {
	$( 'html' ).addClass( 'is-loaded' );
};

var Util = {
	isTouch: function() {
		return ! ! ( ( "ontouchstart" in window ) || window.DocumentTouch && document instanceof DocumentTouch );
	},

	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	debounce: function( func, wait, immediate ) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if ( ! immediate ) {
					func.apply( context, args );
				}
			};
			var callNow = immediate && ! timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) {
				func.apply( context, args );
			}
		};
	},

	// Returns a function, that, when invoked, will only be triggered at most once
	// during a given window of time. Normally, the throttled function will run
	// as much as it can, without ever going more than once per `wait` duration;
	// but if you'd like to disable the execution on the leading edge, pass
	// `{leading: false}`. To disable execution on the trailing edge, ditto.
	throttle: function( callback, limit ) {
		var wait = false;
		return function() {
			if ( ! wait ) {
				callback.call();
				wait = true;
				setTimeout( function() {
					wait = false;
				}, limit );
			}
		}
	},

	// search every image that is alone in a p tag
	// and wrap it in a figure element to behave like images with captions
	unwrapImages: function( $container ) {

		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( 'p > img:first-child:last-child' ).each( function( i, obj ) {
			var $image = $( obj ),
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
		} );

	},

	// wrap comment actions in the same container
	// and append it to the comment body
	wrapCommentActions: function( $container ) {

		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( '.comment' ).each( function( i, obj ) {
			var $comment = $( obj ),
				$body = $comment.children( '.comment-body' ),
				$reply = $body.find( '.reply' ),
				$edit = $body.find( '.comment-edit-link' ),
				$meta = $( '<div class="comment-links">' );

			$reply.add( $edit ).appendTo( $meta );
			$meta.appendTo( $body );
		} );
	},

	handleVideos: function( $container ) {
		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( '.video-placeholder' ).each( function( i, obj ) {
			var $placeholder = $( obj ),
				video = document.createElement( 'video' ),
				$video = $( video ).addClass( 'c-hero__video' );

			// play as soon as possible
			video.onloadedmetadata = function() {
				video.play();
			};

			video.src = $placeholder.data( 'src' );
			video.poster = $placeholder.data( 'poster' );
			video.muted = true;
			video.loop = true;

			$placeholder.replaceWith( $video );
		} );
	},

	mq: function( direction, string ) {
		var $temp = $( '<div class="u-mq-' + direction + '-' + string + '">' ).appendTo( 'body' ),
			response = $temp.is( ':visible' );

		$temp.remove();
		return response;
	},

	below: function( string ) {
		return this.mq( 'below', string );
	},

	above: function( string ) {
		return this.mq( 'above', string );
	}
};

var noop = function() {};

var Noah = new pixelgradeTheme(),
	resizeEvent = "ontouchstart" in window && "onorientationchange" in window ? "pxg:orientationchange" : "pxg:resize";

Noah.init = function() {

	Noah.Parallax = new Parallax( '.c-hero__background', {
		bleed: 20,
		scale: 1.2,
		container: '.c-hero__background-mask'
	} );

	Noah.Parallax.disabled = "ontouchstart" in window && "onorientationchange" in window;

	Noah.Hero = new Hero();
	Noah.Navbar = new Navbar();

	// expose pixelgradeTheme API
	$.Noah = Noah;
};

Noah.update = function() {
	Noah.Hero.update( Noah.getScroll() );
	Noah.Navbar.update( Noah.getScroll() );

	if ( typeof Noah.Gallery !== "undefined" ) {
		Noah.Gallery.update( Noah.getScroll() + Noah.getWindowHeight() * 3 / 4 );
	}
};

Noah.adjustLayout = function() {
	Noah.log( "Noah.adjustLayout" );

	Noah.Navbar.destroy();

	if ( Util.below( 'lap' ) ) {
		Noah.Navbar.init();
		Noah.Navbar.onChange();
	}

	$( '.c-hero' ).each( function( i, obj ) {
		var $hero = $( obj ),
			heroHeight = $hero.css( 'minHeight', '' ).css( 'height' );

		$hero.css( 'minHeight', heroHeight );
	});

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', '' );
	$updatable.css( 'paddingTop', headerHeight );

	Noah.Hero.refresh();

	$( window ).trigger( 'rellax' );
};

Noah.handleContent = function( $container ) {
	Noah.log( "Noah.handleContent" );

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	Util.unwrapImages( $container.find( '.entry-content' ) );
	Util.wrapCommentActions( $container );
	Util.handleVideos( $container );

	$container.find( '.js-taxonomy-dropdown' ).resizeselect();

	Noah.Parallax.init( $container );

	Noah.handleImages( $container );
	Noah.eventHandlers( $container );
	Noah.adjustLayout();

	var $masonry = $( '.js-masonry' ).masonry();

	$masonry.imagesLoaded().progress( function() {
		$masonry.masonry( 'layout' );
	} );

	if ( $container.hasClass( 'infinite-scroll' ) ) {
		$masonry.children().addClass('is--loaded');

		// Handle behavior for infinite scroll
		$( document.body ).on( 'post-load', function () {

			// Figure out which are the new loaded posts
			var $newBlocks = $masonry.children().not( '.is--loaded' ).not( '.infinite-loader' );

			// When images have loaded take care of the layout, prepare hover animations, and animate cards in
			$newBlocks.imagesLoaded( function () {
				$masonry.masonry( 'appended', $newBlocks, true ).masonry( 'layout' );
				$newBlocks.addClass( 'is--loaded' );
			});
		});
	}
};

Noah.handleImages = function( $container ) {
	// add every image on the page the .is-loaded class
	// after the image has actually loaded
	$container.find( '.c-card, img' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function() {
			$each.addClass( 'is-loaded' );
		} );
	} );

	$container.find('.gallery').each(function( i, obj ) {
		var $each = $( obj );
		$each.wrap( '<div class="c-slideshow">' );
		$each.wrap( '<div class="u-full-width u-container-sides-spacings">' );
		$each.wrap( '<div class="o-wrapper u-container-width">' );
	});

	$container.find( '.js-taxonomy-dropdown' ).resizeselect();

	Noah.Parallax.init( $container );

	Noah.eventHandlers( $container );

	if ( $( 'body' ).is( '.single-jetpack-portfolio' ) ) {
		var $target = $container.find( '.js-share-target' );

		$container.find( '.js-share-clone' ).remove();
		$container.find( '.c-meta__share-link' ).clone().addClass( 'js-share-clone h4' ).appendTo( $target );
	}
};

Noah.eventHandlers = function( $container ) {

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	Noah.ev.on( 'render', Noah.update );

	$( window ).on( resizeEvent, Noah.adjustLayout );

	$container.find( '.js-taxonomy-dropdown' ).on( 'change' ).change( function() {
		var destination = $( this ).val();

		if ( typeof destination !== "undefined" && destination !== "#" ) {
			window.location.href = destination;
		}
	} );
};

Noah.init();

$(document).ready(function() {
	Noah.handleContent();
	Noah.adjustLayout();
	Noah.eventHandlers();
	Noah.update();
});

})( jQuery, window, document );

/*!
 * jQuery Rellax Plugin v0.3.6.1
 * Examples and documentation at http://pixelgrade.github.io/rellax/
 * Copyright (c) 2016 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
;(
	function( $, window, document, undefined ) {

		if ( ! window.requestAnimationFrame ) {
			return;
		}

		function Rellax( element, options ) {
			this.$el = $( element );
			this.ready = false;
			this.options = $.extend( $.fn.rellax.defaults, options );
			this.$parent = this.$el.parent().closest( this.options.container );
			this.parent = this.$parent.data( "plugin_" + Rellax );

			var $el = this.$el,
				amount = $el.data( 'rellax-amount' ),
				bleed = $el.data( 'rellax-bleed' ),
				fill = $el.data( 'rellax-fill' ),
				scale = $el.data( 'rellax-scale' );

			this.options.amount = amount !== undefined ? parseFloat( amount ) : this.options.amount;
			this.options.bleed = bleed !== undefined ? parseFloat( bleed ) : this.options.bleed;
			this.options.scale = scale !== undefined ? parseFloat( scale ) : this.options.scale;
			this.options.fill = fill !== undefined;

			if ( this.options.amount == 0 ) {
				return;
			}

			elements.push( this );
		}

		$.extend( Rellax.prototype, {
			constructor: Rellax,
			_resetElement: function() {
				this.$el.css({
					position: '',
					top: '',
					left: '',
					width: '',
					height: '',
					transform: ''
				});
			},
			_reloadElement: function() {
				this.$el.css({
					position: '',
					top: '',
					left: '',
					width: '',
					height: ''
				});
				this.offset = this.$el.offset();
				this.height = this.$el.outerHeight();
				this.width = this.$el.outerWidth();

				if ( this.parent === undefined ) {
					this.offset.top -= this.options.bleed;
					this.height += 2 * this.options.bleed;
				}

				this.ready = true;
			},
			_scaleElement: function() {
				var parentHeight = this.$parent.outerHeight(),
					parentWidth = this.$parent.outerWidth(),
					scaleY = ( parentHeight + ( windowHeight - parentHeight ) * ( 1 - this.options.amount ) ) / this.height,
					scaleX = parentWidth / this.width,
					scale = Math.max(scaleX, scaleY);

				this.width = this.width * scale;
				this.height = this.height * scale;

				this.offset.top = ( parentHeight - this.height ) / 2;
				this.offset.left = ( parentWidth - this.width ) / 2;
			},
			_prepareElement: function() {
				if ( this.parent === undefined ) {
					this.$el.addClass( 'rellax-element' );
					this.$el.css({
						position: 'fixed',
						top: this.offset.top,
						left: this.offset.left,
						width: this.width,
						height: this.height
					});
				} else {
					this._scaleElement();
					this.$el.css({
						position: 'absolute',
						top: this.offset.top,
						left: this.offset.left,
						width: this.width,
						height: this.height
					});
				}
			},
			_setParentHeight: function() {
				if ( this.parent == undefined ) {
					var $parent = this.$el.parent(),
						parentHeight = $parent.css( 'minHeight', '' ).outerHeight();

					parentHeight = windowHeight < parentHeight ? windowHeight : parentHeight;
					$parent.css( 'minHeight', parentHeight );
				}
			},
			_updatePosition: function( forced ) {

				if ( this.ready !== true ) return;

				var progress = this._getProgress(),
					height = this.parent !== undefined ? this.parent.height : this.height,
					move = ( windowHeight + height ) * ( progress - 0.5 ) * this.options.amount,
					scale = 1 + ( this.options.scale - 1 ) * progress,
					scaleTransform = scale >= 1 ? 'scale(' + scale + ')' : '';

				if ( this.parent === undefined ) {
					move *= -1;
				}

				if ( forced !== true && ( progress < 0 || progress > 1 ) ) {
					this.$el.addClass( 'rellax-hidden' );
					return;
				}

				this.$el.removeClass( 'rellax-hidden' );

				this.$el.data( 'progress', progress );

				if ( this.$el.is( this.options.container ) ) {
					this.$el.css( 'transform', 'translate3d(0,' + ( - lastScrollY ) + 'px,0)' );
				} else {
					this.$el.css( 'transform', 'translate3d(0,' + move + 'px,0) ' + scaleTransform );
				}
			},
			_getProgress: function() {
				if ( this.parent !== undefined ) {
					return parseFloat( this.$parent.data( 'progress' ) );
				} else {
					return ( ( lastScrollY - this.offset.top + windowHeight ) / ( windowHeight + this.height ) );
				}
			}
		} );

		$.fn.rellax = function( options ) {
			return this.each( function() {
				var element = $.data( this, "plugin_" + Rellax ),
					idx;

				if ( typeof options !== "string" && typeof element === "undefined" ) {
					$.data( this, "plugin_" + Rellax, new Rellax( this, options ) );
				} else {
					if ( options === "destroy" ) {
						idx = elements.indexOf( element );
						if ( idx > -1 ) {
							elements.splice( idx, 1 );
						}
					}
				}
			} );
		};

		$.fn.rellax.defaults = {
			amount: 0.5,
			bleed: 0,
			scale: 1,
			container: "[data-rellax-container]"
		};

		var $window = $( window ),
			windowWidth = window.screen.width || window.innerWidth,
			windowHeight = window.screen.height || window.innerHeight ,
			lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0),
			frameRendered = true,
			elements = [];

		function render() {
			if ( frameRendered !== true ) {
				updateAll();
			}
			window.requestAnimationFrame( render );
			frameRendered = true;
		}

		function updateAll( forced ) {
			$.each(elements, function(i, element) {
				element._updatePosition( forced );
			});
		}

		function resetAll() {
			$.each(elements, function(i, element) {
				element._resetElement();
			});
		}

		function reloadAll() {
			$.each(elements, function(i, element) {
				element._reloadElement();
			});
		}

		function prepareAll() {
			$.each(elements, function(i, element) {
				element._prepareElement();
			});
		}

		function setHeights() {
			$.each(elements, function(i, element) {
				element._setParentHeight();
			});
		}

		function badRestart() {
			windowWidth = window.innerWidth;
			windowHeight = window.innerHeight;
			setHeights();
			resetAll();
			reloadAll();
			prepareAll();
			updateAll( true );
			$( window ).trigger( 'rellax:restart' );
		}

		var restart = debounce(badRestart, 300);

		function debounce(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		}

		function bindEvents() {

			$window.load(function() {
				render();
			});

			$window.on( 'scroll', function() {
				if ( frameRendered === true ) {
					lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
				}
				frameRendered = false;
			});

			$window.on( 'rellax', restart );

		}

		bindEvents();
	}
)( jQuery, window, document );

(function($) {

	$.fn.getStyleObject = function(){
		var dom = this.get(0),
			returns = {},
			style;

		if ( window.getComputedStyle ) {
			var camelize = function( a, b ) {
				return b.toUpperCase();
			};
			style = window.getComputedStyle(dom, null);
			for ( var i = 0, l = style.length; i < l; i++ ) {
				var prop = style[i];
				var camel = prop.replace(/\-([a-z])/g, camelize);
				var val = style.getPropertyValue(prop);
				returns[camel] = val;
			};
			return returns;
		};
		if ( style = dom.currentStyle ) {
			for ( var prop in style ) {
				returns[prop] = style[prop];
			}
			return returns;
		}
		return this.css();
	}

	$.fn.copyCSS = function( source ) {
		var styles = $( source ).getStyleObject();
		this.css( styles );
	}

	$.fn.resizeselect = function(settings) {
		return this.each(function() {

			$(this).change(function(){
				var $this = $(this);

				// create test element
				var text = $this.find("option:selected").text();
				var $test = $("<span>").html(text);

				// add to body, get width, and get out
				$test.appendTo('body').copyCSS($this);
				$test.css('width', 'auto');
				var width = $test.width();
				$test.remove();

				// set select width
				$this.width(width);

			// run on start
			}).change();
		});
	};

})(jQuery, window);