<form role="search" method="get" class="search-form form-group has-feedback" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="control-label sr-only"><?php _ex( 'Search for:', 'label', WHTHEME_DOMAIN ); ?></label>
	<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder',  WHTHEME_DOMAIN ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', WHTHEME_DOMAIN ); ?>">
	<span class="glyphicon glyphicon-search form-control-feedback text-muted"></span>
	<input type="submit" class="search-submit sr-only" value="<?php echo esc_attr_x( 'Search', 'submit button', WHTHEME_DOMAIN ); ?>">
</form>