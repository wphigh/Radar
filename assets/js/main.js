/**
 * Theme functions file
 *
 * @since 1.0.0
 *
 */

jQuery( document ).ready(function( $ ) {	
	/**
	 * Global
	 * --------------------------------------------------------------
	 */
	
	// Bootstrap tooltip
	$( '.radar-tooltip' ).tooltip();
	
	// Bootstrap popover
	$( '.radar-popover' ).popover({
		container   : 'body'
	});
	
	// Set #main min-height
	var minHeight = $( window ).height() - $( '#masthead' ).height() - $( '#colophon' ).height() - $( '#wpadminbar' ).height();
	$( '#main' ).css( 'min-height', minHeight + 'px' );
	
	
	/**
	 * Post
	 * --------------------------------------------------------------
	 */
	
	// Table bootstrap
	$( '.entry-content table' ).addClass( 'table table-striped table-bordered' ).wrap( '<div class="table-responsive"></div>' );

	// Add embed, iframe, object wrap, make them responsive via css
	$( '.entry-content iframe, .entry-content embed, .entry-content object' ).wrap( '<div class="video-container"></div>' );
	
	// Responsive pagination
	$( '.pagination' ).rPage();

	
	/**
	 * Widgets
	 * --------------------------------------------------------------
	 */
	 
	// Widget title divide bootstrap
	$( '.widget-title-divide .progress-bar' ).animate( {
		width: '30%'
	}, 500 );	 
	
	// Select bootstrap
	$( '.widget select' ).addClass( 'form-control' );
	
	// Table bootstrap
	$( '.widget table' ).addClass( 'table table-bordered' );
	$( '.widget table caption' ).addClass( 'h4' );
	
	// Tagcloud bootstrap
	$( '.widget .tagcloud a' ).addClass( 'label label-primary' );
	
	// Add icon to list
	$( '.widget_archive ul li' ).prepend( '<i class="glyphicon glyphicon-calendar"></i>' );
	$( '.widget_categories ul li' ).prepend( '<i class="glyphicon glyphicon-folder-open"></i>' );
	$( '.widget_links ul li' ).prepend( '<i class="glyphicon glyphicon-new-window"></i>' );
	$( '.widget_pages ul li' ).prepend( '<i class="glyphicon glyphicon-file"></i>' );
	$( '.widget_recent_entries ul li' ).prepend( '<i class="glyphicon glyphicon-check"></i>' );
	$( '.widget_nav_menu ul li' ).prepend( '<i class="glyphicon glyphicon-bookmark"></i>' );
	$( '.widget_meta ul li' ).prepend( '<i class="glyphicon glyphicon-cog"></i>' );
	$( '.widget_recent_comments ul li' ).prepend( '<i class="glyphicon glyphicon-comment"></i>' );
	$( '.widget_rss ul li' ).prepend( '<i class="glyphicon glyphicon-ok"></i>' );
	
	// Add categories and archive posts count badge		
	$( '.widget_categories ul, .widget_archive ul' ).each( function() {		
		var obj = $(this);
		
		var ul = obj.html();
		var patt = /\(\d+\)/g;
		var result = ul.match( patt );
		
		if ( null == result || 0 >= result.length ) 
			return;
			
		$.each( result, function( i, v ) {
			var r = v.replace( '(', '' );
			var r = r.replace( ')', '' );
			ul = ul.replace( v, ' <span class="badge">' + r + '</span>' );
		} );
		
		obj.html( ul );			
	} );
	
	
	/**
	 * Comments
	 * --------------------------------------------------------------
	 */
	$( '.comment-list .avatar' ).addClass( 'img-circle' );
	
	$( '.comment-list .comment-author, .comment-list .comment-metadata' ).addClass( 'small' );
	
	$( '.comment-list .reply' ).each( function() {
		var replyStr = $.trim( $(this).html() );
		if ( '' != replyStr ) {
			$(this).addClass( 'label label-warning' );	
		}
	} );
	
	$( '.comment-list table' ).addClass( 'table table-striped table-bordered' ).wrap( '<div class="table-responsive"></div>' );	
	
	$( '#respond #submit' ).addClass( 'btn btn-warning' );
	
	$( '.no-comments, .comment-awaiting-moderation' ).addClass( 'text-danger' );
	

	/**
	 * Arrange footer widgets vertically.
	 * --------------------------------------------------------------
	 */
	if ( $.isFunction( $.fn.masonry ) ) {
		$( '#footer-sidebar' ).masonry( {
			itemSelector: '.widget',
			isResizable: true,
			isRTL: $( 'body' ).is( '.rtl' )
		} );
	}	
		
});