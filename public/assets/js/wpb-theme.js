(function($) {


	'use strict';


	/**
	 *	Menus
	 *	---------------------------------------------------------------- */

	$(function() {

		// Open
		$('.nav-menu').on( 'wpb.open', function() {

			$(this).addClass('nav-menu-open');
			$(this).data( 'wpb-open', true );

		});

		// Close
		$('.nav-menu').on( 'wpb.close', function() {

			$(this).removeClass('nav-menu-open');
			$(this).data( 'wpb-open', false );

		});

		// Toggle
		$('.nav-menu').on( 'click', '.menu-toggle', function(e) {

			e.preventDefault();

			var $menu = ( $(this).data('target') ? $( $(this).data('target') ) : $(this).closest('.nav-menu') );

			if ( $menu.length ) {

				if ( $menu.data( 'wpb-open' ) ) {

					$menu.trigger( 'wpb.close' );

				} else {

					$menu.trigger( 'wpb.open' );

				}

			}

		});

	});


	/**
	 *	Notifications
	 *	---------------------------------------------------------------- */

	$(function() {

		// Dismiss
		$('.notification').on( 'wpb.dismiss', function() {

			var $notification = $(this);

			$notification.fadeOut( 300, function() {

				$notification.remove();

			});

		});

		$('.notification').on( 'click', '.notification-close', function(e) {

			e.preventDefault();

			var $notification = $(this).closest('.notification');

			$notification.trigger( 'wpb.dismiss' );

		});

	});


}(jQuery));