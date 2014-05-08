<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */
?>
<div class="alert alert-danger clearfix">

	<header class="page-header">
		<h1 class="page-title"><?php _e( 'Nothing Found', WHTHEME_DOMAIN ); ?></h1>
	</header>
	
	<div class="page-content col-md-12">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	
			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', WHTHEME_DOMAIN ), admin_url( 'post-new.php' ) ); ?></p>
	
		<?php elseif ( is_search() ) : ?>
	
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', WHTHEME_DOMAIN ); ?></p>
			<div class="form-inline"><?php get_search_form(); ?></div>
	
		<?php else : ?>
	
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', WHTHEME_DOMAIN ); ?></p>
			<div class="form-inline"><?php get_search_form(); ?></div>
	
		<?php endif; ?>
	</div><!-- .page-content -->

</div>