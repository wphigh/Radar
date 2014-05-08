<?php
/**
 * Bootstrap framework
 * Add some bootstrap functionality
 *
 * See http://getbootstrap.com/
 *
 * @since Wphigh Bootstrap 1.0.0
 */
if ( class_exists( 'Wphigh_Bootstrap' ) ) return;

class Wphigh_Bootstrap extends Wphigh_Bootstrap_Init {
	
	/**
	 * Get Font Awesome icon
	 *
	 * @param string $class
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function get_icon( $class ) {
		$class = trim( $class );
		
		if ( empty( $class ) )
			return '';

		return sprintf( '<i class="glyphicon glyphicon-%s"></i>', esc_attr( $class ) );
	}
	
	
	/**
	 * Print Font Awesome icon
	 * See function get_icon()
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function the_icon( $class ) {
		echo $this->get_icon( $class );
	}	
	
	/**
	 * Generate button elements.
	 *
	 * See http://getbootstrap.com/css/#buttons.
	 *
	 * @param string $label: button label name.
	 * @param array $attrs: form field attributes
	 * @param string $tag: form field tag name
	 *
	 * @since 1.0.0
	 *
	 * @return buttom html code.
	 */
	public function get_button( $label, $attrs = array(), $tag = 'button' ) {
		// Check
		if ( empty( $label ) || ! in_array( $tag, array( 'button', 'a', 'input' ) ) ) {
			return '';
		}
		
		// Get $attrs
		$attrs = wp_parse_args( $attrs, array(
			'type'     => 'button',
			'class'    => '',
			'disabled' => false
		) );
		
		if ( empty( $attrs['class'] ) ) {
			$attrs['class'] = 'btn';
		} else {
			$attrs['class'] = 'btn ' . trim( $attrs['class'] );
		}
		
		// Generate button elements
		switch ( $tag ) {
			case 'button' : {
				$output = $this->paired_tag( 'button', $attrs, $label );
				break;
			}
			
			case 'a' : {
				unset( $attrs['type'] );
				unset( $attrs['disabled'] );
				$attrs['role'] = 'button';
				$output = $this->paired_tag( 'a', $attrs, $label );
				break;
			}
			
			case 'input' : {
				$attrs['value'] = $label;
				$output = $this->indie_tag( 'input', $attrs );
				break;
			}			
		}
		
		// Return
		return $output . "\n";
	}
	
	/**
	 * Display button elements.
	 *
	 * See function get_button.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function the_button( $label, $attrs = array(), $tag = 'button' ) {
		echo $this->get_button( $label, $attrs, $tag );
	}	
	
	/**
	 * Get carets to indicate dropdown functionality and direction.
	 *
	 * @since 1.0.0
	 *
	 * @return string.
	 */
	public function get_caret() {
		return "<span class=\"caret\"></span>\n";
	}
	
	/**
	 * Get caret button.
	 *
	 * See function get_button.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function get_caret_button( $attrs = array(), $tag = 'button' ) {
  		$label = sprintf( '%1$s<span class="sr-only">%2$s</span>', 
			$this->get_caret(), 
			__( 'Toggle Dropdown' , $this->domain ) 
		);
		
		if ( ! isset( $attrs['class'] ) || empty( $attrs['class'] ) ) {
			$class = 'dropdown-toggle';
		} else {
			$class = 'dropdown-toggle ' . trim( $attrs['class'] );
		}		
		
		$attrs = array(
			'class'       => $class,
			'data-toggle' => 'dropdown'
		);
		
		return $this->get_button( $label, $attrs, $tag );
	}						
	
	/**
	 * Generate nav menu elements.
	 *
	 * See http://getbootstrap.com/components/#navbar.
	 *
	 * @param array $args
	 *
	 * @since 1.0.0
	 *
	 * @print nav menu html code.
	 */
	public function nav_menu( $args = array() ) {
		require_once $this->dir . '/class-bootstrap-nav-walker.php';
		
		$args = wp_parse_args( $args, array(
			'theme_location'  => 'primary',
			'container_id'    => 'menu-primary-nav',
			'container_class' => 'collapse navbar-collapse',
			'menu_class'      => 'nav navbar-nav navbar-right',
			'fallback_cb'     => array( 'Wphigh_Bootstrap_Navwalker', 'fallback' ),
			'echo'            => false,
			'walker'          => new Wphigh_Bootstrap_Navwalker
		) );
		
		$output = '<nav id="menu-primary" class="navbar navbar-inverse" role="navigation">';
		$output .= '<div class="container clearfix">';
		
		// .navbar-header
		$description = get_bloginfo( 'description', 'display' );
		$brand = '<h1 class="site-title navbar-brand radar-popover" data-placement="bottom" data-trigger="hover" data-content="' . esc_attr( $description ) . '"><a href="' . esc_attr( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h1>';
		$output .= '<div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#' . $args['container_id'] . '"><span class="sr-only">' . __( 'Toggle navigation', $this->domain ) . '</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>' . $brand . '</div>' . "\n";
		
		// WP nav menu
		$output .= wp_nav_menu( $args );
		
		$output .= '</div>';	// .container
		$output .= '</nav>';		// nav
		
		echo $output . "\n";
	}	
	
	/**
	 * Generate carousel  elements.
	 *
	 * See http://getbootstrap.com/javascript/#carousel.
	 *
	 * @param array $datas: such as array( array( 'img' =>'', 'title' => '', 'des' => '' ), array( ... ), ... )
	 * @param array $args: 
	 *                    'id' : (string) carousel container id.
	 *                    'controls': (boolean) display controls or not. 
	 *					  'echo': (boolean) return or print result.
	 *
	 * @since 1.0.0
	 *
	 * @return or print carousel html code.
	 */
	public function carousel( $datas, $args = array() ) {
		if ( empty( $datas ) )
			return '';
			
		$args = wp_parse_args( $args, array(
			'id'         => 'carousel',
			'class'      => '',
			'controls'   => true,
			'echo'       => true,
			'attrs'      => array()
		) );
		extract( $args );
		
		$class = empty( $class ) ? '' : " $class";
		$output = sprintf( '<div id="%1$s" class="carousel slide%2$s" data-ride="carousel"%3$s>' . "\n",
			esc_attr( $id ),
			$class,
			empty( $attrs ) ? '' : ' ' . $this->implode_attrs( $attrs )
		);
		
		// Indicators
		$datas = (array) $datas;
		$num = count( $datas );
		$output .= "<ol class=\"carousel-indicators\">\n";
		for ( $i = 0; $i < $num; $i++ ) {
			$active = ( 0 === $i ) ? ' class="active"' : '';
			$output .= sprintf( '<li data-target="#%1$s" data-slide-to="%2$s"%3$s></li>' . "\n",
				esc_attr( $id ),
				$i,
				$active
			);
		}
		$output .= "</ol>\n";		// carousel-indicators
		
		// Wrapper for slides
		$output .= '<div class="carousel-inner">' . "\n";
		
		$j = 0;
		foreach ( $datas as $data ) {
			$img   = ( isset( $data['img'] ) && $data['img'] ) ? $data['img'] : '';
			if( empty( $img ) )
				continue;
			$title = ( isset( $data['title'] ) && $data['title'] ) ? '<h3>' . $data['title'] . '</h3>' : '';
			$des   = ( isset( $data['des'] ) && $data['des'] ) ? $data['des'] : '';
			
			if ( empty( $title ) && empty( $des ) ) {
				$caption = '';
			} else {
				$caption = sprintf( '<div class="carousel-caption">%1$s%2$s</div>', $title, $des );
			}
			
			$active = ( 0 == $j ) ? $active = ' active' : '';
			$output .= sprintf( '<div class="item%1$s">%2$s</div>' . "\n", $active, $img . $caption );
			
			$j++;
		}
		
		$output .= "</div>\n";	//.carousel-inner
		
		// Controls
		if ( true == $controls ) {
			$output .= '<a class="left carousel-control" href="#' . esc_attr( $id ) . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>';
			$output .= '<a class="right carousel-control" href="#' . esc_attr( $id ) . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>';
		}
		
		$output .= "</div>\n";	// .carousel
		
		// Return or echo $output
		if ( true == $echo ) {
			echo $output;
		} else {
			return $output;
		}		
	}			
	
}