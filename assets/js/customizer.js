/**
 * Noah Lite Customizer JavaScript - keeps things nicer for all
 * v 1.0.0
 */
(function( $, exports ) {
	$( document ).ready( function() {
		// when the customizer is ready add our actions
		wp.customize.bind( 'ready', function() {

			if ( typeof noahCustomizerObject !== "undefined" && $('.preview-notice' ).length > 0 ) {
				$( '<a class="badge-noah-pro" target="_blank" href="' + noahCustomizerObject.upsell_link + '">' + noahCustomizerObject.upsell_label + '</a><div class="upsell_link_details">Not interested? <a href="' + noahCustomizerObject.dismiss_link + '" class="upsell_link_dismiss">Dismiss</a></div>' ).insertAfter( '.preview-notice' );
			} else {
				$('.wp-full-overlay-sidebar-content').addClass('upsell_links_dismissed');
			}
		} );
	} );
})( jQuery, window );