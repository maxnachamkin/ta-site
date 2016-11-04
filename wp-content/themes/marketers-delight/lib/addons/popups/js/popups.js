window.mdPopups = {

	// Private
	popup: {},
	close: {},
	trigger: {},
	bg: {},
	shown: false,
	cookieName: 'mdPopups_shown',

	// Options
	exitIntent: false,
	mainPopup: '',
	delay: 5,
	showOnDelay: false,
	cookieExp: 30,

	// Set main HTML, output default or user options
	setOptions: function( opts ) {
		this.html         = document.getElementsByTagName( 'html' )[0];
		this.popups       = document.getElementById( 'md-popups' );
		this.bg           = document.getElementById( 'md_popup_bg' );
		this.trigger      = document.getElementsByClassName( 'md-popup-trigger' );
		this.triggerClose = document.getElementsByClassName( 'md-popup-close' );

		this.mainPopup   = ( typeof opts.mainPopup === 'undefined' ) ? this.mainPopup : opts.mainPopup;
		this.exitIntent  = ( typeof opts.exitIntent === 'undefined' ) ? this.exitIntent : opts.exitIntent;
		this.delay       = ( typeof opts.delay === 'undefined' ) ? this.delay : opts.delay;
		this.showOnDelay = ( typeof opts.showOnDelay === 'undefined' ) ? this.showOnDelay : opts.showOnDelay;
		this.cookieExp   = ( typeof opts.cookieExp === 'undefined' ) ? this.cookieExp : opts.cookieExp;
	},

	// Create, get and erase cookie (http://www.quirksmode.org/js/cookies.html)
	cookieManager: {
		create: function( name, value, days ) {
			var expires = '';

			if ( days ) {
				var date = new Date();
				date.setTime( date.getTime() + (days * 24 * 60 * 60 * 1000 ) );
				expires = '; expires=' + date.toGMTString();
			}

			document.cookie = name + '=' + value + expires + '; path=/';
		},

		get: function( name ) {
			var nameEQ = name + '=';
			var ca     = document.cookie.split( ';' );

			for ( var i = 0; i < ca.length; i++ ) {
				var c = ca[i];
				while ( c.charAt(0) == ' ' ) c = c.substring( 1, c.length );
				if ( c.indexOf( nameEQ ) === 0 ) return c.substring( nameEQ.length, c.length );
			}

			return null;
		},

		erase: function( name ) {
			this.create( name, '', -1 );
		}
	},

	// Check if cookie exists
	checkCookie: function() {
		if ( this.cookieExp <= 0 ) {
			this.cookieManager.erase( this.cookieName );
			return false;
		}

		if ( this.cookieManager.get( this.cookieName ) == 'true' )
			return true;

		this.cookieManager.create( this.cookieName, 'true', this.cookieExp );

		return false;
	},

	// Call to show popup HTML
	showPopup: function( id ) {
		this.popup = document.getElementById( id );

		if ( ! this.popup )
			return false;

		this.html.style.overflow     = 'hidden';
		this.popups.style.visibility = this.bg.style.visibility = 'visible';
		this.popups.style.opacity    = this.bg.style.opacity    = 1;
		this.popups.style.height     = '100%';
		this.popup.style.display     = 'block';

		this.toggleVideo( 'show' );
	},

	// Manually call popup with assocuated trigger
	triggeredPopup: function() {
		for ( var i = 0; i < this.trigger.length; i++ ) {
			this.trigger[i].onclick = function() {
				var dataType = this.getAttribute( 'data-popup' );
				mdPopups.showPopup( dataType );
				event.preventDefault();
			}
		}
	},

	// Hide popup
	hidePopup: function() {
		this.html.style.overflow = this.popups.style.height = this.popups.style.visibility = this.bg.style.visibility = this.popups.style.opacity = this.bg.style.opacity = this.popup.style.display = this.popups.style.height = '';
		mdPopups.toggleVideo( 'hide' );
		event.preventDefault();
	},

	// Ensure video stops on popup close
	toggleVideo: function( state ) {
	    var iframe = this.popup.querySelector( 'iframe');
	    var video  = this.popup.querySelector( 'video' );

	    if ( iframe !== null ) {
	        var iframeSrc = iframe.src;
	        iframe.src    = iframeSrc;
	    }

	    if ( video !== null )
	        video.pause();
	},

	// Close Events Listener
	closeEvents: function() {
		this.bg.addEventListener( 'click' , function() {
			mdPopups.hidePopup();
		});

		window.document.onkeydown = function( evt ) {
			evt = evt || window.event;
			if ( evt.keyCode == 27 )
				mdPopups.hidePopup();
		};

		for ( var i = 0; i < this.triggerClose.length; i++ ) {
			this.triggerClose[i].addEventListener( 'click', function() {
				mdPopups.hidePopup();
			});
		}
	},

	// Exit Intent Listener
	exitEvent: function() {
		document.addEventListener( 'mousemove', function( e ) {
			if ( this.shown ) return;

			var scroll = window.pageYOffset || document.documentElement.scrollTop;

			if ( ( e.pageY - scroll ) < 7 ) {
				mdPopups.showPopup( 'md_popup_' + mdPopups.mainPopup );
				this.shown = true;
			}
		});
	},

	// Once DOM has fully loaded
	domReady: function( callback ) {
		( document.readyState === 'interactive' || document.readyState === 'complete' ) ? callback() : document.addEventListener( 'DOMContentLoaded', callback );
	},

	// Initialize
	init: function( opts ) {
		this.domReady( function() {
			if ( typeof opts !== 'undefined' )
				mdPopups.setOptions( opts );

			mdPopups.closeEvents();

			mdPopups.triggeredPopup();

			if ( ( ! mdPopups.exitIntent && ! mdPopups.showOnDelay ) || mdPopups.checkCookie() )
				return;

			setTimeout( function() {
				if ( mdPopups.exitIntent )
					mdPopups.exitEvent();

				if ( mdPopups.showOnDelay )
					mdPopups.showPopup( 'md_popup_' + mdPopups.mainPopup );
			}, mdPopups.delay * 1000 );

		});
	}
}