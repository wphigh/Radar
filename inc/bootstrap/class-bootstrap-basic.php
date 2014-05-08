<?php
/**
 * Add some basic method for Bootstrap framework
 *
 * @since Wphigh Bootstrap 1.0.0
 */

if( class_exists( 'Wphigh_Bootstrap_Basic' ) ) return;

class Wphigh_Bootstrap_Basic {

	/**
	 * Implode html tag attributes
	 *
	 * @parm array $attrs = array( $name1 => $value1, $name2 => $value2, ... )
	 * @return string such as: 'id="" name="" class=""'.
	 * @since 1.0.0  
	 */
	protected function implode_attrs( $attrs ) {
		if( empty( $attrs ) || ! is_array( $attrs ) )
			return '';
		
		$output = '';	
		foreach( $attrs as $name => $val ) {
			if( empty( $name ) || false === $val )
				continue;
			
			if( true === $val )
				$output .= sprintf( '%s ', trim( $name ) );		// Boolean attribute
			else
				$output .= sprintf( '%s="%s" ', trim( $name ), esc_attr( trim( $val ) ) );
		}
		
		// Clear a space at both ends
		$output = trim( $output );
		
		return $output;
	}
	
	/**
	 * Implode class attribute
	 *
	 * @param array $class = array( $class1, $class2, ... ).
	 * @param boolean $esc_attr: Use function esc_attr() escape return value or not.
	 *
	 * @return string such as: 'btn btn-default'.
	 *
	 * @since 1.0.0  
	 */
	protected function implode_class( $class, $esc_attr = true ) {
		if( empty( $class ) || ! is_array( $class ) )
			return '';
			
		// Remove all empty value elements, the same elements and trim every element.
		$class = array_filter( $class );
		$class = array_flip( $class );
		$class = array_flip( $class );
		$class = array_map( 'trim', $class );	
		
		$output = implode( ' ', $class );
		
		// Clear a space at both ends
		$output = trim( $output );
		
		if ( true == $esc_attr )
			$output = esc_attr( $output );
		
		return $output;
	}
	
	/**
	 * Generate independent html element
	 *
	 * @param string $tag: form tag
	 * @param array $arrts = array( $name1 => $value1, $name2 => $value2, ... )
	 * @return string html elements
	 * @since 1.0.0
	 */
	protected function indie_tag( $tag, $attrs = array() ) {
		if( empty( $attrs ) )
			return sprintf( "<%s>", $tag );
		
		$attr = $this->implode_attrs( $attrs );
		return sprintf( "<%s %s>", $tag , $attr );
	}
	
	/**
	 * Generate paired html element
	 *
	 * @param string $tag: form tag
	 * @param array $arrts = array( $name1 => $value1, $name2 => $value2, ... )
	 * @param string $content in paired element
	 * @return string html elements	 
	 * @since 1.0.0
	 */
	protected function paired_tag(  $tag, $attrs = array(), $content = '' ) {
		if( empty( $attrs ) )
			return sprintf( "<%s>%s</%s>", $tag, $content, $tag );
		
		$attr = $this->implode_attrs( $attrs );
		return sprintf( "<%s %s>%s</%s>", $tag, $attr, $content, $tag );
	}
	
	/**
	 * This will generate multiple css
	 *
	 * @param array $styles: array( $selector1 => $style1, $selector2 => $style2, ... )
	 * @param string $id: Style id.
	 * @param boolean $echo
	 *
	 * @return or print string
	 *
	 * @since 1.0.0
	 */
	public function generate_css( $styles, $id = '', $echo = true ) {
		if( empty( $styles ) || ! is_array( $styles ) )
			return '';
		
		$id_attr = empty( $id ) ? '' : ' id="' . esc_attr( $id ) . '"';
		
		$output = '<style type="text/css"' . $id_attr . '>';
		foreach( $styles as $selector => $style ) {
			if( empty( $selector ) || empty( $style ) )
				continue;
			
			$output .= sprintf('%s { %s }',
				$selector,
				$style
			);
		}
		
		$output .= "</style>\n";
		
		if( $echo )
			echo $output;
		else
			return $output;
	}
	
}