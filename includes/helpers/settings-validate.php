<?php


/**
 *
 *	Validate required
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/settings/validate/text', 'wpb_setting_validate_required', 5, 3 );

if ( !function_exists( 'wpb_setting_validate_required' ) ) :

function wpb_setting_validate_required( $validated, $value, $setting )
{

	if ( empty( $setting->required ) )
		return $validated;

	if ( empty( $value ) ) {

		wpb( 'settings/error', $setting->key, __( 'This field is required', 'wpb' ) );

		return false;

	}

	return $validated;

}

endif;


/**
 *
 *	Validate file setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/settings/validate/file', 'wpb_setting_validate_file', 5, 3 );

if ( !function_exists( 'wpb_setting_validate_file' ) ) :

function wpb_setting_validate_file( $validated, $value, $setting )
{

	// Required
	if ( !empty( $setting->required ) ) {

		if ( empty( $value ) ) {

			wpb( 'settings/error', $setting->key, __( 'Please select a file', 'wpb' ) );

			return false;

		}

	}

	if ( empty( $value ) )
		return $validated;

	// File type
	if ( !empty( $setting->ext ) ) {

		// Check file type
		$file_url  = wp_get_attachment_url( $value );
		$file_type = wp_check_filetype( $file_url );

		if ( !in_array( $file_type['ext'], $setting->ext ) ) {

			wpb( 'settings/error', $setting->key, __( 'Invalid file type', 'wpb' ) );

			return false;

		}

	}

	return $validated;

}

endif;


/**
 *
 *	Validate number setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/settings/validate/number', 'wpb_setting_validate_number', 5, 3 );

if ( !function_exists( 'wpb_setting_validate_number' ) ) :

function wpb_setting_validate_number( $validated, $value, $setting )
{

	if ( !$value )
		return $validated;

	// Not numeric
	if ( !is_numeric( $value ) ) {

		wpb( 'settings/error', $setting->key, __( 'Please enter a number', 'wpb' ) );

		return false;

	}

	// Check min/max
	$min = $setting->min;
	$max = $setting->max;

	if ( '' !== $min || '' !== $max ) {

		$error = false;

		if ( '' !== $max && (int) $value > $max ) {

			$error = sprintf( __( 'Please enter a number less than <strong>%d</strong>', 'wpb' ), ( $max - 1 ) );

			if ( '' !== $min )
				$error = sprintf( __( 'Please enter a number between <strong>%1$d</strong> and <strong>%2$s</strong', 'wpb' ), $min, $max );

		} elseif ( '' !== $min && (int) $value < $min ) {

			$error = sprintf( __( 'Please enter a number greater than <strong>%d</strong>', 'wpb' ), ( $min - 1 ) );

		}

		if ( $error ) {

			wpb( 'settings/error', $setting->key, $error );

			return false;

		}

	} 

	return $validated;

}

endif;