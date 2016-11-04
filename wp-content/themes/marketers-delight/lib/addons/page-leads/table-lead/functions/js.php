<?php

/**
 * Prints inline JavaScript used to add toggle functionality
 * to the Table Lead on mobile. Requires Apollo JS library.
 *
 * @since 1.0
 */

if ( ! function_exists( 'table_lead_js' ) ) :

	function table_lead_js() {
		if ( table_lead_field( 'template' ) != 'pro' )
			return;
	?>

		<script>

			(function() {

				var toggleState = function ( elem, one, two ) {
					var elem = document.querySelector( elem );

					elem.setAttribute( 'class', elem.getAttribute( 'class' ) === one ? two : one );
				};

				<?php if ( has_table_lead_col( 1 ) ) : ?>
					document.getElementById( 'table-lead-control-1' ).onclick = function( e ) {
						apollo.toggleClass( document.getElementById( 'table-lead-content-1' ), 'close-on-mobile' );
						toggleState( '#control-icon-1', 'md-icon md-icon-angle-up', 'md-icon md-icon-angle-down' );
					};
				<?php endif; ?>

				<?php if ( has_table_lead_col( 2 ) ) : ?>
					document.getElementById( 'table-lead-control-2' ).onclick = function( e ) {
						apollo.toggleClass( document.getElementById( 'table-lead-content-2' ), 'close-on-mobile' );
						toggleState( '#control-icon-2', 'md-icon md-icon-angle-up', 'md-icon md-icon-angle-down' );
					};
				<?php endif; ?>

				<?php if ( has_table_lead_col( 3 ) ) : ?>
					document.getElementById( 'table-lead-control-3' ).onclick = function( e ) {
						apollo.toggleClass( document.getElementById( 'table-lead-content-3' ), 'close-on-mobile' );
						toggleState( '#control-icon-3', 'md-icon md-icon-angle-up', 'md-icon md-icon-angle-down' );
					};
				<?php endif; ?>

			})();

		</script>

	<?php }

endif;