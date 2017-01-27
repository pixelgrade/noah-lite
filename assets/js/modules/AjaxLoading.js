var AjaxLoading = function() {

    var _this = this;

    _this.ev = $({});

    if ( typeof Barba === "undefined" ) {
        return;
    }

    var ignored = ['.pdf', '.doc', '.eps', '.png', '.jpg', '.jpeg', '.zip', 'admin', 'wp-', 'wp-admin', 'feed', '#', '&add-to-cart=', '?add-to-cart=', '?remove_item'],
        barbaPreventCheck = Barba.Pjax.preventCheck;

    Barba.Pjax.preventCheck = function( ev, element ) {

        if ( !element || !element.href ) {
            return false;
        } else {
            for ( var i = ignored.length - 1; i >= 0; i-- ) {
                if ( element.href.indexOf( ignored[i] ) > -1 ) {
                    return false;
                }
            }
        }

        return barbaPreventCheck.call( Barba.Pjax, ev, element );
    };

    /**
    * Next step, you have to tell Barba to use the new Transition
    */
    Barba.Pjax.getTransition = function() {
        /**
        * Here you can use your own logic!
        * For example you can use different Transition based on the current page or link...
        */

        return Barba.BaseTransition.extend({
            start: function() {
                /**
                 * This function is automatically called as soon the Transition starts
                 * this.newContainerLoading is a Promise for the loading of the new container
                 * (Barba.js also comes with an handy Promise polyfill!)
                 */
                Promise
                    .all( [this.newContainerLoading, this.fadeOut()] )
                    .then( this.fadeIn.bind( this ) );
            },

            fadeOut: function() {
                /**
                 * this.oldContainer is the HTMLElement of the old Container
                 */
                var _that = this,
                    $old = $( _that.oldContainer );

                $old.find('video').each( function() {
                    this.pause(); // can't hurt
                    delete this; // @sparkey reports that this did the trick (even though it makes no sense!)
                    this.src = ""; // empty source
                    this.load();
                    $( this ).remove(); // this is probably what actually does the trick
                });

                _this.ev.trigger( 'beforeOut', $( _that.newContainer ) );


                return new Promise( function( resolve ) {
                    // alternate syntax for adding a callback
                    setTimeout(function() {
                        resolve( true );
                        _this.ev.trigger( 'afterOut', $( _that.newContainer ) );
                    }, 1000);
                });
            },

            fadeIn: function() {
                var _that = this;

                /**
                 * this.newContainer is the HTMLElement of the new Container
                 * At this stage newContainer is on the DOM (inside our #barba-container and with visibility: hidden)
                 * Please note, newContainer is available just after newContainerLoading is resolved!
                 */
                Barba.Pjax.Cache.data[Barba.HistoryManager.currentStatus().url].then( function(data) {
                    // get data and replace the body tag with a nobody tag
                    // because jquery strips the body tag when creating objects from data
                    var $newBody = $( data.replace(/(<\/?)body( .+?)?>/gi, '$1NOTBODY$2>', data) ).filter( 'notbody' );

                    // need to get the id and edit string from the data attributes
                    var curPostID = $newBody.data('curpostid'),
                        curPostTax = $newBody.data( 'curtaxonomy' ),
                        curPostEditString = $newBody.data( 'curpostedit' );

                    // Put the new body classes
                    $( 'body' ).attr( 'class', $newBody.attr( 'class' ) );

                    // Fix the admin bar, including modifying the body classes and attributes
                    adminBarEditFix( curPostID, curPostEditString, curPostTax );

                    window.scrollTo(0, 0);

                    _this.ev.trigger( 'beforeIn', $( _that.newContainer ) );

                    _that.done();

                    // find and initialize Tiled Galleries via Jetpack
                    if ( typeof tiledGalleries !== "undefined" ) {
                        tiledGalleries.findAndSetupNewGalleries();
                    }

                    // lets do some Google Analytics Tracking
                    if ( window._gaq ) {
                        _gaq.push( ['_trackPageview'] );
                    }

                    _this.ev.trigger( 'afterIn', $( _that.newContainer ) );
                });
            }
        });
    };

    Barba.Pjax.start();
};
