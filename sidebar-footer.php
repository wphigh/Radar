<?php
/**
 * The Footer Sidebar
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

if ( ! is_active_sidebar( 'footer' ) ) {
	return;
}
?>

<div id="supplementary" class="container">
	<div id="footer-sidebar" class="footer-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'footer' ); ?>
	</div><!-- #footer-sidebar -->
</div><!-- #supplementary -->