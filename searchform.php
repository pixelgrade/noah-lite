<?php
/**
 * Template for displaying search forms
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

$unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo $unique_id; ?>">
		<?php _ex( 'Search for:', 'label', 'noah-lite' ); ?>
	</label>
	<input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'noah-lite' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit"><?php _ex( 'Search', 'submit button', 'noah-lite' ); ?></button>
</form>
