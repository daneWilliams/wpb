<?php


namespace WPB;


/**
 *
 *	Addons class
 *
 *	================================================================ 
 *
 *	@package	WPB
 *	@since		1.0.0
 *
 */


class Addons
{


	// Addons
	protected $addons;
	protected $objects;
	protected $active;
	protected $init;


	/**
	 *
	 *	Setup the class
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct()
	{

		// Initialise addons
		add_action( 'wpb/setup', array( $this, 'init' ), 5 );

		// Reset data
		add_filter( 'wpb/admin/reset/settings/plugin', array( $this, 'plugin_reset_settings' ), 5    );
		add_filter( 'wpb/admin/reset/settings/addon',  array( $this, 'addon_reset_settings'  ), 5, 2 );

		add_action( 'wpb/admin/reset', array( $this, 'reset_data' ), 5 );

	}


	/**
	 *
	 *	Initialise addons
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init( $addon = '' )
	{

		// Initialise all
		if ( !$addon ) {

			if ( $this->init )
				return false;

			$addons = $this->get_registered();
			$active = $this->get_active_objects();

			// None active
			if ( empty( $active ) )
				return false;

			foreach ( $active as $slug => $object ) {

				$this->init( $object );

			}

			do_action( 'wpb/addons/init' );

			return true;

		}

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		// Already initialised
		if ( !empty( $this->init ) && in_array( $addon->id(), $this->init ) )
			return true;

		// Initialise
		$addon->init();

		// Register requests
		$addon->register_requests();

		// Load saved setting values
		wpb( ':settings/load_saved_values', 'addon/' . $addon->id() );

		// Register settings
		$addon->register_settings();

		// Callback
		do_action( 'wpb/addons/init/single', $addon->id(), $addon );
		do_action( 'wpb/addons/init/single/' . $addon->id(), $addon );

		// Cache
		if ( !is_array( $this->init ) )
			$this->init = array();

		$this->init[] = $addon->id();

		return true;
	
	}


	/**
	 *
	 *	Get registered addons
	 *
	 *	================================================================
	 *
	 *	@return		array 						// Addons
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_registered()
	{

		// Register addons
		if ( is_null( $this->addons ) ) {

			do_action( 'wpb/addons/register' );

			if ( is_null( $this->addons ) )
				$this->addons = array();

		}

		// Get addons
		$addons = $this->addons;

		// Remove invalid
		if ( !empty( $addons ) ) {

			foreach ( $this->addons as $slug => $addon ) {

				if ( !$addon )
					unset( $addons[ $slug ] );

			}

		}

		ksort( $addons );

		return $addons;
	
	}


	/**
	 *
	 *	Get active addons
	 *
	 *	================================================================
	 *
	 *	@return		array 						// Addons
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_active()
	{
	
		// Cached
		if ( !is_null( $this->active ) )
			return $this->active;

		// Get saved
		$active = get_option( 'wpb_addons_active' );

		if ( empty( $active ) )
			$active = array();

		ksort( $active );

		// Cache
		$this->active = $active;

		return $this->active;

	}



	/**
	 *
	 *	Get registered addon objects
	 *
	 *	================================================================
	 *
	 *	@return		array 						// Addons
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_registered_objects()
	{

		// Get registered addons
		$addons = $this->get_registered();

		if ( empty( $addons ) )
			return array();

		// Get objects
		$objects = array();

		foreach ( $addons as $slug => $addon ) {

			$objects[ $slug ] = $this->get_object( $slug );

		}

		return $objects;

	}


	/**
	 *
	 *	Get active addon objects
	 *
	 *	================================================================
	 *
	 *	@return		array 						// Addons
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_active_objects()
	{

		// Get active addons
		$addons = $this->get_active();

		if ( empty( $addons ) )
			return array();

		// Get objects
		$objects = array();

		foreach ( $addons as $slug ) {

			$objects[ $slug ] = $this->get_object( $slug );

		}

		return $objects;

	}
	

	/**
	 *
	 *	Get an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		boolean		$object			// Return object
	 *
	 *	@return		mixed						// Addon data or object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_addon( $slug, $object = true )
	{

		$addons = $this->get_registered();

		if ( empty( $addons ) )
			return false;

		// Already an object
		if ( $slug instanceof Addon ) {

			// Return object
			if ( $object )
				return $slug;

			// Return registered addon data
			return $addons[ $slug->id() ];

		}

		// Already an addon
		if ( isset( $slug['slug'] ) )
			return $this->get_addon( $slug['slug'], $object );

		// Addon isn't registered
		if ( !isset( $addons[ $slug ] ) )
			return false;

		// Return registered addon data
		if ( !$object )
			return $addons[ $slug ];

		// Return object
		return $this->get_object( $slug );
	
	}


	/**
	 *
	 *	Get an addon object
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *
	 *	@return		mixed						// Addon object or false if not found
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_object( $slug )
	{

		// Already an object
		if ( $slug instanceof Addon )
			return $slug;

		// Already an addon
		if ( isset( $slug['slug'] ) )
			return $this->get_object( $slug['slug'] );

		// Cached
		if ( isset( $this->objects[ $slug ] ) )
			return $this->objects[ $slug ];

		// Get data
		$data = $this->get_addon( $slug, false );

		if ( !$data ) {

			$this->objects[ $slug ] = false;
			return false;

		}

		// Get file
		if ( empty( $data['path'] ) || !file_exists( $data['path'] ) ) {

			$this->objects[ $slug ] = false;
			return false;

		}

		require_once( $data['path'] );

		// Get class
		$classname = 'WPB\\' . str_replace( '-', '_', $slug ) . '_Addon';

		if ( !class_exists( $classname ) ) {

			$this->objects[ $slug ] = false;
			return false;

		}

		// Create object
		$this->objects[ $slug ] = new $classname( $slug, $data );

		return $this->objects[ $slug ];

	}


	/**
	 *
	 *	Register an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		string		$path			// Path to addon file
	 *	@param		array 		$data			// Addon data
	 *
	 *	@return		boolean						// Registered or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_addon( $slug, $path, $data = array() )
	{

		// Already registered
		if ( isset( $this->addons[ $slug ] ) )
			return false;

		// Format data
		if ( isset( $data['slug'] ) )
			unset( $data['slug'] );

		if ( isset( $data['path'] ) )
			unset( $data['path'] );

		$data = $this->format_data( $data, $slug, $path );

		if ( !$data ) {

			$this->addons[ $slug ] = false;
			return false;

		}

		// Get directory path
		$pathinfo = pathinfo( $data['path'] );

		$dir = str_replace( '\\', '/', $pathinfo['dirname'] );

		if ( empty( $pathinfo['extension'] ) ) {

			$dir .= '/';
			$dir .= $pathinfo['basename'];

		}

		// Get file path
		$filename = 'addon-' . $slug . '.php';

		$filepath  = $dir;
		$filepath .= '/';
		$filepath .= $filename;

		if ( !empty( $pathinfo['extension'] ) && substr( $pathinfo['filename'], 0, 6 ) == 'addon-'  ) {

			$filename = $pathinfo['filename'] . '.'. $pathinfo['extension'];

			if ( !file_exists( $filepath ) ) {

				if ( file_exists( $dir . '/' . $filename ) )
					$filepath = $dir . '/' . $filename;

			}

		}

		$data['path'] = $filepath;

		if ( !file_exists( $data['path'] ) ) {

			if ( !file_exists( $dir . '/' . $slug . '/' . $filename ) ) {

				$this->addons[ $slug ] = false;
				return false;

			}

			$data['path'] = $dir . '/' . $slug . '/' . $filename;

		}

		if ( !file_exists( $data['path'] ) ) {

			$this->addons[ $slug ] = false;
			return false;

		}

		// Register
		$this->addons[ $slug ] = $data;

		do_action( 'wpb/addons/register/single', $data );

		return true;

	}


	/**
	 *
	 *	Activate an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *
	 *	@return		boolean						// Activated or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function activate( $slug )
	{

		// Already active
		if ( $this->is_active( $slug ) )
			return;

		// Get addon
		$addon = $this->get_addon( $slug, true );

		if ( !$addon )
			return;

		// Activate
		$active   = $this->get_active();
		$active[] = $addon->id();

		$this->active = $active;

		update_option( 'wpb_addons_active', $active );

		// Initialise
		$this->init( $addon );

		// Callback
		$addon->activate();

		do_action( 'wpb/addons/activate', $addon->id() );
		do_action( 'wpb/addons/activate/' . $addon->id() );

		return true;

	}


	/**
	 *
	 *	Deactivate an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *
	 *	@return		boolean						// Deactivated or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function deactivate( $slug )
	{

		// Already inactive
		if ( !$this->is_active( $slug ) )
			return;

		// Get addon
		$addon = $this->get_addon( $slug, true );

		if ( !$addon )
			return;

		// Deactivate
		$active = $this->get_active();

		$key = array_search( $addon->id(), $active );

		if ( false === $key )
			return false;
		
		unset( $active[ $key ] );

		$this->active = $active;

		update_option( 'wpb_addons_active', $active );

		// Callback
		$addon->deactivate();

		do_action( 'wpb/addons/deactivate', $addon->id() );
		do_action( 'wpb/addons/deactivate/' . $addon->id() );

		return true;

	}


	/**
	 *
	 *	Check if addon is active
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *
	 *	@return		boolean						// Active or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function is_active( $addon )
	{
	
		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		// Get active
		$active = $this->get_active();

		return ( in_array( $addon['slug'], $active ) );
	
	}


	/**
	 *
	 *	Register setting
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *	@param		string		$key			// Setting key(s)
	 *	@param		boolean		$data			// Settings data
	 *	@param		mixed		$default		// Default value
	 *
	 *	@return		mixed						// Setting(s)
	 *
	 *	@see		WPB\Settings::get()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_setting( $addon, $key = '', $data = array(), $default = NULL )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		return wpb( ':settings/register', $key, $data, $default, 'addon/' . $addon['slug'] );

	}


	/**
	 *
	 *	Get setting(s)
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
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
	
	public function get_settings( $addon, $key = '', $default = NULL, $objects = false, $format = true )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		// Get settings
		$settings = wpb( ':settings/get', $key, $default, $objects, $format, 'addon/' . $addon['slug'] );

		return $settings;

	}


	/**
	 *
	 *	Delete settings
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *
	 *	@return		boolean						// Deleted or not
	 *
	 *	@see		WPB\Settings::delete()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function delete_settings( $addon )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		// Delete
		return wpb( ':settings/delete', 'addon/' . $addon['slug'] );

	}


	/**
	 *
	 *	Check if addon has settings
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *	@param		boolean		$trust_addon	// Allow the addon to bypass the check
	 *
	 *	@return		boolean						// Has settings or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function has_settings( $addon, $trust_addon = false )
	{

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		if ( $trust_addon && $addon->has_settings() )
			return true;

		return ( $this->get_settings( $addon ) ? true : false );

	}



	/**
	 *
	 *	Get addon admin URL
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *	@param		string		$append			// String/page to append
	 *
	 *	@return		string						// URL
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_url( $addon = '', $append = '' )
	{


		if ( $append ) {

			$append = esc_attr( $append );

			if ( '&amp;' == substr( $append, 0, 5 ) )
				$append = substr( $append, 5 );

		}

		// Addons page
		if ( !$addon ) {

			$url = 'admin.php?page=wpb-addons';

			if ( $append )
				$url .= '&amp;' . $append;

			return admin_url( $url );

		}

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		$url = 'admin.php?page=wpb-addons&amp;addon=' . $addon['slug'];

		if ( $append ) {

			if ( !strstr( $append, '=' ) && !strstr( $append, '&amp;' ) ) {

				$url .= '&amp;addon-page=' . $append;

			} else {

				$url .= '&amp;' . $append;

			}

		}

		return admin_url( $url );
	
	}


	/**
	 *
	 *	Get addon activation URL
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *	@param		string		$page			// Addon page slug
	 *
	 *	@return		string						// URL
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function activate_url( $addon, $page = '' )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		// Get the base URL
		$url = admin_url( 'admin.php?page=wpb-addons&amp;addon=' . $addon['slug'] );

		if ( !$page && isset( $_GET['addon-page'] ) )
			$page = $_GET['addon-page'];

		if ( $page )
			$url = $this->admin_url( $addon, $page );

		$url .= '&amp;action=activate';

		return wp_nonce_url( $url, 'addon_activate', wpb()->nonce() );
	
	}


	/**
	 *
	 *	Get addon deactivation URL
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *	@param		string		$page			// Addon page slug
	 *
	 *	@return		string						// URL
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function deactivate_url( $addon, $page = '' )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( empty( $addon['slug'] ) )
			return false;

		// Get the base URL
		$url = admin_url( 'admin.php?page=wpb-addons&amp;addon=' . $addon['slug'] );

		if ( !$page && isset( $_GET['addon-page'] ) )
			$page = $_GET['addon-page'];

		if ( $page )
			$url = $this->admin_url( $addon, $page );

		$url .= '&amp;action=deactivate';

		return wp_nonce_url( $url, 'addon_deactivate', wpb()->nonce() );
	
	}


	/**
	 *
	 *	Get addon admin pages
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *
	 *	@return		array						// Pages
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_pages( $addon )
	{

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		// Check for cached
		$pages = wpb()->data( 'admin/pages', NULL, 'addon/' . $addon->id() );

		if ( $pages )
			return $pages;

		// Get pages
		$pages = $addon->admin_pages();
		$pages = apply_filters( 'wpb/addon/admin/pages', ( is_array( $pages ) ? $pages : array() ), $addon );

		// Add/remove settings
		$pages['settings'] = array(
			'icon' => 'admin-generic',
			'name' => __( 'Settings', 'wpb' )
		);
		
		if ( !$this->has_settings( $addon, false ) || !$this->is_active( $addon ) )
			unset( $pages['settings'] );

		// Add details
		$pages['details'] = array(
			'name'     => __( 'Details', 'wpb' ),
			'inactive' => true
		);

		// Format
		$formatted = array();

		foreach ( $pages as $slug => $page ) {

			if ( !is_array( $page ) ) {

				$page = array(
					'name' => $slug,
					'url'  => $page
				);

			}

			$page = wp_parse_args( $page, array(
				'slug' => $slug,
				'name' => '',
				'icon' => '',
				'url'  => '',
				'inactive' => false,
				'callback' => ''
			) );

			// Set slug
			if ( empty( $page['slug'] ) )
				$page['slug'] = $slug;

			$page['slug'] = sanitize_title( $page['slug'] );

			// Set URL
			if ( empty( $page['url'] ) )
				$page['url'] = $this->admin_url( $addon->id(), $page['slug'] );

			// Set name
			if ( empty( $page['name'] ) )
				$page['name'] = $page['slug'];

			// Set callback
			if ( empty( $page['callback'] ) )
				$page['callback'] = 'output_' . $page['slug'] . '_admin_page';

			if ( !method_exists( $addon, $page['callback'] ) )
				$page['callback'] = 'output_admin_page';

			$formatted[ $page['slug'] ] = $page;

		}

		// Cache
		wpb()->data( 'admin/pages', $formatted, 'addon/' . $addon->id() );

		return $formatted;

	}


	/**
	 *
	 *	Get addon admin links
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$addon			// Addon slug or object
	 *
	 *	@return		array						// Links
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_links( $addon )
	{

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		// Check for cached
		$links = wpb()->data( 'admin/links', NULL, 'addon/' . $addon->id() );

		if ( $links )
			return $links;

		$links = $addon->admin_links();
		$links = apply_filters( 'wpb/addon/admin/links', ( is_array( $links ) ? $links : array() ), $addon );

		// Add settings
		if ( $this->has_settings( $addon, true ) ) {

			$links['settings'] = array(
				'icon' => 'admin-generic',
				'name' => __( 'Settings', 'wpb' )
			);

		}

		// Add details
		$links['details'] = array(
			'name'     => __( 'Details', 'wpb' ),
			'inactive' => true
		);

		// Format
		$formatted = array();

		foreach ( $links as $slug => $link ) {

			if ( !is_array( $link ) ) {

				$link = array(
					'name' => $slug,
					'url'  => $link
				);

			}

			$link = wp_parse_args( $link, array(
				'slug' => $slug,
				'name' => '',
				'icon' => '',
				'url'  => '',
				'inactive' => false
			) );

			// Set slug
			if ( empty( $link['slug'] ) )
				$link['slug'] = $slug;

			$link['slug'] = sanitize_title( $link['slug'] );

			// Set URL
			if ( empty( $link['url'] ) )
				$link['url'] = $this->admin_url( $addon->id(), $link['slug'] );

			// Set name
			if ( empty( $link['name'] ) )
				$link['name'] = $link['slug'];

			$formatted[ $link['slug'] ] = $link;

		}

		// Cache
		wpb()->data( 'admin/links', $formatted, 'addon/' . $addon->id() );

		return $formatted;

	}


	/**
	 *
	 *	Format addon data to be registered
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$data			// Data to format
	 *	@param		string		$slug			// Addon slug
	 *	@param		string		$path			// Addon path
	 *
	 *	@return		mixed						// Formatted data, or false if invalid
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function format_data( $data = array(), $slug = '', $path = '' )
	{

		// Default data
		$default = array(
			'slug'       => $slug,
			'name'       => '',
			'desc'       => '',
			'ver'        => '1.0.0',
			'addon_url'  => '',
			'author'     => '',
			'author_url' => '',
			'icon'       => 'admin-plugins',
			'path'       => $path
		);

		// Merge with user data
		$data = wp_parse_args( $data, $default );

		// Filter
		$data = apply_filters( 'wpb/addons/data', $data, $slug );

		if ( $slug )
			$data = apply_filters( 'wpb/addons/data/' . $slug, $data );

		// Set slug
		if ( $slug )
			$data['slug'] = $slug;

		if ( empty( $data['slug'] ) )
			return false;

		$data['slug'] = sanitize_title( $data['slug'] );

		// Set name
		if ( empty( $data['name'] ) )
			$data['name'] = $data['slug'];

		// Set path
		if ( $path && empty( $data['path'] ) )
			$data['path'] = $path;

		// Set version
		if ( empty( $data['ver'] ) )
			$data['ver'] = '1.0.0';

		// Set icon
		if ( empty( $data['icon'] ) )
			$data['icon'] = 'admin-plugins';

		return $data;

	}


	/**
	 *
	 *	Get plugin reset settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function plugin_reset_settings( $settings = array() )
	{
	
		$settings['addons'] = array(
			'data' => array(
				'label'   => __( 'Addons', 'wpb' ),
				'type'    => 'checkbox',
				'choices' => array(
					'deactivate' => __( 'Deactivate addons', 'wpb' ),
					'old' => __( 'Delete old addon settings', 'wpb' )
				)
			),
			'default' => array( 'deactivate' )
		);

		return $settings;

	}


	/**
	 *
	 *	Get addon reset settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function addon_reset_settings( $settings = array(), $addon = '' )
	{

		// Get addon
		$addon = $this->get_addon( $addon, false );

		if ( !$addon )
			return $settings;

		// Already reset
		if ( wpb()->data( 'addons/reset/' . $addon['slug'] ) )
			return $settings;

		// Settings
		if ( get_option( 'wpb_settings_addon-' . $addon['slug'] ) ) {

			$settings['settings'] = array(
				'data' => array(
					'label' => __( 'Settings', 'wpb' ),
					'type'  => 'boolean',
					'text'  => __( 'Delete addon settings', 'wpb' )
				),
				'default' => true
			);

		}

		// Filter
		$settings = apply_filters( 'wpb/admin/reset/settings/addon/' . $addon['slug'], $settings );

		return $settings;

	}


	/**
	 *
	 *	Reset data
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function reset_data( $reset_settings = array() )
	{

		global $wpdb;

		if ( empty( $reset_settings ) )
			return;

		// Deactivate addons
		if ( !empty( $reset_settings['plugin']['addons'] ) && in_array( 'deactivate', $reset_settings['plugin']['addons'] ) ) {

			// Deactivate
			$active = $this->get_active();
			$deactivated = 0;

			if ( !empty( $active ) ) {

				foreach ( $active as $slug ) {

					if ( $this->deactivate( $slug ) )
						$deactivated++;

				}

			}

			// Just to be sure
			delete_option( 'wpb_addons_active' );

			if ( $deactivated ) {

				wpb( 'admin/notification/success', array(
					'text' => __( 'Addons deactivated', 'wpb' ),
					'desc' => sprintf( _n( '1 addon affected', '%d addons affected', $deactivated, 'wpb' ), $deactivated ),
					'dismiss' => true
				) );

			}

		}

		// Reset individual addons
		if ( !empty( $reset_settings['addon'] ) ) {

			$reset = 0;

			foreach ( $reset_settings['addon'] as $slug => $addon_settings ) {

				// Get addon
				$addon = $this->get_addon( $slug );

				if ( !$addon )
					continue;

				// Already reset
				if ( wpb()->data( 'addons/reset/' . $slug ) )
					continue;

				// Allow addon to reset data
				$addon->reset_data( $addon_settings, $reset_settings );

				// Delete settings
				if ( !empty( $addon_settings['settings'] ) ) {

					if ( $this->delete_settings( $addon ) )
						$reset++;

				}

				// Prevent being reset again
				wpb()->data( 'addons/reset/' . $slug, true );

			}

			if ( $reset ) {

				wpb( 'admin/notification/success', array(
					'text' => __( 'Addon settings deleted', 'wpb' ),
					'desc' => sprintf( _n( '1 addon affected', '%d addons affected', $reset, 'wpb' ), $reset ),
					'dismiss' => true
				) );

			}

		}

		// Delete old addon settings
		if ( !empty( $reset_settings['plugin']['addons'] ) && in_array( 'old', $reset_settings['plugin']['addons'] ) ) {

			$query_values = array( '%wpb_settings_addon%' );

			// Get addons to keep
			$keep_addons     = array();
			$keep_addons_str = array();

			$registered = $this->get_registered();

			if ( !empty( $registered ) )
				return;

			foreach ( $registered as $slug => $addon ) {

				$keep_addons[]     = $slug;
				$keep_addons_str[] = '%s';
				$query_values[]    = 'wpb_settings_addon-' . $slug;

			}

			// Build query
			$query  = "DELETE FROM $wpdb->options";
			$query .= " WHERE $wpdb->options.option_name LIKE %s";

			if ( !empty( $keep_addons_str ) )
				$query .= " AND WHERE $wpdb->options.option_name NOT IN ( " . implode( ", ", $keep_addons_str ) . " )";

			// Perform query
			$deleted = $wpdb->query( $wpdb->prepare( $query, $query_values ) );

			if ( $deleted ) {

				wpb( 'admin/notification/success', array(
					'text' => __( 'Old addon settings deleted', 'wpb' ),
					'desc' => sprintf( _n( '1 addon affected', '%d addons affected', $deleted, 'wpb' ), $deleted ),
					'dismiss' => true
				) );

			}

		}

	
	}
	
			

}