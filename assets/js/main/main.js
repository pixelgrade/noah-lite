var Noah = new pixelgradeTheme(),
	resizeEvent = 'ontouchstart' in window && 'onorientationchange' in window ? 'pxg:orientationchange' : 'pxg:resize';

Noah.init = function() {
	Noah.Hero = new Hero();
	Noah.Navbar = new Navbar();

	Noah.eventHandlers();
	Noah.handleContent();
	Noah.adjustLayout();
};

Noah.update = function() {
	Noah.Hero.update();
	Noah.Navbar.update();
};

Noah.adjustLayout = function() {

	Noah.Navbar.destroy();

	if ( below( 'lap' ) ) {
		Noah.Navbar.init();
		Noah.Navbar.onChange();
	}

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', '' );
	$updatable.css( 'paddingTop', headerHeight );

	Noah.Hero.refresh()
};

Noah.handleContent = function( $container ) {
	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	unwrapImages( $container.find( '.entry-content' ) );
	wrapCommentActions( $container );
	handleVideos( $container );
	handleCustomCSS( $container );

	$container.find( '.js-taxonomy-dropdown' ).resizeselect();
	Slider.init( $container.find( '.c-hero__slider' ) );

	$container.find( '.c-hero__background' ).rellax();

	// add every image on the page the .is-loaded class
	// after the image has actually loaded
	$container.find( 'img' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function() {
			$each.addClass( 'is-loaded' );
		} );
	} );

	Noah.eventHandlers( $container );

	var NoahGallery = new Gallery( $container );

	$( window ).on( 'scroll', function() {
		$.each( NoahGallery.galleries, showGalleries );
	} );

	$.each( NoahGallery.galleries, showGalleries );

	function showGalleries( i, obj ) {
		var $gallery = $( obj );

		if ( Noah.getScroll() + Noah.getWindowHeight() * 3 / 4 > $gallery.offset().top ) {
			NoahGallery.show( $gallery );
		}
	}

	Noah.adjustLayout();
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

$( document ).ready( function() {
	Noah.init();
} );

$.fn.rellax.defaults.bleed = 20;
$.fn.rellax.defaults.scale = 1.2;
