<?php


namespace WPB;


/**
 *
 *	Contact addon
 *
 *	================================================================ 
 *
 *	@version	1.0.0
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Contact_Addon extends Addon
{


	// Profile post type
	public $profile_post_type = 'wpb_contact_profile';

	// Profile settings
	protected $profile_settings_registered = false;
	protected $profile_fields_registered   = false;

	// Profile IDs
	protected $profile_ids;


	/**
	 *
	 *	Setup addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		array 		$data			// Addon data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $slug, $data = array() )
	{

		parent::__construct( $slug, $data );

		// Reset data
		add_filter( 'wpb/admin/reset/settings/addon/contact', array( $this, 'add_reset_settings' ), 10 );

	}


	/**
	 *
	 *	Fired when the addon is initialised
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init() 
	{

		// Register post types
		add_action( 'init', array( $this, 'register_post_types' ) );

		// Add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_profile_meta_boxes' ) );
		add_action( 'save_post_' . $this->profile_post_type, array( $this, 'save_meta' ), 10, 3 );

		// Add assets
		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_assets' ) );

		// Modify setting locations
		add_filter( 'wpb/admin/settings/locations/names', array( $this, 'add_setting_location_names' ), 10, 2 );

		// Output no profiles message
		add_action( 'wpb/admin/setting/choices/empty', array( $this, 'output_profile_setting_message' ) );

		// Register widget
		add_action( 'widgets_init', array( $this, 'register_widget' ), 5 );

	}


	/**
	 *
	 *	Fired when the addon is activated
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function activate() 
	{

		// Check if default profile has already been created
		if ( get_option( 'wpb_contact_addon_default_profile_created' ) )
			return;

		// Create default profile
		wp_insert_post( array(
			'post_type'   => $this->profile_post_type,
			'post_title'  => __( 'Default', 'wpb' ),
			'post_name'   => 'default',
			'post_status' => 'publish'
		) );

		// Prevent it being created again
		update_option( 'wpb_contact_addon_default_profile_created', true, false );

	}


	/**
	 *
	 *	Register requests
	 *
	 *	================================================================ 
	 *
	 *	@see		WPB\Addon::register_request()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_requests()
	{

		// contact/detail
		$this->register_request( 'detail', 'get_detail', array(
			'key'     => '',
			'profile' => ''
		) );

		// contact/*
		$this->register_request( 'detail/*', 'detail', 'key' );

		// contact/profiles
		$this->register_request( 'profiles', 'get_profiles' );

		// contact/profile
		$this->register_request( 'profile', 'get_profile', array(
			'id' => ''
		) );

		// contact/profile/*
		$this->register_request( 'profile/*', 'profile', 'id' );

		// contact/output
		$this->register_request( 'output', 'request__output', array(
			'profile' => '',
			'fields'  => array(),
			'schema'  => '',
			'icons'   => array(),
			'labels'  => array(),
			'echo'    => true,
			'return'  => true
		) );

	}


	/**
	 *
	 *	Tell WPB there are settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function has_settings()
	{

		return true;
	
	}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@see		WPB\Addon::register_setting()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_settings()
	{

		// Default profile
		$this->register_setting( 'default_profile', array(
			'type'  => 'select',
			'label' => __( 'Default Profile', 'wpb' ),
			'choices' => $this->get_profile_setting_choices(),
			'location' => 'profiles'
		), 'default' );

		// Stylesheet
		$this->register_setting( 'stylesheet', array(
			'type'  => 'boolean',
			'label' => __( 'Stylesheet', 'wpb' ),
			'text'  => __( 'Use addon styles', 'wpb' ),
			'location' => 'styles'
		), true );

		// contactPoint icon
		$this->register_setting( 'icon/contactPoint', array(
			'label' =>  __( 'Contact Person Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'admin-users' );

		// Organisation icon
		$this->register_setting( 'icon/org_name', array(
			'label' =>  __( 'Organisation Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'admin-users' );

		// Email icon
		$this->register_setting( 'icon/email', array(
			'label' =>  __( 'Email Address Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'email' );

		// Phone icon
		$this->register_setting( 'icon/telephone', array(
			'label' =>  __( 'Phone Number Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'phone' );

		// Fax icon
		$this->register_setting( 'icon/faxNumber', array(
			'label' =>  __( 'Fax Number Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'fax' );

		// Website icon
		$this->register_setting( 'icon/url', array(
			'label' =>  __( 'Website Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'admin-links' );

		// Address icon
		$this->register_setting( 'icon/PostalAddress', array(
			'label' =>  __( 'Address Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'building' );

		// Country icon
		$this->register_setting( 'icon/addressCountry', array(
			'label' =>  __( 'Country Icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'icons'
		), 'admin-site' );

	}


	/**
	 *
	 *	Add setting location names
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_setting_location_names( $names, $locations = array() )
	{

		// Profiles
		$names['addon/contact/profiles'] = __( 'Profiles', 'wpb' );

		// Styles
		$names['addon/contact/styles'] = __( 'Styles', 'wpb' );

		// Icons
		$names['addon/contact/icons'] = __( 'Icons', 'wpb' );

		return $names;

	}


	/**
	 *
	 *	Get profiles
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profiles()
	{

		// Cached
		$profiles = $this->data( 'profiles' );

		if ( $profiles )
			return $profiles;

		$profiles = array();

		// Get profiles
		$profile_query_args = array(
			'posts_per_page' => '-1',
			'post_type' => $this->profile_post_type
		);

		$profiles_query = new \WP_Query( $profile_query_args );

		if ( $profiles_query->have_posts() ) :

			while ( $profiles_query->have_posts() ) : $profiles_query->the_post();

				$profiles[ get_the_ID() ] = get_post();

			endwhile;

		endif;

		wp_reset_postdata();

		// Format profiles
		if ( !empty( $profiles ) ) {

			$formatted = array();

			foreach ( $profiles as $post_id => $profile_post ) {

				$profile = (object) array(
					'id'       => $post_id,
					'name'     => $profile_post->post_title,
					'slug'     => $profile_post->post_name,
					'desc'     => $profile_post->post_excerpt,
					'fields'   => $this->get_profile_fields( $post_id ),
					'settings' => $this->get_profile_settings( $post_id )
				);

				$formatted[ $post_id ] = $profile;
				$this->profile_ids[ $profile_post->post_name ] = $post_id;

			}

			$profiles = $formatted;

		}

		// Cache
		$this->data( 'profiles', $profiles );

		return $profiles;

	}


	/**
	 *
	 *	Get profile
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile( $id )
	{

		// Deal with request
		if ( is_array( $id ) && isset( $id['id'] ) )
			$id = $id['id'];

		if ( !$id )
			return false;

		if ( isset( $this->profile_ids[ $id ] ) )
			$id = $this->profile_ids[ $id ];

		// Get profiles
		$profiles = $this->get_profiles();

		if ( !isset( $profiles[ $id ] ) )
			return false;

		return $profiles[ $id ];

	}


	/**
	 *
	 *	Get default profile
	 *
	 *	================================================================ 
	 *
	 *	@return		object						// Profile object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_default_profile()
	{

		$id = $this->get_default_profile_id();

		if ( !$id )
			return false;

		return $this->get_profile( $id );

	}


	/**
	 *
	 *	Get default profile ID
	 *
	 *	================================================================ 
	 *
	 *	@return		int							// Profile ID or NULL
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_default_profile_id()
	{

		return $this->settings( 'default_profile' );

	}


	/**
	 *
	 *	Output profile
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__output( $args = array() )
	{

		// Get profile
		if ( empty( $args['profile'] ) )
			$args['profile'] = $this->get_default_profile_id();

		$profile = $this->get_profile( $args['profile'] );

		if ( empty( $profile ) )
			return;

		// Get schema
		if ( empty( $args['schema'] ) && !empty( $profile->settings['schema/itemtype'] ) ) {

			$args['schema'] = $profile->settings['schema/itemtype'];

			if ( !empty( $profile->settings['schema/itemtype/custom'] ) )
				$args['schema'] = $profile->settings['schema/itemtype/custom'];

		}

		// Remove unwanted fields
		$fields  = $profile->fields;
		$address = array( 'address_1', 'address_2', 'address_town', 'address_region', 'address_postcode' );

		if ( !empty( $args['fields'] ) ) {

			if ( !is_array( $args['fields'] ) )
				$args['fields'] = array_map( 'trim', explode( ',', $args['fields'] ) );

			foreach ( $fields as $field => $value ) {

				if ( !in_array( $field, $args['fields'] ) ) {

					if ( !in_array( $field, $address ) ) {
				
						unset( $fields[ $field ] );
						continue;

					}

					// Remove address fields
					if ( !in_array( 'address', $args['fields'] ) )
						unset( $fields[ $field ] );

				}

			}

		}

		// Get mapped schema values
		$icons  = ( in_array( 'icons', $args['fields'] )  ? $args['icons']  : array() );
		$labels = ( in_array( 'labels', $args['fields'] ) ? $args['labels'] : array() );
		$values = $this->get_schema_values( $fields, $args['schema'], array(), $icons, $labels );

		if ( empty( $values ) )
			return false;

		return $this->get_schema_html( $values, $args['schema'] );

	}


	/**
	 *
	 *	Get mapped schema values
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$fields			// Field values
	 *	@param		string		$schema			// Schema type
	 *	@param		array 		$props			// Allowed properties
	 *	@param		array 		$icons			// Item icons
	 *	@param		array 		$labels			// Property labels
	 *
	 *	@return		array 						// Values
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_schema_values( $fields, $schema = '', $props = array(), $icons = array(), $labels = array() )
	{

		// Get item
		$item = $this->get_schema_item( $schema );

		// Get values
		$values = array();

		if ( empty( $item['props'] ) )
			return $values;

		foreach ( $item['props'] as $prop ) {

			if ( $props && !in_array( $prop, $props ) )
				continue;

			$icon  = ( isset( $icons[ $prop ] )  ? $icons[ $prop ]  : '' );
			$label = ( isset( $labels[ $prop ] ) ? $labels[ $prop ] : '' );

			// Sub item
			if ( isset( $item['items'][ $prop ] ) || ( isset( $item['items'] ) && in_array( $prop, $item['items'] ) ) ) {

				if ( isset( $item['items'][ $prop ]['item'] ) ) {

					$item_props = array();

					// Filter properties
					if ( !empty( $item['items'][ $prop ]['props'] ) )
						$item_props = $item['items'][ $prop ]['props'];

					if ( !$icon && isset( $icons[ $item['items'][ $prop ]['item'] ] ) )
						$icon = $icons[ $item['items'][ $prop ]['item'] ];

					$values[ $item['items'][ $prop ]['item'] ] = array(
						'_prop'   => $prop,
						'_values' => $this->get_schema_values( $fields, $item['items'][ $prop ]['item'], $item_props ),
						'_icon'   => $icon
					);

					continue;

				}

				$item_prop = $prop;

				if ( isset( $item['items'][ $prop ] ) )
					$item_prop = $item['items'][ $prop ];

				$values[ $prop ] = array(
					'_prop'   => $prop,
					'_values' => $this->get_schema_values( $fields, $item_prop ),
					'_icon'   => $icon
				);

				continue;

			}

			// Map schema property to field value
			if ( isset( $item['fields'][ $prop ] ) ) {

				if ( isset( $fields[ $item['fields'][ $prop ] ] ) ) {

					if ( isset( $labels[ $item['fields'][ $prop ] ] ) )
						$label = $labels[ $item['fields'][ $prop ] ];

					if ( isset( $icons[ $item['fields'][ $prop ] ] ) )
						$icon = $icons[ $item['fields'][ $prop ] ];

					$values[ $prop ] = array(
						'prop'  => $prop,
						'value' => $fields[ $item['fields'][ $prop ] ],
						'icon'  => $icon,
						'label' => $label
					);

				}

				continue;

			}

			// Use field name as schema property
			if ( isset( $fields[ $prop ] ) ) {

				$values[ $prop ] = array(
					'prop'  => $prop,
					'value' => $fields[ $prop ],
					'icon'  => $icon,
					'label' => $label
				);

			}

		}

		return $values;

	}


	/**
	 *
	 *	Get schema HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$values			// Properties/items and values
	 *	@param		string		$schema			// Schema type
	 *	@param		string		$prop			// Item property
	 *	@param		string		$icon			// Item icon
	 *
	 *	@return		string 						// HTML
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_schema_html( $values, $schema = '', $prop = '', $icon = '' )
	{

		$html = '';

		// Check there are values
		$has_values = false;

		foreach ( $values as $value ) {

			if ( empty( $value ) )
				continue;

			if ( !is_array( $value ) ) {

				$has_values = true;
				break;

			}

			foreach ( $value as $sub_value ) {

				if ( !empty( $sub_value ) ) {

					$has_values = true;
					break;

				}

			}

		}

		if ( !$has_values )
			return;

		// Add schema HTML
		if ( $schema && 'default' != $schema ) {

			// Get attributes
			$attr = array(
				'class' => array( 'contact-item', 'contact-item-' . strtolower( $schema ) ),
				'itemscope',
				'itemtype' => 'http://schema.org/' . $schema
			);

			if ( $prop )
				$attr['itemprop'] = $prop;

			if ( $icon )
				$attr['class'][] = 'with-icon';

			switch ( $schema ) {

				// PostalAddress
				case 'PostalAddress' :

					$html .= '<address' . wpb( 'attr', $attr ) . '>';

				break;

				// Default
				default :

					$html .= '<div' . wpb( 'attr', $attr ) . '>';

				break;

			}

			// Icon
			if ( $icon ) {

				$html .= wpb( 'icon', $icon );
				$icon  = '';

			}	

		}

		// Add items/properties
		foreach ( $values as $prop => $item ) {

			// Add item HTML
			if ( isset( $item['_values'] ) ) {

				$html .= $this->get_schema_html( $item['_values'], $prop, ( isset( $item['_prop'] ) ? $item['_prop'] : '' ), ( isset( $item['_icon'] ) ? $item['_icon'] : '' ) );
				continue;

			}

			if ( empty( $item['value'] ) )
				continue;

			$value = $item['value'];
			$prop  = ( !empty( $item['prop'] ) ? $item['prop'] : $prop );

			// Get attributes
			$attr = array( 
				'itemprop' => $item['prop'],
				'class'    => array( 'contact-detail', 'detail-' . strtolower( $prop ) ),
			);

			// Get icon
			$icon = ( !empty( $item['icon'] ) ? $item['icon'] : '' );

			if ( $icon ) {

				$icon = wpb( 'icon', $icon );

				$attr['class'][] = 'with-icon';

			}

			// Get label
			$label = ( !empty( $item['label'] ) ? $item['label'] : '' );

			if ( $label ) {

				$label = '<label class="contact-label">' . $label . '</label>';

				$attr['class'][] = 'with-label';

			}

			// Add single property HTML
			switch ( $prop ) {

				// Email
				case 'email' :

					unset( $attr['itemprop'] );

					$html .= '<div' . wpb( 'attr', $attr ) . '>';

					$html .= $label;
					$html .= $icon;

					$html .= '<a href="mailto:' . esc_attr( $value ) . '" itemprop="email">' . $value . '</a>';

					$html .= '</div>';

				break;

				// URL
				case 'url' :

					unset( $attr['itemprop'] );

					$html .= '<div' . wpb( 'attr', $attr ) . '>';

					$html .= $label;
					$html .= $icon;

					$html .= '<a href="' . esc_attr( $value ) . '" itemprop="url">' . $value . '</a>';

					$html .= '</div>';

				break;

				// Address
				case 'streetAddress' :
				case 'addressLocality' :
				case 'addressRegion' :
				case 'postalCode' :

					unset( $attr['itemprop'] );

					$html .= '<span' . wpb( 'attr', $attr ) . '>';

					$html .= '<span itemprop="' . $prop . '">' . $value . '</span>';
					$html .= '<span class="contact-separator">,</span>';

					$html .= '</span>';

				break;

				// Country
				case 'addressCountry' :

					if ( $icon || $label )
						unset( $attr['itemprop'] );

					$countries = $this->get_countries();
					$country   = ( isset( $countries[ $value ] ) ? $countries[ $value ] : $value );

					$html .= '<span' . wpb( 'attr', $attr ) . '>';

					$html .= $label;
					$html .= $icon;

					if ( $icon || $label )
						$html .= '<span itemprop="addressCountry">' . $country . '</span>';

					else
						$html .= $country;

					$html .= '</span>';

				break;

				// Default
				default :

					if ( $icon || $label )
						unset( $attr['itemprop'] );

					$html .= '<div' . wpb( 'attr', $attr ) . '>';

					$html .= $label;
					$html .= $icon;

					if ( $icon || $label )
						$html .= '<span itemprop="' . $prop . '">' . $value . '</span>';

					else
						$html .= $value;

					$html .= '</div>';

				break;

			}

			$html .= PHP_EOL;

		}

		if ( $schema && 'default' != $schema ) {

			switch ( $schema ) {

				// PostalAddress
				case 'PostalAddress' :

					$html .= '</address>';

				break;

				// Default
				default :

					$html .= '</div>';

				break;

			}	

		}

		return $html;

	}


	/**
	 *
	 *	Get schema item
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$schema			// Schema type
	 *
	 *	@return		array 						// Fields
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_schema_item( $schema = '' )
	{

		// Get supported items
		$items = $this->get_schema_items();

		if ( empty( $items ) )
			return false;

		if ( !$schema )
			$schema = 'default';

		if ( !isset( $items[ $schema ] ) ) {

			if ( 'default' == $schema )
				return false;

			$schema = 'default';

		}

		return $items[ $schema ];

	}


	/**
	 *
	 *	Get supported schema items
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_schema_items()
	{
	
		// Cached
		$items = $this->data( 'schema_items' );

		if ( $items )
			return $items;

		$items = array();

		// Default
		$items['default'] = array(
			'props' => array( 'contactPoint', 'name', 'address', 'email', 'telephone', 'faxNumber', 'url' ),
			'items' => array( 
				'contactPoint' => array(
					'item'  => 'Person',
					'props' => array( 'name', 'jobTitle' )
				),
				'address' => array(
					'item' => 'PostalAddress'
				)
			),
			'fields' => array(
				'name'      => 'org_name', 
				'telephone' => 'phone',
				'faxNumber' => 'fax', 
				'url'       => 'website' 
			)
		);

		// Person
		$items['Person'] = array(
			'props'  => array( 'name', 'jobTitle', 'PostalAddress', 'email', 'telephone', 'faxNumber', 'url' ),
			'items'  => array(
				'PostalAddress'
			),
			'fields' => array( 
				'name'      => 'contact_name',
				'jobTitle'  => 'job_title',
				'telephone' => 'phone', 
				'faxNumber' => 'fax', 
				'url'       => 'website' 
			)
		);

		// PostalAddress
		$items['PostalAddress'] = array(
			'props'  => array( 'streetAddress', 'addressLocality', 'addressRegion', 'postalCode', 'addressCountry' ),
			'fields' => array( 
				'streetAddress'   => 'address_1', 
				'addressLocality' => 'address_2', 
				'addressRegion'   => 'address_region', 
				'postalCode'      => 'address_postcode', 
				'addressCountry'  => 'address_country'
			)
		);

		// Filter
		$items = apply_filters( 'wpb/contact/schema/items', $items );

		// Cache
		$this->data( 'schema_items', $items );

		return $items;
	
	}


	/**
	 *
	 *	Get profile fields
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *	@param		boolean		$objects		// Return objects
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_fields( $post_id = '', $objects = false )
	{

		// Get post ID
		if ( !$post_id )
			$post_id = get_the_ID();

		if ( !$post_id )
			return false;

		// Register settings
		if ( !isset( $this->profile_fields_registered[ $post_id ] ) )
			$this->register_profile_fields( $post_id );

		// Get settings
		$settings = wpb( 'settings/get', array(
			'objects' => $objects,
			'group'   => 'tmp/addon-contact_profile-fields_' . $post_id 
		) );

		return $settings;

	}


	/**
	 *
	 *	Get profile settings
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *	@param		boolean		$objects		// Return objects
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_settings( $post_id = '', $objects = false )
	{

		// Get post ID
		if ( !$post_id )
			$post_id = get_the_ID();

		if ( !$post_id )
			return false;

		// Register settings
		if ( !isset( $this->profile_settings_registered[ $post_id ] ) )
			$this->register_profile_settings( $post_id );

		// Get settings
		$settings = wpb( 'settings/get', array(
			'objects' => $objects,
			'group'   => 'tmp/addon-contact_profile-settings_' . $post_id 
		) );

		return $settings;

	}


	/**
	 *
	 *	Register profile fields
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_profile_fields( $post_id = '' )
	{

		// Get post ID
		if ( !$post_id )
			$post_id = get_the_ID();

		if ( !$post_id )
			return false;

		// Get meta values
		$values = $this->get_profile_fields_meta( $post_id );

		// Get group
		$group = 'tmp/addon-contact_profile-fields_' . $post_id;

		// Get setting fields
		$settings = $this->get_profile_fields_data();

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update ID
			$data['attr']['id'] = 'wpb-contact-profile-field_' . str_replace( '/', '-', $key );

			// Update name attribute
			$data['attr']['name'] = 'wpb_contact_profile[fields][' . $key . ']';

			$registered[ $key ] = wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		return $registered;

	}


	/**
	 *
	 *	Register profile settings
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *
	 *	@return		array 						// Registered settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_profile_settings( $post_id = '' )
	{

		// Get post ID
		if ( !$post_id )
			$post_id = get_the_ID();

		if ( !$post_id )
			return false;

		// Get meta values
		$values = $this->get_profile_settings_meta( $post_id );

		// Get group
		$group = 'tmp/addon-contact_profile-settings_' . $post_id;

		// Get settings
		$settings = $this->get_profile_settings_data();

		if ( empty( $settings ) )
			return false;

		foreach ( $settings as $key => $setting ) {

			$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
			$default = ( isset( $values[ $key ] ) ? $values[ $key ] : ( isset( $setting['default'] ) ? $setting['default'] : NULL ) );

			// Update ID
			$data['attr']['id'] = 'wpb-contact-profile-setting_' . str_replace( '/', '-', $key );

			// Update name attribute
			$data['attr']['name'] = 'wpb_contact_profile[settings][' . $key . ']';

			wpb( 'settings/register', array(
				'key'     => $key,
				'data'    => $data,
				'default' => $default,
				'group'   => $group
			) );

		}

		return true;

	}


	/**
	 *
	 *	Get profile meta
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *
	 *	@return		array 						// Values
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_meta( $post_id = '' )
	{

		// Get post ID
		if ( !$post_id )
			$post_id = get_the_ID();

		if ( !$post_id )
			return false;

		// Cached
		$meta = $this->data( 'meta_' . $post_id );

		if ( $meta )
			return $meta;

		// Get meta
		$meta = get_post_meta( $post_id, 'wpb_profile', true );

		if ( !$meta )
			$meta = array();

		// Cache
		$this->data( 'meta_' . $post_id, $meta );

		return $meta;

	}


	/**
	 *
	 *	Get profile fields meta
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *
	 *	@return		array 						// Values
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_fields_meta( $post_id = '' )
	{

		// Get meta
		$meta = $this->get_profile_meta( $post_id );

		return ( isset( $meta['fields'] ) ? $meta['fields'] : array() );

	}


	/**
	 *
	 *	Get profile settings meta
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$post_id		// Post ID
	 *
	 *	@return		array 						// Values
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_settings_meta( $post_id = '' )
	{

		// Get meta
		$meta = $this->get_profile_meta( $post_id );

		return ( isset( $meta['settings'] ) ? $meta['settings'] : array() );

	}


	/**
	 *
	 *	Save meta values
	 *
	 *	================================================================ 
	 *
	 * 	@param 		int 		$post_id 		// The post ID
	 * 	@param 		post 		$post 			// The post object
	 * 	@param 		bool 		$update 		// Whether this is an existing post being updated or not
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function save_meta( $post_id, $post, $update )
	{

		if ( empty( $_REQUEST['wpb_contact_profile'] ) )
			return false;

		// Prevent updating on autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// Check user can edit post
		if ( !current_user_can( 'manage_options' ) )
			return;

		// Get current values
		$values = array(
			'fields'   => $this->get_profile_fields( $post_id ),
			'settings' => $this->get_profile_settings( $post_id )
		);

		// Save values
		foreach ( $_REQUEST['wpb_contact_profile'] as $type => $type_values ) {

			$group = 'tmp/addon-contact_profile-' . $type . '_' . $post_id;

			$saved = wpb( 'settings/save', array(
				'values'   => $type_values, 
				'validate' => true,
				'sanitize' => true,
				'group'    => $group 
			) );

			if ( empty( $saved['values'] ) )
				continue;

			$values[ $type ] = array_merge( $values[ $type ], $saved['values'] );

		}

		// Update meta
		update_post_meta( $post_id, 'wpb_profile', $values );
	
	}
	

	/**
	 *
	 *	Add profile meta boxes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_profile_meta_boxes()
	{

		// Profile fields
		add_meta_box( 
			'wpb-contact-profile-fields', 
			__( 'Profile Fields', 'wpb' ), 
			array( $this, 'output_profile_meta_settings' ), 
			$this->profile_post_type,
			'advanced',
			'high',
			array( 'settings' => $this->get_profile_fields( '', true ) )
		);

		// Profile settings
		add_meta_box( 
			'wpb-contact-profile-settings', 
			__( 'Profile Settings', 'wpb' ), 
			array( $this, 'output_profile_meta_settings' ), 
			$this->profile_post_type,
			'advanced',
			'default',
			array( 'settings' => $this->get_profile_settings( '', true ) )
		);

	}


	/**
	 *
	 *	Output settings meta box
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_profile_meta_settings( $post, $metabox )
	{

		if ( empty( $metabox['args']['settings'] ) ) {

			return;

		}

		// Output settings
		$settings = $metabox['args']['settings'];

		echo '<div class="wpb-meta-settings">';

		foreach ( $settings as $slug => $setting ) {

			wpb( 'admin/setting/field', array( 'setting' => $setting ) );

		}

		echo '</div>';

	}


	/**
	 *
	 *	Get admin links
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Links
	 *
	 *	@see		WPB\Addons::admin_links()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_links()
	{

		return array(
			'profiles' => array(
				'name' => __( 'Profiles', 'wpb' ),
				'icon' => 'admin-users',
				'url'  => admin_url( 'edit.php?post_type=' . $this->profile_post_type )
			)
		);

	}


	/**
	 *
	 *	Add assets
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function add_assets()
	{

		if ( $this->choice( 'stylesheet' ) ) {

			wp_enqueue_style( 'wpb-contact', $this->url( 'assets/css/wpb-contact-styles.min.css' ), array(), $this->data( 'ver' ) );

		}

	}


	/**
	 *
	 *	Add admin assets
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function add_admin_assets()
	{

		// Scripts
		wp_enqueue_script( 'wpb-contact-admin', $this->url( 'assets/js/wpb-contact-admin.js' ), array( 'jquery' ), $this->data( 'ver' ) );

	}


	/**
	 *
	 *	Register post types
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_post_types()
	{

		// Profile
		$labels = array(
			'name'                  => _x( 'Contact Profiles', 'Post type general name', 'wpb' ),
			'singular_name'         => _x( 'Profile', 'Post type singular name', 'wpb' ),
			'menu_name'             => _x( 'Profiles', 'Admin Menu text', 'wpb' ),
			'name_admin_bar'        => _x( 'Profile', 'Add New on Toolbar', 'wpb' ),
			'add_new'               => __( 'Add New', 'wpb' ),
			'add_new_item'          => __( 'Add New Profile', 'wpb' ),
			'new_item'              => __( 'New Profile', 'wpb' ),
			'edit_item'             => __( 'Edit Profile', 'wpb' ),
			'view_item'             => __( 'View Profile', 'wpb' ),
			'all_items'             => __( 'Contact Profiles', 'wpb' ),
			'search_items'          => __( 'Search Profiles', 'wpb' ),
			'parent_item_colon'     => __( 'Parent Profiles:', 'wpb' ),
			'not_found'             => __( 'No profiles found.', 'wpb' ),
			'not_found_in_trash'    => __( 'No profiles found in Trash.', 'wpb' ),
			'featured_image'        => _x( 'Profile Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wpb' ),
			'set_featured_image'    => _x( 'Set profile image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wpb' ),
			'remove_featured_image' => _x( 'Remove profile image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wpb' ),
			'use_featured_image'    => _x( 'Use as profile image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wpb' ),
			'archives'              => _x( 'Profile archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wpb' ),
			'insert_into_item'      => _x( 'Insert into profile', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wpb' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this profile', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wpb' ),
			'filter_items_list'     => _x( 'Filter profiles list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wpb' ),
			'items_list_navigation' => _x( 'Profiles list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wpb' ),
			'items_list'            => _x( 'Profiles list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wpb' )
		);

		$args = array(
			'labels'               => $labels,
			'description'          => '',
			'public'               => false,
			'publicly_queryable'   => false,
			'exclude_from_search'  => true,
			'show_ui'              => true,
			'show_in_menu'         => 'options-general.php',
			'show_in_admin_bar'    => false,
			'show_in_nav_menus'    => false,
			'query_var'            => true,
			'rewrite'              => false,
			'capability_type'      => 'post',
			'has_archive'          => false,
			'hierarchical'         => false,
			'menu_position'        => null,
			'menu_icon'            => 'phone',
			'delete_with_user'     => false,
			'supports'             => array( 'title', 'thumbnail' ),
			'capabilities'         => array(
				'edit_post'           => 'manage_options',
				'delete_post'         => 'manage_options',
				'edit_posts'          => 'manage_options',
				'edit_others_posts'   => 'manage_options',
				'publish_posts'       => 'manage_options',
				'read_private_posts'  => 'manage_options',
				'delete_posts'        => 'manage_options',
				'delete_others_posts' => 'manage_options'
			)
		);

		register_post_type( $this->profile_post_type, $args );

	}


	/**
	 *
	 *	Register widget
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_widget()
	{

		$this->file( 'includes/wpb-widget-contact' );
		register_widget( 'WPB\Contact_Widget' );

	}


	/**
	 *
	 *	Get profile settings data
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_settings_data()
	{

		$settings = array();

		// Schema type
		$settings['schema/itemtype'] = array(
			'data' => array(
				'label' => __( 'Schema Item Type', 'wpb' ),
				'type'  => 'select',
				'choices' => array(
					'Person' => 'Person',
					'LocalBusiness' => 'LocalBusiness',
					'Organization' => 'Organization'
				)
			),
			'default' => 'Organization' 
		);

		$settings['schema/itemtype/custom'] = array(
			'data' => array(
				'label' => __( 'Custom Schema Type', 'wpb' ),
				'desc'  => sprintf( __( 'Please refer to <a href="%1$s" target="_blank">%1$s</a> for a full list of available types', 'wpb' ), esc_url( 'https://schema.org/docs/full.html' ) )
			)
		) ;

		return $settings;

	}


	/**
	 *
	 *	Get profile fields data
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_fields_data()
	{

		$settings = array();

		// Organisation name
		$settings['org_name'] = array(
			'data' => array(
				'label' => __( 'Organisation Name', 'wpb' ),
				'attr'  => array(
					'placeholder' => get_bloginfo( 'name' )
				)
			) 
		);

		// Contact name
		$settings['contact_name'] = array(
			'data' => array(
				'label' => __( 'Contact Name', 'wpb' )
			) 
		);

		// Job title
		$settings['job_title'] = array(
			'data' => array(
				'label' => __( 'Job Title', 'wpb' )
			) 
		);

		// Email
		$settings['email'] = array(
			'data' => array(
				'label' => __( 'Email Address', 'wpb' ),
				'attr'  => array(
					'type' => 'email',
					'placeholder' => get_option( 'admin_email' )
				)
			) 
		);

		// Phone
		$settings['phone'] = array(
			'data' => array(
				'label' => __( 'Phone Number', 'wpb' )
			) 
		);

		// Fax
		$settings['fax'] = array(
			'data' => array(
				'label' => __( 'Fax Number', 'wpb' )
			) 
		);

		// Address
		$settings['address_1'] = array(
			'data' => array(
				'label' => __( 'Address 1', 'wpb' )
			) 
		);

		$settings['address_2'] = array(
			'data' => array(
				'label' => __( 'Address 2', 'wpb' )
			) 
		);

		$settings['address_town'] = array(
			'data' => array(
				'label' => __( 'Town', 'wpb' )
			) 
		);

		$settings['address_region'] = array(
			'data' => array(
				'label' => __( 'Region', 'wpb' )
			) 
		);

		$settings['address_postcode'] = array(
			'data' => array(
				'label' => __( 'Postal Code', 'wpb' )
			) 
		);

		// Country
		$lang = explode( '-', get_bloginfo( 'language' ) );

		$settings['address_country'] = array(
			'data' => array(
				'label'    => __( 'Country', 'wpb' ),
				'type'     => 'select',
				'choices'  => $this->get_countries()
			),
			'default' => $lang[1] 
		);

		// Website
		$settings['website'] = array(
			'data' => array(
				'label' => __( 'Website', 'wpb' ),
				'attr'  => array(
					'type' => 'url',
					'placeholder' => home_url( '/' )
				)
			) 
		);

		return $settings;

	}


	/**
	 *
	 *	Get profile setting choices
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_profile_setting_choices()
	{

		// Cached
		$choices = $this->data( 'profile_choices' );

		if ( $choices )
			return $choices;

		$choices = array();

		// Get profiles
		$profiles = $this->get_profiles();

		if ( !empty( $profiles ) ) {

			foreach ( $profiles as $post_id => $profile ) {

				$choices[ $post_id ] = $profile->name;

			}

		}

		// Cache
		$this->data( 'profile_choices', $choices );

		return $choices;

	}


	/**
	 *
	 *	Output message if no profile choices are available
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_profile_setting_message()
	{
	
		// Get setting
		$setting = wpb( 'admin/setting' );

		if ( 'addon/contact/default_profile' != $setting->key )
			return;

		// Output message
		echo wpb( 'notification/alert', array(
			'text' => __( 'No profiles are available', 'wpb' ),
			'desc' => sprintf( __( '<a class="button" href="%s">Add new profile</a>', 'wpb' ), admin_url( 'post-new.php?post_type=' . $this->profile_post_type ) ) 
		) );
	
	}


	/**
	 *
	 *	Add reset settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_reset_settings( $settings = array() )
	{

		// Profiles
		if ( get_posts( array( 'post_type' => $this->profile_post_type, 'posts_per_page' => '1' ) ) ) {

			// Profiles
			$settings['profiles'] = array(
				'data' => array(
					'label' => __( 'Profiles', 'wpb' ),
					'type'  => 'boolean',
					'text'  => __( 'Delete contact profiles', 'wpb' )
				),
				'default' => true
			);

		}

		if ( get_option( 'wpb_contact_addon_default_profile_created' ) ) {

			$settings['default'] = array(
				'data' => array(
					'label' => __( 'Default Profile', 'wpb' ),
					'type'  => 'boolean',
					'text'  => __( 'Allow default profile to be created when the addon is next activated', 'wpb' )
				),
				'default' => true
			);

		}

		return $settings;
	
	}


	/**
	 *
	 *	Fired when data is reset
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$addon_settings		// Addon reset settings
	 *	@param		array 		$reset_settings		// All reset settings
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function reset_data( $addon_settings = array(), $reset_settings = array() ) 
	{

		// Allow default to be recreated
		if ( !empty( $addon_settings['default'] ) )
			delete_option( 'wpb_contact_addon_default_profile_created' );

		// Delete profiles
		if ( empty( $addon_settings['profiles'] ) )
			return;

		$profiles = get_posts( array( 'post_type' => $this->profile_post_type, 'posts_per_page' => '-1' ) );

		if ( empty( $profiles ) )
			return;

		foreach ( $profiles as $profile ) {

			// Delete profile
			wp_delete_post( $profile->ID, true );

		}

		wpb( 'admin/notification/success', array(
			'text' => __( 'Contact profiles deleted', 'wpb' ),
			'dismiss' => true
		) );

	}


	/**
	 *
	 *	Get countries
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_countries()
	{

		$countries = $this->data( 'countries' );

		if ( $countries )
			return $countries;

		$countries = array(
			'AF' => __( 'Afghanistan' ),
			'AX' => __( 'Aland Islands' ),
			'AL' => __( 'Albania' ),
			'DZ' => __( 'Algeria' ),
			'AS' => __( 'American Samoa' ),
			'AD' => __( 'Andorra' ),
			'AO' => __( 'Angola' ),
			'AI' => __( 'Anguilla' ),
			'AQ' => __( 'Antarctica' ),
			'AG' => __( 'Antigua And Barbuda' ),
			'AR' => __( 'Argentina' ),
			'AM' => __( 'Armenia' ),
			'AW' => __( 'Aruba' ),
			'AU' => __( 'Australia' ),
			'AT' => __( 'Austria' ),
			'AZ' => __( 'Azerbaijan' ),
			'BS' => __( 'Bahamas' ),
			'BH' => __( 'Bahrain' ),
			'BD' => __( 'Bangladesh' ),
			'BB' => __( 'Barbados' ),
			'BY' => __( 'Belarus' ),
			'BE' => __( 'Belgium' ),
			'BZ' => __( 'Belize' ),
			'BJ' => __( 'Benin' ),
			'BM' => __( 'Bermuda' ),
			'BT' => __( 'Bhutan' ),
			'BO' => __( 'Bolivia' ),
			'BA' => __( 'Bosnia And Herzegovina' ),
			'BW' => __( 'Botswana' ),
			'BV' => __( 'Bouvet Island' ),
			'BR' => __( 'Brazil' ),
			'IO' => __( 'British Indian Ocean Territory' ),
			'BN' => __( 'Brunei Darussalam' ),
			'BG' => __( 'Bulgaria' ),
			'BF' => __( 'Burkina Faso' ),
			'BI' => __( 'Burundi' ),
			'KH' => __( 'Cambodia' ),
			'CM' => __( 'Cameroon' ),
			'CA' => __( 'Canada' ),
			'CV' => __( 'Cape Verde' ),
			'KY' => __( 'Cayman Islands' ),
			'CF' => __( 'Central African Republic' ),
			'TD' => __( 'Chad' ),
			'CL' => __( 'Chile' ),
			'CN' => __( 'China' ),
			'CX' => __( 'Christmas Island' ),
			'CC' => __( 'Cocos (Keeling) Islands' ),
			'CO' => __( 'Colombia' ),
			'KM' => __( 'Comoros' ),
			'CG' => __( 'Congo' ),
			'CD' => __( 'Congo, Democratic Republic' ),
			'CK' => __( 'Cook Islands' ),
			'CR' => __( 'Costa Rica' ),
			'CI' => __( 'Cote D\'Ivoire' ),
			'HR' => __( 'Croatia' ),
			'CU' => __( 'Cuba' ),
			'CY' => __( 'Cyprus' ),
			'CZ' => __( 'Czech Republic' ),
			'DK' => __( 'Denmark' ),
			'DJ' => __( 'Djibouti' ),
			'DM' => __( 'Dominica' ),
			'DO' => __( 'Dominican Republic' ),
			'EC' => __( 'Ecuador' ),
			'EG' => __( 'Egypt' ),
			'SV' => __( 'El Salvador' ),
			'GQ' => __( 'Equatorial Guinea' ),
			'ER' => __( 'Eritrea' ),
			'EE' => __( 'Estonia' ),
			'ET' => __( 'Ethiopia' ),
			'FK' => __( 'Falkland Islands (Malvinas)' ),
			'FO' => __( 'Faroe Islands' ),
			'FJ' => __( 'Fiji' ),
			'FI' => __( 'Finland' ),
			'FR' => __( 'France' ),
			'GF' => __( 'French Guiana' ),
			'PF' => __( 'French Polynesia' ),
			'TF' => __( 'French Southern Territories' ),
			'GA' => __( 'Gabon' ),
			'GM' => __( 'Gambia' ),
			'GE' => __( 'Georgia' ),
			'DE' => __( 'Germany' ),
			'GH' => __( 'Ghana' ),
			'GI' => __( 'Gibraltar' ),
			'GR' => __( 'Greece' ),
			'GL' => __( 'Greenland' ),
			'GD' => __( 'Grenada' ),
			'GP' => __( 'Guadeloupe' ),
			'GU' => __( 'Guam' ),
			'GT' => __( 'Guatemala' ),
			'GG' => __( 'Guernsey' ),
			'GN' => __( 'Guinea' ),
			'GW' => __( 'Guinea-Bissau' ),
			'GY' => __( 'Guyana' ),
			'HT' => __( 'Haiti' ),
			'HM' => __( 'Heard Island & Mcdonald Islands' ),
			'VA' => __( 'Holy See (Vatican City State)' ),
			'HN' => __( 'Honduras' ),
			'HK' => __( 'Hong Kong' ),
			'HU' => __( 'Hungary' ),
			'IS' => __( 'Iceland' ),
			'IN' => __( 'India' ),
			'ID' => __( 'Indonesia' ),
			'IR' => __( 'Iran, Islamic Republic Of' ),
			'IQ' => __( 'Iraq' ),
			'IE' => __( 'Ireland' ),
			'IM' => __( 'Isle Of Man' ),
			'IL' => __( 'Israel' ),
			'IT' => __( 'Italy' ),
			'JM' => __( 'Jamaica' ),
			'JP' => __( 'Japan' ),
			'JE' => __( 'Jersey' ),
			'JO' => __( 'Jordan' ),
			'KZ' => __( 'Kazakhstan' ),
			'KE' => __( 'Kenya' ),
			'KI' => __( 'Kiribati' ),
			'KR' => __( 'Korea' ),
			'KW' => __( 'Kuwait' ),
			'KG' => __( 'Kyrgyzstan' ),
			'LA' => __( 'Lao People\'s Democratic Republic' ),
			'LV' => __( 'Latvia' ),
			'LB' => __( 'Lebanon' ),
			'LS' => __( 'Lesotho' ),
			'LR' => __( 'Liberia' ),
			'LY' => __( 'Libyan Arab Jamahiriya' ),
			'LI' => __( 'Liechtenstein' ),
			'LT' => __( 'Lithuania' ),
			'LU' => __( 'Luxembourg' ),
			'MO' => __( 'Macao' ),
			'MK' => __( 'Macedonia' ),
			'MG' => __( 'Madagascar' ),
			'MW' => __( 'Malawi' ),
			'MY' => __( 'Malaysia' ),
			'MV' => __( 'Maldives' ),
			'ML' => __( 'Mali' ),
			'MT' => __( 'Malta' ),
			'MH' => __( 'Marshall Islands' ),
			'MQ' => __( 'Martinique' ),
			'MR' => __( 'Mauritania' ),
			'MU' => __( 'Mauritius' ),
			'YT' => __( 'Mayotte' ),
			'MX' => __( 'Mexico' ),
			'FM' => __( 'Micronesia, Federated States Of' ),
			'MD' => __( 'Moldova' ),
			'MC' => __( 'Monaco' ),
			'MN' => __( 'Mongolia' ),
			'ME' => __( 'Montenegro' ),
			'MS' => __( 'Montserrat' ),
			'MA' => __( 'Morocco' ),
			'MZ' => __( 'Mozambique' ),
			'MM' => __( 'Myanmar' ),
			'NA' => __( 'Namibia' ),
			'NR' => __( 'Nauru' ),
			'NP' => __( 'Nepal' ),
			'NL' => __( 'Netherlands' ),
			'AN' => __( 'Netherlands Antilles' ),
			'NC' => __( 'New Caledonia' ),
			'NZ' => __( 'New Zealand' ),
			'NI' => __( 'Nicaragua' ),
			'NE' => __( 'Niger' ),
			'NG' => __( 'Nigeria' ),
			'NU' => __( 'Niue' ),
			'NF' => __( 'Norfolk Island' ),
			'MP' => __( 'Northern Mariana Islands' ),
			'NO' => __( 'Norway' ),
			'OM' => __( 'Oman' ),
			'PK' => __( 'Pakistan' ),
			'PW' => __( 'Palau' ),
			'PS' => __( 'Palestinian Territory, Occupied' ),
			'PA' => __( 'Panama' ),
			'PG' => __( 'Papua New Guinea' ),
			'PY' => __( 'Paraguay' ),
			'PE' => __( 'Peru' ),
			'PH' => __( 'Philippines' ),
			'PN' => __( 'Pitcairn' ),
			'PL' => __( 'Poland' ),
			'PT' => __( 'Portugal' ),
			'PR' => __( 'Puerto Rico' ),
			'QA' => __( 'Qatar' ),
			'RE' => __( 'Reunion' ),
			'RO' => __( 'Romania' ),
			'RU' => __( 'Russian Federation' ),
			'RW' => __( 'Rwanda' ),
			'BL' => __( 'Saint Barthelemy' ),
			'SH' => __( 'Saint Helena' ),
			'KN' => __( 'Saint Kitts And Nevis' ),
			'LC' => __( 'Saint Lucia' ),
			'MF' => __( 'Saint Martin' ),
			'PM' => __( 'Saint Pierre And Miquelon' ),
			'VC' => __( 'Saint Vincent And Grenadines' ),
			'WS' => __( 'Samoa' ),
			'SM' => __( 'San Marino' ),
			'ST' => __( 'Sao Tome And Principe' ),
			'SA' => __( 'Saudi Arabia' ),
			'SN' => __( 'Senegal' ),
			'RS' => __( 'Serbia' ),
			'SC' => __( 'Seychelles' ),
			'SL' => __( 'Sierra Leone' ),
			'SG' => __( 'Singapore' ),
			'SK' => __( 'Slovakia' ),
			'SI' => __( 'Slovenia' ),
			'SB' => __( 'Solomon Islands' ),
			'SO' => __( 'Somalia' ),
			'ZA' => __( 'South Africa' ),
			'GS' => __( 'South Georgia And Sandwich Isl.' ),
			'ES' => __( 'Spain' ),
			'LK' => __( 'Sri Lanka' ),
			'SD' => __( 'Sudan' ),
			'SR' => __( 'Suriname' ),
			'SJ' => __( 'Svalbard And Jan Mayen' ),
			'SZ' => __( 'Swaziland' ),
			'SE' => __( 'Sweden' ),
			'CH' => __( 'Switzerland' ),
			'SY' => __( 'Syrian Arab Republic' ),
			'TW' => __( 'Taiwan' ),
			'TJ' => __( 'Tajikistan' ),
			'TZ' => __( 'Tanzania' ),
			'TH' => __( 'Thailand' ),
			'TL' => __( 'Timor-Leste' ),
			'TG' => __( 'Togo' ),
			'TK' => __( 'Tokelau' ),
			'TO' => __( 'Tonga' ),
			'TT' => __( 'Trinidad And Tobago' ),
			'TN' => __( 'Tunisia' ),
			'TR' => __( 'Turkey' ),
			'TM' => __( 'Turkmenistan' ),
			'TC' => __( 'Turks And Caicos Islands' ),
			'TV' => __( 'Tuvalu' ),
			'UG' => __( 'Uganda' ),
			'UA' => __( 'Ukraine' ),
			'AE' => __( 'United Arab Emirates' ),
			'GB' => __( 'United Kingdom' ),
			'US' => __( 'United States' ),
			'UM' => __( 'United States Outlying Islands' ),
			'UY' => __( 'Uruguay' ),
			'UZ' => __( 'Uzbekistan' ),
			'VU' => __( 'Vanuatu' ),
			'VE' => __( 'Venezuela' ),
			'VN' => __( 'Viet Nam' ),
			'VG' => __( 'Virgin Islands, British' ),
			'VI' => __( 'Virgin Islands, U.S.' ),
			'WF' => __( 'Wallis And Futuna' ),
			'EH' => __( 'Western Sahara' ),
			'YE' => __( 'Yemen' ),
			'ZM' => __( 'Zambia' ),
			'ZW' => __( 'Zimbabwe' )
		);

		// Filter
		$countries = apply_filters( 'wpb/addon/contact/countries', $countries );

		// Cache
		$this->data( 'countries', $countries );

		return $countries;
	
	}


}