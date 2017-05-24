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
	} );

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

	$container.find( '.gallery' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.wrap( '<div class="c-slideshow">' );
		$each.wrap( '<div class="u-full-width u-container-sides-spacings">' );
		$each.wrap( '<div class="o-wrapper u-container-width">' );
	} );

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

$( document ).ready( function() {
	Noah.handleContent();
	Noah.adjustLayout();
	Noah.eventHandlers();
	Noah.update();
} );
