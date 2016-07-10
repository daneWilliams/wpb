<?php


/**
 *
 *	AJAX: Save plugin settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_ajax_wpb_settings_save', 'wpb_ajax__save_plugin_settings' );

function wpb_ajax__save_plugin_settings() 
{

	// Check nonce
	$nonce = wpb()->nonce();

	if ( empty( $_POST[ $nonce ] ) )
		wp_die();

	if ( !wp_verify_nonce( $_POST[ $nonce ], 'settings_save' ) )
		wp_die();

	// Get values
	if ( empty( $_POST['values'] ) )
		wp_die();

	$values = array();

	parse_str( $_POST['values'], $values );

	if ( empty( $values['wpb_settings'] ) )
		wp_die();

	// Create response
	$response = array(
		'values'  => $values['wpb_settings'],
		'error'   => false,
		'success' => false,
		'errors'  => array()
	);

	// Save settings
	$saved = wpb( 'settings/save', array( 'values' => $values['wpb_settings'] ) );

	// Not saved
	if ( !$saved ) {

		$response['error'] = wpb( 'notification/error', array(
			'text'    => __( 'Settings could not be saved', 'wpb' ),
			'dismiss' => false
		) );

		// Get errors
		foreach ( $values['wpb_settings'] as $key => $value ) {

			$setting_errors = wpb( ':settings/get_errors', $key );

			if ( !empty( $setting_errors ) ) {

				$id = str_replace( '/', '-', $key ) . '_setting';
				$response['errors'][ $id ] = array();

				foreach ( $setting_errors as $i => $error ) {

					$response['errors'][ $id ][ $i ] = wpb( 'notification/error', array(
						'text' => $error,
						'dismiss' => false
					) );

				}

			}

		}

	} else {

		$response['success'] = wpb( 'notification/success', array(
			'text'    => __( 'Settings saved', 'wpb' ),
			'dismiss' => true
		) );

	}

	wp_send_json( $response );

}


/**
 *
 *	AJAX: Save addon settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_ajax_wpb_addon_settings_save', 'wpb_ajax__save_addon_settings' );

function wpb_ajax__save_addon_settings() 
{

	// Check nonce
	$nonce = wpb()->nonce();

	if ( empty( $_POST[ $nonce ] ) )
		wp_die();

	if ( !wp_verify_nonce( $_POST[ $nonce ], 'addon_settings_save' ) )
		wp_die();

	// Get values
	if ( empty( $_POST['values'] ) )
		wp_die();

	$values = array();

	parse_str( $_POST['values'], $values );

	if ( empty( $values['wpb_settings'] ) || empty( $values['addon'] ) )
		wp_die();

	// Get addon
	$addon = wpb( 'addons/get', $values['addon'] );

	if ( !$addon )
		return wp_die();

	if ( !wpb( ':addons/is_active', $addon ) )
		return wp_die();

	// Create response
	$response = array(
		'values'  => $values['wpb_settings'],
		'error'   => false,
		'success' => false,
		'errors'  => array()
	);

	// Save settings
	$saved = wpb( 'settings/save', array( 'values' => $values['wpb_settings'], 'group' => 'addon/' . $addon->id() ) );

	// Not saved
	if ( !$saved ) {

		$response['error'] = wpb( 'notification/error', array(
			'text'    => sprintf( __( 'Settings could not be saved', 'wpb' ), $addon->name() ),
			'dismiss' => false
		) );

		// Get errors
		foreach ( $values['wpb_settings'] as $key => $value ) {

			$setting_errors = wpb( ':settings/get_errors', $key );

			if ( !empty( $setting_errors ) ) {

				$id = str_replace( '/', '-', $key ) . '_setting';
				$response['errors'][ $id ] = array();

				foreach ( $setting_errors as $i => $error ) {

					$response['errors'][ $id ][ $i ] = wpb( 'notification/error', array(
						'text' => $error,
						'dismiss' => false
					) );

				}

			}

		}

	} else {

		$response['success'] = wpb( 'notification/success', array(
			'text'    => sprintf( __( 'Settings saved', 'wpb' ), $addon->name() ),
			'dismiss' => true
		) );

	}

	wp_send_json( $response );

}


/**
 *
 *	AJAX: Activate addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_ajax_wpb_addon_activate', 'wpb_ajax__activate_addon' );

function wpb_ajax__activate_addon() 
{

	if ( empty( $_POST['addon'] ) )
		wp_die();

	// Check nonce
	$nonce = wpb()->nonce();

	if ( empty( $_POST[ $nonce ] ) )
		wp_die();

	if ( !wp_verify_nonce( $_POST[ $nonce ], 'addon_activate' ) )
		wp_die();

	// Get addon
	$addon = wpb( 'addons/get', $_POST['addon'] );

	if ( !$addon )
		wp_die();

	// Create response
	$response = array(
		'error'   => false,
		'success' => false
	);

	// Activate
	$activated = wpb( ':addons/activate', $addon );

	// Not activated
	if ( !$activated ) {

		if ( false === $activated ) {

			$response['error'] = wpb( 'notification/error', array(
				'text' => sprintf( __( '<strong>%s</strong> addon could not be activated', 'wpb' ), $addon->name() ),
				'dismiss' => false
			) );

		}

	} else {

		$response['success'] = wpb( 'notification/success', array(
			'text' => sprintf( __( '<strong>%s</strong> addon activated', 'wpb' ), $addon->name() ),
			'dismiss' => true
		) );

	}

	wp_send_json( $response );

}


/**
 *
 *	AJAX: Deactivate addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_ajax_wpb_addon_deactivate', 'wpb_ajax__deactivate_addon' );

function wpb_ajax__deactivate_addon() 
{

	if ( empty( $_POST['addon'] ) )
		wp_die();

	// Check nonce
	$nonce = wpb()->nonce();

	if ( empty( $_POST[ $nonce ] ) )
		wp_die();

	if ( !wp_verify_nonce( $_POST[ $nonce ], 'addon_deactivate' ) )
		wp_die();

	// Get addon
	$addon = wpb( 'addons/get', $_POST['addon'] );

	if ( !$addon )
		wp_die();

	// Create response
	$response = array(
		'error'   => false,
		'success' => false
	);

	// Deactivate
	$deactivated = wpb( ':addons/deactivate', $addon );

	// Not deactivated
	if ( !$deactivated ) {

		if ( false === $deactivated ) {

			$response['error'] = wpb( 'notification/error', array(
				'text' => sprintf( __( '<strong>%s</strong> addon could not be deactivated', 'wpb' ), $addon->name() ),
				'dismiss' => false
			) );

		}

	} else {

		$response['success'] = wpb( 'notification/success', array(
			'text' => sprintf( __( '<strong>%s</strong> addon deactivated', 'wpb' ), $addon->name() ),
			'dismiss' => true
		) );

	}

	wp_send_json( $response );

}