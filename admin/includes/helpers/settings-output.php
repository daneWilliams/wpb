<?php


/**
 *
 *	Modify settings tab name
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/settings/tab/name', 'wpb_modify_settings_tab_name', 5, 2 );

if ( !function_exists( 'wpb_modify_setting_location_names' ) ) :

function wpb_modify_settings_tab_name( $name, $slug = '' )
{

	switch ( $slug ) {

		// Images
		case 'images' :
			$name = __( 'Images', 'wpb' );
		break;

		// Content
		case 'content' :
			$name = __( 'Content', 'wpb' );
		break;

		// Layout
		case 'layout' :
			$name = __( 'Layout', 'wpb' );
		break;

		// Assets
		case 'assets' :
			$name = __( 'Assets', 'wpb' );
		break;

	}

	return $name;

}

endif;


/**
 *
 *	Modify index settings links
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/index/settings/links', 'wpb_modify_index_settings_links', 5 );

if ( !function_exists( 'wpb_modify_index_settings_links' ) ) :

function wpb_modify_index_settings_links( $links )
{

	foreach ( $links as $slug => $link ) {

		switch ( $slug ) {

			// Images
			case 'images' :

				// Icon
				$links[ $slug ]['icon'] = 'format-gallery';

				// Description
				$links[ $slug ]['desc'] = __( 'Thumbnail styles, banner images and custom headers', 'wpb' );

			break;

			// Content
			case 'content' :

				// Icon
				$links[ $slug ]['icon'] = 'welcome-write-blog';

				// Description
				$links[ $slug ]['desc'] = __( 'Excerpts and archive pages', 'wpb' );

			break;

			// Layout
			case 'layout' :

				// Icon
				$links[ $slug ]['icon'] = 'layout';

				// Description
				$links[ $slug ]['desc'] = __( 'Sidebar position and header &amp; footer widgets', 'wpb' );

			break;

			// Assets
			case 'assets' :

				// Icon
				$links[ $slug ]['icon'] = 'admin-appearance';

				// Description
				$links[ $slug ]['desc'] = __( 'CSS and JavaScript', 'wpb' );

			break;

		}

	}

	return $links;

}

endif;


/**
 *
 *	Modify setting location names
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/settings/locations/names', 'wpb_modify_setting_location_names', 5, 2 );

if ( !function_exists( 'wpb_modify_setting_location_names' ) ) :

function wpb_modify_setting_location_names( $names = array(), $locations = array() )
{

	// Images
	$names['plugin/images/thumbnails']    = __( 'Thumbnails', 'wpb' );
	$names['plugin/images/banners']       = __( 'Banners', 'wpb' );
	$names['plugin/images/custom-header'] = __( 'Custom Header', 'wpb' );

	// Content
	$names['plugin/content/excerpts']  = __( 'Excerpts', 'wpb' );
	$names['plugin/content/404']       = __( '404 Page', 'wpb' );
	$names['plugin/content/search']    = __( 'Search Page', 'wpb' );
	$names['plugin/content/term']      = __( 'Term Archive', 'wpb' );
	$names['plugin/content/post-type'] = __( 'Post Type Archive', 'wpb' );
	$names['plugin/content/author']    = __( 'Author Archive', 'wpb' );

	// Layout
	$names['plugin/layout/sidebar'] = __( 'Sidebar', 'wpb' );
	$names['plugin/layout/widgets'] = __( 'Widgets', 'wpb' );

	// Assets
	$names['plugin/assets/css'] = __( 'CSS', 'wpb' );
	$names['plugin/assets/js']  = __( 'JavaScript', 'wpb' );

	return $names;

}

endif;


/**
 *
 *	Modify text attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/text', 'wpb_modify_text_attr', 5, 2 );
add_filter( 'wpb/admin/setting/input/attr/icon', 'wpb_modify_text_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_text_attr' ) ) :

function wpb_modify_text_attr( $attr = array(), $setting )
{

	// Value
	$attr['value'] = wpb( 'admin/setting/value' );

	if ( is_array( $attr['value'] ) ) {

		$attr['value']    = serialize( $attr['value'] );
		$attr['disabled'] = true;

	}

	return $attr;

}

endif;


/**
 *
 *	Modify code attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/code', 'wpb_modify_code_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_code_attr' ) ) :

function wpb_modify_code_attr( $attr = array(), $setting )
{

	if ( empty( $setting->lang ) )
		return $attr;

	// Language
	if ( empty( $attr['data-wpb-lang'] ) )
		$attr['data-wpb-lang'] = $setting->lang;

	return $attr;

}

endif;


/**
 *
 *	Modify number attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/number', 'wpb_modify_number_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_number_attr' ) ) :

function wpb_modify_number_attr( $attr = array(), $setting )
{

	// Value
	$attr['value'] = (int) wpb( 'admin/setting/value' );

	return $attr;

}

endif;


/**
 *
 *	Modify boolean attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/boolean', 'wpb_modify_boolean_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_boolean_attr' ) ) :

function wpb_modify_boolean_attr( $attr = array(), $setting )
{

	// Value
	$attr['value'] = '1';

	// Checked
	if ( !empty( wpb( 'admin/setting/value' ) ) )
		$attr['checked'] = 'checked';

	return $attr;

}

endif;


/**
 *
 *	Modify file setting attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/attr/file', 'wpb_modify_file_setting_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_file_setting_attr' ) ) :

function wpb_modify_file_setting_attr( $attr = array(), $setting )
{

	$value = wpb( 'admin/setting/value' );

	if ( $value )
		$attr['class'][] = 'wpb-file-selected';

	return $attr;

}

endif;


/**
 *
 *	Modify file input attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/file', 'wpb_modify_file_input_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_file_input_attr' ) ) :

function wpb_modify_file_input_attr( $attr = array(), $setting )
{

	// Hidden
	$attr['type'] = 'hidden';

	// Classes
	$attr['class'][] = 'wpb-file-id';

	// Value
	$attr['value'] = wpb( 'admin/setting/value' );

	if ( is_array( $attr['value'] ) ) {

		$attr['value']    = serialize( $attr['value'] );
		$attr['disabled'] = true;

	}

	return $attr;

}

endif;


/**
 *
 *	Modify file button attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/button/attr/file', 'wpb_modify_file_button_attr', 5, 3 );

if ( !function_exists( 'wpb_modify_file_button_attr' ) ) :

function wpb_modify_file_button_attr( $attr = array(), $setting, $slug = '' )
{

	// Button type
	$attr['type'] = 'button';

	// Remove name
	$attr['name'] = '';

	// Classes
	$attr['class'][] = 'button';
	$attr['class'][] = 'button-secondary';

	if ( 'remove' != $slug )
		$attr['class'][] = 'wpb-file-select';

	else
		$attr['class'][] = 'wpb-file-remove';

	// Data
	$attr['data-wpb-target'] = '#' . wpb( 'admin/setting/input/id' );

	if ( 'remove' != $slug ) {

		$attr['data-wpb-title-text']  = ( !empty( $setting->upload_title_text )  ? $setting->upload_title_text  : __( 'Select File', 'wpb' ) );
		$attr['data-wpb-button-text'] = ( !empty( $setting->upload_button_text ) ? $setting->upload_button_text : __( 'Select File', 'wpb' ) );

	}

	return $attr;

}

endif;


/**
 *
 *	Modify image setting attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/attr/image', 'wpb_modify_image_setting_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_image_setting_attr' ) ) :

function wpb_modify_image_setting_attr( $attr = array(), $setting )
{

	$value = wpb( 'admin/setting/value' );

	if ( $value )
		$attr['class'][] = 'wpb-image-selected';

	return $attr;

}

endif;


/**
 *
 *	Modify image input attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/input/attr/image', 'wpb_modify_image_input_attr', 5, 2 );

if ( !function_exists( 'wpb_modify_image_input_attr' ) ) :

function wpb_modify_image_input_attr( $attr = array(), $setting )
{

	// Hidden
	$attr['type'] = 'hidden';

	// Classes
	$attr['class'][] = 'wpb-image-id';

	// Value
	$attr['value'] = wpb( 'admin/setting/value' );

	if ( is_array( $attr['value'] ) ) {

		$attr['value']    = serialize( $attr['value'] );
		$attr['disabled'] = true;

	}

	return $attr;

}

endif;


/**
 *
 *	Modify image button attributes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/setting/button/attr/image', 'wpb_modify_image_button_attr', 5, 3 );

if ( !function_exists( 'wpb_modify_image_button_attr' ) ) :

function wpb_modify_image_button_attr( $attr = array(), $setting, $slug = '' )
{

	// Button type
	$attr['type'] = 'button';

	// Remove name
	$attr['name'] = '';

	// Classes
	$attr['class'][] = 'button';
	$attr['class'][] = 'button-secondary';

	if ( 'remove' != $slug )
		$attr['class'][] = 'wpb-image-select';

	else
		$attr['class'][] = 'wpb-image-remove';

	// Data
	$attr['data-wpb-target'] = '#' . wpb( 'admin/setting/input/id' );

	if ( 'remove' != $slug ) {

		$attr['data-wpb-title-text']  = ( !empty( $setting->upload_title_text )  ? $setting->upload_title_text  : __( 'Select Image', 'wpb' ) );
		$attr['data-wpb-button-text'] = ( !empty( $setting->upload_button_text ) ? $setting->upload_button_text : __( 'Select Image', 'wpb' ) );

	}

	return $attr;

}

endif;


/**
 *
 *	Output widgets layout
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/admin/after/setting/label', 'wpb_admin_output_widgets_layout_select', 5 );

if ( !function_exists( 'wpb_admin_output_widgets_layout_select' ) ) :

function wpb_admin_output_widgets_layout_select()
{

	$id = wpb( 'admin/setting/input/id' );

	if ( !in_array( $id, array( 'plugin-header-widgets_layout', 'plugin-footer-widgets_layout' ) ) )
		return;

	echo '<div class="wpb-widgets-layout-select hide-if-no-js">';
	echo '<label>';

	_e( 'Preview areas:', 'wpb' );
	echo ' <select><option>4</option><option>3</option><option>2</option></select>';

	echo '</label>';
	echo '</div>';

}

endif;


/**
 *
 *	Output setting description
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/admin/after/setting/input', 'wpb_admin_output_setting_desc', 5 );

if ( !function_exists( 'wpb_admin_output_setting_desc' ) ) :

function wpb_admin_output_setting_desc()
{

	$desc = wpb( 'admin/setting/desc' );

	if ( !$desc )
		return;

	echo '<div class="wpb-setting-desc">' . $desc . '</div>';

}

endif;


/**
 *
 *	Output setting errors
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/admin/after/setting/input', 'wpb_admin_output_setting_errors', 100 );

if ( !function_exists( 'wpb_admin_output_setting_errors' ) ) :

function wpb_admin_output_setting_errors()
{

	// Get setting
	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Get errors
	$errors = wpb( ':settings/get_errors', $setting->key );

	if ( empty( $errors ) )
		return false;

	echo '<div class="wpb-setting-errors">';

	foreach ( $errors as $error ) {

		echo wpb( 'notification/error', array(
			'text' => $error,
			'dismiss' => false
		) );

	}

	echo '</div>';

}

endif;