<?php


/**
 *
 *	Get admin URL
 *
 *	================================================================ 
 *
 *	@return		string 						// URL
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__url' ) ) :

function wpb_admin_request__url()
{

	return admin_url( 'admin.php?page=wpb' );

}

endif;


/**
 *
 *	Get admin page
 *
 *	================================================================ 
 *
 *	@return		object 						// Admin page
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page' ) ) :

function wpb_admin_request__page()
{

	return wpb( ':admin/get_current_page_object' );

}

endif;


/**
 *
 *	Get admin page wrapper attributes
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_attr' ) ) :

function wpb_admin_request__page_attr( $attr = array() )
{

	$attr = wp_parse_args( $attr, array( 'class' => array() ) );

	if ( isset( $attr['echo'] ) )
		unset( $attr['echo'] );

	if ( isset( $attr['return'] ) )
		unset( $attr['return'] );

	// ID
	$attr['id'] = 'wpb-admin-page';

	// Classes
	$attr['class'] = wpb( 'classes/get', '#wpb-admin-page', $attr['class'], false );

	// Add page attributes
	$page = wpb( 'admin/page' );

	if ( $page )
		$attr = $page->get_attr( $attr );

	// Get attributes
	$attr = wpb( 'attr', $attr );

	return $attr;

}

endif;


/**
 *
 *	Output admin page header
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_header' ) ) :

function wpb_admin_request__page_header()
{

	// Get page
	$page = wpb( 'admin/page' );

	if ( !$page )
		return;

	$html = '';

	// Title
	$title = wpb( 'admin/page/title' );

	if ( $title ) {

		$html .= '<h1 id="wpb-admin-page-title">';
		$html .= $title;
		$html .= '</h1>';

	}

	// Description
	$desc = wpb( 'admin/page/desc' );

	if ( $desc ) {

		$html .= '<div id="wpb-admin-page-desc">';
		$html .= $desc;
		$html .= '</div>';

	}

	// Tabs
	$tabs = $page->get_tabs();

	if ( !empty( $tabs ) ) {

		$html .= '<h2 class="wpb-admin-page-tabs nav-tab-wrapper">';

		foreach ( $tabs as $slug => $tab ) {

			$class = 'nav-tab';

			if ( !empty( $tab['active'] ) )
				$class .= ' nav-tab-active';

			$html .= sprintf( '<a href="%1$s" class="%2$s" data-wpb-slug="%3$s">%4$s</a>', $tab['url'], $class, esc_attr( $slug ), $tab['name'] );

		}

		$html .= '</h2>';

	}

	return $html;

}

endif;


/**
 *
 *	Output admin page content
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_content' ) ) :

function wpb_admin_request__page_content()
{

	// Get page
	$page = wpb( 'admin/page' );

	if ( !$page )
		return;

	// Output content
	$page->output();

}

endif;


/**
 *
 *	Output admin page footer
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_footer' ) ) :

function wpb_admin_request__page_footer()
{

	// Get page
	$page = wpb( 'admin/page' );

	if ( !$page )
		return;

	// Output footer
	$page->output_footer();

}

endif;


/**
 *
 *	Get admin page title
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_title' ) ) :

function wpb_admin_request__page_title()
{

	// Get page
	$page = wpb( 'admin/page' );

	if ( !$page )
		return;

	$title = $page->data( 'title' );

	// Filter
	$title = apply_filters( 'wpb/admin/page/title', $title );

	// Add base
	if ( 'index' != $page->id() ) {
	
		$base_title = apply_filters( 'wpb/admin/page/title/base', __( 'WPB', 'wpb' ) );
		$base_url   = apply_filters( 'wpb/admin/page/title/base/url', wpb( 'admin/url' ) );

		$base  = sprintf( '<a href="%1$s">%2$s</a>', $base_url, $base_title );
		$title = sprintf( '<span class="wpb-page-base">%1$s</span> <span class="wpb-page-separator">&rsaquo;</span> %2$s', $base, $title );

	}

	// Wrap
	$title = sprintf( '<span class="wpb-text">%1$s</span>', $title );

	// Add icon
	$icon = $page->data( 'icon' );

	if ( $icon ) {

		$icon  = '<span class="dashicons dashicons-' . $icon . '"></span>';
		$title = sprintf( '<span class="wpb-page-icon">%1$s</span> %2$s', $icon, $title );

	}

	return $title;

}

endif;


/**
 *
 *	Get admin page description
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__page_desc' ) ) :

function wpb_admin_request__page_desc()
{

	// Get page
	$page = wpb( 'admin/page' );

	if ( !$page )
		return;

	$desc = $page->data( 'desc' );

	// Filter
	$desc = apply_filters( 'wpb/admin/page/desc', $desc );

	return $desc;

}

endif;


/**
 *
 *	Get current setting
 *
 *	================================================================ 
 *
 *	@return		object 						// Setting
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting' ) ) :

function wpb_admin_request__setting()
{

	return wpb()->data( 'admin/setting' );

}

endif;


/**
 *
 *	Get grouped settings
 *
 *	================================================================ 
 *
 *	@param		array 		$args			// Settings to group
 *	@param		boolean		$tabs			// Group for tabs
 *
 *	@return		array 						// Grouped settings
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__settings_grouped' ) ) :

function wpb_admin_request__settings_grouped( $args = array() )
{

	if ( empty( $args['settings'] ) )
		return false;

	// Get locations
	$locations = array();
	$grouped_settings = array();

	foreach ( $args['settings'] as $key => $setting ) {

		// Get group
		$group = wpb( ':settings/group', $setting->key, true );

		// Get location
		$location  = $group->key;
		$location .= '/';
		$location .= $setting->location;

		$locations[ $location ] = $setting->location;
		$grouped_settings[ $location ][] = $key;

	}

	// Tabbed
	$grouped_locations = array();

	if ( !empty( $args['tabs'] ) ) {

		$misc = array();

		foreach ( $locations as $full_key => $location ) {

			$diff = strlen( $full_key ) - strlen( $location );

			// Misc
			if ( !strstr( $location, '/' ) ) {

				$misc[ substr( $full_key, 0, $diff ) . 'misc' ][] = $full_key;
				continue;

			}

			// Get group
			$parts = explode( '/', $location );
			$first = array_shift( $parts );
			$group = substr( $full_key, 0, $diff + strlen( $first ) );

			// Add to groups
			$grouped_locations[ $group ][] = $full_key;

		}

		if ( !empty( $misc ) ) {

			$grouped_locations = $grouped_locations + $misc;

		}

	}

	// Get location names
	$names = apply_filters( 'wpb/admin/settings/locations/names', array(), $locations );

	foreach ( $locations as $location => $name ) {

		$locations[ $location ] = ( isset( $names[ $location ] ) ? $names[ $location ] : '' );

	}

	// Add together
	$grouped = array(
		'settings'  => array(
			'locations' => $locations,
			'grouped'   => $grouped_settings
		),
		'tabbed' => $grouped_locations
	);

	return $grouped;

}

endif;


/**
 *
 *	Output settings
 *
 *	================================================================ 
 *
 *	@param		array 		$settings		// Settings
 *	@param		boolean		$grouped		// Group settings
 *	@param		boolean		$tabs			// Group for tabs
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__settings_output' ) ) :

function wpb_admin_request__settings_output( $args = array() )
{

	if ( empty( $args['settings'] ) )
		return false;

	// Get grouped settings
	if ( !empty( $args['grouped'] ) ) {

		$grouped = wpb( 'admin/settings/grouped', array( 'settings' => $args['settings'], 'tabs' => $args['tabs'] ) );

		if ( empty( $grouped ) )
			return false;

		// Output
		if ( !empty( $grouped['tabbed'] ) ) {

			// Grouped locations
			foreach ( $grouped['tabbed'] as $group => $locations ) {

				$classes = array( 'wpb-grouped-settings', 'wpb-nav-tab-content' );

				$group_key  = wpb( ':settings/key', $group );
				$group_slug = sanitize_title( str_replace( '/', '-', $group_key->single ) );

				if ( is_bool( $args['tabs'] ) )
					$args['tabs'] = $group_slug;

				$active = ( $group_slug == $args['tabs'] ? true : false );

				if ( $active )
					$classes[] = 'wpb-nav-tab-content-active';

				echo '<div class="' . implode( ' ', $classes ) . '" id="wpb-settings_' . $group_slug . '" data-wpb-slug="' . esc_attr( $group_slug ) . '">';

				foreach ( $locations as $location ) {

					echo '<div class="wpb-settings">';

					if ( !empty( $grouped['settings']['locations'][ $location ] ) )
						echo '<h3 class="wpb-settings-heading">' . $grouped['settings']['locations'][ $location ] . '</h3>';

					foreach ( $grouped['settings']['grouped'][ $location ] as $id ) {

						if ( isset( $args['settings'][ $id ] ) )
							wpb( 'admin/setting/field', array( 'setting' => $args['settings'][ $id ] ) );

					}

					echo '</div>';

				}

				echo '</div>';

			} 

		} else {

			// Grouped settings
			foreach ( $grouped['settings']['grouped'] as $location => $settings ) {

				echo '<div class="wpb-settings">';

				if ( !empty( $grouped['settings']['locations'][ $location ] ) )
					echo '<h3 class="wpb-settings-heading">' . $grouped['settings']['locations'][ $location ] . '</h3>';

				foreach ( $settings as $id ) {

					if ( isset( $args['settings'][ $id ] ) )
						wpb( 'admin/setting/field', array( 'setting' => $args['settings'][ $id ] ) );

				}

				echo '</div>';

			}

		}

	} else {

		echo '<div class="wpb-settings">';

		foreach ( $args['settings'] as $key => $setting ) {

			wpb( 'admin/setting/field', array( 'setting' => $setting ) );

		}

		echo '</div>';

	}

}

endif;


/**
 *
 *	Output setting field
 *
 *	================================================================ 
 *
 *	@param		object 		$setting		// Setting to output. Defaults to current setting.
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_field' ) ) :

function wpb_admin_request__setting_field( $args = array() )
{

	// Setup setting
	if ( !empty( $args['setting'] ) && !empty( $args['setting']->template ) ) {

		// Set current setting
		wpb()->data( 'admin/setting', $args['setting'] );

	}

	if ( empty( $args['setting'] ) )
		$args['setting'] = wpb()->data( 'admin/setting' );

	if ( empty( $args['setting'] ) || empty( $args['setting']->template ) )
		return false;

	// Get template
	wpb()->file( $args['setting']->template, false, false );

}

endif;


/**
 *
 *	Get current setting value
 *
 *	================================================================ 
 *
 *	@return		mixed 						// Value
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_value' ) ) :

function wpb_admin_request__setting_value()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	if ( !isset( $_POST['wpb_settings'] ) )
		return $setting->value;

	// Get posted value
	$name = wpb( 'admin/setting/input/name' );
	$key = 'wpb_settings[';

	if ( $key == substr( $name, 0, strlen( $key ) ) ) {

		$name = substr( $name, strlen( $key ) );
		$name = substr( $name, 0, -1 );

	}

	if ( isset( $_POST['wpb_settings'][ $name ] ) )
		return $_POST['wpb_settings'][ $name ];

	return $setting->value;

}

endif;


/**
 *
 *	Output current setting attributes
 *
 *	================================================================ 
 *
 *	@param		mixed		$attr			// Attributes to add
 *
 *	@return		string						// Attributes HTML
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_attr' ) ) :

function wpb_admin_request__setting_attr( $args = array() )
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Get attributes
	$attr = wpb()->data( 'setting/' . $setting->key . '/attr' );

	if ( !$attr ) {

		$attr = array();

		// ID
		$attr['id'] = str_replace( '/', '-', $setting->key ) . '_setting';

		// Classes
		$attr['class'] = array( 'wpb-setting', 'wpb-setting_' . $setting->type );

		if ( !empty( $setting->inline ) )
			$attr['class'][] = 'wpb-setting-inline';

		// Check for errors
		if ( wpb( ':settings/get_errors', $setting->key ) )
			$attr['class'][] = 'wpb-setting-has-error';

		// Filter
		$attr = apply_filters( 'wpb/admin/setting/attr', $attr, $setting );
		$attr = apply_filters( 'wpb/admin/setting/attr/' . $setting->type, $attr, $setting );

		// Format
		$attr = wpb( 'attr/format', $attr );

		// Cache
		wpb()->data( 'setting/' . $setting->key . '/attr', $attr );

	}

	return wpb( 'attr', $args['attr'], $attr );

}

endif;


/**
 *
 *	Get current setting label
 *
 *	================================================================ 
 *
 *	@return		string						// Label
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_label' ) ) :

function wpb_admin_request__setting_label()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Cached
	$label = wpb()->data( 'setting/' . $setting->key . '/label' );

	if ( $label )
		return $label;

	$label = wpb( 'admin/setting/data/label' );

	// Add asterisk
	if ( !empty( $setting->required ) )
		$label = sprintf( '%s <span class="wpb-required">*</span>', $label );

	// Get label
	$label = apply_filters( 'wpb/admin/setting/label', $label, $setting );

	// Cache
	wpb()->data( 'setting/' . $setting->key . '/label', $label );

	return $label;

}

endif;


/**
 *
 *	Get current setting description
 *
 *	================================================================ 
 *
 *	@return		string						// Description
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_desc' ) ) :

function wpb_admin_request__setting_desc()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Cached
	$desc = wpb()->data( 'setting/' . $setting->key . '/desc' );

	if ( $desc )
		return $desc;

	// Get description
	$desc = apply_filters( 'wpb/admin/setting/desc', wpb( 'admin/setting/data/desc' ), $setting );

	if ( $desc )
		$desc = wpautop( $desc );

	// Cache
	wpb()->data( 'setting/' . $setting->key . '/desc', $desc );

	return $desc;

}

endif;


/**
 *
 *	Get current setting choices
 *
 *	================================================================ 
 *
 *	@return		array						// Choices
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_choices' ) ) :

function wpb_admin_request__setting_choices()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Cached
	$choices = wpb()->data( 'setting/' . $setting->key . '/choices' );

	if ( $choices )
		return $choices;

	// Get current value
	$value = wpb( 'admin/setting/value' );

	// Get choices
	$choices = wpb( 'admin/setting/data/choices' );

	if ( empty( $choices ) )
		$choices = array();

	$i = 1;

	foreach ( $choices as $choice_value => $choice ) {

		// Check if checked
		$checked = false;

		if ( !is_array( $value ) ) {

			if ( $choice_value == $value )
				$checked = true;

		} elseif ( in_array( $choice_value, $value ) ) {

			$checked = true;

		}

		// Get attributes
		$attr = array( 
			'class' => array(
				'wpb-setting-choice'
			)
		);

		if ( !empty( $choice['attr'] ) ) {

			if ( !empty( $choice['attr']['class'] ) ) {

				if ( !is_array( $choice['attr']['class'] ) )
					$choice['attr']['class'] = explode( ' ', $choice['attr']['class'] );

				$attr['class'] = array_merge( $attr['class'], $choice['attr']['class'] );

				unset( $choice['attr']['class'] );

			}

			$attr = array_merge( $attr, $choice['attr'] );

		}

		// Get choice
		$choice = array_merge( $choice, array(
			'attr'    => wpb( 'attr', $attr ),
			'value'   => $choice_value,
			'name'    => wpb( 'admin/setting/input/name' ),
			'id'      => wpb( 'admin/setting/input/id' ) . '-' . $i,
			'checked' => $checked
		) );

		$choices[ $choice_value ] = (object) $choice;

		$i++;

	}

	// Cache
	wpb()->data( 'setting/' . $setting->key . '/choices', $choices );

	return $choices;

}

endif;


/**
 *
 *	Get current setting data
 *
 *	================================================================ 
 *
 *	@param		string		$key			// Data key
 *	@param		boolean		$esc			// Escape for HTML
 *
 *	@return		mixed						// Data
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_data' ) ) :

function wpb_admin_request__setting_data( $args = array() )
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	$data = (array) $setting;

	if ( empty( $args['key'] ) )
		return $data;

	if ( !isset( $data[ $args['key'] ] ) )
		return;

	if ( !empty( $args['esc'] ) )
		return esc_attr( $data[ $args['key'] ] );

	return $data[ $args['key'] ];

}

endif;


/**
 *
 *	Output current setting input attributes
 *
 *	================================================================ 
 *
 *	@param		array 		$attr			// Attributes to add
 *
 *	@return		string						// Attribute(s)
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_input_attr' ) ) :

function wpb_admin_request__setting_input_attr( $args = array() )
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	return wpb( 'attr', $args['attr'], wpb( 'admin/setting/input/attr/get' ) );

}

endif;


/**
 *
 *	Get current setting input attributes
 *
 *	================================================================ 
 *
 *	@return		array						// Attribute(s)
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_input_attr_get' ) ) :

function wpb_admin_request__setting_input_attr_get()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Get attributes
	$attr = wpb()->data( 'setting/' . $setting->key . '/input/attr' );

	if ( !$attr ) {

		// Get from setting
		$attr = $setting->attr;

		// Filter
		$attr = apply_filters( 'wpb/admin/setting/input/attr/' . $setting->type, $attr, $setting );

		// Set ID and name
		$attr['id']   = wpb( 'admin/setting/input/id' );
		$attr['name'] = wpb( 'admin/setting/input/name' );

		// Format
		$attr = wpb( 'attr/format', $attr );

		// Cache
		wpb()->data( 'setting/' . $setting->key . '/input/attr', $attr );

	}

	return $attr;

}

endif;


/**
 *
 *	Output current setting button attributes
 *
 *	================================================================ 
 *
 *	@param		array 		$attr			// Attributes to add
 *	@param		string		$slug			// Unique slug
 *
 *	@return		string						// Attribute(s)
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_button_attr' ) ) :

function wpb_admin_request__setting_button_attr( $args = array() )
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	return wpb( 'attr', $args['attr'], wpb( 'admin/setting/button/attr/get', 'slug=' . $args['slug'] ) );

}

endif;


/**
 *
 *	Get current setting button attributes
 *
 *	================================================================ 
 *
 *	@param		string		$slug			// Unique slug
 *
 *	@return		array						// Attribute(s)
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_button_attr_get' ) ) :

function wpb_admin_request__setting_button_attr_get( $args = array() )
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Get attributes
	$attr = wpb()->data( 'setting/' . $setting->key . '/button/attr' . ( !empty( $args['slug'] ) ? '/' . $args['slug'] : '' ) );

	if ( !$attr ) {

		// Get from setting
		$attr = $setting->attr;

		// Filter
		$attr = apply_filters( 'wpb/admin/setting/button/attr/' . $setting->type, $attr, $setting, $args['slug'] );

		// Format
		$attr = wpb( 'attr/format', $attr );

		// Cache
		wpb()->data( 'setting/' . $setting->key . '/button/attr' . ( !empty( $args['slug'] ) ? '/' . $args['slug'] : '' ), $attr );

	}

	return $attr;

}

endif;


/**
 *
 *	Get current setting button text
 *
 *	================================================================ 
 *
 *	@return		string						// Text
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_button_text' ) ) :

function wpb_admin_request__setting_button_text()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	return ( isset( $setting->button_text ) ? $setting->button_text : '' );

}

endif;


/**
 *
 *	Get current setting remove button text
 *
 *	================================================================ 
 *
 *	@return		string						// Text
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_button_remove_text' ) ) :

function wpb_admin_request__setting_button_remove_text()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	return ( isset( $setting->remove_button_text ) ? $setting->remove_button_text : __( 'Remove', 'wpb' ) );

}

endif;


/**
 *
 *	Get current setting input ID
 *
 *	================================================================ 
 *
 *	@return		string						// ID
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_input_id' ) ) :

function wpb_admin_request__setting_input_id()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Cached
	$id = wpb()->data( 'setting/' . $setting->key . '/input/id' );

	if ( $id )
		return $id;

	// Get from setting
	if ( !empty( $setting->attr['id'] ) )
		$id = $setting->attr['id'];

	// Get from key
	else
		$id = str_replace( '/', '-', $setting->key );

	// Filter
	$id = apply_filters( 'wpb/admin/setting/id', $id, $setting );
	$id = esc_attr( $id );

	// Cache
	wpb()->data( 'setting/' . $setting->key . '/input/id' );

	return $id;

}

endif;


/**
 *
 *	Get current setting input name
 *
 *	================================================================ 
 *
 *	@return		string						// Name
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_input_name' ) ) :

function wpb_admin_request__setting_input_name()
{

	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	// Cached
	$name = wpb()->data( 'setting/' . $setting->key . '/input/name' );

	if ( $name )
		return $name;

	// Get from setting
	if ( !empty( $setting->attr['name'] ) )
		$name = $setting->attr['name'];

	// Get from key
	else
		$name = 'wpb_settings[' . $setting->key . ']';

	// Filter
	$name = apply_filters( 'wpb/admin/setting/name', $name, $setting );
	$name = esc_attr( $name );

	// Cache
	wpb()->data( 'setting/' . $setting->key . '/input/name' );

	return $name;

}

endif;


/**
 *
 *	Get current file setting link
 *
 *	================================================================ 
 *
 *	@return		string						// Link/text
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_file_link' ) ) :

function wpb_admin_request__setting_file_link()
{

	// Get setting
	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	if ( 'file' != $setting->type )
		return false;

	// Get value
	$value = wpb( 'admin/setting/value' );

	if ( !$value )
		return false;

	// Get attachment
	$url = wp_get_attachment_url( $value );

	if ( !$url )
		return false;

	return sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_attr( $url ), get_the_title( $value ) );

}

endif;


/**
 *
 *	Get current image setting preview
 *
 *	================================================================ 
 *
 *	@return		string						// Link/text
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__setting_image_preview' ) ) :

function wpb_admin_request__setting_image_preview()
{

	// Get setting
	$setting = wpb()->data( 'admin/setting' );

	if ( !$setting )
		return false;

	if ( 'image' != $setting->type )
		return false;

	// Get value
	$value = wpb( 'admin/setting/value' );

	if ( !$value )
		return false;

	// Get attachment
	$src = wp_get_attachment_image_src( $value, 'medium' );
	
	if ( empty( $src[0] ) )
		return;

	return sprintf( '<img src="%s" />', esc_attr( $src[0] ) );

}

endif;


/**
 *
 *	Get current addon
 *
 *	================================================================ 
 *
 *	@return		object 						// Addon
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon' ) ) :

function wpb_admin_request__addon()
{

	return wpb()->data( 'admin/addon' );

}

endif;


/**
 *
 *	Get current addon name
 *
 *	================================================================ 
 *
 *	@return		string 						// Addon name
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_name' ) ) :

function wpb_admin_request__addon_name()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	return $addon->name();

}

endif;


/**
 *
 *	Get current addon description
 *
 *	================================================================ 
 *
 *	@return		string 						// Addon description
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_desc' ) ) :

function wpb_admin_request__addon_desc()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	$desc = $addon->data( 'desc' );

	if ( !$desc )
		return;

	return wpautop( $desc );

}

endif;


/**
 *
 *	Get current addon version
 *
 *	================================================================ 
 *
 *	@return		string 						// Addon version
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_ver' ) ) :

function wpb_admin_request__addon_ver()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	$ver = $addon->data( 'ver' );

	if ( !$ver )
		$ver = '1.0.0';

	return $ver;

}

endif;


/**
 *
 *	Get current addon icon
 *
 *	================================================================ 
 *
 *	@return		string 						// Addon icon
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_icon' ) ) :

function wpb_admin_request__addon_icon()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	$icon = $addon->data( 'icon' );

	if ( !$icon )
		$icon = 'admin-plugin';

	return '<span class="dashicons dashicons-' . esc_attr( $icon ) . '"></span>';

}

endif;


/**
 *
 *	Get current addon author
 *
 *	================================================================ 
 *
 *	@param		boolean		$link			// Link to author URL
 *
 *	@return		string 						// Addon author
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_author' ) ) :

function wpb_admin_request__addon_author( $args = array() )
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	// Get author
	$author = $addon->data( 'author' );

	if ( !$author )
		return false;

	if ( empty( $args['link'] ) )
		return $author;

	$link = $addon->data( 'author_url' );

	if ( !$link )
		return $author;

	return sprintf( '<a class="wpb-addon-author-link" href="%1$s">%2$s</a>', esc_attr( $link ), $author );

}

endif;


/**
 *
 *	Get current addon links
 *
 *	================================================================ 
 *
 *	@return		array 						// Addon links
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_links' ) ) :

function wpb_admin_request__addon_links()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	$pages = wpb( ':addons/admin_links', $addon );

	if ( empty( $pages ) )
		return false;

	// Create HTML links
	$html = array();

	foreach ( $pages as $slug => $page ) {

		$name = $page['name'];

		if ( !empty( $page['icon'] ) )
			$name = sprintf( '<span class="dashicons dashicons-%1$s"></span> %2$s', $page['icon'], $name );

		$class = 'wpb-addon-link button button-secondary';

		if ( empty( $page['inactive'] ) )
			$class .= ' wpb-hide-if-inactive';

		$html[ $slug ] = sprintf( '<a href="%1$s" class="%3$s">%2$s</a>', esc_attr( $page['url'] ), $name, $class );

	}

	return implode( ' ', $html );

}

endif;


/**
 *
 *	Get current addon URL
 *
 *	================================================================ 
 *
 *	@return		string 						// Addon URL
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_url' ) ) :

function wpb_admin_request__addon_url()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	return $addon->admin_url();

}

endif;


/**
 *
 *	Get current addon activation URL
 *
 *	================================================================ 
 *
 *	@return		string 						// Activation URL
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_activate_url' ) ) :

function wpb_admin_request__addon_activate_url()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	return $addon->activate_url();

}

endif;


/**
 *
 *	Get current addon deactivation URL
 *
 *	================================================================ 
 *
 *	@return		string 						// Activation URL
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_deactivate_url' ) ) :

function wpb_admin_request__addon_deactivate_url()
{

	// Get addon
	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	return $addon->deactivate_url();

}

endif;


/**
 *
 *	Output current addon attributes
 *
 *	================================================================ 
 *
 *	@param		mixed		$attr			// Attributes to add
 *
 *	@return		string						// Attributes HTML
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__addon_attr' ) ) :

function wpb_admin_request__addon_attr( $args = array() )
{

	$addon = wpb()->data( 'admin/addon' );

	if ( !$addon )
		return false;

	$attr = array();

	// Data
	$attr['data'] = array( 'wpb-addon' => $addon->id() );

	// Classes
	$attr['class'] = array( 'wpb-addon' );

	if ( $addon->is_active() )
		$attr['class'][] = 'wpb-addon-active';

	else
		$attr['class'][] = 'wpb-addon-inactive';

	// Return attributes
	return wpb( 'attr', $args['attr'], $attr );

}

endif;


/**
 *
 *	Add notification
 *
 *	================================================================ 
 *
 *	@param		string		$text			// Notification text
 *	@param		string		$class 			// Notification class
 *	@param		string		$desc			// Notification description
 *	@param		string		$slug			// Unique slug
 *	@param		string		$icon			// Icon class
 *	@param		boolean		$dismiss		// Dismissible or not
 *	@param		array 		$attr			// HTML attributes
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__notification' ) ) :

function wpb_admin_request__notification( $args )

{

	// Get existing notifications
	$notifications = wpb()->data( 'admin/notifications' );

	if ( !$notifications )
		$notifications = array();

	// Get slug
	$slug = ( !empty( $args['slug'] ) ? $args['slug'] : sanitize_title( $args['text'] ) );

	// Add notification
	$notifications[ $slug ] = $args;

	// Set
	wpb()->data( 'admin/notifications', $notifications );

}

endif;


/**
 *
 *	Output notifications
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_admin_request__notifications' ) ) :

function wpb_admin_request__notifications()
{

	// Get notifications
	$notifications = wpb()->data( 'admin/notifications' );

	if ( empty( $notifications ) )
		return;

	echo '<div class="wpb-notifications">';

	foreach ( $notifications as $notification ) {

		echo wpb( 'notification', $notification );

	}

	echo '</div>';

}

endif;