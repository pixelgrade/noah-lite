var Gallery = function( $container ) {
	var _this = this;

	_this.galleries = [];

	_this.show = function( $gallery ) {

		$gallery.find( '.c-card' ).each( function( i, obj ) {
			var $this = $( obj );

			if ( typeof $this.data( 'is-visible' ) !== "undefined" && $this.data( 'is-visible' ) ) {
				return;
			}

			$this.data( 'is-visible', true );

			setTimeout( function() {

				$this.imagesLoaded( function() {
					$this.addClass( 'is-visible' );
				} );

			}, i * 100 );
		} );

	};

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	$container.find( '.c-gallery' ).each( function( i, obj ) {
		var $gallery = $( obj );

		if ( ! $gallery.children().length ) {
			return;
		}

		_this.galleries.push( $gallery );

		$gallery.imagesLoaded( function() {
			refresh( $gallery );
		} );

		$( window ).resize( function() {
			refresh( $gallery );
		} );
	} );

	function refresh( $gallery ) {

		if ( ! $gallery.is( '.js-masonry' ) ) {
			return;
		}

		var minWidth = $gallery.children()[0].getBoundingClientRect().width;

		$gallery.children().each( function() {
			var width = this.getBoundingClientRect().width;

			if ( width < minWidth ) {
				minWidth = width;
			}
		} );

		$gallery.masonry( {
			isAnimated: false,
			columnWidth: minWidth
		} );
	}
};