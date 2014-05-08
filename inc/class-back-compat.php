<?php
/**
 * Theme back compat functionality
 *
 * Prevents this theme from running on WordPress versions prior to $min_version,
 * since this theme is not meant to be backward compatible beyond that
 * and relies on many newer functions and markup changes introduced in $min_version.
 *
 * @since Wphigh_Back_Compat 1.0.0
 */
if ( class_exists( 'Wphigh_Back_Compat' ) ) return;

class Wphigh_Back_Compat {
	
	/**
	 * Incompatible message.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $message;		

	/**
	 * Constructor.
	 *
	 * @param string $domain: Translate domain.
	 * @param string $min_version: Minimum compatible WordPress version.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $domain, $min_version ) {
		$current_theme = wp_get_theme();
		$theme_name = $current_theme->Name;	
		global $wp_version;
		
		$this->message = sprintf( __( '%s requires at least WordPress version %s. You are running version %s. Please upgrade and try again.', $domain ), $theme_name, $min_version, $wp_version );
		
		// Hooks
		add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		add_action( 'load-customize.php', array( $this, 'customize' ) );
		add_action( 'template_redirect' , array( $this, 'preview' ) );
	}	
	
	/**
	 * Prevent switching to this theme on old versions of WordPress.
	 *
	 * Switches to the default theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
		unset( $_GET['activated'] );
		add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );
	}
	
	/**
	 * Add message for unsuccessful theme switch.
	 *
	 * Prints an update nag after an unsuccessful attempt to switch to
	 * this theme on WordPress versions prior to $this->min_version.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function upgrade_notice() {
		printf( '<div class="error"><p>%s</p></div>', $this->message );
	}
	
	/**
	 * Prevent the Theme Customizer from being loaded on WordPress versions prior to $this->min_version.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function customize() {
		wp_die( $this->message, '', array(
			'back_link' => true,
		) );
	}
	
	/**
	 * Prevent the Theme Preview from being loaded on WordPress versions prior to $this->min_version.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function preview() {
		if ( isset( $_GET['preview'] ) ) {
			wp_die( $this->message );
		}
	}
	
}