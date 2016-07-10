<?php


namespace WPB;


/**
 *
 *	Admin index page object template
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


class Index_Admin_Page extends Admin_Page
{


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

		// Set tabs
		$tabs = array(
			'dashboard' => array(
				'name' => __( 'Dashboard', 'wpb' ),
				'url'  => $this->url( 'admin-page=dashboard' )
			)
		);

		$this->data( 'tabs', $tabs );

	}


	/**
	 *
	 *	Output page content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output() 
	{

		// Get info
		$info = $this->get_info();

		// Get settings
		$settings = $this->get_settings_links();

		// Get addons
		$addons = $this->get_addons_links();

		// Get tools
		$tools = $this->get_tools_links();

		// Get page template
		wpb()->file( 'admin/templates/pages/page-index', false, false );

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
		$attr['method'] = 'post';
		$attr['action'] = $this->url();

		// Classes
		$attr['class'][] = 'about-wrap';

		return $attr;

	}


	/**
	 *
	 *	Get info
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Info
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_info()
	{

		// Cached
		$info = $this->data( 'info' );

		if ( $info )
			return $info;

		$info = array();

		// Version
		$info['version'] = array(
			'label' => __( 'Version', 'wpb' ),
			'value' => wpb()->ver()
		);

		// Cache
		$this->data( 'info', $info );

		return $info;

	}


	/**
	 *
	 *	Get addons links
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Links
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_addons_links()
	{

		// Cached
		$links = $this->data( 'addons-links' );

		if ( $links )
			return $links;

		$links  = array();
		$addons = wpb( ':addons/get_registered_objects' );

		if ( !empty( $addons ) ) {

			foreach ( $addons as $slug => $addon ) {

				$active = wpb( 'addons/active', $slug );
				$status = ( $active ? __( 'Active', 'wpb' ) : __( 'Inactive', 'wpb' ) );

				$links[ $slug ] = array(
					'name'   => $addon->name(),
					'url'    => $addon->admin_url(),
					'icon'   => $addon->data( 'icon' ),
					'desc'   => sprintf( __( 'Version %1$s', 'wpb' ), $addon->data( 'ver' ) ),
					'status' => $status,
					'active' => $active
				);

			}

			// Filter
			$links = apply_filters( 'wpb/admin/index/addons/links', $links );

			// Format
			$links = $this->format_links( $links );

		}

		// Cache
		$this->data( 'addons-links', $links );

		return $links;

	}


	/**
	 *
	 *	Get settings links
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Links
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_settings_links()
	{

		// Cached
		$links = $this->data( 'settings-links' );

		if ( $links )
			return $links;

		$links = array();

		// Get settings groups
		$settings = wpb( 'settings/get', 'objects=1' );
		$grouped  = wpb( 'admin/settings/grouped', array( 'settings' => $settings, 'grouped' => true, 'tabs' => true ) );

		if ( !empty( $grouped['tabbed'] ) ) {

			foreach ( $grouped['tabbed'] as $group => $locations ) {

				if ( empty( $locations ) )
					continue;

				// Get slug
				$key  = wpb( ':settings/key', $group );
				$key  = str_replace( '/', '-', $key->single );
				$slug = sanitize_title( $key );

				$links[ $slug ] = array(
					'name' => apply_filters( 'wpb/admin/settings/tab/name', $slug, $slug ),
					'url'  => admin_url( 'admin.php?page=wpb-settings&amp;group=' . $slug ),
					'desc' => '',
					'icon' => ''
				);

			}

			// Filter
			$links = apply_filters( 'wpb/admin/settings/tabs', $links );
			$links = apply_filters( 'wpb/admin/index/settings/links', $links );

			// Format
			$links = $this->format_links( $links );

		}

		// Cache
		$this->data( 'settings-links', $links );

		return $links;

	}


	/**
	 *
	 *	Get tools links
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Links
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_tools_links()
	{

		// Cached
		$links = $this->data( 'tools-links' );

		if ( $links )
			return $links;

		$links = array();

		// Transfer
		$links['transfer'] = array(
			'name' => __( 'Transfer', 'wpb' ),
			'url'  => admin_url( 'admin.php?page=wpb-tools&amp;action=transfer' ),
			'icon' => 'media-archive',
			'desc' => __( 'Import/export WPB data', 'wpb' )
		);

		// Reset
		$links['reset'] = array(
			'name' => __( 'Reset', 'wpb' ),
			'url'  => admin_url( 'admin.php?page=wpb-tools&amp;action=reset' ),
			'icon' => 'backup',
			'desc' => __( 'Selectively delete saved WPB data', 'wpb' )
		);

		// Filter
		$links = apply_filters( 'wpb/admin/index/tools/links', $links );

		// Format
		$links = $this->format_links( $links );

		// Cache
		$this->data( 'tools-links', $links );

		return $links;

	}


	/**
	 *
	 *	Format links
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function format_links( $links )
	{

		if ( empty( $links ) )
			return array();

		$formatted = array();

		foreach ( $links as $slug => $link ) {

			$link_text = $link['name'];

			if ( !empty( $link['icon'] ) )
				$link_text = sprintf( '<span class="dashicons dashicons-%1$s"></span> %2$s', $link['icon'], $link_text );

			if ( !empty( $link['desc'] ) )
				$link_text = sprintf( '%1$s <span class="wpb-link-desc">%2$s</span>', $link_text, $link['desc'] );

			$link['text'] = $link_text;

			$formatted[ $slug ] = $link;

		}

		return $formatted;	
	
	}


}