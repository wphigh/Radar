<?php
/**
 * The template used for displaying page content
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
		edit_post_link( '<i class="glyphicon glyphicon-edit pull-right" style="margin: 0 15px 15px;"></i>', '<hr>' );
	?>
</article><!-- #post-## -->
