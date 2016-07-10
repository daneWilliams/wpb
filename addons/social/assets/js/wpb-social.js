(function($) {


	'use strict';


	/**
	 *	Share
	 *	---------------------------------------------------------------- */

	$(function() {

		var $container = $('.button-share-main').next();

		$container.each(function() {

			$(this).data( 'wpb-height', $(this).height() );
			$(this).height(0);

		});

		$('.share-buttons').addClass('share-buttons-hidden');

		// Toggle
		$('.share-buttons').on( 'click', '.button-share-main', function(e) {

			e.preventDefault();

			var $buttons   = $(this).parent();
			var $container = $(this).next();

			$buttons.toggleClass('share-buttons-hidden share-buttons-open');

			// Close
			if ( $buttons.data( 'wpb-open' ) ) {

				$container.data( 'wpb-height', $container.height() );
				$container.height(0);

				$buttons.data( 'wpb-open', false );

			}

			// Open
			else {

				$container.height( $container.data( 'wpb-height' ) );

				$container.one( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {

					$container.css( 'height', '' );

				});

				$buttons.data( 'wpb-open', true );

			}

		});


	});


}(jQuery));