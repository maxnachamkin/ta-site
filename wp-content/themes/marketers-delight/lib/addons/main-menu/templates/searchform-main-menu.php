<form role="search" method="get" class="menu-search" action="<?php echo home_url( '/' ); ?>">

	<!-- Trigger -->

	<span id="menu-desktop-trigger-search" class="menu-trigger-search menu-trigger close-on-max">
		<i class="md-icon md-icon-search"></i>
	</span>

	<!-- Form -->

	<div id="main-menu-search" class="main-menu-search menu-content close-on-desktop close-on-max">

		<div id="menu-search-field" class="menu-search-field clear">

			<input type="search" id="menu-search-input" class="search-input" placeholder="<?php esc_attr_e( 'To search, type and hit enter&hellip;', 'md-main-menu' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" />

			<button type="submit" class="search-submit md-icon md-icon-search" id="searchsubmit" />

		</div>

	</div>

</form>