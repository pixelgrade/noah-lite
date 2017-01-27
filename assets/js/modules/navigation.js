var Navigation = ( function () {

    var $targets = $( '.c-navbar' ).find( ".menu-item-has-children > a, .page_item_has_children > a" ),
        toggleClass = 'is-toggled';

    $targets.parent().addClass( toggleClass )

    $targets.on( 'click', function(e) {

        var $parent = $(this).parent();

        if ( $parent.hasClass( toggleClass ) ) {
            $parent.removeClass( toggleClass );
            stopEvent(e);
            return false;
        }

    });

    return {
    };

})();
