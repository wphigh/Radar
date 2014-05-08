<?php
/**
 * Radar functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */
 
/**
 * Define the theme domain constant.
 *
 * @since Radar 1.0.0
 */
if ( ! defined( 'WHTHEME_DOMAIN' ) )
	define( 'WHTHEME_DOMAIN', 'radar' );
 

/**
 * Radar only works in WordPress 3.7+.
 *
 * @since Radar 1.0.0
 */
if ( version_compare( $GLOBALS['wp_version'], '3.7', '<' ) ) {
	require get_template_directory() . '/inc/class-back-compat.php';
	new Wphigh_Back_Compat( WHTHEME_DOMAIN, '3.7' );
}


/**
 * Include Bootstrap files and instance.
 *
 * @since Radar 1.0.0
 */
require_once get_template_directory() . '/inc/bootstrap/class-bootstrap-basic.php';
require_once get_template_directory() . '/inc/bootstrap/class-bootstrap-init.php';
require_once get_template_directory() . '/inc/bootstrap/class-bootstrap.php';

$radar_bs = new Wphigh_Bootstrap(
	WHTHEME_DOMAIN, 
	get_template_directory() . '/inc/bootstrap', 
	get_template_directory_uri() . '/inc/bootstrap',
	'3.1.1' 
);
 

/**
 * Include theme framework, and instantiation.
 *
 * @since Radar 1.0.0
 */
require_once get_template_directory() . '/inc/class-theme-framework.php';
new Wphigh_Theme_Framework();


/*
 * Add Featured Content functionality.
 *
 * @since Radar 1.0.0 
 */
require_once get_template_directory() . '/inc/featured-content/setup-featured-content.php';


/*
 * Custom template tags for this theme.
 *
 * @since Radar 1.0.0 
 */
require get_template_directory() . '/inc/template-tags.php';