<?php
/**
 * The template for displaying archive pages for the jetpack-portfolio-tag taxonomy.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = 'archive portfolio portfolio-tag jetpack';
pixelgrade_set_location( $location );

get_header(); ?>

<?php
/**
 * pixelgrade_before_main_content hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_before_main_content', $location );
?>

<header class="c-page-header content-area">
	<h1 class="c-page-header__title h1">
		<?php esc_html_e( 'Projects', 'noah' ); ?>
	</h1>
	<div class="c-page-header__meta h7">
		<span><?php esc_html_e( 'Show', 'noah' ); ?></span>
		<span class="c-page-header__taxonomy  u-color-accent"><?php noah_the_taxonomy_dropdown( 'jetpack-portfolio-tag', get_query_var( 'term' ) ); ?></span>
	</div>
	<?php if ( term_description() ) {
		echo term_description();
	} ?>
</header><!-- .archive-header -->

<?php get_template_part( 'template-parts/jetpack-portfolio-loop' ); ?>
<?php the_posts_navigation(); ?>

<?php
/**
 * pixelgrade_after_main_content hook.
 *
 * @hooked nothing - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_main_content', $location );
?>

<?php
get_sidebar();
get_footer(); ?>
