<?php
/**
 * Template for the tag archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'archive tag' );

get_header(); ?>

<div id="primary" class="content-area  u-blog_sides_spacings">
	<main id="main" class="o-wrapper  u-blog_grid_width  site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="c-page-header">
				<h1 class="c-page-header__title h1"><?php single_tag_title( esc_html__('Tag: ', 'noah') ); ?></h1>

				<?php if ( tag_description() ) : // Show an optional tag description ?>
				<div class="c-page-header__meta h7"><?php echo tag_description(); ?></div>
				<?php endif; ?>

			</header><!-- .archive-header -->

			<div <?php noah_blog_class(); ?> id="posts-container">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() ); ?>
				<?php endwhile; ?>

			</div><!-- .c-gallery -->

			<?php the_posts_navigation(); ?>

		<?php else : ?>
			<div class="u-content-width">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</div>
		<?php endif; ?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer(); ?>

