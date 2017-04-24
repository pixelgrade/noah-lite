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
