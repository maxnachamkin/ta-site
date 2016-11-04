( function( $ ) {
	function initColorPicker( widget ) {
		widget.find( '.md-color-picker-widget' ).wpColorPicker( {
			change: _.throttle( function() {
				$(this).trigger( 'change' );
			}, 3000 )
		});
	}

	function onFormUpdate( event, widget ) {
		initColorPicker( widget );
	}

	$( document ).on( 'widget-added widget-updated', onFormUpdate );

	$( document ).ready( function() {
		$( '#widgets-right .widget:has(.md-color-picker-widget)' ).each( function () {
			initColorPicker( $( this ) );
		} );
	} );
}( jQuery ) );