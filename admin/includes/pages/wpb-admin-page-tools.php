<?php


namespace WPB;


/**
 *
 *	Admin tools page object template
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 *	@see		WPB\Admin_Page
 *
 */


class Tools_Admin_Page extends Admin_Page
{


	// Registered settings
	protected $import_settings_registered;
	protected $export_settings_registered;
	protected $plugin_reset_settings_registered;
	protected $addon_reset_settings_registered;


	/**
	 *
	 *	Fired when the page is initialised
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init() 
	{

		// Setup current page
		$page = $this->setup_current_page();

		// Set tabs
		$this->data( 'tabs', $this->get_pages() );

	}


	/**
	 *
	 *	Setup the current page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function setup_current_page()
	{
	
		// Get the current page
		$page = $this->get_current_page();

		if ( !$page )
			return false;

		// Update title
		$current_title = $this->title();

		$this->data( 'wp_title', sprintf( '%1$s &lsaquo; %2$s', $page['name'], $current_title ) );
		$this->data( 'title', $page['name'] );

		add_filter( 'wpb/admin/page/title', function( $title ) use( $current_title ) {

			return sprintf( __( '<a href="%1$s">%2$s</a> <span class="wpb-page-separator">&rsaquo;</span> %3$s', 'wpb' ), $this->url(), $current_title, $title );

		});

		// Custom setup
		$method = 'setup_' . str_replace( '-', '_', $page['slug'] ) . '_page';

		if ( method_exists( $this, $method ) )
			return $this->$method( $page );
	
	}


	/**
	 *
	 *	Setup the transfer page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function setup_transfer_page()
	{

		// Import/export
		$this->import_export();

	}


	/**
	 *
	 *	Setup the reset page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function setup_reset_page()
	{

		$this->reset_data();

	}



	/**
	 *
	 *	Import/export
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function import_export()
	{
	
		if ( empty( $_POST['wpb_transfer'] ) )
			return false;

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( !isset( $_POST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_POST[ $nonce ], 'transfer' ) )
			return false;	

		// Export
		if ( !empty( $_POST['wpb_transfer']['export']['submit'] ) )
			return $this->export();

		// Import
		if ( !empty( $_POST['wpb_transfer']['import']['submit'] ) )
			return $this->import();

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

	protected function reset_data()
	{

		if ( empty( $_POST['wpb_reset'] ) )
			return false;

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( !isset( $_POST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_POST[ $nonce ], 'reset' ) )
			return false;	

		$reset  = $_POST['wpb_reset'];
		$values = array();

		// Get plugin settings
		$no_plugin = true;
		$settings['plugin'] = $this->get_plugin_reset_settings();

		if ( !empty( $settings['plugin'] ) ) {

			foreach ( $settings['plugin'] as $key => $setting ) {

				$key = wpb( ':settings/key', $setting->key, 'tmp/reset/plugin' );

				$values['plugin'][ $key->single ] = ( isset( $reset['plugin'][ $key->single ] ) ? $reset['plugin'][ $key->single ] : '' );

				if ( $values['plugin'][ $key->single ] )
					$no_plugin = false;

			}

		}

		$saved['plugin']  = wpb( 'settings/save', array( 'values' => $values['plugin'], 'group' => 'tmp/reset/plugin' ) );
		$values['plugin'] = ( !empty( $saved['plugin']['values'] ) ? $saved['plugin']['values'] : array() );

		// Get addon settings
		$no_addons = true;

		if ( !empty( $reset['addon'] ) ) {

			foreach ( $reset['addon'] as $addon => $addon_values ) {

				// Get addon
				$addon = wpb( 'addons/get', $addon );

				if ( !$addon )
					continue;

				$settings['addon'][ $addon->id() ] = $this->get_addon_reset_settings( $addon );

				if ( empty( $settings['addon'][ $addon->id() ] ) )
					continue;

				foreach ( $settings['addon'][ $addon->id() ] as $key => $setting ) {

					$key = wpb( ':settings/key', $setting->key, 'tmp/reset/addon/' . $addon->id() );

					$values['addon'][ $addon->id() ][ $key->single ] = ( isset( $addon_values[ $key->single ] ) ? $addon_values[ $key->single ] : '' );

					if ( $values['addon'][ $addon->id() ][ $key->single ] )
						$no_addons = false;

				}

				$saved['addon'][ $addon->id() ]  = wpb( 'settings/save', array( 'values' => $values['addon'][ $addon->id() ], 'group' => 'tmp/reset/addon/' . $addon->id() ) );
				$values['addon'][ $addon->id() ] = ( !empty( $saved['addon'][ $addon->id() ]['values'] ) ? $saved['addon'][ $addon->id() ]['values'] : array() );

			}

		}

		// Nothing to reset
		if ( $no_plugin && $no_addons ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Data could not be reset', 'wpb' ),
				'desc' => __( 'No data selected', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Reset
		do_action( 'wpb/admin/reset', $values );

		if ( !wpb()->data( 'admin/notifications' ) ) {

			wpb( 'admin/notification/success', array(
				'text' => __( 'Data reset', 'wpb' ),
				'dismiss' => false
			) );

		}

	}
	

	/**
	 *
	 *	Import
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function import()
	{

		$import = $_POST['wpb_transfer']['import'];

		// Get settings
		$import_settings = $this->get_import_settings();
		$import_values   = array();

		foreach ( $import_settings as $key => $setting ) {

			$key = wpb( ':settings/key', $setting->key, 'tmp/transfer/import' );

			$import_values[ $key->single ] = ( isset( $import[ $key->single ] ) ? $import[ $key->single ] : '' );

		}

		// Save settings
		$saved = wpb( 'settings/save', array( 'values' => $import_values, 'group' => 'tmp/transfer/import' ) );

		// Not saved
		if ( !$saved ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Data could not be imported', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Get file
		$file_id = wpb( 'settings/get', 'key=file&group=tmp/transfer/import' );

		// No file
		if ( !$file_id ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Data could not be imported', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Check file type
		$file_url  = wp_get_attachment_url( $file_id );
		$file_type = wp_check_filetype( $file_url );

		if ( 'txt' != $file_type['ext'] ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Data could not be imported', 'wpb' ),
				'desc' => __( 'Invalid import file', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Get contents
		$contents = json_decode( file_get_contents( $file_url ), true );
		$has_data = false;

		if ( !empty( $contents ) ) {

			foreach ( $contents as $type => $settings ) {

				if ( is_array( $settings ) && !empty( $settings ) ) {

					$has_data = true;
					break;

				}

			}

		}

		if ( !$has_data ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Data could not be imported', 'wpb' ),
				'desc' => __( 'Invalid import data', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Import plugin settings
		if ( !empty( $contents['plugin'] ) ) {

			$imported['plugin'] = wpb( 'settings/save', array( 'values' => $contents['plugin'], 'group' => 'plugin' ) );

			if ( $imported['plugin'] ) {

				wpb( 'admin/notification/success', __( '<strong>Plugin</strong> settings imported', 'wpb' ) );

			} else {

				wpb( 'admin/notification/alert', __( '<strong>Plugin</strong> settings could not be imported', 'wpb' ) );

			}

		}

		// Import addon settings
		foreach ( $contents as $group => $group_settings ) {

			if ( 'addon/' != substr( $group, 0, 6 ) )
				continue;

			$addon = wpb( ':addons/get_addon', substr( $group, 6 ) );

			if ( !$addon ) {

				wpb( 'admin/notification/alert', array(
					'text' => sprintf( __( '<strong>%1$s</strong> settings could not be imported', 'wpb' ), $group ),
					'desc' => __( 'Addon doesn\'t exist', 'wpb' )
				) );

				continue;

			}

			// Register settings
			$addon->register_settings();

			if ( !wpb( ':addons/has_settings', $addon ) ) {

				wpb( 'admin/notification/alert', array(
					'text' => sprintf( __( '<strong>%1$s</strong> settings could not be imported', 'wpb' ), $group ),
					'desc' => __( 'Addon has no settings', 'wpb' )
				) );

				continue;

			}

			// Save settings
			$imported['addon'][ $group ] = wpb( 'settings/save', array( 'values' => $group_settings, 'group' => $group ) );

			if ( $imported['addon'][ $group ] ) {

				wpb( 'admin/notification/success', sprintf( __( '<strong>%1$s</strong> settings imported', 'wpb' ), $addon->name() ) );

			} else {

				wpb( 'admin/notification/alert', sprintf( __( '<strong>%1$s</strong> settings could not be imported', 'wpb' ), $addon->name() ) );

			}

		}

		// Reset file
		wpb( 'settings/save', array( 'values' => array( 'file' => '' ), 'validate' => false, 'group' => 'tmp/transfer/import' ) );

		// Delete file
		if ( wpb( 'settings/get', 'key=delete&group=tmp/transfer/import' ) ) {

			if ( false !== wp_delete_attachment( $file_id, true ) ) {

				wpb( 'admin/notification/success', __( 'Import file deleted', 'wpb' ) );

			} else {

				wpb( 'admin/notification/alert', __( 'Import file could not be deleted', 'wpb' ) );

			}

		}

	}


	/**
	 *
	 *	Export
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function export()
	{

		// Nothing to export
		if ( empty( $_POST['wpb_transfer']['export']['settings'] ) )
			return false;

		$settings = $_POST['wpb_transfer']['export']['settings'];

		// Build the JSON
		$json = array();

		foreach ( $settings as $type ) {

			if ( !isset( $_POST['wpb_transfer']['export'][ $type ] ) ) {

				$json[ $type ] = wpb( 'settings/get', 'group=' . $type );
				continue;

			}

			$sub_settings = $_POST['wpb_transfer']['export'][ $type ];

			if ( empty( $sub_settings ) )
				continue;

			foreach ( $sub_settings as $id ) {

				$json[ $type . '/' . $id ] = wpb( 'settings/get', 'group=' . $type . '/' . $id );

			}

		}

		// Nothing to export
		if ( empty( $json ) ) {

			wpb( 'admin/notification/error', __( 'Export file could not be created', 'wpb' ) );
			return false;

		}

		// Generate export file
		$file_name     = sprintf( '%1$s_%2$s_%3$s.json.txt', wpb()->slug( 'settings' ), sanitize_title( get_bloginfo( 'name' ) ), date( 'Y-m-d' ) );
		$file_contents = json_encode( $json );

		ob_clean();

		header( 'Content-Type: text/json; charset=' . get_option( 'blog_charset' ) );
		header( 'Content-Disposition: attachment; filename=' . $file_name );

		echo $file_contents;

		exit();

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
		$pages = $this->data( 'pages' );

		if ( $pages )
			return $pages;

		// Get pages
		$pages = array(
			'transfer' => array(
				'name' => __( 'Transfer', 'wpb' ),
				'url'  => $this->url( 'action=transfer' )
			),
			'reset' => array(
				'name' => __( 'Reset', 'wpb' ),
				'url'  => $this->url( 'action=reset' )
			)
		);

		// Filter
		$pages = apply_filters( 'wpb/admin/tools/pages', $pages );

		// Format
		if ( !empty( $pages ) ) {

			foreach ( $pages as $slug => $page ) {

				$formatted = wp_parse_args( $page, array(
					'slug' => $slug,
					'name' => '',
					'url'  => ''
				) );

				if ( empty( $formatted['slug'] ) )
					$formatted['slug'] = $slug;

				if ( empty( $formatted['url'] ) )
					$formatted['url'] = $this->url( 'action=' . $formatted['slug'] );

				if ( empty( $formatted['name'] ) )
					$formatted['name'] = $formatted['slug'];

				$pages[ $slug ] = $formatted;

			}

		}

		// Cache
		$this->data( 'pages', $pages );

		return $pages;
	
	}


	/**
	 *
	 *	Get page
	 *
	 *	================================================================ 
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

		if ( empty( $pages ) )
			return false;

		if ( !isset( $pages[ $slug ] ) )
			return false;

		return $pages[ $slug ];

	}


	/**
	 *
	 *	Get current page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_current_page()
	{

		// Get pages
		$pages = $this->get_pages();

		if ( empty( $pages ) )
			return false;

		// Cached
		$page = $this->data( 'current-page' );

		if ( $page )
			return $pages[ $page ];

		// Use first page
		$page = reset( $pages );

		// User request
		if ( isset( $_GET['action'] ) && isset( $pages[ $_GET['action'] ] ) )
			$page = $pages[ $_GET['action'] ];

		// Cache
		$this->data( 'current-page', $page['slug'] );

		return $page;

	}


	/**
	 *
	 *	Get page tabs
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_tabs( $active = '') 
	{

		// Get current page
		$page = $this->get_current_page();

		if ( !$active && $page )
			$active = $page['slug'];

		return parent::get_tabs( $active );

	}


	/**
	 *
	 *	Get page attributes
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$attr			// Current attributes
	 *
	 *	@return		array 						// Update attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_attr( $attr = array() ) 
	{

		$attr = parent::get_attr( $attr );

		// Form
		$page = $this->get_current_page();
		$url  = ( !empty( $page['url'] ) ? $page['url'] : $this->url() );

		$attr['method'] = 'post';
		$attr['action'] = $url;

		return $attr;

	}


	/**
	 *
	 *	Get import settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_import_settings()
	{

		// Register just in case
		$this->register_import_settings();

		return wpb( 'settings/get', 'group=tmp/transfer/import&objects=1' );

	}


	/**
	 *
	 *	Get import settings fields
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_import_settings_fields()
	{

		$settings = array();

		// Import file
		$settings['file'] = array(
			'data' => array(
				'label' => __( 'Import File', 'wpb' ),
				'type'  => 'file',
				'required' => true,
				'ext' => 'txt, json'
			)
		);

		// Delete file
		$settings['delete'] = array(
			'data' => array(
				'label' => '',
				'type'  => 'boolean',
				'text'  => __( 'Delete file after importing', 'wpb' )
			),
			'default' => true
		);

		return $settings;

	}


	/**
	 *
	 *	Get export settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_export_settings()
	{

		// Register just in case
		$this->register_export_settings();

		return wpb( 'settings/get', 'group=tmp/transfer/export&objects=1' );

	}


	/**
	 *
	 *	Get export settings fields
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_export_settings_fields()
	{

		$settings = array();

		// Settings
		$settings['settings'] = array(
			'data' => array(
				'label'   => __( 'Settings', 'wpb' ),
				'type'    => 'checkbox',
				'choices' => array(
					'plugin'    => __( 'Plugin settings', 'wpb' ),
					'addon'     => __( 'Addon settings', 'wpb' )
				),
				'required' => true
			),
			'default' => array( 'plugin', 'addon' )
		);

		// Addons
		$addons = wpb( ':addons/get_registered' );
		$addon_choices   = array();
		$addons_selected = array();

		if ( !empty( $addons ) ) {

			$addon_choices = array();

			foreach ( $addons as $slug => $addon ) {

				if ( !wpb( ':addons/has_settings', $addon ) )
					continue;

				$addons_selected[] = $slug;

				$addon_choices[ $slug ] = array(
					'label' => $addon['name']
				);

			}

		}

		if ( !empty( $addon_choices ) ) {

			$settings['addon'] = array(
				'data' => array(
					'label'   => __( 'Addon Settings', 'wpb' ),
					'type'    => 'checkbox',
					'choices' => $addon_choices,
					'toggle'  => true
				),
				'default' => $addons_selected
			);

		} else {

			unset( $settings['settings']['choices']['addon'] );

		}

		return $settings;

	}


	/**
	 *
	 *	Get reset settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_plugin_reset_settings()
	{

		// Register just in case
		$this->register_plugin_reset_settings();

		return wpb( 'settings/get', 'group=tmp/reset/plugin&objects=1' );

	}


	/**
	 *
	 *	Get addon reset settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_addon_reset_settings( $addon )
	{

		// Register just in case
		$this->register_addon_reset_settings( $addon );

		return wpb( 'settings/get', 'group=tmp/reset/addon/' . $addon->id() . '&objects=1' );

	}


	/**
	 *
	 *	Get plugin reset settings fields
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_plugin_reset_settings_fields()
	{

		global $wpdb;

		$settings = array();

		// Settings
		$settings['settings'] = array(
			'data' => array(
				'label' => __( 'Settings', 'wpb' ),
				'type'  => 'boolean',
				'text'  => __( 'Delete plugin settings', 'wpb' )
			),
			'default' => true
		);

		// Filter
		$settings = apply_filters( 'wpb/admin/reset/settings/plugin', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get addon reset settings fields
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_addon_reset_settings_fields( $addon )
	{

		// Filter
		$settings = apply_filters( 'wpb/admin/reset/settings/addon', array(), $addon );

		return $settings;

	}


	/**
	 *
	 *	Register import settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_import_settings( $values = array() )
	{

		if ( $this->import_settings_registered )
			return false;

		// Get group
		$group = 'tmp/transfer/import';

		// Get setting fields
		$settings = $this->get_import_settings_fields();

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update name attribute
			$data['attr']['name'] = 'wpb_transfer[import][' . $key . ']';

			$registered[ $key ] = wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		$this->import_settings_registered = true;

		return $registered;

	}


	/**
	 *
	 *	Register export settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_export_settings( $values = array() )
	{

		if ( $this->export_settings_registered )
			return false;

		// Get group
		$group = 'tmp/transfer/export';

		// Get setting fields
		$settings = $this->get_export_settings_fields();

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update name attribute
			$data['attr']['name'] = 'wpb_transfer[export][' . $key . ']';

			$registered[ $key ] = wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		$this->export_settings_registered = true;

		return $registered;

	}


	/**
	 *
	 *	Register reset settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_plugin_reset_settings( $values = array() )
	{

		if ( $this->plugin_reset_settings_registered )
			return false;

		// Get group
		$group = 'tmp/reset/plugin';

		// Get setting fields
		$settings = $this->get_plugin_reset_settings_fields();

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update name attribute
			$data['attr']['name'] = 'wpb_reset[plugin][' . $key . ']';

			$registered[ $key ] = wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		$this->plugin_reset_settings_registered = true;

		return $registered;

	}


	/**
	 *
	 *	Register addon reset settings
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_addon_reset_settings( $addon, $values = array() )
	{

		// Get addon
		$addon = wpb( ':addons/get_addon', $addon );

		if ( !$addon )
			return false;

		// Already registered
		if ( isset( $this->addon_reset_settings_registered[ $addon->id() ] ) )
			return false;

		// Get group
		$group = 'tmp/reset/addon/' . $addon->id();

		// Get setting fields
		$settings = $this->get_addon_reset_settings_fields( $addon );

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update name attribute
			$data['attr']['name'] = 'wpb_reset[addon][' . $addon->id() . '][' . $key . ']';

			$registered[ $key ] = wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		$this->addon_reset_settings_registered[ $addon->id() ] = true;

		return $registered;

	}



	/**
	 *
	 *	Output content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output()
	{
	
		// Get current page
		$page = $this->get_current_page();

		if ( $page ) {

			$method = 'output_' . str_replace( '-', '_', $page['slug'] );

			if ( method_exists( $this, $method ) ) {

				return $this->$method( $page );

			}

		}

		parent::output();

	}


	/**
	 *
	 *	Output page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_footer()
	{

		// Get current page
		$page = $this->get_current_page();

		if ( $page ) {

			$method = 'output_' . str_replace( '-', '_', $page['slug'] ) . '_footer';

			if ( method_exists( $this, $method ) ) {

				return $this->$method( $page );

			}

		}

		parent::output_footer();

	}


	/**
	 *
	 *	Output transfer content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_transfer( $page )
	{

		// Get settings
		$import_settings = $this->get_import_settings();
		$export_settings = $this->get_export_settings();

		// Import
		if ( !empty( $import_settings ) ) { ?>


<div class="wpb-box" id="wpb-transfer-import">

	<header class="wpb-box-header">

		<button class="wpb-box-toggle hide-if-no-js">
			<span class="screen-reader-text"><?php _e( 'Toggle import', 'wpb' ); ?></span>
		</button>

		<h3 class="wpb-box-title"><span class="dashicons dashicons-upload"></span> <?php _e( 'Import', 'wpb' ); ?></h3>

	</header>

	<section class="wpb-box-content wpb-box-settings"><?php

		wpb( 'admin/settings/output', array( 'settings' => $import_settings ) ); ?>

	</section>

	<footer class="wpb-box-footer">

		<div class="wpb-box-buttons">

			<button class="button button-primary wpb-import-button" type="submit" name="wpb_transfer[import][submit]" value="1" data-wpb-loading-text="<?php esc_attr_e( __( 'Importing&hellip;', 'wpb' ) ); ?>"><?php _e( 'Import', 'wpb' ); ?></button>

			<span class="spinner"></span>

		</div>

	</footer>

</div><?php


		}


		// Export
		if ( !empty( $export_settings ) ) { ?>


<div class="wpb-box" id="wpb-transfer-export">

	<header class="wpb-box-header">

		<button class="wpb-box-toggle hide-if-no-js">
			<span class="screen-reader-text"><?php _e( 'Toggle export', 'wpb' ); ?></span>
		</button>

		<h3 class="wpb-box-title"><span class="dashicons dashicons-download"></span> <?php _e( 'Export', 'wpb' ); ?></h3>

	</header>

	<section class="wpb-box-content wpb-box-settings"><?php

		wpb( 'admin/settings/output', array( 'settings' => $export_settings ) ); ?>

	</section>

	<footer class="wpb-box-footer">

		<div class="wpb-box-buttons">
			<button class="button button-primary wpb-export-button" type="submit" name="wpb_transfer[export][submit]" value="1"><?php _e( 'Export', 'wpb' ); ?></button>
		</div>

	</footer>

</div><?php


		}


	}


	/**
	 *
	 *	Output transfer page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output_transfer_footer( $page ) 
	{

		// Nonce
		wp_nonce_field( 'transfer', wpb()->nonce() );

	}


	/**
	 *
	 *	Output reset content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_reset( $page )
	{

		// Warning
		echo wpb( 'notification/alert', array(
			'text' => sprintf(
				__( '<strong>Note:</strong> Selected data will be permanently deleted. It is recommended to <a href="%s">export your data</a> before resetting.', 'wpb' ),
				$this->url( 'transfer' )
			)
		) );


		// Plugin
		$plugin_settings = $this->get_plugin_reset_settings();

		if ( !empty( $plugin_settings ) ) { ?>


<div class="wpb-box" id="wpb-reset-plugin">

	<header class="wpb-box-header">

		<button class="wpb-box-toggle hide-if-no-js">
			<span class="screen-reader-text"><?php _e( 'Toggle plugin', 'wpb' ); ?></span>
		</button>

		<h3 class="wpb-box-title"><span class="dashicons dashicons-hammer"></span> <?php echo wpb()->name(); ?></h3>

	</header>

	<section class="wpb-box-content wpb-box-settings"><?php

		wpb( 'admin/settings/output', array( 'settings' => $plugin_settings ) ); ?>

	</section>


</div><?php


		}


		// Addons
		$addons = wpb( ':addons/get_registered_objects' );

		if ( !empty( $addons ) ) {

			foreach ( $addons as $slug => $addon ) {

				$reset_settings = $this->get_addon_reset_settings( $addon );

				if ( empty( $reset_settings ) )
					continue; ?>


<div class="wpb-box" id="wpb-reset-addon-<?php echo $addon->id(); ?>">

	<header class="wpb-box-header">

		<button class="wpb-box-toggle hide-if-no-js">
			<span class="screen-reader-text"><?php _e( 'Toggle addon', 'wpb' ); ?></span>
		</button>

		<h3 class="wpb-box-title"><span class="dashicons dashicons-<?php echo $addon->data( 'icon' ); ?>"></span> <?php printf( __( '<span class="wpb-meta">Addon:</span> %s', 'wpb' ), $addon->name() ); ?></h3>

	</header>

	<section class="wpb-box-content wpb-box-settings"><?php

		wpb( 'admin/settings/output', array( 'settings' => $reset_settings ) ); ?>

	</section>


</div><?php


			}

		}


	}


	/**
	 *
	 *	Output reset page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output_reset_footer( $page ) 
	{

		echo '<div class="wpb-page-buttons"><div class="wpb-page-buttons-container">';

		// Submit button
		echo '<button type="submit" id="wpb-reset-submit" class="wpb-submit button button-primary" data-wpb-loading-text="' . esc_attr( __( 'Resetting&hellip;', 'wpb' ) ) . '">';
		_e( 'Reset', 'wpb' );
		echo '</button>';

		// Spinner
		echo '<span class="spinner"></span>';

		echo '</div></div>';

		// Nonce
		wp_nonce_field( 'reset', wpb()->nonce() );

	}



}