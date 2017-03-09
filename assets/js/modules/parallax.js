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
