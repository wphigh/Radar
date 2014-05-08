<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, This theme
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

get_header(); 
?>
<div id="primary" class="content-area row">

	<div id="content" <?php radar_content_class(); ?> role="main">

		<?php if ( have_posts() ) : 				
				// Display header
				radar_archive_header();		
				
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'parts/content', get_post_format() );

				endwhile;
				// Previous/next page navigation.
				radar_paging_nav();

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'parts/content', 'none' );

			endif;
		?>
	</div><!-- #content -->
	
	<?php if ( have_posts() ) get_sidebar(); ?>
</div><!-- #primary -->
<?php
get_footer();