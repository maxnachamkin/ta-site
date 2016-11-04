( function() {
	var footnotes = document.getElementsByClassName( 'md-footnote' );
	for ( var i = 0; i < footnotes.length; i++ ) {
		footnotes[i].onclick = function( e ) {
			var currentClass = this.className;
			var toggleClass  = ' md-footnote-show';
			if ( currentClass.indexOf( toggleClass ) > -1 )
				newClass = currentClass.replace( toggleClass, '' );
			else
				newClass = currentClass + toggleClass;
			this.className = newClass;
		}
	}
})();