jQuery( document ).ready( function( $ ) {

	var md_media_media_frame;

	$( document.body ).on( 'click.MDMediaUploaderOpenMediaManager', '.md-media-preview-image', function( e ) {

		e.preventDefault();

		$div = $( e.target ).closest( '.md-media' );

		if ( md_media_media_frame ) {
			md_media_media_frame.open();

			return;
		}

		md_media_media_frame = wp.media.frames.md_media_media_frame = wp.media({
			frame:    'select',
			multiple: false,
			title:    'Select Image',
			library:  { type: 'image' },
			button:   { text: 'Use Image' }
		});

		md_media_media_frame.on( 'select', function() {
			selection = md_media_media_frame.state().get('selection');

			if ( ! selection )
				return;

			selection.each( function( attachment ) {
				$div.find( '.md-media-url' ).val( attachment.attributes.sizes.full.url );
				$div.find( '.md-media-preview-image' ).attr( 'src', attachment.attributes.sizes.full.url );
				$div.find( '.md-media-remove' ).show();
			});
		});

		md_media_media_frame.open();
	});

	$( document.body ).on( 'click.MDMediaRemove', '.md-media-remove', function( e ) {
		e.preventDefault();

		$div = $( this ).closest( '.md-media' );

		$( this ).hide();
		$div.find( '.md-media-url' ).val( '' );
		$div.find( '.md-media-preview-image' ).attr( 'src', '' );
	});

});