(function($) {


	'use strict';


	/**
	 *	Dashicons
	 *	---------------------------------------------------------------- */

	$(function() {

		$('#wpb-dashicons-menu').clone().attr( 'id', 'wpb-dashicons-menu-clone' );

		// Open menu
		$('.wp-admin').on( 'wpb.open', '.wpb-dashicons-menu', function( e, $field ) {

			// Update classes
			$(this).find('.button-primary').removeClass('button-primary');
			$(this).find('.wpb-dashicons-section-open').removeClass('wpb-dashicons-section-open');

			// Add to field
			if ( $field ) {

				$field = $( $field );

				if ( $field.length ) {

					$(this).appendTo( $field );

					$field.find('.wpb-icon-button-toggle').addClass('button-primary');

					// Get selected icon
					var $input = $field.find('input');

					$(this).trigger( 'wpb.select', [ $field.find('input').val(), $field ] );

				}

			}

			// Update classes
			$(this).addClass('wpb-dashicons-menu-open');

		});

		// Close menu
		$('.wp-admin').on( 'wpb.close', '.wpb-dashicons-menu', function() {

			$(this).removeClass('wpb-dashicons-menu-open');
			$('.wpb-icon-button-toggle.button-primary').removeClass('button-primary');

		});

		// Toggle
		$('.wp-admin').on( 'click', '.wpb-icon-button-toggle', function(e) {

			e.preventDefault();

			var $dashicons = $('#wpb-dashicons-menu-clone');

			if ( !$dashicons.length ) {

				$dashicons = $('#wpb-dashicons-menu').clone();
				$dashicons.attr( 'id', 'wpb-dashicons-menu-clone' );

			}

			if ( $dashicons.hasClass('wpb-dashicons-menu-open') ) {

				$dashicons.trigger( 'wpb.close' );

			} else {

				$dashicons.trigger( 'wpb.open', $(this).closest('.wpb-setting-input') );

			}

		});

		// Select icon
		$('.wp-admin').on( 'wpb.select', '.wpb-dashicons-menu', function( e, icon, field ) {

			var $field = false;

			if ( field ) {

				if ( $( field ).length )
					$field = $( field );

			}

			// Get icon
			var $icon = $(this).find( '.dashicons-' + icon );

			if ( !$icon.length ) {

				if ( $field ) {

					$field.find('.wpb-icon-preview').remove();
					$field.removeClass('wpb-setting-icon-preview');

				}

				return;

			}

			// Update classes
			$(this).find('.button-primary').removeClass('button-primary');
			$icon.parent().addClass('button-primary');
			$icon.closest('.wpb-dashicons-section').addClass('wpb-dashicons-section-open');

			// Update input
			if ( $field ) {

				$field.find('input').val( icon );
				$field.addClass('wpb-setting-icon-preview');

				$field.find('.wpb-icon-preview').remove();
				$field.find('.wpb-setting-icon-input').prepend('<span class="wpb-icon-preview dashicons dashicons-' + icon + '"></span>');

			}

			// Close
			$(this).trigger( 'wpb.close' );

		});

		// Select trigger
		$('.wp-admin').on( 'click', '.wpb-dashicons-button', function(e) {

			e.preventDefault();

			$(this).closest('.wpb-dashicons-menu').trigger( 'wpb.select', [ $(this).data( 'wpb-icon' ), $(this).closest('.wpb-setting-input') ] );

		});

		// Add preview
		$('.wpb-setting_icon').each(function() {

			var $input = $(this).find('input');

			var $dashicons = $('#wpb-dashicons-menu-clone');

			if ( !$dashicons.length ) {

				$dashicons = $('#wpb-dashicons-menu').clone().appendTo('body');
				$dashicons.attr( 'id', 'wpb-dashicons-menu-clone' );

			}

			if ( $input.val() )
				$dashicons.trigger( 'wpb.select', [ $input.val(), $(this).children('.wpb-setting-input') ] );

		});

		$('.wp-admin').on( 'blur', '.wpb-setting_icon input', function() {

			var $dashicons = $('#wpb-dashicons-menu-clone');

			if ( !$dashicons.length ) {

				$dashicons = $('#wpb-dashicons-menu').clone().appendTo('body');
				$dashicons.attr( 'id', 'wpb-dashicons-menu-clone' );

			}

			$dashicons.trigger( 'wpb.select', [ $(this).val(), $(this).parents('.wpb-setting-input') ] );

		});

		// Open section
		$('.wp-admin').on( 'click', '.wpb-dashicons-heading', function() {

			$(this).parent().toggleClass('wpb-dashicons-section-open');

		});

		// Set open section
		$('.wpb-dashicons-menu .button-primary').closest('.wpb-dashicons-section').addClass('wpb-dashicons-section-open');

	});

	// Update widget Dashicons
	$(document).ajaxSuccess(function( e, xhr, settings ) {

		if ( settings.data.search('action=save-widget') != -1 ) {

			// Update icons
			var $dashicons = $('#wpb-dashicons-menu-clone');

			if ( !$dashicons.length ) {

				$dashicons = $('#wpb-dashicons-menu').clone().appendTo('body');
				$dashicons.attr( 'id', 'wpb-dashicons-menu-clone' );

			}

			// Add preview
			$('.wpb-setting_icon').each(function() {

				var $input = $(this).find('input');

				if ( $input.val() )
					$dashicons.trigger( 'wpb.select', [ $input.val(), $(this).children('.wpb-setting-input') ] );

			});

		}

	});


	/**
	 *	File
	 *	---------------------------------------------------------------- */

	$(function() {

		// Select image
		$('.wp-admin').on( 'click', '.wpb-file-select', function(e) {

			var $parent  = $(this).closest('.wpb-setting_file');
			var $button  = $(this);
			var $input   = ( $button.data('wpb-target') ? $( $button.data('wpb-target') ) : $parent.find('.wpb-file-id') );
			var $preview = $parent.find('.wpb-file-link');

			e.preventDefault();

			// Close any existing dialogs
			if ( wpb.settings.uploader )
				wpb.settings.uploader.close();

			// Extend the wp.media object
			wpb.settings.uploader = wp.media.frames.file_frame = wp.media({
				title: $button.data('wpb-title-text'),
				button: {
					text: $button.data('wpb-button-text')
				},
				multiple: false
			});

			// Set attachment ID
			wpb.settings.uploader_attachment = $input.val();

			// When a file is selected, update the value and preview
			wpb.settings.uploader.on( 'select', function() {

				var attachment = wpb.settings.uploader.state().get('selection').first().toJSON();

				// Update class
				$parent.addClass('wpb-file-selected');

				// Set attachment ID
				$input.val( attachment.id )
				wpb.settings.uploader_attachment = attachment.id;

				// Update preview name
				$preview.html( '<a href="' + attachment.url + '" target="_blank">' + attachment.title + '</a>' );

			});

			// Open the uploader dialog
			wpb.settings.uploader.on( 'open', function() {

				if ( wpb.settings.uploader_attachment ) {

					var selection = wpb.settings.uploader.state().get('selection'),
						id = wpb.settings.uploader_attachment;

					var attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );

					console.log( attachment );

				}

			});

			wpb.settings.uploader.open();

		});

		// Remove file
		$('.wp-admin').on( 'click', '.wpb-file-remove', function(e) {

			var $button  = $(this);
			var $parent  = $(this).closest('.wpb-setting_file');
			var $input   = ( $button.data('wpb-target') ? $( $button.data('wpb-target') ) : $parent.find('.wpb-file-id') );
			var $preview = $parent.find('.wpb-file-link');

			e.preventDefault();

			// Remove attachment ID
			$input.val('');

			// Update preview
			if ( $preview.data( 'wpb-prev-text' ) )
				$preview.html( $preview.data( 'wpb-prev-text' ) );

			// Update class
			$parent.removeClass('wpb-file-selected');

		});

	});


	/**
	 *	Image
	 *	---------------------------------------------------------------- */

	$(function() {

		// Select image
		$('.wp-admin').on( 'click', '.wpb-image-select, .wpb-image-preview img', function(e) {

			var $parent = $(this).closest('.wpb-setting_image');
			var $button = $parent.find('.wpb-image-select');
			var $input  = ( $button.data('target') ? $( $button.data('target') ) : $parent.find('.wpb-image-id') );

			e.preventDefault();

			// Close any existing dialogs
			if ( wpb.settings.uploader )
				wpb.settings.uploader.close();

			// Extend the wp.media object
			wpb.settings.uploader = wp.media.frames.file_frame = wp.media({
				title: $button.data('wpb-title-text'),
				button: {
					text: $button.data('wpb-button-text')
				},
				multiple: false
			});

			// Set attachment ID
			wpb.settings.uploader_attachment = $( $button.data('target') ).val();

			// When a file is selected, update the value and preview
			wpb.settings.uploader.on( 'select', function() {

				var attachment = wpb.settings.uploader.state().get('selection').first().toJSON();

				// Update class
				$parent.addClass('wpb-image-selected');

				// Set preview
				$parent.find('.wpb-image-preview').html( '<img src="' + attachment.url + '" />' );

				// Set attachment ID
				$input.val( attachment.id )
				wpb.settings.uploader_attachment = attachment.id;

			});

			// Open the uploader dialog
			wpb.settings.uploader.on( 'open', function() {

				if ( wpb.settings.uploader_attachment ) {

					var selection = wpb.settings.uploader.state().get('selection'),
						id = wpb.settings.uploader_attachment;

					var attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );

				}

			});

			wpb.settings.uploader.open();

		});

		// Remove image
		$('.wp-admin').on( 'click', '.wpb-image-remove', function(e) {

			var $button = $(this);
			var $parent = $(this).closest('.wpb-setting_image');
			var $input  = ( $button.data('target') ? $( $button.data('target') ) : $parent.find('.wpb-image-id') );

			e.preventDefault();

			// Remove attachment ID
			$input.val('');

			// Remove preview
			$parent.find('.wpb-image-preview img').remove();

			// Update class
			$parent.removeClass('wpb-image-selected');

		});

	});


	/**
	 *	Code
	 *	---------------------------------------------------------------- */

	$(function() {

		if ( typeof CodeMirror !== 'undefined' ) {

			var $code = $('.wpb-setting_code');

			$code.each(function() {

				var $textarea = $(this).find('textarea');

				var options = {
					mode: $textarea.data('wpb-lang'),
					indentUnit: 4,
					indentWithTabs: true,
					lineNumbers: true,
					lineWrapping: true
				};

				var editor = CodeMirror.fromTextArea( $textarea[0], options );

				$('.wpb-admin-page-tabs').on( 'wpb.select', function() {

					editor.refresh();

				});

			});

		}

	});


	/**
	 *	=Widget layouts
	 *	---------------------------------------------------------------- */

	$(function() {

		$('.wpb-widgets-layout-select select').change(function() {

			$(this).closest('.wpb-setting').find('img').hide();
			$(this).closest('.wpb-setting').find( 'img.wpb-widgets-layout-' + $(this).val() ).css( 'display', 'block' );

		});

	});


}(jQuery));