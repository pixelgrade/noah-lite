(function($) {

	$.fn.getStyleObject = function(){
		var dom = this.get(0),
			returns = {},
			style;

		if ( window.getComputedStyle ) {
			var camelize = function( a, b ) {
				return b.toUpperCase();
			};
			style = window.getComputedStyle(dom, null);
			for ( var i = 0, l = style.length; i < l; i++ ) {
				var prop = style[i];
				var camel = prop.replace(/\-([a-z])/g, camelize);
				var val = style.getPropertyValue(prop);
				returns[camel] = val;
			};
			return returns;
		};
		if ( style = dom.currentStyle ) {
			for ( var prop in style ) {
				returns[prop] = style[prop];
			}
			return returns;
		}
		return this.css();
	}

	$.fn.copyCSS = function( source ) {
		var styles = $( source ).getStyleObject();
		this.css( styles );
	}

	$.fn.resizeselect = function(settings) {
		return this.each(function() {

			$(this).change(function(){
				var $this = $(this);

				// create test element
				var text = $this.find("option:selected").text();
				var $test = $("<span>").html(text);

				// add to body, get width, and get out
				$test.appendTo('body').copyCSS($this);
				$test.css('width', 'auto');
				var width = $test.width();
				$test.remove();

				// set select width
				$this.width(width);

			// run on start
			}).change();
		});
	};

})(jQuery, window);