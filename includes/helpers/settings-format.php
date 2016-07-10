<?php


/**
 *
 *	Add support for advanced setting types
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/settings/types', 'wpb_advanced_setting_types', 5 );

if ( !function_exists( 'wpb_advanced_setting_types' ) ) :

function wpb_advanced_setting_types( $types = array() )
{

	$types[] = 'boolean';
	$types[] = 'file';
	$types[] = 'image';
	$types[] = 'email';
	$types[] = 'number';
	$types[] = 'code';
	$types[] = 'icon';

	return $types;

}

endif;


/**
 *
 *	Format setting choices
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format', 'wpb_setting_format_choices', 5 );

if ( !function_exists( 'wpb_setting_format_choices' ) ) :

function wpb_setting_format_choices( $setting = array() )
{

	// No choices
	if ( empty( $setting['choices'] ) )
		return $setting;

	$choices = array();

	foreach ( $setting['choices'] as $value => $choice ) {

		if ( !is_array( $choice ) )
			$choice = array( 'label' => $choice );

		$choice = array_merge( array(
			'label' => '',
			'desc'  => '',
			'value' => $value
		), $choice );

		$choices[ esc_attr( trim( $value ) ) ] = $choice;

	}

	$setting['choices'] = $choices;

	return $setting;

}

endif;


/**
 *
 *	Format text setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/text', 'wpb_setting_format_text', 5 );

if ( !function_exists( 'wpb_setting_format_text' ) ) :

function wpb_setting_format_text( $setting = array() )
{

	// Input type
	if ( empty( $setting['attr']['type'] ) )
		$setting['attr']['type'] = 'text';

	// Classes
	if ( empty( $setting['attr']['class'] ) )
		$setting['attr']['class'] = array( 'regular-text' );

	return $setting;

}

endif;


/**
 *
 *	Format textarea setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/textarea', 'wpb_setting_format_textarea', 5 );

if ( !function_exists( 'wpb_setting_format_textarea' ) ) :

function wpb_setting_format_textarea( $setting = array() )
{

	// Cols
	if ( empty( $setting['attr']['cols'] ) )
		$setting['attr']['cols'] = 56;

	// Rows
	if ( empty( $setting['attr']['rows'] ) )
		$setting['attr']['rows'] = 8;

	return $setting;

}

endif;


/**
 *
 *	Format code setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/code', 'wpb_setting_format_code', 5 );

if ( !function_exists( 'wpb_setting_format_code' ) ) :

function wpb_setting_format_code( $setting = array() )
{

	// Cols
	if ( empty( $setting['attr']['cols'] ) )
		$setting['attr']['cols'] = 80;

	// Rows
	if ( empty( $setting['attr']['rows'] ) )
		$setting['attr']['rows'] = 16;

	// Language
	if ( empty( $setting['lang'] ) )
		$setting['lang'] = 'htmlmixed'; 

	// Classes
	if ( empty( $setting['attr']['class'] ) )
		$setting['attr']['class'] = array( 'code' );

	return $setting;

}

endif;


/**
 *
 *	Format boolean setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/boolean', 'wpb_setting_format_boolean', 5 );

if ( !function_exists( 'wpb_setting_format_boolean' ) ) :

function wpb_setting_format_boolean( $setting = array() )
{

	// Attributes
	$setting['attr']['type']  = 'checkbox';
	$setting['attr']['value'] = '1';

	return $setting;

}

endif;


/**
 *
 *	Format email setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/email', 'wpb_setting_format_email', 5 );

if ( !function_exists( 'wpb_setting_format_email' ) ) :

function wpb_setting_format_email( $setting = array() )
{

	// Template
	$setting['template'] = 'admin/templates/settings/setting-text';

	// Input type
	$setting['attr']['type'] = 'email';

	// Classes
	if ( empty( $setting['attr']['class'] ) )
		$setting['attr']['class'] = array( 'regular-text' );

	return $setting;

}

endif;


/**
 *
 *	Format number setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/number', 'wpb_setting_format_number', 5 );

if ( !function_exists( 'wpb_setting_format_number' ) ) :

function wpb_setting_format_number( $setting = array() )
{

	// Template
	$setting['template'] = 'admin/templates/settings/setting-text';

	// Input type
	$setting['attr']['type'] = 'number';

	// Min/max
	if ( !isset( $setting['min'] ) )
		$setting['min'] = '';

	if ( !isset( $setting['max'] ) )
		$setting['max'] = '';

	$setting['attr']['min'] = $setting['min'];
	$setting['attr']['max'] = $setting['max'];

	// Classes
	if ( empty( $setting['attr']['class'] ) )
		$setting['attr']['class'] = array( 'small-text' );

	return $setting;

}

endif;


/**
 *
 *	Format icon setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/icon', 'wpb_setting_format_text', 5 );


/**
 *
 *	Format file setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/file', 'wpb_setting_format_file', 5 );

if ( !function_exists( 'wpb_setting_format_file' ) ) :

function wpb_setting_format_file( $setting = array() )
{

	// File extensions
	if ( empty( $setting['ext'] ) )
		$setting['ext'] = array();

	if ( !is_array( $setting['ext'] ) )
		$setting['ext'] = array_map( 'trim', explode( ',', $setting['ext'] ) );

	// Button text
	if ( empty( $setting['button_text'] ) )
		$setting['button_text'] = __( 'Select file', 'wpb' );

	// Remove button text
	if ( empty( $setting['remove_button_text'] ) )
		$setting['remove_button_text'] = __( 'Remove file', 'wpb' );

	// Upload title text
	if ( empty( $setting['upload_title_text'] ) )
		$setting['upload_title_text'] = __( 'Select File', 'wpb' );

	// Upload button text
	if ( empty( $setting['upload_button_text'] ) )
		$setting['upload_button_text'] = __( 'Select File', 'wpb' );

	return $setting;

}

endif;


/**
 *
 *	Format image setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/format/image', 'wpb_setting_format_image', 5 );

if ( !function_exists( 'wpb_setting_format_image' ) ) :

function wpb_setting_format_image( $setting = array() )
{

	// Classes
	if ( empty( $setting['attr']['class'] ) )
		$setting['attr']['class'] = array( 'button' );

	// Button text
	if ( empty( $setting['button_text'] ) )
		$setting['button_text'] = __( 'Select image', 'wpb' );

	// Remove button text
	if ( empty( $setting['remove_button_text'] ) )
		$setting['remove_button_text'] = __( 'Remove image', 'wpb' );

	// Upload title text
	if ( empty( $setting['upload_title_text'] ) )
		$setting['upload_title_text'] = __( 'Select Image', 'wpb' );

	// Upload button text
	if ( empty( $setting['upload_button_text'] ) )
		$setting['upload_button_text'] = __( 'Select Image', 'wpb' );

	return $setting;

}

endif;