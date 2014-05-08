<?php
/**
 * Bootstrap framework initialization
 *
 * @since Wphigh Bootstrap 1.0.0
 */

if( class_exists( 'Wphigh_Bootstrap_Init' ) ) return;

class Wphigh_Bootstrap_Init extends Wphigh_Bootstrap_Basic {

	/**
	 * Translate domain.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $domain;
	
	/**
	 * Bootstrap framework root directory.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $dir;	
	
	/**
	 * Bootstrap framework root uri.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $dir_uri;
	
	/**
	 * Bootstrap framework version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $version;					

	/**
	 * Constructor.
	 *
	 * @param string $domain: Translate domain.
	 * @param string $dir: Bootstrap framework root directory( no slash tailer ).
	 * @param string $dir_uri: Bootstrap framework root uri( no slash tailer ).
	 * @param string $version: Bootstrap framework version.
	 *
	 * @since 1.0.0
	 */
 	public function __construct( $domain, $dir, $dir_uri, $version ) {
		$this->domain  = $domain;
		$this->dir     = $dir;
		$this->dir_uri = $dir_uri;
		$this->version = $version;
		
		// Hooks
		add_action( 'wp_enqueue_scripts',                   array( $this, 'register_scripts' ), 2 );
		add_action( 'wp_enqueue_scripts',                   array( $this, 'enqueue_scripts' ), 3 );
		add_action( 'wp_head',                              array( $this, 'devices_fix' ), 10 );
		
		add_filter( 'the_content_more_link',                array( $this, 'the_content_more_link' ), 2 );
		add_filter( 'excerpt_more',                         array( $this, 'excerpt_more' ), 2 );
		add_filter( 'wp_link_pages_link',                   array( $this, 'wp_link_pages_link' ), 2, 2 );
		add_filter( 'the_password_form',                    array( $this, 'the_password_form' ), 2 );
		add_filter( 'comment_class',                        array( $this, 'comment_class' ), 2 );	
	}
	
	/**
	 * Register bootstrap framework scripts.
	 *
	 * You my need to enqueue html5shiv.js(See https://github.com/aFarkas/html5shiv) and respond.js( See https://github.com/scottjehl/Respond ) additionally.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_scripts() {
		if ( is_rtl() ) {
			wp_register_style( $this->domain . '-bootstrap', $this->dir_uri . '/bs-dist/css/bootstrap-rtl.min.css', '', $this->version );
		} else {
			wp_register_style( $this->domain . '-bootstrap', $this->dir_uri . '/bs-dist/css/bootstrap.min.css', '', $this->version );
		}
		
		wp_register_script( $this->domain . '-bootstrap', $this->dir_uri . '/bs-dist/js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( $this->domain . '-bootstrap-paginate-responsive', $this->dir_uri . '/others/responsive-paginate.min.js', array( 'jquery', $this->domain . '-bootstrap' ), $this->version, true );
	}		
	
	/**
	 * Enqueue bootstrap framework scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( $this->domain . '-bootstrap' );
		wp_enqueue_script( $this->domain . '-bootstrap' );
		
		wp_enqueue_script( $this->domain . '-bootstrap-paginate-responsive' );
	}
	
	/**
	 * Fix some browser bugs.
	 *
	 * html5shiv.js and Respond.js IE8 support of HTML5 elements and media queries
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function devices_fix() {
	?>
	<!--[if lt IE 9]>
	<script src="<?php echo $this->dir_uri; ?>/others/html5shiv.js"></script>
	<script src="<?php echo $this->dir_uri; ?>/others/respond.min.js"></script>
	<![endif]-->
	<?php
	}
	
	/**
	 * wp_link_pages_link filter.
	 * Make it work in the bootstrap.
	 *
	 * @since 1.0.0
	 *
	 * @return string.
	 */		
	public function wp_link_pages_link ( $link, $i ) {
		global $page;
		if ( $page == $i ) {
			return "<li class=\"active\">{$link}</li>";
		} else {
			return "<li>{$link}</li>";
		}
		
	}
	
	/**
	 * the_password_form filter.
	 * Make it work in the bootstrap.
	 *
	 * @since 1.0.0
	 *
	 * @return string.
	 */		
	public function the_password_form ( $output ) {
		$output = str_replace( 'post-password-form', 'post-password-form form-inline', $output );
		$output = str_replace( 'type="password"', 'type="password" class="form-control"', $output );
		$output = str_replace( 'type="submit"', 'type="submit" class="btn btn-primary"', $output );
		return $output;		
	}
	
	public function the_content_more_link( $more_link_text ) {
		$more_link_text = $this->get_button( __( 'Read more' , WHTHEME_DOMAIN ), array(
			'class' => 'btn-sm btn-primary pull-right read-more',
			'href'  => get_permalink() . '#more-' . get_the_ID()
		), 'a' );
		return $more_link_text;
	}
	
	/**
	 * Set the post excerpt more in the archive and search page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more.
	 * @return string.
	 */
	public function excerpt_more( $more ) {
		$button = $this->get_button( __( 'Read more' , WHTHEME_DOMAIN ), array(
			'class' => 'btn-primary btn-sm read-more',
			'href'  => get_permalink()
		), 'a' );
		
		return " ...<br>{$button}";
	}

	/**
	 * Add bootstrap class to comment class
	 *
	 * @since 1.0.4
	 *
	 * @param array $classes.
	 * @return array.
	 */	
	public function comment_class( $classes ) {
		if ( in_array( 'pingback', $classes ) ) {
			$classes[] = 'bg-info';
		}
		
		if ( in_array( 'bypostauthor', $classes ) ) {
			$classes[] = 'bg-success';
		}		
		
		return $classes;
	}
		
}