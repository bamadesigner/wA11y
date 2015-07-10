(function( $ ) {
    'use strict';

    // When the window is loaded...
    $( window ).load(function() {

        axe.a11yCheck( document, function ( $results ) {
            //ok(results.violations.length === 0, 'Should be no accessibility issues');
            console.log( $results );
        });

    });

})( jQuery );
