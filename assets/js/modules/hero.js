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
