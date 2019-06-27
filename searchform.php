<?php
/**
 * Template for displaying search forms
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$unique_id = uniqid( 'search-form-' ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>">
		<?php echo esc_html_x( 'Search for:', 'label', 'noah-lite' ); ?>
	</label>
	<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'noah-lite' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'noah-lite' ); ?></button>
</form>
