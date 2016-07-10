<?php


namespace WPB;


/**
 *
 *	Data class
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Data
{


	// Data
	private $data;
	private $grouped;

	// Data groups
	private $groups;
	private $default_group;


	/**
	 *
	 *	Get data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Data key
	 *	@param		array 		$data			// Data to search
	 *	@param		string		$group			// Group
	 *
	 *	@return 	mixed						// Data value
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_data( $key = '', $data = array(), $group = '' )
	{

		if ( !$key && !$group && empty( $data ) )
			return $this->data;

		// Get key
		$key = $this->key( $key, $group );

		// Multiple keys
		if ( !empty( $key->keys ) ) {

			$values = array();

			foreach ( $key->keys as $data_key ) {

				$values[ $data_key->orig ] = $this->get_data( $data_key, $data );

			}

			return $values;

		}

		// Get data
		$data = ( !empty( $data ) ? $data : $this->data );	

		if ( isset( $data[ $key->full ] ) )
			return $data[ $key->full ];

		// No data
		if ( empty( $data ) )
			return NULL;

		// Check for grouped
		$grouped = array();

		if ( !$key->full ) {

			if ( empty( $this->grouped[ $key->group->type ] ) )
				return NULL;

			$keylen = strlen( $key->group->type );

			foreach ( $this->grouped[ $key->group->type ] as $data_key ) {

				if ( !isset( $data[ $data_key ] ) )
					continue;

				$grouped[ substr( $data_key, ( $keylen + 1 ) ) ] = $data[ $data_key ];

			}

		} else {

			$keylen = strlen( $key->full ) + 1;

			foreach ( $data as $data_key => $setting ) {

				if ( substr( $data_key, 0, $keylen ) == $key->full . '/' )
					$grouped[ substr( $data_key, $keylen ) ] = $data[ $data_key ];

			}

		}

		if ( empty( $grouped ) )
			return NULL;

		return $grouped;

	}


	/**
	 *
	 *	Set data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Data key
	 *	@param		mixed 		$value			// Value to set
	 *	@param		boolean		$merge			// Merge existing arrays
	 *	@param		string		$group			// Group
	 *
	 *	@return 	boolean						// Set or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function set_data( $key, $value, $merge = true, $group = '' )
	{

		// Multiple values
		if ( is_array( $key ) ) {

			$set = 0;

			foreach ( $key as $sub_key => $sub_value ) {

				if ( $this->set_data( $sub_key, $sub_value, $merge, $group ) )
					$set++;

			}

			return $set;

		}

		// Get key
		$key = $this->key( $key, $group );

		// Get current value
		$data = $this->get_data( $key );

		// Check for private
		if ( '_' == substr( $key->single, 0, 1 ) ) {

			// Already exists
			if ( !is_null( $data ) )
				return false;

		}

		// Deal with arrays
		if ( $merge ) {

			if ( is_array( $value ) ) {

				if ( is_array( $data ) )
					$value = array_merge( $data, $value );

			} elseif ( is_array( $data ) ) {

				$value = array_merge( $data, array( $value ) );

			}

		}

		// Set
		$this->grouped[ $key->group->key ][] = $key->full;
		$this->data[ $key->full ] = $value;

		return true;

	}


	/**
	 *
	 *	Get key object
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Original key
	 *	@param		mixed		$group			// Group
	 *
	 *	@return 	object 						// Key object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function key( $key = '', $group = '' )
	{

		// Already a key object
		if ( isset( $key->single ) )
			return $key;

		// Get from setting
		if ( isset( $key->key ) && isset( $key->value ) )
			return $this->key( $key->key, $group );

		// Create key object
		$object = array(
			'single' => '',
			'keys'   => array(),
			'group'  => '',
			'orig'   => $key,
			'full'   => ''
		);

		// Get key(s)
		if ( $key && !is_array( $key ) ) {

			$keys = explode( ',', $key );

			if ( count( $keys ) > 1 ) {

				$object['keys'] = array_map( 'trim', $keys );

			} else {

				$object['single'] = trim( $key );

			}

		} elseif ( is_array( $key ) ) {

			$object['keys'] = array_map( 'trim', $key );

		}

		// Get sub keys
		if ( !empty( $object['keys'] ) ) {

			$keys = array();

			foreach ( $object['keys'] as $multi_key ) {

				$multi_key = $this->key( $multi_key, $group );

				$keys[ $multi_key->orig ] = $multi_key;

			}

			$object['keys'] = $keys;

		}

		// Get group
		if ( $group )
			$object['group'] = $this->group( $group );

		else
			$object['group'] = $this->group( $object['single'], true );

		// Update data
		if ( !empty( $object['single'] ) ) {

			// Update single key
			$group_strlen = strlen( $object['group']->key );

			if ( substr( $object['single'], 0, $group_strlen ) == $object['group']->key )
				$object['single'] = substr( $object['single'], $group_strlen );

			$object['single'] = trim( $object['single'], '/' );

			// Update full key
			$object['full'] = trim( $object['group']->key . '/' . $object['single'], '/' );

		}

		if ( empty( $object['full'] ) ) {

			$object['full'] = $object['group']->key;

		}

		return (object) $object;

	}


	/**
	 *
	 *	Get group object
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$group			// Original group
	 *	@param		boolean		$from_key		// If this is from a setting key or not
	 *
	 *	@return 	object 						// Group object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function group( $group = '', $from_key = false )
	{

		// Already a group
		if ( isset( $group->type ) )
			return $group;

		// Create group object
		$object = array(
			'key'  => '',
			'id'   => '',
			'type' => ''
		);

		// Get group parts
		if ( $group ) {

			$group_parts = explode( '/', $group );
			$first_part  = array_shift( $group_parts );

			// Get type
			if ( in_array( $first_part, $this->get_groups() ) ) {

				$object['type'] = $first_part;

				// Get ID
				if ( $object['type'] != $this->get_default_group() && !empty( $group_parts ) ) {

					if ( $from_key )
						$object['id'] = array_shift( $group_parts );

					else
						$object['id'] = implode( '/', $group_parts );

				}

			}

		}

		// Set default type
		if ( empty( $object['type'] ) )
			$object['type'] = $this->get_default_group();

		// Set key
		$object['key'] = trim( implode( '/', array( $object['type'], $object['id'] ) ), '/' );

		return (object) $object;

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

		$groups = array( 'plugin', 'object', 'addon', 'setting', 'request', 'admin', 'admin_page' );
		$groups = apply_filters( 'wpb/data/groups', $groups );

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
		$group = apply_filters( 'wpb/data/groups/default', $group );

		// Cache
		$this->default_group = $group;

		return $group;

	}


}