<?php
/**
 * The template for displaying featured content
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

global $radar_bs;
$featured_posts = (array) wphigh_get_featured_posts();

$datas = array();
$i = 0;
foreach ( (array) $featured_posts as $post ) {
	setup_postdata( $post );
	
	if ( ! has_post_thumbnail() )
		continue;
	
	$datas[ $i ]['img'] = get_the_post_thumbnail( get_the_ID(), 'full' );
	$datas[ $i ]['title'] = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', 
		esc_url( get_permalink() ) ,
		get_the_title()
	);
	$datas[ $i ]['des'] = wpautop( get_the_excerpt() );
		
	wp_reset_postdata();
	
	$i++;
}

$radar_bs->carousel( $datas, 'id=featured-content' );