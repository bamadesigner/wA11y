(function( $ ) {
	'use strict';
	
	// When the document is ready...
	$(document).ready(function() {

		// Check the main editor's content right off the bat with aXe
		$('#content').wa11y_run_axe_a11y_check();

		// Check editor content with aXe
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

			// Run the test on the editor
			$this_editor.wa11y_run_axe_a11y_check();

		});

	});

	// Is invoked by the item/content we're checking
	$.fn.wa11y_run_axe_a11y_check = function() {

		// This holds the content we're checking
		var $this_content_item = $(this);

		// Get the content
		var $this_content = null;
		if ( $this_content_item.is('textarea') ) {
			$this_content = $this_content_item.val();
		} else {
			$this_content = $this_content_item.html();
		}

		// Set our content tester
		var $content_tester = undefined;
		var $content_tester_id = 'wa11y-axe-check-content';

		// If the content tester already exists, update the content
		if ( $('#'+$content_tester_id).length >= 1 ) {
			$content_tester = $('#'+$content_tester_id).html($this_content);
		}

		// If it doesn't exist, create the tester and add the content
		// We have to put the div into the DOM for the testing to work
		else {
			$content_tester = $('<div id="'+$content_tester_id+'"></div>').append($this_content).insertAfter($this_content_item);
		}

		// Run the test
		axe.a11yCheck($content_tester, function($axe_results) {

			console.log('passes: '+$axe_results.passes.length);
			console.log('violations: '+$axe_results.violations.length);
			console.log($axe_results);

			// Process the results and get results HTML
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'html',
				async: true,
				cache: false,
				data: {
					action: 'wa11y_get_axe_evaluation_results_html',
					wa11y_axe_evaluation_results: $axe_results
				},
				error:function( $jqXHR, $textStatus, $errorThrown ){},
				success: function( $results_html, $textStatus, $jqXHR ) {

					// If we have results...
					if ( $results_html !== undefined && $results_html != '' ) {

						// Load into the results area
						$('#wa11y-axe-evaluation-mb-results').html($results_html);

					}

				},
				complete:function( $jqXHR, $textStatus ) {}
			});

			// Go to meta box
			$(window).scrollTop($('#wa11y-axe-evaluation-mb').position().top);

			// Delete the testing content
			$content_tester.remove();

		});

	}

})( jQuery );