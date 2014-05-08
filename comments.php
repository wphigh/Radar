<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Radar
 * @since Radar 1.0.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<div class="panel panel-warning">
		
			<div class="panel-heading">
				<h2 class="comments-title">
					<?php
						global $radar_bs;
						printf( $radar_bs->get_icon( 'comment' ) . _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), WHTHEME_DOMAIN ),
							number_format_i18n( get_comments_number() ), 
							get_the_title() 
						);
					?>
				</h2>
			</div>
			
			<div class="panel-body">
			
				<ol class="comment-list">
					<?php
						wp_list_comments( array(
							'style'      => 'ol',
							'short_ping' => true,
							'avatar_size'=> 36,
						) );
					?>
				</ol><!-- .comment-list -->
				
				<?php radar_comments_nav(); ?>
			
				<?php if ( ! comments_open() ) : ?>
					<p class="no-comments"><?php _e( 'Comments are closed.', WHTHEME_DOMAIN ); ?></p>
				<?php endif; ?>		
				
			</div><!-- .panel-body -->
			
		</div>

	<?php endif; // have_comments() ?>
	
	<?php 
		global $radar_bs;
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$required = ( $req ? ' <span class="required">*</span>' : '' );
		$fields = array(
			'author' =>
			'<div class="comment-form-author input-group has-warning"><label class="input-group-addon" for="author">' . $radar_bs->get_icon( 'user' ) . $required . '</label> '.
			'<input id="author" class="form-control" name="author" placeholder="' . __( 'Username' , WHTHEME_DOMAIN ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" ' . $aria_req . ' /></div>',
			
			'email' =>
			'<div class="comment-form-email input-group has-warning"><label class="input-group-addon" for="email">' . $radar_bs->get_icon( 'envelope' ) . $required . '</label> ' .
			'<input id="email" name="email" class="form-control" placeholder="' . __( 'Email' , WHTHEME_DOMAIN ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" ' . $aria_req . ' /></div>',
			
			'url' =>
			'<div class="comment-form-url input-group has-warning"><label class="input-group-addon" for="url">' . $radar_bs->get_icon( 'link' )  . '</label>' .
			'<input id="url" name="url" class="form-control" placeholder="' . __( 'Website' , WHTHEME_DOMAIN ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" /></div>'			
		);
		
		$comment_field = '<div class="comment-form-comment form-group has-warning"><label class="sr-only" for="comment">' . _x( 'Comment', 'type a comment', WHTHEME_DOMAIN ) . '</label><textarea id="comment" class="form-control" placeholder="' . __( 'type a comment.' , WHTHEME_DOMAIN ) . '" name="comment" rows="8" aria-required="true"></textarea></div>';
				
		comment_form( array(
			'fields'              => $fields,
			'comment_field'       => $comment_field
		) );
	?>	
	
</div><!-- #comments -->
