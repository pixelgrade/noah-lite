// let the magic begin!
var latestKnownScrollY = $( window ).scrollTop();

$( window ).on( 'scroll', function () {
	latestKnownScrollY = $( window ).scrollTop();
} );

function adjust_layout() {

	Noah.Navbar.destroy();

	if ( below( 'lap' ) ) {
		Noah.Navbar.init();
		Noah.Navbar.onChange();
	}

	// initialize or destroy slideshows
	if ( below( 'pad' ) || (
			below( 'lap' ) && Util.isTouch && window.innerWidth > window.innerHeight
		) ) {
		$( '.gallery' ).noahSlideshow( 'destroy' );
	} else {
		$( '.gallery' ).noahSlideshow();
	}

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', '' );
	$updatable.css( 'paddingTop', headerHeight );

	Noah.Hero.refresh()
}

function handle_new_content( $container ) {
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
	$container.find( 'img' ).each( function ( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function () {
			$each.addClass( 'is-loaded' );
		} );
	} );

	event_handlers( $container );

	var NoahGallery = new Gallery( $container );

	$( window ).on( 'scroll', function () {
		$.each( NoahGallery.galleries, showGalleries );
	} );

	$.each( NoahGallery.galleries, showGalleries );

	function showGalleries( i, obj ) {
		var $gallery = $( obj );

		if ( latestKnownScrollY + window.innerHeight * 3 / 4 > $gallery.offset().top ) {
			NoahGallery.show( $gallery );
		}
	}

	adjust_layout();
}

function event_handlers_once() {
	var $body = $( 'body' );

	$body.on( 'click', '.c-meta__share-link', function ( e ) {
		e.preventDefault();

		var shareContent = $( '.sharedaddy' );

		if ( shareContent.length ) {
			Overlay.setContent( shareContent );
			shareContent.show();
			Overlay.show();
		}
	} );

	$body.on( 'click', '.menu-item.overlay a', function ( e ) {
		stopEvent( e );
		var url = $( this ).attr( 'href' );
		Overlay.load( url );
	} );

	$body.on( 'click', '.gallery-item a img', function ( e ) {
		stopEvent( e );
	} );
}

function event_handlers( $container ) {

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	$( window ).on( 'resize', function () {
		adjust_layout();
	} );

	$container.find( '.js-taxonomy-dropdown' ).on( 'change' ).change( function () {

		var destination = $( this ).val();

		if ( typeof destination !== "undefined" && destination !== "#" ) {
			if ( typeof Barba !== "undefined" && Barba.hasOwnProperty( "Pjax" ) ) {
				Barba.Pjax.goTo( destination );
			} else {
				window.location.href = destination;
			}
		}
	} );
}

Noah = new pixelgradeTheme();

$( document ).ready( function () {

	var $body = $( 'body' );

	if ( ! $body.is( '.customizer-preview' ) && typeof $body.data( 'ajaxloading' ) !== "undefined" ) {

		Noah.Ajax = new AjaxLoading();

		Noah.Ajax.ev.on( 'beforeOut', function ( ev, container ) {
			Noah.Navbar.close();
			show_page_mask();
		} );

		Noah.Ajax.ev.on( 'afterOut', function ( ev, container ) {
			$( 'html' ).addClass( 'no-transitions' );
		} );

		Noah.Ajax.ev.on( 'afterIn', function ( ev, container ) {

			var $container = $( container );

			$container.css( 'top', 100 );

			reload_js( 'related-posts.js' );

			setTimeout( function () {
				$( 'html' ).removeClass( 'no-transitions' );
				$container.css( 'top', '' );
				handle_new_content( $container );
				hide_page_mask();
			} );

		} );
	}

	Noah.Hero = new Hero();
	Noah.Navbar = new Navbar();

	Noah.ev.on( 'beforeRender', function () {
		Noah.Hero.update();
		Noah.Navbar.update();
	} );

	Noah.init();

	event_handlers_once();

	handle_new_content();
	hide_page_mask();

} );

window.onbeforeunload = show_page_mask;

function show_page_mask() {
	$( 'html' ).removeClass( 'fade-in' ).addClass( 'fade-out' );
}

function hide_page_mask() {
	$( 'html' ).removeClass( 'fade-out no-transitions' ).addClass( 'fade-in' );
}

function reload_js( filename ) {
	var $old = $( 'script[src*="' + filename + '"]' ),
		$new = $( '<script>' ),
		src = $old.attr( 'src' );

	$old.replaceWith( $new );
	$new.attr( 'src', src );
}

$.fn.rellax.defaults.bleed = 20;
$.fn.rellax.defaults.scale = 1.2;
