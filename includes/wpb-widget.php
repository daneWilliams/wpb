<?php


namespace WPB;


/**
 *
 *	Widget object template
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 *	@see		WP_Widget
 *
 */

class Widget extends \WP_Widget
{


	/**
	 *
	 *	Setup widget
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$id_base			// Optional Base ID for the widget, lowercase and unique
	 *	@param		string 		$name				// Name for the widget displayed on the configuration page
	 *	@param		array 		$widget_options		// Widget options. See wp_register_sidebar_widget() for information on accepted arguments
	 *	@param		array 		$control_options	// Widget control options. See wp_register_widget_control() for information on accepted arguments
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $id_base, $name = '', $widget_options = array(), $control_options = array() )
	{

		// Format name
		if ( !$name )
			$name = $id_base;

		$name = sprintf( '%1$s: %2$s', wpb()->name(), $name );

		// Format ID base
		if ( 'wpb-' != substr( $id_base, 0, 4 ) )
			$id_base = 'wpb-' . $id_base;

		// Setup widget
		parent::__construct( $id_base, $name, $widget_options, $control_options );

		// Set data
		$this->wpb_data( array(
			'id_base' => $this->id_base,
			'name'    => $this->name,
			'options' => $this->widget_options,
			'control' => $this->control_options
		) );

		// Register settings
		add_action( 'wpb/settings/register/widget', array( $this, 'wpb_register_settings' ) );
	}


	/**
	 *
	 *	Output widget
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$args			// Display arguments
	 *	@param		array 		$instance		// Current instance values
	 *
	 *	@since		1.0.0
	 *
	 */

	public function widget( $args, $instance ) 
	{

		// Update settings
		$settings = $this->wpb_update_settings( $instance, false );

		// Get the title
		$title = ( !empty( $instance['title'] ) ? $instance['title'] : '' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		// Before widget
		echo $args['before_widget'];

		// Title
		if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];

		// Content
		$this->wpb_widget_content( $args, $settings );

		// After widget
		echo $args['after_widget'];

	}


	/**
	 *
	 *	Update widget instance
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$new_instance	// New settings for this instance as input by the user via WP_Widget::form()
	 *	@param		array 		$old_instance	// Old settings for this instance
	 *
	 *	@return		array 						// Settings to save, or false to cancel saving
	 *
	 *	@since		1.0.0
	 *
	 */

	public function update( $new_instance, $old_instance ) 
	{

		// Get settings
		$settings = $this->wpb_update_settings( $new_instance, false );

		if ( empty( $settings ) )
			return $old_instance;

		return $settings;

	}


	/**
	 *
	 *	Output the settings update form
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$instance		// Current settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function form( $instance ) 
	{

		// Get settings
		$settings = $this->wpb_update_settings( $instance );

		if ( empty( $settings ) )
			return;

		// Output
		echo '<div class="wpb-widget-settings">';

		foreach ( $settings as $slug => $setting ) {

			wpb( 'admin/setting/field', array( 'setting' => $setting ) );

		}

		echo '</div>';

	}


	/**
	 *
	 *	Output widget content
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$args			// Display arguments
	 *	@param		array 		$instance		// Current instance values
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_widget_content( $args, $instance ) {}


	/**
	 *
	 *	Get widget ID
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// ID
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function wpb_id()
	{

		return ( $this->id ? $this->id : $this->id_base );

	}


	/**
	 *
	 *	Get and set data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Data key
	 *	@param		mixed		$value			// Data value
	 *
	 *	@return		mixed						// Data value, or boolean if setting data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function wpb_data( $key = '', $value = NULL )
	{

		return wpb()->data( $key, $value, 'widget/' . $this->wpb_id() );

	}


	/**
	 *
	 *	Get relative directory path
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

	public function wpb_dir( $append = '' )
	{

		$path = $this->wpb_data( '_dir' );

		// Set path
		if ( !$path ) {

			$c = new \ReflectionClass( $this );

			$path = plugin_dir_path( $c->getFilename() );
			$path = str_replace( '\\', '/', $path );
			$this->wpb_data( '_dir', $path );

		}

		// Append
		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}


	/**
	 *
	 *	Get relative URL path
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

	public function wpb_url( $append = '' )
	{

		$path = $this->wpb_data( '_url' );

		// Set path
		if ( !$path ) {

			$c = new \ReflectionClass( $this );

			$path = plugin_dir_url( $c->getFilename() );
			$path = str_replace( '\\', '/', $path );
			$this->wpb_data( '_url', $path );

		}

		// Append
		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}
	

	/**
	 *
	 *	Load a widget file
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$path			// Path to file, relative to widget directory
	 *	@param		boolean		$once			// Include once
	 *
	 *	@return		string						// Full path to the file, or false if not found
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_file( $path, $once = true )
	{

		// Add file extension
		if ( !substr( strchr( $path, '.' ), 1 ) )
			$path .= '.php';

		$path = $this->wpb_dir( $path );

		if ( !file_exists( $path ) )
			return false;

		// Get file
		if ( $once )
			require_once $path;

		else
			require $path;

		return $path;

	}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_register_settings() {}


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

	public function wpb_register_setting( $key, $data = array(), $default = NULL )
	{

		// Update attributes
		if ( empty( $data['attr']['name'] ) )
			$data['attr']['name'] = $this->get_field_name( $key );

		if ( empty( $data['attr']['id'] ) )
			$data['attr']['id'] = $this->get_field_id( $key );

		return wpb( 'settings/register', $key, $data, $default, 'widget/' . $this->wpb_id() );

	}


	/**
	 *
	 *	Register title setting
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$default		// Default title
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_register_title_setting( $default = '' )
	{

		$this->wpb_register_setting( 'title', array(
			'label' => __( 'Title', 'wpb' ),
			'attr'  => array(
				'class' => 'widefat'
			)
		), $default );

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

	public function wpb_get_settings( $key = '', $default = NULL, $objects = true, $format = false )
	{

		return wpb( 'settings/get', array(
			'key'     => $key, 
			'default' => $default,
			'objects' => $objects,
			'format'  => $format,
			'group'   => 'widget/' . $this->wpb_id()
		) );

	}


	/**
	 *
	 *	Update settings values
	 *
	 *	================================================================ 
	 *
	 *	@param		array		$values			// Values to update
	 *	@param		boolean		$objects		// Return objects instead of values
	 *
	 *	@return		array 						// Settings objects or values
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_update_settings( $values = array(), $objects = true )
	{

		if ( !empty( $values ) ) {

			// Update
			$updated = wpb( 'settings/save', array(
				'values' => $values,
				'group'  => 'widget/' . $this->wpb_id()
			) );

			if ( !empty( $updated ) ) {

				return ( $objects ? $updated['settings'] : $updated['values'] );

			}

		}

		return $this->wpb_get_settings( '', NULL, $objects );

	}


	/**
	 *
	 *	Get settings values
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key(s)
	 *	@param		mixed		$default		// Default value
	 *	@param		boolean		$format			// Format values
	 *
	 *	@return		mixed						// Setting(s)
	 *
	 *	@see		WPB\Settings::get()
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_get_values( $key = '', $default = NULL, $format = true )
	{

		return $this->wpb_get_settings( $key, $default, false, $format );

	}


}