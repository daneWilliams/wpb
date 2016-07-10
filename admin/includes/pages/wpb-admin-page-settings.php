<?php


namespace WPB;


/**
 *
 *	Admin settings page object template
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


class Settings_Admin_Page extends Admin_Page
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

		// Get setting class
		wpb()->file( 'includes/wpb-object-setting' );

		// Get settings
		$settings = wpb( 'settings/get', 'objects=1' );

		// Set tabs
		$grouped = wpb( 'admin/settings/grouped', array( 'settings' => $settings, 'grouped' => true, 'tabs' => true ) );

		if ( !empty( $grouped['tabbed'] ) ) {

			$tabs = array();

			foreach ( $grouped['tabbed'] as $group => $locations ) {

				if ( empty( $locations ) )
					continue;

				// Get slug
				$key  = wpb( ':settings/key', $group );
				$key  = str_replace( '/', '-', $key->single );
				$slug = sanitize_title( $key );

				$tabs[ $slug ] = array(
					'name' => apply_filters( 'wpb/admin/settings/tab/name', $slug, $slug ),
					'url'  => $this->url( 'group=' . $slug )
				);

			}

			// Filter
			$tabs = apply_filters( 'wpb/admin/settings/tabs', $tabs );

			// Set
			$this->data( 'tabs', $tabs );

		}

		// Save settings
		$this->save_settings();

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

	protected function save_settings() 
	{

		if ( empty( $_POST['wpb_settings'] ) )
			return false;

		if ( !current_user_can( $this->data( 'capability' ) ) )
			return false;

		// Check nonce
		$nonce = wpb()->nonce();

		if ( !isset( $_POST[ $nonce ] ) )
			return false;

		if ( !wp_verify_nonce( $_POST[ $nonce ], 'settings_save' ) )
			return false;

		// Save settings
		$saved = wpb( 'settings/save', array( 'values' => $_POST['wpb_settings'] ) );

		// Not saved
		if ( !$saved ) {

			wpb( 'admin/notification/error', array(
				'text' => __( 'Settings could not be saved', 'wpb' ),
				'dismiss' => false
			) );

			return false;

		}

		// Saved
		echo wpb( 'admin/notification/success', __( 'Settings saved', 'wpb' ) );

		return true;

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
	
	public function get_tabs( $active = '' ) 
	{

		// Get current page
		if ( !$active ) {

			if ( isset( $_GET['group'] ) )
				$active = $_GET['group'];

		}

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

		// Get settings
		$settings = wpb( 'settings/get', 'objects=1' );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $settings, 'grouped' => true, 'tabs' => ( isset( $_GET['group'] ) ? $_GET['group'] : true ) ) );

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

		echo '<div class="wpb-page-buttons"><div class="wpb-page-buttons-container">';

		// Submit button
		echo '<button type="submit" id="wpb-settings-submit" class="wpb-submit button button-primary" data-wpb-loading-text="' . esc_attr( __( 'Saving&hellip;', 'wpb' ) ) . '">';
		_e( 'Save Settings', 'wpb' );
		echo '</button>';

		// Spinner
		echo '<span class="spinner"></span>';

		echo '</div></div>';

		// Nonce
		wp_nonce_field( 'settings_save', wpb()->nonce() );

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

		$attr['class'][] = 'wpb-page-settings';
		$attr['data-wpb-action'] = 'wpb_settings_save';

		return $attr;

	}


}