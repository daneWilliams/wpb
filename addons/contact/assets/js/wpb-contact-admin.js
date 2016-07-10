(function($) {


	'use strict';


	/**
	 *	Widget
	 *	---------------------------------------------------------------- */

	// Show/hide settings
	$(function() {

		// Display labels
		$('.wpb-contact-widget-display-labels').each(function() {

			var $settings = $(this).closest('.wpb-widget-settings');
			var $labels   = $settings.find('.wpb-contact-widget-label-input').closest('.wpb-setting');
			var $input    = $(this).find('input[type="checkbox"]');

			if ( $input.is(':checked') ) {

				$labels.show();

			} else {

				$labels.hide();

			}

		});

		$('.wp-admin').on( 'change', '.wpb-contact-widget-display-labels input', function(e) {

			var $settings = $(this).closest('.wpb-widget-settings');
			var $labels   = $settings.find('.wpb-contact-widget-label-input').closest('.wpb-setting');

			if ( $(this).is(':checked') ) {

				$labels.show();

			} else {

				$labels.hide();

			}

		});

		// Display icons
		$('.wpb-contact-widget-display-icons').each(function() {

			var $setting = $(this).closest('.wpb-setting');
			var $icons   = $setting.siblings('.wpb-setting_icon');
			var $input   = $(this).find('input[type="checkbox"]');

			if ( $input.is(':checked') ) {

				$icons.show();

			} else {

				$icons.hide();

			}

		});

		$('.wp-admin').on( 'change', '.wpb-contact-widget-display-icons input', function(e) {

			var $setting = $(this).closest('.wpb-setting');
			var $icons   = $setting.siblings('.wpb-setting_icon');
			var $choice  = $(this).parent();

			if ( $(this).is(':checked') ) {

				$icons.show();

			} else {

				$icons.hide();

			}

		});


	});


	$(document).ajaxSuccess(function(e, xhr, settings) {

		var widget_id_base = 'wpb-contact';

		if ( settings.data.search('action=save-widget') != -1 && settings.data.search( 'id_base=' + widget_id_base ) != -1 ) {

			// Display labels
			$('.wpb-contact-widget-display-labels').each(function() {

				var $settings = $(this).closest('.wpb-widget-settings');
				var $labels   = $settings.find('.wpb-contact-widget-label-input').closest('.wpb-setting');
				var $input    = $(this).find('input[type="checkbox"]');

				if ( $input.is(':checked') ) {

					$labels.show();

				} else {

					$labels.hide();

				}

			});

			// Display icons
			$('.wpb-contact-widget-display-icons').each(function() {

				var $setting = $(this).closest('.wpb-setting');
				var $icons   = $setting.siblings('.wpb-setting_icon');
				var $input   = $(this).find('input[type="checkbox"]');

				if ( $input.is(':checked') ) {

					$icons.show();

				} else {

					$icons.hide();

				}

			});

		}

	});


}(jQuery));