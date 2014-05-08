<?php
/**
 * A WordPress theme framework incldes basic functions. Such as theme support, register sidebar, title filter, etc.
 *
 * For more information on hooks, actions, and filters,
 *
 * @since Wphigh_Theme_Framework 1.0.0
 */
if ( class_exists( 'Wphigh_Theme_Framework' ) ) return;

class Wphigh_Theme_Framework {	

	/**
	 * Constructor.
	 *
	 * @param string $domain: Translate domain.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {		
		// Hooks
		add_action( 'after_setup_theme',                   array( $this, 'setup' ), 2 );
		add_action( 'template_redirect',                   array( $this, 'content_width' ), 2 );
		add_action( 'widgets_init',                        array( $this, 'widgets_init' ), 2 );
		add_action( 'wp_enqueue_scripts',                  array( $this, 'enqueue_scripts' ), 5 );
		add_filter( 'wp_title',                            array( $this, 'wp_title' ), 2, 2 );
		add_filter( 'body_class',                          array( $this, 'body_classes' ), 2 );
		add_filter( 'post_class',                          array( $this, 'post_classes' ), 2 );
	}		
	
	/**
	 * theme setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 * @since 1.0.0
	 */
	public function setup() {
		/**
		 * Set up the content width value based on the theme's design.
		 *
		 * @since Radar 1.0.0
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) { 
			$content_width = 700;
		}
	
		/*
		 * Make theme available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 */
		load_theme_textdomain( WHTHEME_DOMAIN, get_template_directory() . '/languages' );
	
		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
	
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 750, 350, true );
		add_image_size( WHTHEME_DOMAIN . '-full-width', 1140, 530, true );
	
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'   => __( 'Top primary menu', WHTHEME_DOMAIN )
		) );
	
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );
	
		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery'
		) );
	
		// This theme allows users to set a custom background.
		add_theme_support( 'custom-background', apply_filters( 'radar_custom_background_args', array(
			'default-color' => '1e2a36',
		) ) );
	}
		
	/**
	 * Adjust content_width value for image attachment template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function content_width() {
		if ( radar_is_full() ) {
			$GLOBALS['content_width'] = 1090;
		}
	}		
	
	/**
	 * Register widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', WHTHEME_DOMAIN ),
			'id'            => 'sidebar',
			'description'   => __( 'Additional sidebar that appears on the right.', WHTHEME_DOMAIN ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3><div class="widget-title-divide progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
<span class="sr-only">30% Complete</span></div></div>',
		) );
		
		register_sidebar( array(
			'name'          => __( 'Footer Widget Area', WHTHEME_DOMAIN ),
			'id'            => 'footer',
			'description'   => __( 'Appears in the footer section of the site.', WHTHEME_DOMAIN ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s col-md-3 col-sm-6">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}	
	
	/**
	 * Enqueue js and css files for the front end.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {		
		// Load our main css.
		wp_enqueue_style( WHTHEME_DOMAIN . '-main', get_template_directory_uri() . '/assets/css/main.css', array(
			WHTHEME_DOMAIN . '-bootstrap'
		) );
		
		// Load style.css
		wp_enqueue_style( WHTHEME_DOMAIN . '-style', get_stylesheet_uri() );
		
		// Load theme main js.
		wp_enqueue_script( WHTHEME_DOMAIN . '-main', get_template_directory_uri() . '/assets/js/main.js', array(
			'jquery',
			WHTHEME_DOMAIN . '-bootstrap'
		), '', true );
		
		// Comment reply js
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Load masonry grid layout library
		if ( is_active_sidebar( 'footer' ) ) {
			wp_enqueue_script( 'jquery-masonry' );
		}
	}			
	
	/**
	 * Create a nicely formatted and more specific title element text for output
	 * in head of document, based on current view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	public function wp_title( $title, $sep ) {
		global $paged, $page;
	
		if ( is_feed() ) {
			return $title;
		}
	
		// Add the site name.
		$title .= get_bloginfo( 'name' );
	
		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}
	
		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 ) {
			$title = "$title $sep " . sprintf( __( 'Page %s', WHTHEME_DOMAIN ), max( $paged, $page ) );
		}
	
		return $title;
	}
	
	/**
	 * Extend the default WordPress body classes.
	 *
	 * Adds body classes to denote:
	 * 1. Single or multiple authors.
	 * 2. Index views.
	 * 3. Full-width content layout.
	 * 4. Presence of footer widgets.
	 * 5. Single views.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes A list of existing body class values.
	 * @return array The filtered body class list.
	 */
	public function body_classes( $classes ) {
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
	
		if ( is_archive() || is_search() || is_home() ) {
			$classes[] = 'list-view';
		}
	
		if ( radar_is_full() ) {
			$classes[] = 'full-width';
		}
	
		if ( is_active_sidebar( 'footer' ) ) {
			$classes[] = 'footer-widgets';
		}
	
		if ( is_singular() && ! is_front_page() ) {
			$classes[] = 'singular';
		}
	
		return $classes;
	}
	
	/**
	 * Extend the default WordPress post classes.
	 *
	 * Adds a post class to denote:
	 * Non-password protected page with a post thumbnail.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes A list of existing post class values.
	 * @return array The filtered post class list.
	 */
	public function post_classes( $classes ) {
		if ( ! post_password_required() && has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail';
		}
		
		if ( radar_is_sticky() ) {
			$classes[] = 'row panel panel-danger';
		} else {
			$classes[] = 'row panel panel-primary';
		}
	
		return $classes;
	}
		
}