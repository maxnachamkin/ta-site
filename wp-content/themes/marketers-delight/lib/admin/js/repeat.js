jQuery( document ).ready( function( $ ) {

	var MD_Repeatable_Fields = {

		setOptions: function() {
			this.increment = $('.md-repeat-field-new:last-child .md-repeat-increment');
		},

		add: function() {
			$( '.md-repeat-add' ).click( function( e ) {
				e.preventDefault();

				var lastRepeatingGroup = $( this ).closest( '.md-repeat' ).find( '.md-repeat-field' ).last(),
					clone = lastRepeatingGroup.clone( true );

				clone.addClass( 'md-repeat-field-new' );

				clone.find( 'input.regular-text, textarea, select' ).val( '' );
				clone.find( 'input.regular-text' ).prop( 'readonly', false );
				clone.find( 'input[type=checkbox]' ).attr( 'checked', false );

				clone.insertAfter( lastRepeatingGroup );

				MD_Repeatable_Fields.resetAtts( clone );

				var increment = $( '.md-repeat-field-new:last-child .md-repeat-increment' );
				increment.html( parseInt( increment.html(), 10 ) + 1 );

			});
		},

		remove: function() {
			$( '.md-repeat-delete' ).click( function( e ) {
				e.preventDefault();

				var current = $( this ).parent( 'div' ), others = current.siblings( '.md-repeat-field' );

				current.fadeOut( 'fast', function() {
					current.remove();

					others.each( function() {
						MD_Repeatable_Fields.resetAtts( $(this) );
					});
				});

				var increment = $( '.md-repeat-field-new:last-child .md-repeat-increment' );
				increment.html( parseInt( increment.html(), 10 ) - 1 );

			});
		},

		resetAtts: function( section ) {
			var attrs = [ 'for', 'id', 'name' ],
				tags  = section.find( 'input, label, textarea' ),
				count = section.index();

			tags.each( function() {
				var $this = $( this );

				$.each( attrs, function( i, attr ) {
					var attr_val = $this.attr( attr );

					if ( attr_val ) {
						$this.attr( attr, attr_val.replace( /(\d+)(?=\D+$)/, count ) );
					}
				});
			});
		},

		init: function() {
			this.add();
			this.remove();
		}

	}

	MD_Repeatable_Fields.init();

});