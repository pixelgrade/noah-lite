<?php
/**
 * Displays footer widgets if assigned
 *
 * @package Noah Lite
 * @since 1.1.1
 */

?>

<?php if ( is_active_sidebar( 'sidebar-1' ) ): ?>
	<div class="c-gallery c-gallery--footer o-grid o-grid--4col-@lap">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- .c-gallery--footer -->
<?php endif; ?>
