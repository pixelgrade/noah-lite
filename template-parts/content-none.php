<?php
/**
 * The template used when something is not found or no results were found for the search query.
 * @package Noah Lite
 * @since   Noah 1.0
 */
?>

<div class="u-align-center">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) { ?>
		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'noah-lite' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php } elseif ( is_search() ) { ?>
		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'noah-lite' ); ?></p>
		<?php get_search_form(); ?>
	<?php } else { ?>
		<p><?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help.", 'noah-lite' ); ?></p>
		<?php get_search_form(); ?>
	<?php } ?>
</div>
