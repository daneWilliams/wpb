<?php


namespace WPB;


/**
 *
 *	Settings class
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Settings extends Data
{


	// Settings groups
	private $groups;
	private $default_group;

	// Settings types
	private $types;
	private $default_type;

	// Settings
	private $data;
	private $grouped;
	private $values;
	private $saved;

	// Errors
	private $errors;


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

		// Load settings
		wpb()->file( 'includes/helpers/settings-register' );
		wpb()->file( 'includes/helpers/settings-format' );
		wpb()->file( 'includes/helpers/settings-sanitize' );
		wpb()->file( 'includes/helpers/settings-validate' );

		// Load saved values
		add_action( 'wpb/setup', array( $this, 'load_saved_values' ), 4 );

		// Register settings
		add_action( 'wpb/setup', array( $this, 'register_settings' ), 5 );

		// Reset data
		add_action( 'wpb/admin/reset', array( $this, 'reset_data' ), 6 );

	}


	/**
	 *
	 *	Get setting(s)
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key(s)
	 *	@param		mixed		$default		// Default value
	 *	@param		boolean		$values			// Return objects
	 *	@param		boolean		$format			// Format values
	 *	@param		string		$group			// Group
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get( $key = '', $default = NULL, $objects = false, $format = true, $group = '' )
	{

		// Get settings
		$settings = $this->get_settings( $key, $group );

		// Get key
		$key = $this->key( $key, $group );

		// Return object(s)
		if ( $objects )
			return $settings;

		// No settings
		if ( empty( $settings ) )
			return $default;

		// Return single value
		if ( $key->single && !is_array( $settings ) )
			return $this->get_value( $settings, $format, $default );

		// Return multiple values
		$values = array();

		foreach ( $settings as $setting_key => $setting ) {

			$key_obj = $this->key( $setting_key );

			// Get value
			$values[ $key_obj->single ] = $this->get_value( $setting, $format, $default );

		}

		return $values;

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
	 *	@param		string		$group			// Group
	 *
	 *	@return		boolean						// Chosen or not
	 *
	 *	@since		1.0.0
	 *
	 */

	public function choice( $key, $value = true, $default = false, $group = '' )
	{

		// Get key
		$key = $this->key( $key, $group );

		// Get setting value
		$setting_value = $this->get( $key );

		if ( is_null( $setting_value ) )
			return $default;

		// Multiple values
		if ( is_array( $setting_value ) ) {

			if ( is_array( $value ) ) {

				foreach ( $value as $multi_value ) {

					if ( !in_array( $multi_value, $setting_value ) )
						return false;

				}

				return true;

			}

			if ( in_array( $value, $setting_value ) )
				return true;

			return false;

		}

		// Single value
		if ( $value == $setting_value )
			return true;

		return false;

	}


	/**
	 *
	 *	Save setting values
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$values			// Values to save
	 *	@param		boolean		$validate		// Validate or not
	 *	@param		boolean		$sanitize		// Sanitise or not
	 *	@param		string		$group			// Group
	 *
	 *	@return		array						// Updated setting objects
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function save( $values, $validate = true, $sanitize = true, $group = '' )
	{

		// Get settings
		$settings = array();
		$invalid  = array();

		foreach ( $values as $key => $value ) {

			$setting = $this->get_settings( $key, $group );

			if ( !$setting ) {

				$invalid[] = $key;
				continue;

			}

			$settings[ $key ] = $setting;

		}

		// No settings to save
		if ( empty( $settings ) )
			return false;

		// Validate
		if ( $validate ) {

			$errors = 0;

			foreach ( $settings as $key => $setting ) {

				$validated = $this->validate_value( $setting, $values[ $key ] );

				if ( !$validated )
					$errors++;

			}

			if ( $errors )
				return false;

		}

		// Update values
		foreach ( $settings as $key => $setting ) {

			$settings[ $key ] = $this->update_value( $setting, $values[ $key ] );

		}

		// Sanitise
		$sanitized = array();

		if ( $sanitize ) {

			foreach ( $settings as $key => $setting ) {

				$sanitized[ $key ] = $this->sanitize_value( $setting, $setting->value );

			}

		}

		// Check for temporary
		$group = $this->group( $group );

		if ( in_array( $group->type, array( 'tmp', 'widget' ) ) || 'id' == $group->id ) {

			return array(
				'settings' => $settings,
				'values'   => ( $sanitize ? $sanitized : $values )
			);

		}

		// Get currently saved
		$db_key = 'wpb_settings_' . str_replace( '/', '-', $group->key );
		$saved  = get_option( $db_key );

		if ( !$saved )
			$saved = array();

		// Get values to save
		$to_save = array();

		foreach ( $settings as $key => $setting ) {

			$to_save_key   = $this->key( $key, $group )->single;
			$to_save_value = ( isset( $sanitized[ $key ] ) ? $sanitized[ $key ] : $setting->value );

			$to_save[ $to_save_key ] = $to_save_value;

		}

		// Save values
		$to_save = array_merge( $saved, $to_save );

		update_option( $db_key, $to_save );

		return array(
			'settings' => $settings,
			'values'   => ( $sanitize ? $sanitized : $values )
		);

	}


	/**
	 *
	 *	Delete saved setting values
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$group			// Group
	 *
	 *	@return		boolean						// Deleted or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function delete( $group = '' )
	{

		// Get group
		$group = $this->group( $group );

		// Remove values
		if ( !empty( $this->saved[ $group->key ] ) ) {

			$this->saved[ $group->key ] = array();

		}

		if ( !empty( $this->grouped[ $group->key ] ) ) {

			foreach ( $this->grouped[ $group->key ] as $key ) {

				if ( isset( $this->values[ $key ] ) )
					unset( $this->values[ $key ] );

				if ( isset( $this->data[ $key ] ) )
					unset( $this->data[ $key ] );

			}

			$this->grouped[ $group->key ] = array();

		}

		// Delete from database
		$db_key  = 'wpb_settings_' . str_replace( '/', '-', $group->key );
		$deleted = delete_option( $db_key );

		return $deleted;

	}
	

	/**
	 *
	 *	Get setting objects
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		string		$group			// Setting group
	 *
	 *	@param		mixed						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_settings( $key = '', $group = '' )
	{

		if ( !$key && $group && is_string( $group ) ) {

			if ( isset( $this->grouped[ $group ] ) )
				return $this->get_settings( $this->grouped[ $group ], $group );

		}

		// Get key
		$key = $this->key( $key, $group );

		// Get settings
		$settings = $this->get_data( $key );

		// Attempt to register
		if ( empty( $settings ) ) {

			$this->register_settings( $key->group->type );

			$settings = $this->get_data( $key );

			if ( empty( $settings ) )
				return false;

		}

		// Return single
		if ( $key->single )
			return (object) $settings;

		// Return multiple
		$objects = array();

		foreach ( $settings as $setting_key => $setting ) {

			$key_obj = $this->key( $setting_key );

			$objects[ $key_obj->single ] = (object) $setting;

		}

		return $objects;

	}


	/**
	 *
	 *	Get value from a setting
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting			// Setting object
	 *	@param		boolean		$format				// Format the value
	 *	@param		mixed		$default			// Default value
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_value( $setting, $format = false, $default = NULL )
	{

		// Not a setting
		if ( !isset( $setting->value ) ) {

			if ( is_string( $setting ) || isset( $setting->single ) )
				$setting = $this->get_settings( $setting );

			if ( !isset( $setting->value ) )
				return $default;

		}

		// Get the key
		$key = $this->key( $setting );

		// Get value
		$value = $default;

		if ( isset( $this->values[ $key->full ] )  )
			$value = $this->values[ $key->full ];

		elseif ( isset( $setting->value ) )
			$value = $setting->value;

		// Format
		if ( $format )
			$value = $this->format_value( $setting, $value );

		return $value;

	}


	/**
	 *
	 *	Validate a setting value
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting		// Setting object
	 *	@param		mixed		$value			// Value to validate
	 *
	 *	@return 	boolean						// Validated or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function validate_value( $setting, $value )
	{
	
		// Validate
		$validated = apply_filters( 'wpb/settings/validate', true, $value, $setting );
		$validated = apply_filters( 'wpb/settings/validate/' . $setting->type, $validated, $value, $setting );

		return $validated;
	
	}


	/**
	 *
	 *	Sanitise a setting value
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting		// Setting object
	 *	@param		mixed		$value			// Value to santise
	 *
	 *	@return 	mixed						// Sanitised value
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function sanitize_value( $setting, $value )
	{

		// Sanitise
		$sanitised = apply_filters( 'wpb/settings/sanitize', $value, $setting );
		$sanitised = apply_filters( 'wpb/settings/sanitize/' . $setting->type, $sanitised, $value, $setting );

		return $sanitised;
	
	}


	/**
	 *
	 *	Update a setting value
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting		// Setting object
	 *	@param		mixed		$value			// Value to update
	 *
	 *	@return 	mixed						// Updated setting or false
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function update_value( $setting, $value )
	{

		// Not a setting
		if ( !isset( $setting->value ) && !isset( $setting->key ) )
			return false;

		$setting->value = $value;
		$this->values[ $setting->key ] = $value;
		$this->set_data( $setting->key, array( 'value' => $value ), true );

		return $setting;

	}
	

	/**
	 *
	 *	Format a setting
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$data			// Setting data
	 *	@param		mixed		$default		// Default value
	 *	@param		string		$key			// Setting key
	 *
	 *	@return 	array 						// Setting
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function format_setting( $data, $default = NULL, $key = '' )
	{

		// Get key
		$key = ( $key ? $this->key( $key ) : false );

		// Create setting
		$setting = array(
			'key'      => '',
			'type'     => '',
			'value'    => $default,
			'label'    => '',
			'sublabel' => '',
			'desc'     => '',
			'required' => false,
			'location' => '',
			'choices'  => array(),
			'attr'     => array(),
			'template' => ''
		);

		// Merge with user data
		$setting = wp_parse_args( $data, $setting );

		// Get type
		$type = ( !empty( $setting['type'] ) ? $setting['type'] : '' );

		if ( !$type || !in_array( $type, $this->get_types() ) )
			$type = $this->get_default_type();

		// Allow custom formatting
		$setting = apply_filters( 'wpb/settings/format', $setting, $type, $key );
		$setting = apply_filters( 'wpb/settings/format/' . $type, $setting, $key );

		// Set key
		if ( $key ) {

			$key = $this->key( $key );

			$setting['key'] = $key->full;

		}

		// Set type
		$setting['type'] = $type;

		// Set value
		$setting['value'] = $default;

		// Set template
		if ( !$setting['template'] )
			$setting['template'] = 'admin/templates/settings/setting-' . $type;

		return $setting;

	}


	/**
	 *
	 *	Format a value
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$setting		// Setting object
	 *	@param		mixed		$value			// Value to format
	 *
	 *	@return 	mixed 						// Formatted value
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function format_value( $setting, $value )
	{

		$value = apply_filters( 'wpb/settings/format/value/' . $setting->type, $value, $setting );
		$value = apply_filters( 'wpb/settings/format/value/' . $setting->key, $value );

		return $value;

	}


	/**
	 *
	 *	Register setting
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		array 		$data			// Setting data
	 *	@param		mixed		$default		// Default value
	 *	@param		string		$group			// Group
	 *
	 *	@return		object						// Registered setting
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register( $key, $data = array(), $default = NULL, $group = '' )
	{

		$key = $this->key( $key, $group );

		// Not a single key
		if ( empty( $key->single ) )
			return false;

		// Already registered
		if ( isset( $this->data[ $key->full ] ) )
			return $this->data[ $key->full ];

		// Format setting
		$setting = $this->format_setting( $data, $default, $key );

		// Set value
		if ( isset( $this->saved[ $key->group->key ][ $key->single ] ) )
			$setting['value'] = $this->saved[ $key->group->key ][ $key->single ];

		$value = $setting['value'];

		$this->values[ $key->full ] = $value;
		$this->grouped[ $key->group->key ][] = $key->full;

		// Add setting
		$this->set_data( $key, $setting, false, $key->group->key );

		return $setting;

	}


	/**
	 *
	 *	Load saved values
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function load_saved_values( $group = '' )
	{

		if ( is_null( $this->saved ) )
			$this->saved = array();

		// Get groups
		$groups = ( $group ? array( $group ) : $this->get_groups() );

		if ( empty( $groups ) )
			return;

		foreach ( $groups as $group ) {

			$group = $this->group( $group );

			// Skip temporary
			if ( in_array( $group->type, array( 'tmp', 'widget' ) ) )
				continue;

			// Already loaded
			if ( isset( $this->saved[ $group->key ] ) )
				continue;

			// Get values
			$db_key = 'wpb_settings_' . str_replace( '/', '-', $group->key );
			$saved  = get_option( $db_key );

			if ( $saved )
				$this->saved[ $group->key ] = $saved;

		}
	
	}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$group				// Group
	 *
	 *	@return		array 							// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_settings( $group = '' )
	{

		// Get group
		$group = $this->group( $group );

		// Register
		if ( !isset( $this->grouped[ $group->key ] ) ) {

			// Hook
			do_action( 'wpb/settings/register' );
			do_action( 'wpb/settings/register/' . $group->type );

			if ( !isset( $this->grouped[ $group->key ] ) )
				$this->grouped[ $group->key ] = array();

		} elseif ( in_array( $group->type, array( 'tmp', 'widget' ) ) ) {

			do_action( 'wpb/settings/register/' . $group->type );

		}

		// Get settings
		$settings = array();

		if ( empty( $this->grouped[ $group->key ] ) )
			return array();

		foreach ( $this->grouped[ $group->key ] as $key ) {

			$settings[ $key ] = $this->data[ $key ];

		}

		return $settings;
	
	}


	/**
	 *
	 *	Add setting validation error
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		string		$error			// Error message
	 *	@param		string		$group			// Group
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_error( $key, $error, $group = '' )
	{

		// Get setting
		$setting = $this->get_settings( $key, $group );

		if ( !$setting )
			return false;

		// Get key
		$key = $this->key( $key, $group );

		// Add error
		$this->errors[ $key->group->key ][ $key->single ][] = $error;

		return true;

	}


	/**
	 *
	 *	Get setting validation errors
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		string		$group			// Group
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_errors( $key = '', $group = '' )
	{

		// Return all
		if ( !$key && !$group )
			return $this->errors;

		// Get key
		$key = $this->key( $key, $group );

		// No errors
		if ( empty( $this->errors[ $key->group->key ] ) )
			return false;

		// Grouped errors
		if ( !$key->single )
			return $this->errors[ $key->group->key ];

		// No errors
		if ( empty( $this->errors[ $key->group->key ][ $key->single ] ) )
			return false;

		return $this->errors[ $key->group->key ][ $key->single ];

	}


	/**
	 *
	 *	Clear validation errors
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		string		$group			// Group
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function clear_errors( $key = '', $group = '' )
	{

		// Clear all
		if ( !$key && !$group ) {

			$this->errors = array();
			return true;

		}

		// Get key
		$key = $this->key( $key, $group );

		// Clear group
		if ( !$key->single ) {

			if ( !isset( $this->errors[ $key->group->key ] ) )
				return false;

			$this->errors[ $key->group->key ] = array();
			return true;

		}

		if ( !isset( $this->errors[ $key->group->key ][ $key->single ] ) )
			return false;

		$this->errors[ $key->group->key ][ $key->single ] = false;

		return true;

	}


	/**
	 *
	 *	Get supported groups
	 *
	 *	================================================================ 
	 *
	 *	@return 	array 						// Groups
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_groups()
	{

		// Cached
		if ( !is_null( $this->groups ) )
			return $this->groups;

		$groups = array( 'plugin', 'addon', 'tmp', 'widget' );
		$groups = apply_filters( 'wpb/settings/groups', $groups );

		// Cache
		$this->groups = $groups;

		return $groups;

	}


	/**
	 *
	 *	Get default group
	 *
	 *	================================================================ 
	 *
	 *	@return 	string 						// Group
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_default_group()
	{

		// Cached
		if ( !is_null( $this->default_group ) )
			return $this->default_group;

		$group = 'plugin';
		$group = apply_filters( 'wpb/settings/groups/default', $group );

		// Cache
		$this->default_group = $group;

		return $group;

	}


	/**
	 *
	 *	Get supported setting types
	 *
	 *	================================================================ 
	 *
	 *	@return 	array 						// Types
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_types()
	{

		// Cached
		if ( !is_null( $this->types ) )
			return $this->types;

		$types = array( 'text', 'textarea', 'select', 'checkbox', 'radio' );
		$types = apply_filters( 'wpb/settings/types', $types );

		// Cache
		$this->types = $types;

		return $types;

	}


	/**
	 *
	 *	Get default setting type
	 *
	 *	================================================================ 
	 *
	 *	@return 	string 						// Type
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_default_type()
	{

		// Cached
		if ( !is_null( $this->default_type ) )
			return $this->default_type;

		$type = 'text';
		$type = apply_filters( 'wpb/settings/types/default', $type );

		// Cache
		$this->default_type = $type;

		return $type;

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

		if ( empty( $reset_settings['plugin']['settings'] ) )
			return false;

		if ( $this->delete( 'plugin' ) ) {

			wpb( 'admin/notification/success', array(
				'text' => __( 'Plugin settings deleted', 'wpb' ),
				'dismiss' => true
			) );
			
			return true;

		}

	}
	

}