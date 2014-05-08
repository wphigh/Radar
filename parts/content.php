<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
		radar_post_header(); 
		radar_post_body();
		radar_post_footer();
	?>	
</article><!-- #post-## -->