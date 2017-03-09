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
