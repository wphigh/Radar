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
		radar_attachment_header();
		radar_attachment_body();
		radar_attachment_footer();
	?>	
</article><!-- #post-## -->