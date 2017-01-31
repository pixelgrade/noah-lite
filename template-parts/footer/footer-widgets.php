<?php
/**
 * Displays footer widgets if assigned
 *
 * @package Noah
 * @since 1.1.1
 */

?>

<?php if ( is_active_sidebar( 'sidebar-2' ) ): ?>
	<div class="c-gallery c-gallery--footer o-grid o-grid--4col-@lap">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div><!-- .c-gallery--footer -->
<?php endif; ?>
