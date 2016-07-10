<?php


/**
 *
 *	WPB class
 *
 *	================================================================ 
 *
 *	@version	1.0.0
 *
 *	@package	WPB
 *
 */


class WPB
{

	// Core classes
	protected $classes = array(
		'router'   => 'WPB\Router',
		'data'     => 'WPB\Data',
		'settings' => 'WPB\Settings',
		'addons'   => 'WPB\Addons',
		'admin'    => 'WPB\Admin'
	);

	protected $instances;

	// Nonce
	protected $nonce = '_wpbnonce';

	// Data
	protected $name;
	protected $slug;
	protected $ver;
	protected $file;
	protected $dir_path;
	protected $dir_url;


	/**
	 *
	 *	Setup the plugin
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $file = '', $slug = '', $name = '', $ver = '1.0.0' )
	{

		// Set data
		$this->file = ( $file ? $file : __FILE__ );

		$this->dir_path = str_replace( '\\', '/', plugin_dir_path( $file ) );
		$this->dir_url  = str_replace( '\\', '/', plugin_dir_url(  $file ) );

		$this->slug = $slug;
		$this->name = $name;
		$this->ver  = $ver;

		// Load
		add_action( 'plugins_loaded', array( $this, 'load' ), 5 );

		// Setup
		add_action( 'after_setup_theme', array( $this, 'setup' ), 5 );

		// Initialise
		add_action( 'init', array( $this, 'init' ), 5 );

	}


	/**
	 *
	 *	Load WPB
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function load()
	{

		// Load core classes
		foreach ( $this->classes as $slug => $class_name ) {

			// Get file(s)
			switch ( $slug ) {

				// Admin
				case 'admin' :

					$this->file( 'admin/class-admin' );

				break;

			}

			// Load class
			if ( empty( $this->instances[ $slug ] ) )
				$this->instances[ $slug ] = new $class_name;

		}

		// Load objects
		$this->file( 'includes/wpb-object' );
		$this->file( 'includes/wpb-object-addon' );
		$this->file( 'includes/wpb-widget' );

		// Load helpers
		$this->file( 'includes/helpers/plugin' );
		$this->file( 'includes/helpers/theme' );

		// Callback
		do_action( 'wpb/load' );

	}


	/**
	 *
	 *	Setup WPB
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function setup()
	{

		// Callback
		do_action( 'wpb/setup' );

	}


	/**
	 *
	 *	Initialise WPB
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init()
	{

		// Callback
		do_action( 'wpb/init' );

	}



	/**
	 *
	 *	Process a request
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request( $args = array() )
	{

		if ( empty( $args ) )
			return false;

		// Check for core class
		if ( is_string( $args ) )
			$args = array( $args );

		$first = reset( $args );

		// Perform request
		if ( !is_string( $first ) || ':' != substr( $first, 0, 1 ) )
			return $this->get_class( 'router' )->request( $args );

		// Get method
		$first  = array_shift( $args );
		$parts  = explode( '/', substr( $first, 1 ) );

		$class  = array_shift( $parts );
		$method = ( !empty( $parts ) ? implode( '_', $parts ) : '' );

		if ( !isset( $this->classes[ $class ] ) )
			return false;

		// Get class
		$instance = $this->get_class( $class );

		// Return class
		if ( !$method )
			return $instance;

		// Check method exists
		if ( !method_exists( $this->classes[ $class ], $method ) )
			return false;

		return call_user_func_array( array( $instance, $method ), $args );
	
	}


	/**
	 *
	 *	Register a request
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$request		// Request string
	 *	@param		string		$callback		// Callback function
	 *	@param		array 		$args			// Request arguments
	 *	@param		boolean		$shortcode		// Register shortcode request
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register( $request, $callback, $args = array(), $shortcode = false )
	{

		return $this->get_class( 'router' )->register( $request, $callback, $args, $shortcode );

	}


	/**
	 *
	 *	Get directory path
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$append			// Path to append
	 *
	 *	@return		string						// Path
	 *
	 *	@since		1.0.0
	 *
	 */

	public function dir( $append = '' )
	{

		$path = $this->dir_path;

		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}


	/**
	 *
	 *	Get URL path
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$append			// Path to append
	 *
	 *	@return		string						// Path
	 *
	 *	@since		1.0.0
	 *
	 */

	public function url( $append = '' )
	{

		$path = $this->dir_url;

		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}


	/**
	 *
	 *	Load a plugin file
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$path			// Path to file
	 *	@param		boolean		$theme			// Also check in theme folder
	 *	@param		boolean		$once			// Include once
	 *
	 *	@return		string						// Full path to the file, or false if not found
	 *
	 *	@since		1.0.0
	 *
	 */

	public function file( $path, $theme = false, $once = true )
	{

		// Add file extension
		if ( !substr( strchr( $path, '.' ), 1 ) )
			$path .= '.php';

		// Get root and file path
		$path_parts = explode( '/', $path );
		$root_path  = array_shift( $path_parts );

		if ( empty( $path_parts ) ) {

			$root_path = 'includes';
			$file_path = trim( $path, '/' );

		} else {

			$file_path = rtrim( implode( '/', $path_parts ), '/' );

			// Add 'includes' root folder
			if ( !file_exists( $this->dir( $root_path ) ) ) {

				if ( file_exists( $this->dir( 'includes/' . $root_path ) ) ) {

					$file_path = $root_path . '/' . $file_path;
					$root_path = 'includes';

				}

			}

		}

		// Check theme first
		if ( $theme ) {

			// Ignore includes path
			$theme_path = ( 'includes' != $root_path ? $root_path . '/' : '' ) . $file_path;

			// Get file
			$found = locate_template( $theme_path, true, $once );

			if ( !$found )
				return false;

			return $found;

		}

		// Check plugin
		$plugin_path = $this->dir( $root_path . '/' . $file_path );

		if ( !file_exists( $plugin_path ) )
			return false;

		// Get file
		if ( $once )
			require_once $plugin_path;

		else
			require $plugin_path;

		return $plugin_path;

	}


	/**
	 *
	 *	Get plugin slug
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$append			// String to append
	 *
	 *	@return		string						// Slug
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function slug( $append = '' )
	{

		$slug = $this->slug;

		if ( !$append )
			return $slug;

		// Append
		$append = sanitize_title( $append );
		$append = ltrim( $append, '-' );
		$slug   = rtrim( $slug . '-' . $append, '-' );

		return $slug;

	}


	/**
	 *
	 *	Get plugin name
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$append			// String to append
	 *
	 *	@return		string						// Name
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function name( $append = '' )
	{

		$name = $this->name;

		// Use slug
		if ( !$name )
			$name = $this->slug();

		if ( !$append )
			return $name;

		// Append
		$name = sprintf( '%1$s %2$s', $name, trim( $append ) );

		return $name;

	}


	/**
	 *
	 *	Get plugin version
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$format			// String format
	 *
	 *	@return		string						// Version
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function ver( $format = '%s' )
	{

		$ver = $this->ver;

		if ( !$ver )
			$ver = '1.0.0';

		if ( !$format )
			return $ver;

		// Format
		$ver = sprintf( $format, $ver );

		return $ver;

	}


	/**
	 *
	 *	Get and set data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Data key
	 *	@param		mixed		$value			// Data value
	 *	@param		string		$group			// Group
	 *
	 *	@return		mixed						// Data value, or boolean if setting data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function data( $key = '', $value = NULL, $group = '' )
	{

		// Set data
		if ( ( is_array( $key ) && !isset( $key[0] ) ) || !is_null( $value ) )
			return $this->request( array( ':data/set_data', $key, $value, true, $group ) );

		// Get data
		return $this->request( array( ':data/get_data', $key, $value, $group ) );

	}


	/**
	 *
	 *	Get a core class
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$class 			// Class name
	 *
	 *	@return		mixed						// Class instance, or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_class( $class )
	{
	
		// Doesn't exist
		if ( !isset( $this->classes[ $class ] ) )
			return false;

		// Create new instance
		if ( !isset( $this->instances[ $class ] ) )
			$this->instances[ $class ] = new $this->classes[ $class ];

		// Return class
		return $this->instances[ $class ];
	
	}


	/**
	 *
	 *	Get nonce name
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$append			// String to append
	 *
	 *	@return		string						// Nonce
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function nonce( $append = '' )
	{

		$nonce = $this->nonce;

		if ( !$append )
			return $nonce;

		// Append
		$append = trim( $append );
		$append = ltrim( $append, '_' );
		$nonce .= '_' . $append; 

		return $nonce;

	}
	
	

}