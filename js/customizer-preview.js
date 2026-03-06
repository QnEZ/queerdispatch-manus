/**
 * QueerDispatch Theme Customizer Live Preview
 *
 * Handles postMessage transport for the default style setting,
 * so the Customizer preview pane updates instantly without a full page reload.
 */
( function( $ ) {
    'use strict';

    /**
     * Listen for changes to the default style setting and apply them
     * to the preview iframe in real time.
     */
    wp.customize( 'queerdispatch_default_style', function( value ) {
        value.bind( function( newStyle ) {
            // Update data-style on body (matches our CSS selectors)
            document.body.setAttribute( 'data-style', newStyle );

            // Also update the body class (belt-and-suspenders fallback)
            var classList = document.body.className.split( ' ' );
            var filtered  = classList.filter( function( cls ) {
                return ! cls.match( /^style-/ );
            } );
            filtered.push( 'style-' + newStyle );
            document.body.className = filtered.join( ' ' );
        } );
    } );

} )( jQuery );
