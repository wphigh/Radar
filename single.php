<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area row">
	<div id="content" <?php radar_content_class(); ?> role="main">
		<?php			
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'parts/content', get_post_format() );

				// Previous/next post navigation.
				radar_post_nav();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
		?>
	</div><!-- #content -->
	
	<?php get_sidebar(); ?>		
</div><!-- #primary -->

<?php
get_footer();
