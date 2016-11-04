window.mdMainMenu = {

	// Main Menu
	main: function( callback ) {
		document.getElementById( 'menu-trigger-menu' ).onclick = function( e ) {
			apollo.toggleClass( document.getElementById( 'main-menu-menu' ), 'close-on-max' );
			apollo.toggleClass( document.getElementById( 'menu-trigger-menu' ), 'menu-trigger-active' );

			// search
			if ( mdMainMenuHas.search ) {
				apollo.addClass( document.getElementById( 'main-menu-search' ), 'close-on-max' );
				apollo.removeClass( document.getElementById( 'menu-trigger-search' ), 'menu-trigger-active' );
			}

			// social
			if ( mdMainMenuHas.social ) {
				apollo.addClass( document.getElementById( 'main-menu-social' ), 'close-on-max' );
				apollo.removeClass( document.getElementById( 'menu-trigger-social' ), 'menu-trigger-active' );
			}

			e.preventDefault();
		}
	},

	// Search
	search: function( callback ) {
		document.getElementById( 'menu-desktop-trigger-search' ).onclick = function( e ) {
			apollo.removeClass( document.getElementById( 'main-menu-search' ), 'close-on-desktop' );
			apollo.addClass( document.getElementById( 'menu-desktop-trigger-search' ), 'close-on-desktop' );
			apollo.addClass( document.getElementById( 'main-menu-side' ), 'main-menu-active-search' );

			if ( mdMainMenuHas.social )
				apollo.addClass( document.getElementById( 'main-menu-social' ), 'close-on-desktop' );

			if ( mdMainMenuHas.popup )
				apollo.addClass( document.getElementById( 'menu-desktop-trigger-popup' ), 'close-on-desktop' );

			document.getElementById( 'menu-search-input' ).focus();

			e.preventDefault();
		}

		document.getElementById( 'menu-trigger-search' ).onclick = function( e ) {
			// search
			apollo.toggleClass( document.getElementById( 'menu-trigger-search' ), 'menu-trigger-active' );
			apollo.toggleClass( document.getElementById( 'main-menu-search' ), 'close-on-max' );

			// social
			if ( mdMainMenuHas.social ) {
				apollo.removeClass( document.getElementById( 'menu-trigger-social' ), 'menu-trigger-active' );
				apollo.addClass( document.getElementById( 'main-menu-social' ), 'close-on-max' );
			}

			// main
			if ( mdMainMenuHas.main ) {
				apollo.removeClass( document.getElementById( 'menu-trigger-menu' ), 'menu-trigger-active' );
				apollo.addClass( document.getElementById( 'main-menu-menu' ), 'close-on-max' );
			}

			document.getElementById( 'menu-search-input' ).focus();

			e.preventDefault();
		}

		document.onclick = function( e ) {
			var target = e.target || e.srcElement;

			do {
				if ( document.getElementById( 'main-menu' ) === target )
					return;

				target = target.parentNode;
			}
			while ( target ) {
				apollo.addClass( document.getElementById( 'main-menu-search' ), 'close-on-desktop' );
				apollo.removeClass( document.getElementById( 'menu-desktop-trigger-search' ), 'close-on-desktop' );

				// search
				apollo.addClass( document.getElementById( 'main-menu-search' ), 'close-on-max' );
				apollo.removeClass( document.getElementById( 'menu-trigger-search' ), 'menu-trigger-active' );
				apollo.removeClass( document.getElementById( 'main-menu-side' ), 'main-menu-active-search' );

				// social
				if ( mdMainMenuHas.social )
					apollo.removeClass( document.getElementById( 'main-menu-social' ), 'close-on-desktop' );

				// popup
				if ( mdMainMenuHas.popup )
					apollo.removeClass( document.getElementById( 'menu-desktop-trigger-popup' ), 'close-on-desktop' );
			}
		}
	},

	// Social
	social: function( callback ) {
		document.getElementById( 'menu-trigger-social' ).onclick = function( e ) {
			apollo.toggleClass( document.getElementById( 'main-menu-social' ), 'close-on-max' );
			apollo.toggleClass( document.getElementById( 'menu-trigger-social' ), 'menu-trigger-active' );

			// main menu
			if ( mdMainMenuHas.main ) {
				apollo.addClass( document.getElementById( 'main-menu-menu' ), 'close-on-max' );
				apollo.removeClass( document.getElementById( 'menu-trigger-menu' ), 'menu-trigger-active' );
			}

			// search
			if ( mdMainMenuHas.search ) {
				apollo.addClass( document.getElementById( 'main-menu-search' ), 'close-on-max' );
				apollo.removeClass( document.getElementById( 'menu-trigger-search' ), 'menu-trigger-active' );
			}

			e.preventDefault();
		}
	},

	// Once DOM has fully loaded
	domReady: function( callback ) {
		( document.readyState === 'interactive' || document.readyState === 'complete' ) ? callback() : document.addEventListener( 'DOMContentLoaded', callback );
	},

	// Initialize
	init: function( opts ) {
		this.domReady( function() {
			if ( mdMainMenuHas.main )
				mdMainMenu.main();

			if ( mdMainMenuHas.search )
				mdMainMenu.search();

			if ( mdMainMenuHas.social )
				mdMainMenu.social();
		});
	}

}

mdMainMenu.init();