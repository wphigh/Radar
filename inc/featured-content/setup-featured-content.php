<?php
/**
 * Setup featured content
 *
 * @since 1.0.0
 */

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Wphigh_Featured_Content class on or
 * before the 'setup_theme' hook.
 *
 * @since 1.0.0 
 */
if ( ! class_exists( 'Wphigh_Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {	
	require get_template_directory() . '/inc/featured-content/class-featured-content.php';
	Wphigh_Featured_Content::setup( WHTHEME_DOMAIN, get_template_directory_uri() . '/inc/featured-content' );
}

/**
 * Getter function for Featured Content Plugin.
 *
 * @since 1.0.0
 *
 * @return array An array of WP_Post objects.
 */
function wphigh_get_featured_posts() {
	/**
	 * Filter the featured posts to return in this theme.
	 *
	 * @since 1.0.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( WHTHEME_DOMAIN . '_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since 1.0.0
 *
 * @return bool Whether there are featured posts.
 */
function wphigh_has_featured_posts() {
	return ! is_paged() && (bool) wphigh_get_featured_posts();
}