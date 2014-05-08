<?php
/**
 * Custom template tags for theme
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

/**
 * checks if the current post is a sticky Post in the home page, and not in paged.
 *
 * @since Radar 1.0.0
 *
 * @return boolean
 */
function radar_is_sticky() {	
	if ( is_sticky() && is_home() && ! is_paged() )
		return true;
	else
		return false;
}


/**
 * checks if the current page is full width.
 *
 * @since Radar 1.0.0
 *
 * @return boolean
 */
function radar_is_full() {
	if ( ! is_active_sidebar( 'sidebar' ) || is_page_template( 'page-templates/full-width.php' ) || is_attachment() )
		return true;
	else
		return false;
}


/**
 * Find out if blog has 'category' taxonomy and has more than one category.
 *
 * @since radar 1.0.0
 *
 * @return boolean
 */
function radar_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( WHTHEME_DOMAIN . '_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( WHTHEME_DOMAIN . '_category_count', $all_the_cool_cats );
	}

	if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && 1 < (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so radar_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so radar_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in radar_categorized_blog.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( WHTHEME_DOMAIN . '_category_count' );
}
add_action( 'edit_category', 'radar_category_transient_flusher' );
add_action( 'save_post',     'radar_category_transient_flusher' );


if ( ! function_exists( 'radar_post_header' ) ) :
/**
 * Print the post header.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_post_header() {	
	radar_post_thumbnail();
	
	$post_format = get_post_format();
	if ( 'aside' == $post_format || 'quote' == $post_format || 'link' == $post_format ) {
		return '';
	}
	
	$title = get_the_title();
	if ( empty( $title ) ) {
		$title = get_the_time( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
	}
	
	echo '<header class="entry-header panel-heading">';
	
	if ( is_single() ) {
		printf( '<h1 class="entry-title panel-title">%s</h1>', $title );
	} else {
		printf( '<h1 class="entry-title panel-title"><a href="%1$s" rel="bookmark">%2$s</a></h1>', 
			esc_url( get_permalink() ),
			$title
		);
	}
	
	echo "</header>\n";	
}
endif;


if ( ! function_exists( 'radar_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 * @since Radar 1.0.0
 *
 * @return void
*/
function radar_post_thumbnail() {
	$post_format = get_post_format();
	if ( post_password_required() || ! has_post_thumbnail() ) {
		return;
	}
	
	// Get post thumbnail
	$size = radar_get_thumbnail_size();	
	$tmb = get_the_post_thumbnail( get_the_ID(), $size, array(
		'class' => "img-responsive attachment-$size"
	) );
	
	// Display featured image
	if ( is_singular() ) {
		printf( '<div class="featured-image">%s</div>', $tmb );
	} else {
		printf( '<a class="featured-image" href="%s">%s</a>', esc_url( get_permalink() ), $tmb );
	}	
		
}
endif;


if ( ! function_exists( 'radar_get_thumbnail_size' ) ) :
/**
 * Get current post thumbnail size.
 *
 * @since Radar 1.0.0
 *
 * @return string
*/
function radar_get_thumbnail_size() {
	if ( radar_is_full() ) {
		$size = WHTHEME_DOMAIN . '-full-width';
	} else {
		$size = 'post-thumbnail';
	}
	
	return $size;	
}
endif;


if ( ! function_exists( 'radar_get_post_format_icon' ) ) :
/**
 * Get post format font icon.
 *
 * @since Radar 1.0.0
 *
 * @return string
 */
function radar_get_post_format_icon( $format ) {
	if ( empty( $format ) )
		return;
	
	global $radar_bs;
	
	switch ( $format ) {		
		case 'gallery' : $icon = $radar_bs->get_icon( 'picture' ); break;
		
		case 'quote' : $icon = $radar_bs->get_icon( 'asterisk' ); break;
		
		case 'link' : $icon = $radar_bs->get_icon( 'link' ); break;
		
		case 'aside' : $icon = $radar_bs->get_icon( 'pencil' ); break;
		
		case 'audio' : $icon = $radar_bs->get_icon( 'music' ); break;
		
		case 'video' : $icon = $radar_bs->get_icon( 'film' ); break;
		
		case 'image' : $icon = $radar_bs->get_icon( 'camera' ); break;
		
		default: $icon = $radar_bs->get_icon( 'file' );
	}
	
	return $icon;
}
endif;


if ( ! function_exists( 'radar_post_body' ) ) :
/**
 * Print the post body.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_post_body() {
 	if ( ! get_the_content() )
		return;
		
	$hidden = is_singular() ? '' : ' hidden-xs';
	?>
	<section class="entry-post panel-body clearfix<?php echo $hidden; ?>">
	<?php
		radar_post_content();
		radar_link_pages();		
	?>
	</section>
	<?php	
}
endif;


if ( ! function_exists( 'radar_post_content' ) ) :
/**
 * Print the post content or post excerpt.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_post_content() {	
	$post_format = get_post_format();
	
	// Post content or excerpt
	if ( is_search() && empty( $post_format ) ) {
		echo '<div class="entry-summary">';
		the_excerpt();
		echo '</div>';
	} else {
		echo '<div class="entry-content">';
		global $radar_bs;
		the_content();
		echo '</div>';
	}
	
	// Post formats
	if ( $post_format ) {
		printf( '<a href="%1$s" title="%2$s" class="meta-post-format meta-post-format-%3$s">%4$s</a>',
			esc_url( get_post_format_link( $post_format ) ),
			get_post_format_string( $post_format ),
			$post_format,
			radar_get_post_format_icon( $post_format )
		);
	}		
}
endif;


if ( ! function_exists( 'radar_post_footer' ) ) :
/**
 * Print the post footer.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_post_footer() {	
		if ( get_the_content() )
			$style = ' style="margin-top: 20px;"';
		else
			$style = '';
			
		$hidden = is_singular() ? '' : ' hidden-xs';
	?>
	<footer class="entry-meta btn-group btn-group-justified<?php echo $hidden; ?>"<?php echo $style; ?>>
	<?php
		if ( radar_is_sticky() )
			$option = 'danger';
		else
			$option = 'primary';
			
		radar_post_meta( $option, 'xs' );	
	?>
	</footer>
	<?php	
}
endif;


if ( ! function_exists( 'radar_post_meta' ) ) :
/**
 * Print meta in the the post footer.
 *
 * @param string $option: Change button style, optional values contains 'default', 'primary', 'info', 'success', 'warning', 'danger' .
 * @param string $size: 'lg', 'sm', 'xs'
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_post_meta( $option = '', $size = '' ) {	
	global $radar_bs;
	$btn_size = empty( $size ) ? '' : "btn-$size";
	$btn_option = empty( $option ) ? '' : "btn-$option";
	
	// Sticky
	if ( radar_is_sticky() ) {
		$icon = $radar_bs->get_icon( 'pushpin' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => esc_attr( __( 'Sticky', WHTHEME_DOMAIN ) ),
		) );
		
		echo '<div class="meta-item meta-item-sticky btn-group">' . $button . "</div>\n";
	}	
	
	// Published on
	if ( ! radar_is_sticky() ) {
		$icon = $radar_bs->get_icon( 'time' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => esc_attr( get_the_date() )
		) );
				
		echo '<div class="meta-item meta-item-time btn-group">' . $button . "</div>\n";		
	}
	
	// Author
	$author = get_the_author();
	if ( $author ) {
		$icon = $radar_bs->get_icon( 'user' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => $author,
			'href'  => esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
		), 'a' );		
		
		echo '<div class="meta-item meta-item-author btn-group">' . $button . "</div>\n";		
	}
		
	// Categories
	if ( radar_categorized_blog() ) {
		$icon = $radar_bs->get_icon( 'folder-open' );
		$label = $icon . ' ' . $radar_bs->get_caret();
		
		$button = $radar_bs->get_button( $label, array(
			'class'       => "$btn_size $btn_option dropdown-toggle radar-tooltip",
			'data-toggle' => 'dropdown',
			'title'       => __( 'Categories', WHTHEME_DOMAIN )
		) );		
		
		$cat_list = get_the_category_list();
		$cat_list = str_replace( 'class="post-categories"', 'class="post-categories dropdown-menu" role="menu"', $cat_list );
		
		echo '<div class="meta-item meta-item-categories btn-group">' . $button . $cat_list . "</div>\n";
	}	
	
	// Tags
	$tags = get_the_tags();
	if ( $tags ) {
		$tag_list = '<ul class="dropdown-menu" role="menu">';
		foreach ( $tags as $tag ) {
			$tag_list .= '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" rel="tag">' . $tag->name . '</a></li>';
		}
		$tag_list .= '</ul>';
		
		$icon = $radar_bs->get_icon( 'tags' );
		$label = $icon . ' ' . $radar_bs->get_caret();
		
		$button = $radar_bs->get_button( $label, array(
			'class'       => "$btn_size $btn_option dropdown-toggle radar-tooltip",
			'data-toggle' => 'dropdown',
			'title'       => __( 'Tags' , WHTHEME_DOMAIN )
		) );		
		
		echo '<div class="meta-item meta-item-tags btn-group">' . $button . $tag_list . "</div>\n";
	}
	
	// Comments
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		$icon = $radar_bs->get_icon( 'comment' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => get_comments_number(),
			'href'  => esc_url( get_comments_link() )
		), 'a' );		
		
		echo '<div class="meta-item meta-item-comments btn-group">' . $button . "</div>\n";	
	}
	
	// Attachment publish in
	global $post;
	if ( is_attachment() && $post->post_parent ) {		
		$icon = $radar_bs->get_icon( 'arrow-left' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => sprintf( __( 'Published In %s' , WHTHEME_DOMAIN ), get_the_title( $post->post_parent ) ),
			'href'  => get_permalink( $post->post_parent )
		), 'a' );
				
		echo '<div class="meta-item meta-item-attachment btn-group">' . $button . "</div>\n";
	}
	
	// Attachment image link
	if ( wp_attachment_is_image() ) {		
		$icon = $radar_bs->get_icon( 'zoom-in' );
		
		$metadata = wp_get_attachment_metadata();
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => $metadata['width'] . ' &times; ' . $metadata['height'],
			'href'  => wp_get_attachment_url()
		), 'a' );
				
		echo '<div class="meta-item meta-item-attachment-size btn-group">' . $button . "</div>\n";
	}	
	
	// Edit link
	$edit_link = get_edit_post_link();
	if ( $edit_link ) {
		$icon = $radar_bs->get_icon( 'edit' );
		
		$button = $radar_bs->get_button( $icon, array(
			'class' => "$btn_size $btn_option radar-tooltip",
			'title' => __( 'Edit This', WHTHEME_DOMAIN ),
			'href'  => $edit_link
		), 'a' );		
		
		echo '<div class="meta-item meta-item-edit btn-group">' . $button . "</div>\n";
	}	
	
}
endif;


if ( ! function_exists( 'radar_paging_nav' ) ) :
/**
 * Display navigation to page numbers, next/previous set of posts when applicable.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'      => $pagenum_link,
		'format'    => $format,
		'total'     => $GLOBALS['wp_query']->max_num_pages,
		'current'   => $paged,
		'mid_size'  => 2,
		'add_args'  => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&laquo;', WHTHEME_DOMAIN ),
		'next_text' => __( '&raquo;', WHTHEME_DOMAIN ),
		'type'      => 'array'
	) );

	if ( $links ) :
	?>
		<nav class="navigation paging-navigation" role="navigation">
			<h3 class="sr-only"><?php _e( 'Posts navigation', WHTHEME_DOMAIN ); ?></h3>
			<ul class="pagination">
			<?php 
				foreach ( $links as $link ) {
					if( strip_tags( $link ) == $paged ) {
						$li_class = ' class="active"';
					} else {
						$li_class = '';
					}
					echo "<li{$li_class}>" . $link . '</li>';
				}					
			?>
			</ul>
		</nav><!-- .navigation -->
	<?php
	endif;
}
endif;


if ( ! function_exists( 'radar_link_pages' ) ) :
/**
 * Print the post link pages.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_link_pages() {	
	wp_link_pages( array(
		'before'      => '<hr><div class="page-links text-center"><span class="page-links-title sr-only">' . __( 'Pages:', WHTHEME_DOMAIN ) . '</span><ul class="pagination pagination-sm">',
		'after'       => '</ul></div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
	) );
}
endif;


if ( ! function_exists( 'radar_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since radar_post_nav 1.0
 *
 * @return void
 */
function radar_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h3 class="sr-only"><?php _e( 'Post navigation', WHTHEME_DOMAIN ); ?></h3>
		<ul class="nav-links pager">
			<?php
				if ( $previous ) {
					printf( '<li class="previous"><a class="radar-popover" href="%1$s" rel="prev" data-content="%2$s" data-trigger="hover" data-placement="right">%3$s</a></li>', 
						get_permalink( $previous->ID ), 
						esc_attr( get_the_title( $previous->ID ) ),
						__( '&larr; Older', WHTHEME_DOMAIN )
					);
				}
				
				if ( $next ) {
					printf( '<li class="next"><a class="radar-popover"  href="%1$s" rel="next" data-content="%2$s" data-trigger="hover" data-placement="left">%3$s</a></li>', 
						get_permalink( $next->ID ),
						esc_attr( get_the_title( $next->ID ) ),
						__( 'Newer &rarr;', WHTHEME_DOMAIN )
					);
				}
			?>
		</ul><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if ( ! function_exists( 'radar_comments_nav' ) ) :
/**
 * Display navigation to page numbers, next/previous set of comments when applicable.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_comments_nav() {
	if ( get_comment_pages_count() <= 1 || ! get_option( 'page_comments' ) ) {
		return;
	}

	// Set up paginated links.
	$links = paginate_comments_links( array(
		'echo'      => false,
		'type'      => 'array',
		'prev_text' => '&laquo;', 
		'next_text' => '&raquo;'
	) );

	if ( $links ) :
		$page = get_query_var('cpage');
		if ( !$page )
			$page = 1;
		$max_page = get_comment_pages_count();
	?>
		<nav class="navigation comment-navigation" role="navigation">
			<h3 class="sr-only"><?php _e( 'Posts navigation', WHTHEME_DOMAIN ); ?></h3>
			<ul class="pagination pagination-sm">
			<?php 
				foreach ( $links as $link ) {
					if( strip_tags( $link ) == $page ) {
						$li_class = ' class="active"';
					} else {
						$li_class = '';
					}
					echo "<li{$li_class}>" . $link . '</li>';
				}					
			?>
			</ul>
		</nav><!-- .navigation -->
	<?php
	endif;
}
endif;


if ( ! function_exists( 'radar_content_class' ) ) :
/**
 * Display #content class.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_content_class() {
	if ( radar_is_full() || is_attachment() ) {
		$col = 'col-md-12';
	} else {
		$col = 'col-md-8';
	}
	
	echo "class=\"site-content $col\"";
}
endif;


if ( ! function_exists( 'radar_archive_header' ) ) :
/**
 * Display archive page header(include category, tag, search, etc).
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_archive_header() {
	$title = '';
	$description = '';
	
	// Search
	if ( is_search() ) {
		$title = sprintf( __( 'Search Results for: %s', WHTHEME_DOMAIN ), get_search_query() );
			
	// Author
	} elseif ( is_author() ) {
		the_post();
		$title = sprintf( __( 'All posts by %s', WHTHEME_DOMAIN ), get_the_author() );
		if ( get_the_author_meta( 'description' ) ) {
			$description = get_the_author_meta( 'description' );
		}
		rewind_posts();
	
	// Date	
	} elseif ( is_date() ) {
		if ( is_day() ) {
			$title = sprintf( __( 'Daily Archives: %s', WHTHEME_DOMAIN ), get_the_date() );

		} elseif ( is_month() ) {
			$title = sprintf( __( 'Monthly Archives: %s', WHTHEME_DOMAIN ), get_the_date( _x( 'F Y', 'monthly archives date format', WHTHEME_DOMAIN ) ) );

		} elseif ( is_year() ) {
			$title = sprintf( __( 'Yearly Archives: %s', WHTHEME_DOMAIN ), get_the_date( _x( 'Y', 'yearly archives date format', WHTHEME_DOMAIN ) ) );

		} else {
			$title = __( 'Archives', WHTHEME_DOMAIN );	
		}
	
	// Taxonomy	
	} else {
		$term = get_queried_object();
		if ( ! $term )
			return;
		
		$tax = get_taxonomy( $term->taxonomy );
		$tax_name = $tax->labels->singular_name;
		
		$title = single_term_title( $tax_name . __( ' Archives: ', WHTHEME_DOMAIN ), false );
		$term_description = term_description();
		if ( ! empty( $term_description ) ) :
			$description = sprintf( '<div class="taxonomy-description text-muted">%s</div>', $term_description );
		endif;
	}
	?>
	<header class="archive-header">
		<h1><?php echo $title; ?></h1>
		<?php echo $description; ?>
	</header>
	<?php
}
endif;


if ( ! function_exists( 'radar_attachment_header' ) ) :
/**
 * Print the attachment header.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_attachment_header() {	
	the_title( '<header class="entry-header panel-heading"><h1 class="entry-title panel-title">', "</h1></header>\n" );	
}
endif;


if ( ! function_exists( 'radar_attachment_body' ) ) :
/**
 * Print the attachment.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_attachment_body() {
		if( ! wp_attachment_is_image() )
			add_filter( 'the_content', 'prepend_attachment' );
	?>
	<section class="entry-post panel-body clearfix">
		<div class="entry-content">
			<div class="entry-attachment">
				<?php if ( wp_attachment_is_image() ) : ?>
					<div class="attachment" style="margin-bottom: 10.5px;">
						<?php radar_the_attached_image(); ?>
					</div><!-- .attachment -->
				<?php endif; ?>
	
				<?php if ( has_excerpt() ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div><!-- .entry-caption -->
				<?php endif; ?>
			</div><!-- .entry-attachment -->

			<?php
				the_content();
			?>				
		</div><!-- .entry-content -->
	</section><!-- .entry-post -->
	<?php
		
}
endif;


if ( ! function_exists( 'radar_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Radar attachment size.
	 *
	 * @since Radar 1.0.0
	 *
	 */
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
		
		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, WHTHEME_DOMAIN . '-full-width' )
		);		
	}
	
	// Or Show image attachment img tag only
	else {
		echo wp_get_attachment_image( $post->ID, WHTHEME_DOMAIN . '-full-width' );
	}

}
endif;


if ( ! function_exists( 'radar_attachment_footer' ) ) :
/**
 * Print the attachment footer.
 *
 * @since Radar 1.0.0
 *
 * @return void
 */
function radar_attachment_footer() {
	?>
	<footer class="entry-meta btn-group btn-group-justified" style="margin-top: 20px;">
		<?php radar_post_meta( 'primary', 'xs' ); ?>
	</footer>
	<?php	
}
endif;