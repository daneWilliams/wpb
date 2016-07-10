<?php


namespace WPB;


/**
 *
 *	Admin addons page object template
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


class Addons_Admin_Page extends Admin_Page
{


	// Current addon
	private $addon;


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

		// Check for addon
		$this->check_addon();

	}


	/**
	 *
	 *	Check for addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	private function check_addon() 
	{

		if ( empty( $_GET['addon'] ) )
			return false;

		// Actions
		$this->activate_addon( $_GET['addon'] );
		$this->deactivate_addon( $_GET['addon'] );

		$action = ( isset( $_GET['action'] ) ? $_GET['action'] : '' );

		// Single addon
		if ( isset( $_GET['addon-page'] ) || !$action || !in_array( $action, array( 'activate', 'deactivate' ) ) ) {

			$this->setup_addon( $_GET['addon'] );
			$this->save_settings( $_GET['addon'] );

		}

	}


	/**
	 *
	 *	Setup single addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	private function setup_addon( $addon )
	{

		// Get addon
		$addon = wpb( ':addons/get_addon', $addon );

		if ( !$addon )
			return false;

		// Set slug
		$this->addon = $addon->id();

		// Update title
		$this->data( 'wp_title', sprintf( '%1$s &lsaquo; %2$s', $addon->name(), $this->title() ) );
		$this->data( 'title', $addon->name() );

		add_filter( 'wpb/admin/page/title', function( $title ) {

			return sprintf( __( '<a href="%1$s">Addons</a> <span class="wpb-page-separator">&rsaquo;</span> %2$s', 'wpb' ), $this->url(), $title );

		});

		// Update icon
		$icon = $addon->data( 'icon' );

		if ( $icon )
			$this->data( 'icon', $icon );

		// Set tabs
		$pages = wpb( ':addons/admin_pages', $addon, false );

		if ( !empty( $pages ) )
			$this->data( 'tabs', $pages, false );

		// Callback
		$addon->admin_page_init( $this );

	}


	/**
	 *
	 *	Check if this is a single addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function is_addon() 
	{

		return ( $this->addon ? true : false );

	}


	/**
	 *
	 *	Get addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_addon( $addon = '' ) 
	{

		if ( !$addon && !$this->is_addon() )
			return false;

		return wpb( ':addons/get_addon', ( $addon ? $addon : $this->addon ) );

	}


	/**
	 *
	 *	Activate addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	private function activate_addon( $addon ) 
	{

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( empty( $_REQUEST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_REQUEST[ $nonce ], 'addon_activate' ) )
			return false;

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		// Activate
		$activated = wpb( ':addons/activate', $addon );

		// Not activated
		if ( !$activated ) {

			if ( false === $activated ) {

				wpb( 'admin/notification/error', array(
					'text' => sprintf( __( '<strong>%s</strong> addon could not be activated', 'wpb' ), $addon->name() ),
					'dismiss' => false
				) );

				return false;

			}

			return false;

		}

		wpb( 'admin/notification/success', sprintf( __( '<strong>%s</strong> addon activated', 'wpb' ), $addon->name() ) );

		return true;

	}

	/**
	 *
	 *	Deactivate addon
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	private function deactivate_addon( $addon ) 
	{

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( empty( $_REQUEST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_REQUEST[ $nonce ], 'addon_deactivate' ) )
			return false;

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		// Deactivate
		$deactivated = wpb( ':addons/deactivate', $addon );

		// Not deactivated
		if ( !$deactivated ) {

			if ( false === $deactivated ) {

				wpb( 'admin/notification/error', array(
					'text' => sprintf( __( '<strong>%s</strong> addon could not be deactivated', 'wpb' ), $addon->name() ),
					'dismiss' => false 
				) );

				return false;

			}

			return false;

		}

		wpb( 'admin/notification/success', sprintf( __( '<strong>%s</strong> addon deactivated', 'wpb' ), $addon->name() ) );

		return true;

	}


	/**
	 *
	 *	Save settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	private function save_settings( $addon ) 
	{

		if ( empty( $_POST['wpb_settings'] ) )
			return false;

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( !isset( $_POST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_POST[ $nonce ], 'addon_settings_save' ) )
			return false;

		// Get addon
		$addon = $this->get_addon( $addon );

		if ( !$addon )
			return false;

		if ( !wpb( ':addons/is_active', $addon ) )
			return false;

		// Save settings
		$saved = wpb( 'settings/save', array( 'values' => $_POST['wpb_settings'], 'group' => 'addon/' . $addon->id() ) );

		// Not saved
		if ( !$saved ) {

			wpb( 'admin/notification/error', array(
				'text' => sprintf( __( 'Settings could not be saved', 'wpb' ), $addon->name() ),
				'dismiss' => false
			) );

			return false;

		}

		// Saved
		echo wpb( 'admin/notification/success', sprintf( __( 'Settings saved', 'wpb' ), $addon->name() ) );

		return true;

	}


	/**
	 *
	 *	Get current addon page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_addon_page( $addon = '' )
	{
	
		if ( !$addon )
			$addon = $this->get_addon();

		if ( !$addon )
			return false;

		// Get pages
		$pages = wpb( ':addons/admin_pages', $addon, false );

		if ( empty( $pages['details'] ) )
			return false;

		// Default to first page
		if ( empty( $_GET['addon-page'] ) ) {

			$first = reset( $pages );
			return $first;

		}

		// Default to details
		if ( !isset( $pages[ $_GET['addon-page'] ] ) )
			return $pages['details'];

		if ( !$addon->is_active() && empty( $pages[ $_GET['addon-page'] ]['inactive'] ) )
			return $pages['details'];

		return $pages[ $_GET['addon-page'] ];

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
		$page = $this->get_addon_page();

		if ( !$active && $page )
			$active = $page['slug'];

		return parent::get_tabs( $active );

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

		// Single addon
		if ( $this->is_addon() ) {

			$this->output_single();
			return;

		}

		// Get addons
		$addons = wpb( 'addons/get' );

		if ( empty( $addons ) ) {

			echo wpb( 'notification/alert', __( 'No registered addons', 'wpb' ) );
			return;

		}

		// Output
		echo '<div class="wpb-addons">';

		foreach ( $addons as $slug => $addon ) {

			echo '<div class="wpb-addon-container">';

			wpb()->data( 'admin/addon', $addon );
			wpb()->file( 'admin/templates/addons/addon-loop', false, false );

			echo '</div>';

		}

		echo '</div>';

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

		// Single addon
		if ( $this->is_addon() ) {

			$this->output_single_footer();
			return;

		}

	}


	/**
	 *
	 *	Output single addon page content
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output_single() 
	{

		$addon = $this->get_addon();

		if ( !$addon )
			return false;

		// Set the addon
		wpb()->data( 'admin/addon', $addon );

		// Get current page
		$page = $this->get_addon_page( $addon );

		if ( !$page )
			return false;

		// Addon method
		$addon_method = 'admin_page__output_' . str_replace( '-', '_', $page['slug'] );

		if ( method_exists( $addon, $addon_method ) ) {

			return $addon->$addon_method( $page );

		}

		// Core method
		$method = 'output_single_' . str_replace( '-', '_', $page['slug'] );

		if ( method_exists( $this, $method ) )
			return $this->$method( $addon, $page );

		// Custom callback
		if ( !empty( $page['callback'] ) ) {

			$method = $page['callback'];

			if ( method_exists( $addon, $method ) )
				return $addon->$method( $page );

		}

	}


	/**
	 *
	 *	Output single addon page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_single_footer()
	{
	
		$addon = $this->get_addon();

		if ( !$addon )
			return false;

		// Get current page
		$page = $this->get_addon_page( $addon );

		if ( !$page )
			return false;

		// Addon method
		$addon_method = 'admin_page__output_' . str_replace( '-', '_', $page['slug'] ) . '_footer';

		if ( method_exists( $addon, $addon_method ) ) {

			return $addon->$addon_method( $page );

		}

		// Core page
		$method = 'output_single_' . str_replace( '-', '_', $page['slug'] ) . '_footer';

		if ( method_exists( $this, $method ) )
			return $this->$method( $addon, $page );

		// Activate/deactivate
		$this->output_single_default_footer( $addon, $page );

	}


	/**
	 *
	 *	Output default single addon page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function output_single_default_footer( $addon, $page )
	{

		$slug = ( $page['slug'] != 'details' ? $page['slug'] : '' );

		echo '<div class="wpb-page-buttons"><div class="wpb-page-buttons-container">';

		// Activate
		echo '<a class="wpb-hide-if-active wpb-addon-activate-link button button-primary" href="' . $addon->activate_url( $slug ) . '" data-wpb-loading-text="' . esc_attr( __( 'Activating&hellip;', 'wpb' ) ) . '">';
		_e( 'Activate', 'wpb' );
		echo '</a>';

		// Deactivate
		echo '<a class="wpb-hide-if-inactive wpb-addon-deactivate-link button button-secondary" href="' . $addon->deactivate_url() . '" data-wpb-loading-text="' . esc_attr( __( 'Deactivating&hellip;', 'wpb' ) ) . '">';
		_e( 'Deactivate', 'wpb' );
		echo '</a>';

		// Spinner
		echo '<span class="spinner"></span>';

		echo '</div></div>';

	}


	/**
	 *
	 *	Output addon details
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function output_single_details( $addon, $page ) 
	{

		wpb()->file( 'admin/templates/addons/addon-single-details', false, false );

	}


	/**
	 *
	 *	Output addon settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function output_single_settings( $addon, $page ) 
	{

		$settings = wpb( 'settings/addon/get', 'objects=1&addon=' . $addon->id() );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $settings, 'grouped' => true, 'tabs' => false ) );

	}


	/**
	 *
	 *	Output addon settings page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function output_single_settings_footer( $addon, $page ) 
	{

		echo '<div class="wpb-page-buttons"><div class="wpb-page-buttons-container">';

		// Submit button
		echo '<button type="submit" id="wpb-settings-submit" class="wpb-submit button button-primary" data-wpb-loading-text="' . esc_attr( __( 'Saving&hellip;', 'wpb' ) ) . '">';
		_e( 'Save Settings', 'wpb' );
		echo '</button>';

		// Spinner
		echo '<span class="spinner"></span>';

		echo '</div></div>';

		// Addon slug
		echo '<input type="hidden" name="addon" value="' . esc_attr( $addon->id() ) . '" />';

		// Nonce
		wp_nonce_field( 'addon_settings_save', wpb()->nonce() );

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
		$url = $this->url();

		if ( $this->is_addon() ) {

			$addon = $this->get_addon();

			// Single addon
			if ( $addon ) {

				$url = $addon->admin_url();

				$addon_page = $this->get_addon_page( $addon );

				if ( $addon_page )
					$url = $addon_page['url'];

				$attr['class'][] = 'wpb-addon';
				$attr['class'][] = 'wpb-addon-page';
				$attr['class'][] = ( $addon->is_active() ? 'wpb-addon-active' : 'wpb-addon-inactive' );

				// Settings
				if ( 'settings' == $addon_page['slug'] ) {

					$attr['class'][] = 'wpb-page-settings';
					$attr['data-wpb-action'] = 'wpb_addon_settings_save';

				}

				// Get addon attributes
				$addon_method = 'admin_page__get_' . str_replace( '-', '_', $addon_page['slug'] ) . '_attr';

				if ( !method_exists( $addon, $addon_method ) )
					$addon_method = 'get_admin_page_attr';

				$addon_attr = $addon->$addon_method( $addon_page );

				if ( !empty( $addon_attr ) ) {

					if ( !empty( $addon_attr['class'] ) ) {

						if ( !is_array( $addon_attr['class'] ) )
							$addon_attr['class'] = explode( ' ', $addon_attr['class'] );

						$attr['class'] = array_merge( $attr['class'], $addon_attr['class'] );
						unset( $addon_attr['class'] );

					}

					if ( !empty( $addon_attr ) )
						$attr = array_merge( $attr, $addon_attr );

				}

			}

		}

		$attr['method'] = 'post';
		$attr['action'] = $url;

		return $attr;

	}


}