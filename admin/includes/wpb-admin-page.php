<?php


namespace WPB;


/**
 *
 *	Admin page object template
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


class Admin_Page extends Object
{


	// Page data
	protected $_type = 'admin_page';


	/**
	 *
	 *	Setup page
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Page slug
	 *	@param		array 		$data			// Page data
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
	 *	Fired when the admin is loaded
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function load() {}


	/**
	 *
	 *	Fired when the page is initialised
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init() {}


	/**
	 *
	 *	Output page content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output() {}


	/**
	 *
	 *	Output page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output_footer() {}


	/**
	 *
	 *	Get page title
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// Title
	 *
	 *	@since		1.0.0
	 *
	 */

	public function title() 
	{

		return $this->data( 'title' );

	}


	/**
	 *
	 *	Get page URL
	 *
	 *	================================================================ 
	 *
	 *	@return		string 						// URL
	 *
	 *	@since		1.0.0
	 *
	 */

	public function url( $append = '' ) 
	{

		// Base URL
		$url = wpb( 'admin/url' );

		if ( 'index' != $this->id() )
			$url .= '-' . $this->id();

		if ( $append ) {

			$append = esc_attr( $append );

			if ( '&amp;' == substr( $append, 0, 5 ) )
				$append = substr( $append, 5 );

		}

		if ( $append ) {

			if ( !strstr( $append, '=' ) && !strstr( $append, '&amp;' ) ) {

				$url .= '&amp;wpb-page=' . $append;

			} else {

				$url .= '&amp;' . $append;

			}

		}

		return esc_attr( $url );

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

		// Add page classes
		$attr['class'][] = 'wpb-admin-page_' . $this->id();

		return $attr;

	}


	/**
	 *
	 *	Get page tans
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$active			// Active slug
	 *
	 *	@return		array 						// Tabs
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_tabs( $active = '' ) 
	{

		// Get tabs
		$formatted = $this->data( '_tabs' );

		if ( !$formatted ) {

			$tabs = $this->data( 'tabs' );

			if ( empty( $tabs ) )
				return;

			$formatted = array();

			// Format
			foreach ( $tabs as $slug => $tab ) {

				if ( !is_array( $tab ) ) {

					$tab = array(
						'name' => $slug,
						'url'  => $tab
					);

				}

				$tab = wp_parse_args( $tab, array(
					'slug'   => $slug,
					'name'   => '',
					'url'    => '',
					'active' => false
				) );

				if ( empty( $tab['url'] ) )
					continue;

				// Set slug
				$tab['slug'] = sanitize_title( $tab['slug'] );

				// Set name
				if ( empty( $tab['name'] ) )
					$tab['name'] = $tab['slug'];

				$formatted[ $tab['slug'] ] = $tab;

			}

			// Cache
			$this->data( '_tabs', $formatted );

		}

		// Set active
		$current = '';

		foreach ( $formatted as $slug => $tab ) {

			if ( !empty( $tab['active'] ) ) {

				$current = $slug;
				break;

			}

		}

		if ( !$current ) {

			reset( $formatted );
			$first = key( $formatted );

			$formatted[ $first ]['active'] = true;

		}

		if ( $active && isset( $formatted[ $active ] ) ) {

			foreach ( $formatted as $slug => $tab ) {

				if ( $active != $slug )
					$formatted[ $slug ]['active'] = false;

			}

			$formatted[ $active ]['active'] = true;

		}

		return $formatted;

	}



}