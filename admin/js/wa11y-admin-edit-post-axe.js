(function( $ ) {
	'use strict';
	
	// When the window is loaded...
	$( window ).load(function() {

		// Check the editor content with aXe
		$('.wa11y-axe-check-editor' ).on('click',function($event) {
			$event.preventDefault();

			// Make sure we have an editor ID
			if ( $(this).data('editor') === undefined ) {
				return false;
			}

			// Make sure the editor exists
			var $this_editor = $( '#'+$(this).data('editor') );
			if ( $this_editor.length < 1 ) {
				return false;
			}

			// Set our content tester
			var $content_tester = undefined;
			var $content_tester_id = 'wa11y-axe-check-content';

			// If the content tester already exists, update the content
			if ( $('#'+$content_tester_id).length >= 1 ) {
				$content_tester = $('#'+$content_tester_id).html($this_editor.val());
			}

			// If it doesn't exist, create the tester and add the content
			// We have to put the div into the DOM for the testing to work
			else {
				$content_tester = $('<div id="'+$content_tester_id+'"></div>').append($this_editor.val()).insertAfter($this_editor);
			}

			// Run the test
			axe.a11yCheck($content_tester, function ($results) {

				console.log('passes: '+$results.passes.length);
				console.log('violations: '+$results.violations.length);
				console.log($results);

				// Delete the testing content
				$content_tester.remove();

			});

		});

	});

})( jQuery );