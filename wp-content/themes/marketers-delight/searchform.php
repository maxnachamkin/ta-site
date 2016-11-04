<form role="search" method="get" id="searchform" class="search-form form-attached clear" action="<?php echo home_url( '/' ); ?>">

	<input type="search" class="search-input form-input" placeholder="<?php esc_attr_e( 'To search, type and hit enter&hellip;', 'md' ); ?>" value="<?php esc_attr_e( get_search_query() ); ?>" name="s" id="s" />

	<button type="submit" class="search-submit form-submit md-icon md-icon-search" id="searchsubmit" /></button>

</form>