(function($) {


	'use strict';


	/**
	 *	Buttons
	 *	---------------------------------------------------------------- */

	$(function() {

		var $page = $('#wpb-admin-page');

		// Store original data
		$page.find('.button, button').each(function() {

			var $button = $(this);

			// Original classes
			if ( !$button.data( 'wpb-original-class' ) )
				$button.data( 'wpb-original-class', $button.attr( 'class' ) );

			// Original text
			if ( !$button.data( 'wpb-original-text' ) )
				$button.data( 'wpb-original-text', $button.text() );

			// Original state
			$button.data( 'wpb-original-enabled', ( 'disabled' == $button.attr( 'disabled' ) ? false : true ) );

			if ( 'disabled' != $button.attr( 'disabled' ) )
				$button.removeProp( 'disabled' );

			// Original href
			$button.data( 'wpb-original-href', $button.attr( 'href' ) );

		});

		// Enable
		$page.on( 'wpb.enable', '.button, button', function() {

			var $button = $(this);

			$button.data( 'wpb-disabled', false );
			$button.removeProp( 'disabled' );
			$button.removeClass( 'wpb-button-disabled disabled' );

		});

		// Disable
		$page.on( 'wpb.disable', '.button, button', function() {

			var $button = $(this);

			$button.data( 'wpb-disabled', true );
			$button.prop( 'disabled', 'disabled' );
			$button.addClass( 'wpb-button-disabled disabled' );

		});

		$page.on( 'click', '.button, button', function(e) {

			var $button = $(this);

			if ( $button.data( 'wpb-original-href' ) && $button.data( 'wpb-disabled' ) )
				e.preventDefault();

		});

		// Loading
		$page.on( 'wpb.load', '.button, button', function() {

			var $button = $(this);

			$button.data( 'wpb-loading', true );
			$button.addClass( 'wpb-button-loading' );

			// Disable
			$button.trigger( 'wpb.disable' );

			// Update text
			if ( $button.data( 'wpb-loading-text' ) )
				$button.text( $button.data( 'wpb-loading-text' ) );


		});

		// Reset
		$page.on( 'wpb.reset', '.button, button', function() {

			var $button = $(this);

			// Remove data
			$button.data( 'wpb-loading', false );

			// Reset classes
			if ( $button.data( 'wpb-original-class' ) )
				$button.attr( 'class', $button.data( 'wpb-original-class' ) )

			// Reset text
			if ( $button.data( 'wpb-original-text' ) )
				$button.text( $button.data( 'wpb-original-text' ) );

			// Enable
			if ( $button.data( 'wpb-original-enabled' ) )
				$button.trigger( 'wpb.enable' );

			// Reset href
			if ( $button.data( 'wpb-original-href' ) )
				$button.attr( 'href', $button.data( 'wpb-original-href' ) );

		});

	});


	/**
	 *	Boxes
	 *	---------------------------------------------------------------- */

	$(function() {

		// Close
		if ( $('.wpb-box').length ) {

			$('.wpb-box').each(function() {

				if ( $(this).attr('id') ) {

					var box = Cookies.get( 'wpb_box_' + $(this).attr('id') );

					if ( box ) {

						box = JSON.parse( box );

						if ( box.closed )
							$(this).addClass('wpb-box-closed');

					}

				}

			});

		}

		// Toggle
		$('.wpb-box-header').on( 'click', function(e) {

			e.preventDefault();

			var $box = $(this).closest('.wpb-box');

			$box.toggleClass('wpb-box-closed');

			// Update cookie
			if ( $box.attr('id') ) {

				var closed = $box.hasClass('wpb-box-closed');

				Cookies.set( 'wpb_box_' + $box.attr('id'), { closed: closed } );
			
			}

		});

	});


	/**
	 *	Notifications
	 *	---------------------------------------------------------------- */

	$(function() {

		var $notifications = $('.wpb-notifications');

		// Dismiss
		$('.wpb-admin-page').on( 'wpb.dismiss', '.wpb-notification', function( e, force, noAnimation ) {

			var $notification = $(this);

			if ( !$notification.data( 'wpb-dismiss' ) && !force )
				return;

			if ( noAnimation ) {

				$notification.remove();

			} else {

				$notification.fadeOut( 300, function() {

					$notification.remove();

				});

			}

			if ( !$notifications.children().length )
				$notifications.remove();

		});

		$('.wpb-admin-page').on( 'click', '.notification-close', function(e) {

			e.preventDefault();

			var $notification = $(this).closest('.wpb-notification');

			$notification.trigger( 'wpb.dismiss' );

		});

	});


	/**
	 *	=Footer placeholder
	 *	---------------------------------------------------------------- */

	$(function() {

		var $buttons = $('.wpb-page-buttons');

		if ( $buttons.length ) {

			$('body').addClass('wpb-page-has-buttons');

		}

	});


	/**
	 *	Tabs
	 *	---------------------------------------------------------------- */

	$(function() {

		var $tabs    = $('.wpb-admin-page-tabs');
		var original = $tabs.children('.nav-tab-active').data( 'wpb-slug' );

		if ( $('.wpb-nav-tab-content').length ) {

			// Select
			$tabs.on( 'wpb.select', function( e, slug ) {

				var $tab     = $( 'a[data-wpb-slug="' + slug + '"]' ).eq(0);
				var $content = $( '.wpb-nav-tab-content[data-wpb-slug="' + slug + '"]' ).eq(0);	

				// Update classes
				$('.nav-tab-active').removeClass('nav-tab-active');
				$('.wpb-nav-tab-content-active').removeClass('wpb-nav-tab-content-active');

				$tab.addClass('nav-tab-active');
				$content.addClass('wpb-nav-tab-content-active');

				// Update form
				if ( $('form#wpb-admin-page:not(.wpb-no-ajax)').length ) {

					$('form#wpb-admin-page:not(.wpb-no-ajax)').attr( 'action', $tab.attr('href') );

				}

			});

			// URL change
			window.addEventListener( 'popstate', function(e) {

				if ( $tabs.length ) {

					$tabs.trigger( 'wpb.select', ( e.state && e.state.wpb_tab ? e.state.wpb_tab : original ) );

				}

			});

			// Click
			$tabs.on( 'click', '.nav-tab', function(e) {

				e.preventDefault();

				if ( !$(this).hasClass('.nav-tab-active') ) {

					// Use history API
					if ( !!( window.history && history.pushState ) ) {

						history.pushState( { wpb_tab: $(this).data( 'wpb-slug' ) }, null, $(this).attr('href') );
					}

					$tabs.trigger( 'wpb.select', $(this).data('wpb-slug') );

					// Dismiss notifications
					$('.wpb-notifications .wpb-notification').trigger( 'wpb.dismiss', [ false, true ] );

				}

			});

			// Page load
			if ( original )
				$tabs.trigger( 'wpb.select', original );

		}

	});


	/**
	 *	Settings
	 *	---------------------------------------------------------------- */

	$(function() {

		var $form = $('.wpb-page-settings');

		// Get action
		var action = $form.data( 'wpb-action' );

		// Save
		$form.on( 'submit', function(e) {

			// Update form
			$form.addClass('wpb-loading');

			// Update button
			$('.wpb-submit').trigger( 'wpb.load' );

			if ( !$form.hasClass('wpb-no-ajax') && action ) {

				e.preventDefault();

				var data = {
					action: action,
					values: $form.serialize()
				};

				data[ wpb.nonce ] = $( '#' + wpb.nonce ).val();

				// Request
				$.post( ajaxurl, data, function( response ) {

					// Update form
					$form.removeClass('wpb-loading');

					// Reset button
					$('.wpb-submit').trigger( 'wpb.reset' );

					// Remove errors
					$('.wpb-setting-has-error').removeClass('wpb-setting-has-error');

					// Add notifications wrapper
					if ( !$('#wpb-admin-page-header .wpb-notifications').length )
						$('#wpb-admin-page-header').append( '<div class="wpb-notifications" />' );

					// Clear notifications
					var $notifications = $('#wpb-admin-page-header .wpb-notifications');
					$notifications.empty();

					// Add error
					if ( response.error ) {

						$notifications.append( response.error );

						// Add settings errors
						if ( response.errors ) {

							// Add settings errors
							$.each( response.errors, function( key, errors ) {

								var $setting = $( '#' + key );

								if ( $setting.length ) {

									$setting.addClass('wpb-setting-has-error');

									// Add settings wrapper
									if ( !$setting.find('.wpb-setting-errors').length )
										$setting.find('.wpb-setting-input').append('<div class="wpb-setting-errors" />');

									var $errors = $setting.find('.wpb-setting-errors');

									// Clear previous errors
									$errors.html('');

									// Add errors
									$.each( errors, function( i, error ) {

										$errors.append( error );

									});

								}

							});

						}

					}

					// Success
					if ( response.success ) {

						// Remove errors
						$('.wpb-setting-has-error').removeClass('wpb-setting-has-error');

						// Add success message
						$notifications.append( response.success );

					}

				});

			}

		});

		// Import
		$('#wpb-transfer-import').on( 'click', '.wpb-import-button', function(e) {

			$('#wpb-admin-page').append( '<input type="hidden" name="' + $(this).attr('name') + '" value="1" />' );

			$(this).trigger( 'wpb.load' );
			$(this).closest('.wpb-box').addClass('wpb-box-loading');

		});

	});


	/**
	 *	Addons
	 *	---------------------------------------------------------------- */

	$(function() {

		var $page   = $('#wpb-admin-page');
		var $addons = $('.wpb-addons');

		// Sort
		$addons.on( 'wpb.sort', function() {

			$(this).find('.wpb-addon').each(function() {

				if ( !$(this).hasClass( 'wpb-addon-active' ) )
					$(this).parent().appendTo( $addons );

			});

		});

		$addons.trigger( 'wpb.sort' );

		// Activate/deactivate
		$page.on( 'click', '.wpb-addon-activate-link, .wpb-addon-deactivate-link', function() {

			$(this).trigger( 'wpb.load' );
			$(this).closest('.wpb-addon').addClass('wpb-loading');

		});

		// Activate
		$addons.on( 'click', '.wpb-addon-activate-link', function(e) {

			var $button = $(this);
			var $addon  = $(this).closest('.wpb-addon');

			e.preventDefault();

			var data = {
				action: 'wpb_addon_activate',
				addon: $addon.data( 'wpb-addon' )
			};

			data[ wpb.nonce ] = wpb_get_query_var( wpb.nonce, $button.attr('href') );

			// Request
			$.post( ajaxurl, data, function( response ) {

				// Update addon
				$addon.removeClass('wpb-loading');

				// Reset button
				$button.trigger( 'wpb.reset' );

				// Add notifications wrapper
				if ( !$('#wpb-admin-page-header .wpb-notifications').length )
					$('#wpb-admin-page-header').append( '<div class="wpb-notifications" />' );

				// Clear notifications
				var $notifications = $('#wpb-admin-page-header .wpb-notifications');
				$notifications.empty();

				// Error
				if ( response.error ) {

					$notifications.append( response.error );

				}

				// Success
				if ( response.success ) {

					// Update addon
					$addon.addClass('wpb-addon-active');
					$addon.removeClass('wpb-addon-inactive');

					// Add success message
					$notifications.append( response.success );

				}

			});

		});

		// Deactivate
		$addons.on( 'click', '.wpb-addon-deactivate-link', function(e) {

			var $button = $(this);
			var $addon  = $(this).closest('.wpb-addon');

			e.preventDefault();

			var data = {
				action: 'wpb_addon_deactivate',
				addon: $addon.data( 'wpb-addon' )
			};

			data[ wpb.nonce ] = wpb_get_query_var( wpb.nonce, $button.attr('href') );

			// Request
			$.post( ajaxurl, data, function( response ) {

				// Update addon
				$addon.removeClass('wpb-loading');

				// Reset button
				$button.trigger( 'wpb.reset' );

				// Add notifications wrapper
				if ( !$('#wpb-admin-page-header .wpb-notifications').length )
					$('#wpb-admin-page-header').append( '<div class="wpb-notifications" />' );

				// Clear notifications
				var $notifications = $('#wpb-admin-page-header .wpb-notifications');
				$notifications.empty();

				// Error
				if ( response.error ) {

					$notifications.append( response.error );

				}

				// Success
				if ( response.success ) {

					// Update addon
					$addon.addClass('wpb-addon-inactive');
					$addon.removeClass('wpb-addon-active');

					// Add success message
					$notifications.append( response.success );

				}

			});

		});

	});


}(jQuery));


/**
 *
 *	Get URL query variable
 *
 *
 *	Based on https://css-tricks.com/snippets/javascript/get-url-variables/
 *
 *	================================================================ */

function wpb_get_query_var( key, url )
{

	if ( url ) {

		var urlQuery = url.split( '?' )

		if ( urlQuery.length == 1 )
			url = false;

		else
			url = urlQuery[1];

		url = url.replace( '&amp;', '&' );

	}

	if ( !url )
		url = window.location.search.substring(1);

	var query = url;
	var vars  = query.split( '&' );

	for ( var i = 0; i < vars.length; i++ ) {

		var pair = vars[i].split( '=' );

		if ( pair[0] == key )
			return pair[1];

	}

	return false;

}


/*!
 * JavaScript Cookie v2.1.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
!function(e){if("function"==typeof define&&define.amd)define(e);else if("object"==typeof exports)module.exports=e();else{var n=window.Cookies,t=window.Cookies=e();t.noConflict=function(){return window.Cookies=n,t}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function n(t){function o(n,r,i){var c;if("undefined"!=typeof document){if(arguments.length>1){if(i=e({path:"/"},o.defaults,i),"number"==typeof i.expires){var s=new Date;s.setMilliseconds(s.getMilliseconds()+864e5*i.expires),i.expires=s}try{c=JSON.stringify(r),/^[\{\[]/.test(c)&&(r=c)}catch(a){}return r=t.write?t.write(r,n):encodeURIComponent(String(r)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=encodeURIComponent(String(n)),n=n.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent),n=n.replace(/[\(\)]/g,escape),document.cookie=[n,"=",r,i.expires&&"; expires="+i.expires.toUTCString(),i.path&&"; path="+i.path,i.domain&&"; domain="+i.domain,i.secure?"; secure":""].join("")}n||(c={});for(var p=document.cookie?document.cookie.split("; "):[],u=/(%[0-9A-Z]{2})+/g,d=0;d<p.length;d++){var f=p[d].split("="),l=f[0].replace(u,decodeURIComponent),m=f.slice(1).join("=");'"'===m.charAt(0)&&(m=m.slice(1,-1));try{if(m=t.read?t.read(m,l):t(m,l)||m.replace(u,decodeURIComponent),this.json)try{m=JSON.parse(m)}catch(a){}if(n===l){c=m;break}n||(c[l]=m)}catch(a){}}return c}}return o.set=o,o.get=function(e){return o(e)},o.getJSON=function(){return o.apply({json:!0},[].slice.call(arguments))},o.defaults={},o.remove=function(n,t){o(n,"",e(t,{expires:-1}))},o.withConverter=n,o}return n(function(){})});