<?php


/**
 *
 *	Register requests
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/requests/register', function() {


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

	wpb()->register( 'admin/url', 'wpb_admin_request__url' );


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

	wpb()->register( 'admin/page', 'wpb_admin_request__page' );


	/**
	 *
	 *	Output admin page wrapper attributes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/attr', 'wpb_admin_request__page_attr', array(
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Output admin page header
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/header', 'wpb_admin_request__page_header', array(
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Output admin page content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/content', 'wpb_admin_request__page_content', array(
		'echo'   => true,
		'return' => false
	) );


	/**
	 *
	 *	Output admin page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/footer', 'wpb_admin_request__page_footer', array(
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Get admin page title
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Page title
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/title', 'wpb_admin_request__page_title' );


	/**
	 *
	 *	Get admin page description
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Page description
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/page/desc', 'wpb_admin_request__page_desc' );

	/**
	 *
	 *	Group settings
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$settings		// Settings to group
	 *	@param		boolean		$tabs			// Group for tabs
	 *
	 *	@return		array 						// Grouped settings
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/settings/grouped', 'wpb_admin_request__settings_grouped', array(
		'settings' => array(),
		'tabs'     => false
	) );


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

	wpb()->register( 'admin/settings/output', 'wpb_admin_request__settings_output', array(
		'settings' => array(),
		'grouped'  => false,
		'tabs'     => false,
		'echo'     => true,
		'return'   => false
	) );


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

	wpb()->register( 'admin/setting', 'wpb_admin_request__setting' );


	/**
	 *
	 *	Output setting field
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting		// Setting to output. Defaults to current setting.
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/field', 'wpb_admin_request__setting_field', array(
		'setting' => '',
		'return'  => false,
		'echo'    => true
	) );


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

	wpb()->register( 'admin/setting/value', 'wpb_admin_request__setting_value' );


	/**
	 *
	 *	Output current setting attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$attr			// Attributes to add
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/attr', 'wpb_admin_request__setting_attr', array(
		'attr'   => array(),
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Get current setting label
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Label
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/label', 'wpb_admin_request__setting_label' );


	/**
	 *
	 *	Get current setting description
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Description
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/desc', 'wpb_admin_request__setting_desc' );


	/**
	 *
	 *	Get current setting choices
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/choices', 'wpb_admin_request__setting_choices' );


	/**
	 *
	 *	Output current setting input attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$attr			// Attributes to add
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/input/attr', 'wpb_admin_request__setting_input_attr', array(
		'attr'   => array(),
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Get current setting input attributes
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/input/attr/get', 'wpb_admin_request__setting_input_attr_get' );


	/**
	 *
	 *	Output current setting button attributes
	 *
	 *	================================================================ 
	 *
 	 *	@param		string		$slug			// Unique slug
	 *
	 *	@param		mixed		$attr			// Attributes to add
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/button/attr', 'wpb_admin_request__setting_button_attr', array(
		'attr'   => array(),
		'slug'   => '',
		'echo'   => true,
		'return' => true
	) );


	/**
	 *
	 *	Get current setting button attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Unique slug
	 *
	 *	@return		array 						// Attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/button/attr/get', 'wpb_admin_request__setting_button_attr_get', array(
		'slug' => ''
	) );


	/**
	 *
	 *	Get current setting button text
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Text
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/button/text', 'wpb_admin_request__setting_button_text' );


	/**
	 *
	 *	Get current setting remove button text
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Text
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/button/remove/text', 'wpb_admin_request__setting_button_remove_text' );

	/**
	 *
	 *	Get current setting input ID
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// ID
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/input/id', 'wpb_admin_request__setting_input_id' );


	/**
	 *
	 *	Get current setting input attributes
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Name
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/input/name', 'wpb_admin_request__setting_input_name' );


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

	wpb()->register( 'admin/setting/data', 'wpb_admin_request__setting_data', array(
		'key' => '',
		'esc' => false
	) );

	// Wildcard
	wpb()->register( 'admin/setting/data/*', 'admin/setting/data', 'key' );


	/**
	 *
	 *	Get current file setting link
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Link/text
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/file/link', 'wpb_admin_request__setting_file_link' );


	/**
	 *
	 *	Get current image setting preview
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Image HTML
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/setting/image/preview', 'wpb_admin_request__setting_image_preview' );


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

	wpb()->register( 'admin/addon', 'wpb_admin_request__addon' );


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

	wpb()->register( 'admin/addon/name', 'wpb_admin_request__addon_name' );


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

	wpb()->register( 'admin/addon/desc', 'wpb_admin_request__addon_desc' );


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

	wpb()->register( 'admin/addon/icon', 'wpb_admin_request__addon_icon' );


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

	wpb()->register( 'admin/addon/ver', 'wpb_admin_request__addon_ver' );


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

	wpb()->register( 'admin/addon/author', 'wpb_admin_request__addon_author', array(
		'link' => true
	) );


	/**
	 *
	 *	Get current addon links
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Addon links
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/addon/links', 'wpb_admin_request__addon_links' );


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

	wpb()->register( 'admin/addon/url', 'wpb_admin_request__addon_url' );


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

	wpb()->register( 'admin/addon/url/activate', 'wpb_admin_request__addon_activate_url' );


	/**
	 *
	 *	Get current addon deactivation URL
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Deactivation URL
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/addon/url/deactivate', 'wpb_admin_request__addon_deactivate_url' );


	/**
	 *
	 *	Output current addon attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$attr			// Attributes to add
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/addon/attr', 'wpb_admin_request__addon_attr', array(
		'attr'   => array(),
		'echo'   => true,
		'return' => true
	) );


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
	 *	@param		boolean		$dismiss		// Dismissable or not
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/notification', 'wpb_admin_request__notification', array(
		'text'    => '',
		'class'   => '',
		'desc'    => '',
		'slug'    => '',
		'icon'    => '',
		'dismiss' => true,
		'attr'    => array()
	) );

	// Wildcard
	wpb()->register( 'admin/notification/*', 'admin/notification', 'class' );


	/**
	 *
	 *	Output notifications
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'admin/notifications', 'wpb_admin_request__notifications' );


});