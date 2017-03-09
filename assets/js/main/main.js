var Noah = new pixelgradeTheme(),
	resizeEvent = 'ontouchstart' in window && 'onorientationchange' in window ? 'pxg:orientationchange' : 'pxg:resize';

Noah.init = function() {
	Noah.Hero = new Hero();
	Noah.Navbar = new Navbar();

	Noah.handleContent();
};

Noah.update = function() {
	Noah.Hero.update();
	Noah.Navbar.update();
};

Noah.adjustLayout = function() {

	Noah.Navbar.destroy();

	if ( Util.below( 'lap' ) ) {
		Noah.Navbar.init();
		Noah.Navbar.onChange();
	}

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', '' );
	$updatable.css( 'paddingTop', headerHeight );

	Noah.Hero.refresh();
};

Noah.handleContent = function( $container ) {
	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	Util.unwrapImages( $container.find( '.entry-content' ) );
	Util.wrapCommentActions( $container );
	Util.handleVideos( $container );

	$container.find( '.js-taxonomy-dropdown' ).resizeselect();
	$container.find( '.c-hero__background' ).rellax();

	Noah.handleImages( $container );
	Noah.handleGalleries( $container );
	Noah.eventHandlers( $container );
	Noah.adjustLayout();
};

Noah.handleImages = function( $container ) {
	// add every image on the page the .is-loaded class
	// after the image has actually loaded
	$container.find( 'img' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function() {
			$each.addClass( 'is-loaded' );
		} );
	} );
};

Noah.handleGalleries = function( $container ) {
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
};

Noah.eventHandlers = function( $container ) {

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	Noah.ev.on( 'render', Noah.update );

	$( window ).on( resizeEvent, Noah.adjustLayout );

	$container.find( '.js-taxonomy-dropdown' ).on( 'change' ).change( function() {
		var destination = $( this ).val();
		console.log(destination);

		if ( typeof destination !== "undefined" && destination !== "#" ) {
			window.location.href = destination;
		}
	} );
};

$( document ).ready( function() {
	Noah.init();
} );
