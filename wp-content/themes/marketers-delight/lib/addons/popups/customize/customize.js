var popupPrefix = 'md_popups_' + mdPopupPreview.id + '_';
var content     = mdPopupPreview.content;

function md_popup_display( newval, cssClass ) {
	if ( newval == '' )
		jQuery( cssClass ).addClass( 'display-none' );
	else
		jQuery( cssClass ).removeClass( 'display-none' );
}


function md_popup_content_count( newval ) {
	jQuery( '.md-popup-content' ).removeClass( 'md-popup-content-' + content );

	if ( newval == true || newval != '' )
		content = parseInt( content, 10 ) + 1;
	else
		content = parseInt( content, 10 ) - 1;

	jQuery( '.md-popup-content' ).addClass( 'md-popup-content-' + content );
}


/**
 * Headline, Text, Button
 */

// Show Text

wp.customize( popupPrefix + 'show_text', function( value ) {
	value.bind( function( newval ) {
		var fullWidth = wp.customize.value( popupPrefix + 'full_width' )();

		md_popup_content_count( newval );

		md_popup_display( newval, '.md-popup-text' );

		if ( fullWidth )
			return;

		if ( content == 3 ) {
			jQuery( '.md-popup-image' ).addClass( 'width-small' ).removeClass( 'width-wide' );
			jQuery( '.md-popup-email' ).removeClass( 'width-small' ).addClass( 'width-full' );
		}

		else if ( content == 2 ) {
			jQuery( '.md-popup-text' ).addClass( 'width-wide' ).removeClass( 'width-full' );

			if ( newval )
				jQuery( '.md-popup-image' ).removeClass( 'width-wide' ).addClass( 'width-small' );
			else
				jQuery( '.md-popup-image' ).addClass( 'width-wide' ).removeClass( 'width-small' );

			jQuery( '.md-popup-email' ).addClass( 'width-small' ).removeClass( 'width-full' );
		}

		else if ( content == 1 ) {
			jQuery( '.md-popup-image' ).removeClass( 'width-small' ).addClass( 'width-full' );
			jQuery( '.md-popup-email' ).removeClass( 'width-small' ).addClass( 'width-full' );
		}

	} );
} );


// Headline

wp.customize( popupPrefix + 'headline', function( value ) {
	value.bind( function( newval ) {
		md_popup_display( newval, '.md-popup-headline' );
		jQuery( '.md-popup-headline' ).html( newval );
	} );
} );


// Description

wp.customize( popupPrefix + 'description', function( value ) {
	value.bind( function( newval ) {
		md_popup_display( newval, '.md-popup-description' );
		jQuery( '.md-popup-description' ).html( newval );
	} );
} );


// Bullets

wp.customize( popupPrefix + 'bullets' , function( value ) {
	value.bind( function( newval ) {
		md_popup_display( newval, '.md-popup-bullets' );
		jQuery( '.md-popup-bullets' ).html( newval );
	} );
} );


// Button Text

wp.customize( popupPrefix + 'button_text' , function( value ) {
	value.bind( function( newval ) {
		md_popup_display( newval, '.md-popup-button' );
		jQuery( '.md-popup-button' ).html( newval );
	} );
} );


// Button URL

wp.customize( popupPrefix + 'button_url' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup-button' ).attr( 'href', newval );
	} );
} );


/**
 * Featured Image
 */

wp.customize( popupPrefix + 'image' , function( value ) {
	value.bind( function( newval ) {
		var fullWidth = wp.customize.value( popupPrefix + 'full_width' )();
		var showText  = wp.customize.value( popupPrefix + 'show_text' )();

		jQuery( '.md-popup-featured-image' ).attr( 'src', newval );

		md_popup_content_count( newval );

		md_popup_display( newval, '.md-popup-image' );

		if ( fullWidth )
			return;

		if ( content == 3 ) {
			jQuery( '.md-popup-image' ).removeClass( 'width-wide' ).addClass( 'width-small' );
			jQuery( '.md-popup-email' ).removeClass( 'width-small' ).addClass( 'width-full' );
		}

		else if ( content == 2 ) {
			jQuery( '.md-popup-text' ).addClass( 'width-wide' ).removeClass( 'width-full' );

			if ( showText )
				jQuery( '.md-popup-image' ).addClass( 'width-small' ).removeClass( 'width-full' );
			else
				jQuery( '.md-popup-image' ).addClass( 'width-wide' ).removeClass( 'width-full' );

			jQuery( '.md-popup-email' ).addClass( 'width-small' ).removeClass( 'width-full' );
		}

		else if ( content == 1 ) {
			jQuery( '.md-popup-text' ).addClass( 'width-full' ).removeClass( 'width-wide' );
			jQuery( '.md-popup-image' ).addClass( 'width-full' ).removeClass( 'width-wide' );
			jQuery( '.md-popup-email' ).addClass( 'width-full' ).removeClass( 'width-small' );
		}
	} );
} );


// Image Wrap

wp.customize( popupPrefix + 'image_wrap' , function( value ) {
	value.bind( function( newval ) {
		if ( newval != true ) {
			jQuery( '.md-popup-image' ).addClass( 'block-mid' );
		}
		else {
			jQuery( '.md-popup-image' ).removeClass( 'block-mid' );
		}
	} );
} );


/**
 * Email Form
 */

// Email List

wp.customize( popupPrefix + 'email_list' , function( value ) {
	value.bind( function( newval ) {
		var fullWidth = wp.customize.value( popupPrefix + 'full_width' )();
		var showText  = wp.customize.value( popupPrefix + 'show_text' )();

		md_popup_content_count( newval );

		md_popup_display( newval, '.md-popup-email' );

		if ( fullWidth )
			return;

		if ( content == 3 )
			jQuery( '.md-popup-email' ).removeClass( 'width-small' ).addClass( 'width-full' );

		if ( content == 2 ) {
			jQuery( '.md-popup-text' ).removeClass( 'width-full' ).addClass( 'width-wide' );

			if ( showText )
				jQuery( '.md-popup-image' ).addClass( 'width-small' ).removeClass( 'width-full' );
			else
				jQuery( '.md-popup-image' ).addClass( 'width-wide' ).removeClass( 'width-full' );

			jQuery( '.md-popup-email' ).removeClass( 'width-full' ).addClass( 'width-small' );
		}

		if ( content == 1 ) {
			jQuery( '.md-popup-text' ).removeClass( 'width-wide' ).addClass( 'width-full' );
			jQuery( '.md-popup-image' ).removeClass( 'width-wide' ).addClass( 'width-full' );
		}

	} );
} );


// Show Name

wp.customize( popupPrefix + 'email_show_name', function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup .form-input-name' ).toggle();
	} );
} );


// Name Field Label

wp.customize( popupPrefix + 'email_name_label' , function( value ) {
	value.bind( function( newval ) {
		if ( newval != '' )
			jQuery( '.md-popup .form-input-name' ).attr( 'placeholder', newval );
		else
			jQuery( '.md-popup .form-input-name' ).attr( 'placeholder', 'Enter your name...' );
	});
});


// Email Field Label

wp.customize( popupPrefix + 'email_email_label' , function( value ) {
	value.bind( function( newval ) {
		if ( newval != '' )
			jQuery( '.md-popup .form-input-email' ).attr( 'placeholder', newval );
		else
			jQuery( '.md-popup .form-input-email' ).attr( 'placeholder', 'Enter your email...' );
	} );
} );


// Email Submit Label

wp.customize( popupPrefix + 'email_submit_label' , function( value ) {
	value.bind( function( newval ) {
		if ( newval != '' )
			jQuery( '.md-popup .email-form-submit' ).html( newval );
		else
			jQuery( '.md-popup .email-form-submit' ).html( 'Join Now!' );
	} );
} );


// Attached

wp.customize( popupPrefix + 'email_form_attached' , function( value ) {
	value.bind( function( newval ) {
		if ( wp.customize.value( popupPrefix + 'email_show_name' )() == true )
			jQuery( '.md-popup .email-form-wrap' ).toggleClass( 'form-attached-2' );
		else
			jQuery( '.md-popup .email-form-wrap' ).toggleClass( 'form-attached' );
	});
});


/**
 * CSS Colors + Background Image
 */

// Text Color

wp.customize( popupPrefix + 'text_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup' ).css( 'color', newval );
	} );
} );


// Link Color

wp.customize( popupPrefix + 'link_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup a:not(.button)' ).css( 'color', newval ).css( 'border-bottom-color', newval );
	} );
} );


// Button Color

wp.customize( popupPrefix + 'button_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup .md-popup-close-corner' ).css( 'background-color', newval );
		jQuery( '.md-popup .button' ).css( 'background-color', newval );
		jQuery( '.md-popup .form-submit' ).css( 'background-color', newval );
	} );
} );


// Close Color

wp.customize( popupPrefix + 'close_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup-close' ).css( 'color', newval );
	} );
} );

// BG Color

wp.customize( popupPrefix + 'bg_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup-content' ).css( 'background-color', newval );
	} );
} );

// Email Color

wp.customize( popupPrefix + 'secondary_color' , function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup-sec' ).css( 'background-color', newval );
	} );
} );


// Full Width

wp.customize( popupPrefix + 'full_width', function( value ) {
	value.bind( function( newval ) {
		var showText  = wp.customize.value( popupPrefix + 'show_text' )();
		var text_col  = content > 1 ? 'width-wide' : '';
		var image_col = content == 2 && ! showText ? 'width-wide' : 'width-small';
		var email_col = content == 2 ? 'width-small' : 'width-full';

		if ( newval == true ) {
			jQuery( '.md-popup-text' ).removeClass( text_col ).addClass( 'width-full' );
			jQuery( '.md-popup-image' ).removeClass( image_col ).addClass( 'width-full' );
			jQuery( '.md-popup-email' ).removeClass( email_col ).addClass( 'width-full' );
		}
		else {
			jQuery( '.md-popup-text' ).addClass( text_col ).removeClass( 'width-full' );
			jQuery( '.md-popup-image' ).addClass( image_col ).removeClass( 'width-full' );
			jQuery( '.md-popup-email' ).addClass( email_col );

			if ( content < 3 )
				jQuery( '.md-popup-email' ).removeClass( 'width-full' );
		}
	} );
} );


// Custom Content

wp.customize( popupPrefix + 'custom_template', function( value ) {
	value.bind( function( newval ) {
		jQuery( '.md-popup-content' ).html( newval );
	} );
} );