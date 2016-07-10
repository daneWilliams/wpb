<?php


namespace WPB;


/**
 *
 *	Addon object template
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 *	@see		WPB\Object
 *
 */


class Addon extends Object
{


	// Addon data
	protected $_type = 'addon';


	/**
	 *
	 *	Setup addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		array 		$data			// Addon data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $slug, $data = array() )
	{

		parent::__construct( $slug, $data );

	}


	/**
	 *
	 *	Fired when the addon is initialised
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init() {}


	/**
	 *
	 *	Fired when the addon is activated
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function activate() {}


	/**
	 *
	 *	Fired when the addon is deactivated
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function deactivate() {}


	/**
	 *
	 *	Fired when the admin page is initialised
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$page			// Admin page
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_page_init( $page = '' ) {}


	/**
	 *
	 *	Fired when data is reset
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$addon_settings		// Addon reset settings
	 *	@param		array 		$reset_settings		// All reset settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function reset_data( $addon_settings = array(), $reset_settings = array() ) {}


	/**
	 *
	 *	Register requests
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_requests() {}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_settings() {}


	/**
	 *
	 *	Register a request
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$request		// Request string
	 *	@param		string		$callback		// Callback function
	 *	@param		array 		$args			// Request arguments
	 *	@param		boolean		$shortcode		// Enable shortcode
	 *
	 *	@see		WPB::register()
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_request( $request, $callback, $args = array(), $shortcode = false )
	{

		$request  = $this->id() . '/' . ltrim( $request, '/' );
		$callback = ( strstr( $request, '*' ) ? $this->id() . '/' . $callback : array( $this, $callback ) );

		return wpb()->register( $request, $callback, $args, $shortcode );

	}


	/**
	 *
	 *	Register a setting
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		array		$data			// Setting data
	 *	@param		array 		$default		// Default value
	 *
	 *	@see		WPB\Settings::register()
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_setting( $key, $data = array(), $default = NULL )
	{

		return wpb( ':addons/register_setting', $this->id(), $key, $data, $default );

	}


	/**
	 *
	 *	Check if addon is active
	 *
	 *	================================================================ 
	 *
	 *	@return		boolean						// Active or not
	 *
	 *	@see		WPB\Addons::is_active()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function is_active()
	{
	
		return wpb( ':addons/is_active', $this->id() );
	
	}


	/**
	 *
	 *	Get setting(s)
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key(s)
	 *	@param		mixed		$default		// Default value
	 *	@param		boolean		$objects		// Return objects
	 *	@param		boolean		$format			// Format values
	 *
	 *	@return		mixed						// Setting(s)
	 *
	 *	@see		WPB\Settings::get()
	 *
	 *	@since		1.0.0
	 *
	 */

	public function settings( $key = '', $default = NULL, $objects = false, $format = true ) 
	{

		return wpb( ':settings/get', $key, $default, $objects, $format, 'addon/' . $this->id() );

	}


	/**
	 *
	 *	Check if a setting value has been chosen
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$key			// Setting key
	 *	@param		mixed		$value			// Value to check
	 *	@param		boolean		$default		// Default return value if setting doesn't exist
	 *
	 *	@return		boolean						// Chosen or not
	 *
	 *	@see		WPB\Settings::choice()
	 *
	 *	@since		1.0.0
	 *
	 */

	public function choice( $key, $value = true, $default = false ) 
	{

		return wpb( ':settings/choice', $key, $value, $default, 'addon/' . $this->id() );

	}


	/**
	 *
	 *	Check if addon has settings
	 *
	 *
	 *	This function is only used for situations when the addon might be inactive or otherwise unavailable and the check is not critical. 
	 * 	At all other times, the	existence of settings is determined by whether any have been registered.
	 *	Returning false will also trigger the check for registered settings.
	 *
	 *	================================================================ 
	 *
	 *	@return		boolean						// Has settings or not
	 *
	 *	@see		WPB\Addons::has_settings()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function has_settings()
	{

		return false;
	
	}


	/**
	 *
	 *	Get addon name
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// Name
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function name()
	{

		$name = $this->data( 'name' );

		if ( $name )
			return $name;

		return $this->id();

	}


	/**
	 *
	 *	Get admin URL
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$append			// String/page to append
	 *
	 *	@return		string						// URL
	 *
	 *	@see		WPB\Addons::admin_url()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_url( $append = '' )
	{

		return wpb( ':addons/admin_url', $this->id(), $append );

	}


	/**
	 *
	 *	Get activation URL
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$page			// Addon page slug
	 *
	 *	@return		string						// URL
	 *
	 *	@see		WPB\Addons::activate_url()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function activate_url( $page = '' )
	{

		return wpb( ':addons/activate_url', $this->id(), $page );

	}


	/**
	 *
	 *	Get deactivation URL
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$page			// Addon page slug
	 *
	 *	@return		string						// URL
	 *
	 *	@see		WPB\Addons::deactivate_url()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function deactivate_url( $page = '' )
	{

		return wpb( ':addons/deactivate_url', $this->id(), $page );

	}


	/**
	 *
	 *	Get admin pages
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Pages
	 *
	 *	@see		WPB\Addons::admin_pages()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_pages()
	{

		return array();

	}


	/**
	 *
	 *	Get admin links
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Links
	 *
	 *	@see		WPB\Addons::admin_links()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_links()
	{

		return array();

	}


	/**
	 *
	 *	Output admin page content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_admin_page() 
	{

		wpb()->file( 'admin/templates/addons/addon-single', false, false );

	}


	/**
	 *
	 *	Get admin page attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$page			// Admin page
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_admin_page_attr( $page = '' ) {}


}