<?php


namespace WPB;


/**
 *
 *	Admin class
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Admin
{


	// Pages
	private $pages;
	private $page_refs;
	private $page_objects;
	private $current_page;


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

		if ( is_admin() ) {

			// Load helpers
			wpb()->file( 'includes/helpers/admin' );
			wpb()->file( 'admin/includes/helpers/ajax' );

			wpb()->file( 'admin/includes/helpers/requests-register' );
			wpb()->file( 'admin/includes/helpers/requests-methods' );

			wpb()->file( 'admin/includes/helpers/settings-output' );

			// Add assets
			add_action( 'admin_enqueue_scripts', array( $this, 'add_assets' ), 5 );

			// Add pages
			add_action( 'admin_menu', array( $this, 'add_pages' ), 5 );

			// Load current page
			add_action( 'wpb/setup', array( $this, 'load_page' ), 5 );

			// Initialise current page
			add_action( 'wpb/init', array( $this, 'init_page' ), 5 );

			// Filter page title
			add_filter( 'admin_title', array( $this, 'admin_page_title' ), 5, 2 );

			// Output notifications
			add_action( 'wpb/admin/page/after/header', array( $this, 'output_notifications' ), 5 );

		}

	}


	/**
	 *
	 *	Get pages
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_pages()
	{
	
		// Cached
		if ( !is_null( $this->pages ) )
			return $this->pages;

		// Filter
		$pages = apply_filters( 'wpb/admin/pages', array() );

		if ( empty( $pages ) )
			$pages = array();

		// Format
		if ( !empty( $pages ) ) {

			$formatted = array();

			foreach ( $pages as $slug => $page ) {

				$page = wp_parse_args( $page, array(
					'title' => '',
					'desc'  => '',
					'menu'  => '',
					'slug'  => '',
					'icon'  => '',
					'capability' => '',
					'wp' => ''
				) );

				// Need a slug
				if ( empty( $page['slug'] ) )
					continue;

				// Already formatted
				if ( isset( $formatted[ $page['slug'] ] ) )
					continue;

				// Default titles
				if ( empty( $page['title'] ) )
					$page['title'] = $page['slug'];

				if ( empty( $page['menu'] ) )
					$page['menu'] = $page['title'];

				// Capability
				if ( empty( $page['capability'] ) )
					$page['capability'] = 'manage_options';

				// Add to formatted
				$formatted[ $page['slug'] ] = $page;

			}

			$pages = $formatted;

		}

		// Cache
		$this->pages = $pages;

		return $pages;

	}


	/**
	 *
	 *	Load pages
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function load_pages()
	{

		// Get pages
		$pages = $this->get_pages();

		if ( empty( $pages ) )
			return;

		foreach ( $pages as $slug => $page ) {

			$this->load_page( $slug );

		}
	
	}


	/**
	 *
	 *	Add pages
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_pages()
	{
	
		// Get pages
		$pages = $this->get_pages();

		if ( empty( $pages ) )
			return;

		// Base data
		$name       = __( 'WPB', 'wpb' );
		$capability = 'manage_options';
		$callback   = array( $this, 'output_page' );

		// Add pages
		foreach ( $pages as $slug => $page ) {

			// Get page data
			$callback = ( !empty( $page['callback'] )   ? $page['callback']   : $callback   );

			// Main page
			if ( $slug == 'index' ) {

				$ref = add_menu_page(
					$page['title'],
					$page['menu'],
					$page['capability'],
					'wpb',
					$callback,
					'dashicons-' . $page['icon'],
					( !empty( $page['position'] ) ? $page['position'] : '100.1' )
				);

			}

			// Sub page
			else {

				$ref = add_submenu_page(
					'wpb',
					$page['title'],
					$page['menu'],
					$page['capability'],
					'wpb-' . $slug,
					$callback
				);

			}

			// Add references
			$this->pages[ $slug ]['wp'] = $ref;
			$this->page_refs[ $ref ]    = $slug;

			// Add assets
			add_action( 'load-' . $ref, array( $this, 'add_page_assets' ), $ref );

		}

	}


	/**
	 *
	 *	Enqueue admin assets
	 *
	 *	================================================================
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function add_assets()
	{

		// WP scripts
		wp_enqueue_media();

		// Admin styles
		wp_enqueue_style( 'wpb-admin', wpb()->url( 'admin/assets/css/wpb-admin-styles.css' ) );

		// Admin settings
		wp_enqueue_script( 'wpb-admin-settings', wpb()->url( 'admin/assets/js/wpb-settings.js' ), array( 'jquery' ) );

		// Localised data
		$data = array(

			'nonce' => wpb()->nonce(),

			'settings' => array(
				'uploader' => null,
				'uploader_attachment' => ''
			),

			'actions' => array()

		);

		// Filter
		$data = apply_filters( 'wpb/admin/localized-data', $data );

		wp_localize_script( 'wpb-admin-settings', 'wpb', $data );

	}


	/**
	 *
	 *	Enqueue admin page assets
	 *
	 *	================================================================
	 *
	 *	@param		string		$page			// WP admin page slug
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function add_page_assets( $page ) 
	{

		// Modify body class
		add_filter( 'admin_body_class', function( $classes ) { 

			$classes .= ' wpb-admin-page';

			return $classes;

		});

		// Page scripts
		wp_enqueue_script( 'wpb-admin-page', wpb()->url( 'admin/assets/js/wpb-page.js' ), array( 'jquery' ) );

		// Codemirror
		wp_enqueue_script( 'codemirror',
			wpb()->url( 'admin/lib/codemirror/lib/codemirror.js' )
		);

		wp_enqueue_script( 'codemirror-html',
			wpb()->url( 'admin/lib/codemirror/mode/htmlmixed/htmlmixed.js' ),
			array( 'codemirror' )
		);

		wp_enqueue_script( 'codemirror-xml',
			wpb()->url( 'admin/lib/codemirror/mode/xml/xml.js' ),
			array( 'codemirror' )
		);

		wp_enqueue_script( 'codemirror-css',
			wpb()->url( 'admin/lib/codemirror/mode/css/css.js' ),
			array( 'codemirror' )
		);

		wp_enqueue_script( 'codemirror-js',
			wpb()->url( 'admin/lib/codemirror/mode/javascript/javascript.js' ),
			array( 'codemirror' )
		);

		wp_enqueue_style( 'codemirror', wpb()->url( 'admin/lib/codemirror/lib/codemirror.css' ) );

	}


	/**
	 *
	 *	Get the current page
	 *
	 *	================================================================ 
	 *
	 *	@return		mixed						// Page data or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_current_page()
	{

		// Cached
		if ( $this->current_page )
			return $this->get_page( $this->current_page );

		// Get pages
		$pages = $this->get_pages();

		// Get from URL
		if ( function_exists( 'get_current_screen' ) ) {

			$screen = get_current_screen();

			if ( !empty( $screen->base ) ) {

				// Get from reference
				if ( isset( $this->page_refs[ $screen->base ] ) ) {

					$slug = $this->page_refs[ $screen->base ];

					if ( isset( $pages[ $slug ] ) ) {

						// Set current page
						$this->current_page = $slug;

						return $pages[ $slug ];
					}

				}

				return false;

			}

		}

		// Get from URL
		if ( empty( $_GET['page'] ) )
			return false;

		$page = $_GET['page'];

		if ( $page == 'wpb' )
			$page = 'wpb-index';

		if ( substr( $page, 0, 4 ) != 'wpb-' )
			return false;

		$slug = substr( $page, 4 );

		if ( !isset( $pages[ $slug ] ) )
			return false;

		return $pages[ $slug ];

	}


	/**
	 *
	 *	Get the current page object
	 *
	 *	================================================================ 
	 *
	 *	@return		mixed						// Page object or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_current_page_object()
	{

		return $this->get_page_object();

	}


	/**
	 *
	 *	Get a page
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Page slug
	 *
	 *	@return		mixed						// Page data or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_page( $slug = '' )
	{

		if ( !$slug )
			return $this->get_current_page();

		// Get pages
		$pages = $this->get_pages();

		if ( !isset( $pages[ $slug ] ) )
			return false;

		return $pages[ $slug ];

	}


	/**
	 *
	 *	Get a page object
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Page slug
	 *
	 *	@return		mixed						// Page object or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	private function get_page_object( $slug = '' )
	{

		// Get page
		$page = $this->get_page( $slug );

		if ( empty( $page['slug'] ) )
			return false;

		$slug = $page['slug'];

		// Load object file
		if ( empty( $this->page_objects ) )
			wpb()->file( 'admin/includes/wpb-admin-page' );

		// Create new instance
		if ( !isset( $this->page_objects[ $slug ] ) ) {

			$class = 'WPB\\Admin_Page';

			if ( wpb()->file( 'admin/includes/pages/wpb-admin-page-' . $slug ) )
				$class = 'WPB\\' . ucfirst( str_replace( '-', '_', $slug ) ) . '_Admin_Page';

			$this->page_objects[ $slug ] = new $class( $slug, $page );

		}

		return $this->page_objects[ $slug ];

	}


	/**
	 *
	 *	Load the current page object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function load_page( $page = '' )
	{

		// Get page
		$page = $this->get_page_object( $page );

		if ( !$page )
			return;

		// Callback
		$page->load();

		do_action( 'wpb/admin/page/load' );

	}


	/**
	 *
	 *	Initialise the page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function init_page()
	{

		// Get page
		$page = $this->get_page_object();

		if ( !$page )
			return;

		// Initialise
		$page->init();

		// Callback
		do_action( 'wpb/admin/page/init' );

	}


	/**
	 *
	 *	Output the page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_page()
	{

		// Get page
		$page = $this->get_page_object();

		if ( !$page )
			return;

		// Callback
		do_action( 'wpb/admin/page/output' );

		// Page wrapper template
		wpb()->file( 'admin/templates/page-wrapper' );

	}


	/**
	 *
	 *	Output notifications
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_notifications()
	{

		wpb( 'admin/notifications' );

	}


	/**
	 *
	 *	Modify admin page title
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$admin_title	// The page title, with extra context added
	 *	@param		string		$title			// The original page title
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_page_title( $admin_title, $title = '')
	{

		// Get page
		$page = $this->get_page_object();

		if ( !$page )
			return $admin_title;

		$wpb_title  = __( 'WPB', 'wpb' );
		$site_title = get_bloginfo( 'name' );

		// Index
		if ( 'index' == $page->id() || $page->title() == $wpb_title )
			return $admin_title;

		// Get page title
		$page_title = $page->data( 'wp_title' );

		if ( !$page_title )
			$page_title = sprintf( '%1$s %2$s', $wpb_title, $page->title() );

		$admin_title = sprintf( '%1$s &lsaquo; %2$s &#8212; WordPress', $page_title, $site_title );

		return $admin_title;

	}


}