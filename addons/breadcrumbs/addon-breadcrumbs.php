<?php


namespace WPB;


/**
 *
 *	Breadcrumbs addon
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


class Breadcrumbs_Addon extends Addon
{


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

		// Add assets
		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ), 5 );

		// Modify setting locations
		add_filter( 'wpb/admin/settings/locations/names', array( $this, 'add_setting_location_names' ), 10, 2 );

		// Register widget
		add_action( 'widgets_init', array( $this, 'register_widget' ), 5 );

		// Output breadcrumbs
		add_action( 'wpb/before/page-body', array( $this, 'output_breadcrumbs' ), 20 );

	}


	/**
	 *
	 *	Fired when the admin page is initialised
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$page			// Admin page
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_page_init( $page = '' ) 
	{

		if ( empty( $page ) )
			return;

		// TO DO: Add pages

	}


	/**
	 *
	 *	Get admin pages
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Pages
	 *
	 *	@see		WPB\Addons::admin_pages()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function admin_pages()
	{

		return array(
			'general' => array(
				'name' => __( 'General', 'wpb' ),
				'url'  => $this->admin_url( 'general' )
			),
			'post-types' => array(
				'name' => __( 'Post Types', 'wpb' ),
				'url'  => $this->admin_url( 'post-types' )
			),
			'taxonomies' => array(
				'name' => __( 'Taxonomies', 'wpb' ),
				'url'  => $this->admin_url( 'taxonomies' )
			),
		);

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

		// breadcrumbs
		wpb()->register( 'breadcrumbs', array( $this, 'request__get_breadcrumbs' ), array(
			'wrapper' => false,
			'schema'  => true,
			'attr'    => array()
		), true );

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

		// Location
		$this->register_setting( 'location', array(
			'type'  => 'radio',
			'label' => __( 'Location', 'wpb' ),
			'choices' => array(
				'header' => array(
					'label' => __( 'Display in page header', 'wpb' ),
					'desc'  => sprintf( __( 'Hooks into <code>%s</code>', 'wpb' ), 'wpb/after/page-header' )
				),
				'content' => array(
					'label' => __( 'Display in page content', 'wpb' ),
					'desc'  => sprintf( __( 'Hooks into <code>%s</code>', 'wpb' ), 'wpb/before/page-body' )
				),
				'between' => array(
					'label' => __( 'Display between page header and content', 'wpb' ),
					'desc'  => sprintf( __( 'Hooks into <code>%s</code>', 'wpb' ), 'wpb/page-header' )
				),
				'manual' => array(
					'label' => __( 'Display manually', 'wpb' ),
					'desc'  => sprintf( 
						__( 'Breadcrumbs can be displayed with a widget, in a template with <code>%1$s</code>, or a shortcode with <code>%2$s</code>', 'wpb' ), 
						'wpb( \'<strong>breadcrumbs</strong>\' )',
						'[<strong>wpb-breadcrumbs</strong>]'
					)
				)
			),
			'location' => 'display'
		), 'content' );

		// Wrapper
		$this->register_setting( 'wrapper', array(
			'type'  => 'boolean',
			'label' => __( 'Wrapper', 'wpb' ),
			'text'  => __( 'Wrap breadcrumbs in a page wrapper', 'wpb' ),
			'location' => 'display'
		), true );

		// Stylesheet
		$this->register_setting( 'stylesheet', array(
			'type'  => 'boolean',
			'label' => __( 'Stylesheet', 'wpb' ),
			'text'  => __( 'Use addon styles', 'wpb' ),
			'location' => 'display'
		), true );

		// Text separator
		$this->register_setting( 'separator/text', array(
			'label' => __( 'Text Separator', 'wpb' ),
			'location' => 'format'
		), '&rsaquo;' );

		// Icon separator
		$this->register_setting( 'separator/icon', array(
			'type'  => 'icon',
			'label' => __( 'Icon Separator', 'wpb' ),
			'desc'  => __( 'Leave blank to use text separator', 'wpb' ),
			'location' => 'format'
		), 'arrow-right-alt2' );

		// Schema
		$this->register_setting( 'schema', array(
			'type'  => 'boolean',
			'label' => __( 'Schema', 'wpb' ),
			'text'  => __( 'Use schema markup', 'wpb' ),
			'location' => 'format'
		), true );

		// Specific types
		$types = array( 'home', 'blog', 'author', 'date', 'search', '404' );

		foreach ( $types as $type ) {

			// Get settings
			$method = 'get_' . $type . '_settings_fields';

			if ( !method_exists( $this, $method ) )
				continue;

			$settings = $this->$method();

			if ( empty( $settings ) )
				continue;

			foreach ( $settings as $key => $setting ) {

				$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
				$default = ( isset( $setting['default'] ) ? $setting['default'] : NULL );

				// Add location
				$data['location'] = $type;

				// Register
				$this->register_setting( $type . '/' . $key, $data, $default );

			}

		}

		// Post types
		$post_types = $this->get_post_type_settings_fields();

		if ( !empty( $post_types ) ) {

			foreach ( $post_types as $post_type => $settings ) {

				foreach ( $settings as $key => $setting ) {

					$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
					$default = ( isset( $setting['default'] ) ? $setting['default'] : NULL );

					// Add location
					$data['location'] = 'post_type/' . $post_type;

					// Register
					$this->register_setting( 'post_type/' . $post_type . '/' . $key, $data, $default );

				}

			}

		}

		// Taxonomies
		$taxonomies = $this->get_taxonomy_settings_fields();

		if ( !empty( $taxonomies ) ) {

			foreach ( $taxonomies as $taxonomy => $settings ) {

				foreach ( $settings as $key => $setting ) {

					$data    = ( isset( $setting['data'] ) ? $setting['data'] : array() );
					$default = ( isset( $setting['default'] ) ? $setting['default'] : NULL );

					// Add location
					$data['location'] = 'taxonomy/' . $taxonomy;

					// Register
					$this->register_setting( 'taxonomy/' . $taxonomy . '/' . $key, $data, $default );

				}

			}

		}

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

		// Display
		$names['addon/breadcrumbs/display'] = __( 'Display', 'wpb' );

		// Format
		$names['addon/breadcrumbs/format'] = __( 'Format', 'wpb' );

		// Homepage
		$names['addon/breadcrumbs/home'] = __( 'Home', 'wpb' );

		// Blog
		$names['addon/breadcrumbs/blog'] = __( 'Blog', 'wpb' );

		// Author
		$names['addon/breadcrumbs/author'] = __( 'Author', 'wpb' );

		// Date
		$names['addon/breadcrumbs/date'] = __( 'Date', 'wpb' );

		// Search
		$names['addon/breadcrumbs/search'] = __( 'Search', 'wpb' );

		// 404
		$names['addon/breadcrumbs/404'] = __( '404 Page', 'wpb' );

		// Post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $post_type_slug => $post_type ) {

			$label = ( isset( $post_type->labels->name ) ? $post_type->labels->name : $post_type->label );

			$names[ 'addon/breadcrumbs/post_type/' . $post_type_slug ] = $label;

		}

		// Taxonomies
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

			$label = ( isset( $taxonomy->labels->name ) ? $taxonomy->labels->name : $taxonomy->label );

			$names[ 'addon/breadcrumbs/taxonomy/' . $taxonomy_slug ] = $label;

		}

		return $names;

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

		$this->file( 'includes/wpb-widget-breadcrumbs' );
		register_widget( 'WPB\Breadcrumbs_Widget' );

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

		// Stylesheet
		if ( $this->choice( 'stylesheet' ) ) {

			wp_enqueue_style( 'wpb-breadcrumbs', $this->url( 'assets/css/wpb-breadcrumbs.min.css' ), array(), $this->data( 'ver' ) );

		}

	}


	/**
	 *
	 *	Output core settings page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_settings( $page ) 
	{

		// Get settings
		$settings = $this->settings( false, false, true );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Remove unwanted
		$unwanted = array( 'home/', 'blog/', 'author/', 'date/', 'post_type/', 'taxonomy/', '404/', 'search/' );

		foreach ( $settings as $key => $setting ) {

			foreach ( $unwanted as $unwanted_key ) {

				if ( $unwanted_key == substr( $key, 0, strlen( $unwanted_key ) ) )
					unset( $settings[ $key ] );

			}

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $settings, 'grouped' => true, 'tabs' => false ) );

	}


	/**
	 *
	 *	Output general settings page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_general( $page ) 
	{

		// Get settings
		$settings = $this->settings( false, false, true );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Get wanted settings
		$wanted = array();
		$wanted_keys = array( 'home/', 'blog/', '404/', 'date/', 'author/', 'search/' );

		foreach ( $settings as $key => $setting ) {

			foreach ( $wanted_keys as $wanted_key ) {

				if ( $wanted_key == substr( $key, 0, strlen( $wanted_key ) ) )
					$wanted[ $key ] = $setting;

			}

		}

		if ( empty( $wanted ) ) {

			echo wpb( 'notification/alert', __( 'No registered general settings', 'wpb' ) );
			return;

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $wanted, 'grouped' => true, 'tabs' => false ) );

	}


	/**
	 *
	 *	Get general settings admin page attributes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__get_general_attr() 
	{

		return array(
			'class' => 'wpb-page-settings'
		);

	}


	/**
	 *
	 *	Output post type settings page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_post_types( $page ) 
	{

		// Get settings
		$settings = $this->settings( false, false, true );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Get wanted settings
		$wanted = array();
		$wanted_key = 'post_type/';
		$strlen = strlen( $wanted_key );

		foreach ( $settings as $key => $setting ) {

			if ( $wanted_key == substr( $key, 0, $strlen ) )
				$wanted[ $key ] = $setting;

		}

		if ( empty( $wanted ) ) {

			echo wpb( 'notification/alert', __( 'No registered post type settings', 'wpb' ) );
			return;

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $wanted, 'grouped' => true, 'tabs' => false ) );

	}


	/**
	 *
	 *	Get post type admin page attributes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__get_post_types_attr() 
	{

		return array(
			'class' => 'wpb-page-settings'
		);

	}


	/**
	 *
	 *	Output taxonomies settings page
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_taxonomies( $page ) 
	{

		// Get settings
		$settings = $this->settings( false, false, true );

		if ( empty( $settings ) ) {

			echo wpb( 'notification/alert', __( 'No registered settings', 'wpb' ) );
			return;

		}

		// Get wanted settings
		$wanted = array();
		$wanted_key = 'taxonomy/';
		$strlen = strlen( $wanted_key );

		foreach ( $settings as $key => $setting ) {

			if ( $wanted_key == substr( $key, 0, $strlen ) )
				$wanted[ $key ] = $setting;

		}


		if ( empty( $wanted ) ) {

			echo wpb( 'notification/alert', __( 'No registered taxonomy settings', 'wpb' ) );
			return;

		}

		// Output
		wpb( 'admin/settings/output', array( 'settings' => $wanted, 'grouped' => true, 'tabs' => false ) );

	}


	/**
	 *
	 *	Get taxonomy settings admin page attributes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__get_taxonomies_attr() 
	{

		return array(
			'class' => 'wpb-page-settings'
		);

	}


	/**
	 *
	 *	Output general settings page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_general_footer( $page ) 
	{

		$this->output_admin_settings_page_footer( $page );

	}



	/**
	 *
	 *	Output post type settings page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_post_types_footer( $page ) 
	{

		$this->output_admin_settings_page_footer( $page );

	}


	/**
	 *
	 *	Output taxonomy settings page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function admin_page__output_taxonomies_footer( $page ) 
	{

		$this->output_admin_settings_page_footer( $page );

	}


	/**
	 *
	 *	Output admin settings page footer
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function output_admin_settings_page_footer( $page )
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
		echo '<input type="hidden" name="addon" value="' . esc_attr( $this->id() ) . '" />';

		// Nonce
		wp_nonce_field( 'addon_settings_save', wpb()->nonce() );

	}
	

	/**
	 *
	 *	Request: Get breadcrumbs HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		boolean		$wrapper		// Wrap the breadcrumbs in a page wrapper
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__get_breadcrumbs( $args = array() )
	{

		// Get items
		$items = $this->get_items();

		if ( empty( $items ) )
			return;

		// Get attributes
		$attr = array(
			'class' => wpb( 'classes/get', 'breadcrumbs', 'breadcrumbs' )
		);

		$list_attr = array(
			'class' => 'breadcrumb-list'
		);

		if ( !empty( $args['schema'] ) ) {

			$list_attr[] = 'itemscope';
			$list_attr['itemtype'] = 'http://schema.org/BreadcrumbList';

		}

		// Build the HTML
		$html = '<nav' . wpb( 'attr', $attr ) . '>';

		if ( !empty( $args['wrapper'] ) ) {

			$html .= '<div';
			$html .= wpb( 'classes/get', 'elem=menu-wrapper,page-wrapper&default=menu-wrapper page-wrapper&attr=1' );
			$html .= '>';

		}

		$html .= '<ol' . wpb( 'attr', $list_attr ) . '>';

		$i = 1;

		foreach ( $items as $key => $item ) {

			if ( $i > 1 ) {

				$html .= PHP_EOL;

				// Separator
				$separator = $this->settings( 'separator/text' );
				$icon = $this->settings( 'separator/icon' );

				if ( $icon )
					$separator = wpb( 'icon', $icon );

				if ( $separator ) {

					$separator_classes = array( 'breadcrumb-separator' );

					if ( $icon )
						$separator_classes[] = 'breadcrumb-separator-icon';

					else
						$separator_classes[] = 'breadcrumb-separator-text';

					$html .= '<span class="' . implode( ' ', $separator_classes ) . '">' . $separator . '</span>';
					$html .= PHP_EOL;

				}

			}

			// Get attributes
			$item_attr = array(
				'class' => array( 'breadcrumb-item' )
			);

			if ( !empty( $args['class'] ) ) {

				if ( !is_array( $args['class'] ) )
					$args['class'] = explode( ' ', $args['class'] );

				$item_attr['class'] = array_merge( $item_attr['class'], $args['class'] );

			}

			if ( !empty( $args['schema'] ) ) {

				$item_attr[] = 'itemscope';
				$item_attr['itemprop'] = 'itemListElem';
				$item_attr['itemtype'] = 'http://schema.org/ListItem';

			}

			// Build HTML
			$html .= '<li' . wpb( 'attr', $item_attr ) . '>';

			if ( !empty( $item['prefix'] ) ) {

				$html .= '<span class="breadcrumb-prefix">';
				$html .= $item['prefix'];
				$html .= '</span>';
				$html .= PHP_EOL;

			}

			if ( !empty( $item['url'] ) ) {

				$link_attr = array(
					'href' => $item['url']
				);

				if ( !empty( $args['schema'] ) )
					$link_attr['itemprop'] = 'item';

				$html .= '<a' . wpb( 'attr', $link_attr ) . '>';

			}

			if ( !empty( $args['schema'] ) )
				$html .= '<span itemprop="name">';

			$html .= $item['text'];

			if ( !empty( $args['schema'] ) ) {

				$html .= '</span>';
				$html .= '<meta itemprop="position" content="' . esc_attr( $i ) . '" />';

			}

			if ( !empty( $item['url'] ) )
				$html .= '</a>';

			$html .= '</li>';

			$i++;

		}

		$html .= '</ol>';

		if ( !empty( $args['wrapper'] ) ) {

			$html .= '</div>';

		}

		$html .= '</nav>';

		return $html;

	}


	/**
	 *
	 *	Automatically output the breadcrumbs
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_breadcrumbs()
	{

		$action   = current_filter();
		$location = $this->settings( 'location' );

		if ( empty( $location ) || 'manual' == $location )
			return;

		if ( 'wpb/after/page-header' == $location && 'header' != $location )
			return;

		if ( 'wpb/page-header' == $location && 'between' != $location )
			return;

		if ( 'wpb/before/page-body' == $location && 'content' != $location )
			return;

		echo wpb( 'breadcrumbs', array(
			'wrapper' => $this->choice( 'wrapper' ),
			'schema'  => $this->choice( 'schema' )
		) );

	}


	/**
	 *
	 *	Get items
	 *
	 *	================================================================ 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_items()
	{

		$items = array();

		// Get object
		$obj = $this->get_current_object();

		if ( empty( $obj ) )
			return;

		// Get base items
		$items['base'] = $this->get_base_items( $obj );

		// Get parent items
		$items['parent'] = $this->get_parent_items( $obj );

		// Get current items
		$items['current'] = $this->get_current_items( $obj );

		// Remove empty
		$formatted = array();

		foreach ( $items as $group => $grouped ) {

			if ( empty( $grouped ) )
				continue;

			foreach ( $grouped as $slug => $item ) {

				if ( empty( $item ) )
					continue;

				$formatted[ $group . '/' . $slug ] = $item;

			}

		}

		return $formatted;

	}


	/**
	 *
	 *	Get object
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$type		// Object type
	 *
	 *	@return		object 						// Object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_object( $type = '' )
	{

		// Get object
		$object  = array();
		$default = array(
			'type'    => '',
			'subtype' => '',
			'id'      => '',
			'url'     => '',
			'slug'    => '',
			'name'    => '',
			'parent'  => '',
			'prefix'  => ''
		);

		if ( is_string( $type ) ) {

			$object['type'] = $type;
			$object['id']   = $type;

			$method = 'get_' . str_replace( '-', '_', $type ) . '_object';

			if ( method_exists( $this, $method ) )
				$object = $this->$method();

		} elseif ( isset( $type->term_id ) ) {

			$object = $this->get_term_object( $type );

		} elseif ( isset( $type->post_type ) ) {

			$object = $this->get_post_object( $type );

		}

		if ( empty( $object ) )
			return false;

		return (object) wp_parse_args( $object, $default );

	}


	/**
	 *
	 *	Get current object
	 *
	 *	================================================================ 
	 *
	 *	@return		object 						// Current object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_current_object()
	{

		// Cached
		$obj = $this->data( 'current-obj' );

		if ( !is_null( $obj ) )
			return (object) $obj;

		// Get type
		if ( is_404() )
			$type= '404';

		elseif ( is_search() )
			$type = 'search';

		elseif ( is_author() )
			$type = 'author';

		elseif ( is_date() )
			$type = 'date';

		elseif ( is_home() )
			$type = 'blog';

		elseif ( is_front_page() )
			$type = 'home';

		else
			$type = get_queried_object();

		// Get object
		$obj = $this->get_object( $type );

		// Cache
		$this->data( 'current-obj', $obj );

		return (object) $obj;

	}


	/**
	 *
	 *	Get blog object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_blog_object()
	{

		// Cached
		$object = $this->data( 'blog-obj' );

		if ( $object )
			return $object;

		$object = array();

		// Get URL
		$url = '';

		// Get name
		$name = $this->settings( 'blog/title' );

		// Get page
		$archive_page = $this->settings( 'blog/archive' );

		if ( 'posts' == $archive_page ) {

			$posts_page = get_option( 'page_for_posts' );

			if ( $posts_page )
				$archive_page = get_post( $posts_page );

		} elseif ( $archive_page ) {

			if ( 'page_' == substr( $archive_page, 0, 5 ) )
				$archive_page = get_post( substr( $archive_page, 5 ) );

		}

		if ( !empty( $archive_page->ID ) ) {

			if ( !$name )
				$name = get_the_title( $archive_page->ID );

			$url = get_permalink( $archive_page->ID );

		}

		// Create the object
		$object['name'] = $name;
		$object['url']  = $url;

		// Cache
		$this->data( 'blog-obj', $object );

		return $object;

	}


	/**
	 *
	 *	Get post object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_post_object( $post = '' )
	{
		
		if ( !$post )
			$post = get_queried_object();

		if ( empty( $post->post_type ) )
			return array();

		// Cached
		$object = $this->data( 'post-obj/' . $post->ID );

		if ( $object )
			return $object;

		// Create object
		$object = array();

		$object['type']    = 'post';
		$object['subtype'] = $post->post_type;
		$object['id']      = $post->ID;
		$object['url']     = get_permalink( $post->ID );
		$object['slug']    = $post->post_name;
		$object['name']    = get_the_title( $post->ID );
		$object['parent']  = $post->post_parent;

		// Cache
		$this->data( 'post-obj/' . $post->ID, $object );

		return $object;
	
	}


	/**
	 *
	 *	Get term object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_term_object( $term = '' )
	{

		$object = array();
		
		if ( !$term )
			$term = get_queried_object();

		if ( empty( $term->term_id ) )
			return $object;

		// Cached
		$object = $this->data( 'term-obj/' . $term->term_id );

		if ( $object )
			return $object;

		// Create object
		$object = array();

		$object['type']    = 'term';
		$object['subtype'] = $term->taxonomy;
		$object['id']      = $term->term_id;
		$object['url']     = get_term_link( $term->term_id );
		$object['slug']    = $term->slug;
		$object['name']    = $term->name;
		$object['parent']  = $term->parent;
		$object['prefix']  = $this->settings( 'taxonomy/' . $term->taxonomy . '/prefix' );

		// Cache
		$this->data( 'term-obj/' . $term->term_id, $object );

		return $object;
	
	}


	/**
	 *
	 *	Get author object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_author_object()
	{

		$object = array();
		$author = get_user_by( 'slug', get_query_var( 'author_name' ) );

		if ( !$author )
			return $object;

		// Cached
		$object = $this->data( 'author-obj/' . $author->ID );

		if ( $object )
			return $object;

		$object = array();

		// Get author name
		$name = $author->display_name;

		$format = $this->settings( 'author/format' );

		if ( $format && isset( $author->$format ) )
			$name = $author->$format;

		if ( 'full_name' == $format )
			$name = trim( sprintf( '%1$s %2$s', $author->first_name, $author->last_name ) );

		if ( !$name )
			$name = $author->display_name;

		// Create object
		$object['type']   = 'author';
		$object['id']     = $author->ID;
		$object['slug']   = $author->user_login;
		$object['name']   = $name;
		$object['prefix'] = $this->settings( 'author/prefix' );

		// Cache
		$this->data( 'author-obj/' . $author->ID, $object );

		return $object;
	
	}


	/**
	 *
	 *	Get search object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_search_object()
	{

		$object = array();
		
		// Get search query
		$query = get_search_query();

		$text = $this->settings( 'search/title' );
		$text = str_replace( '%search_terms%', $query, $text );
		$text = str_replace( '%search_terms_q%', '<q>' . $query . '</q>', $text );

		$object['type']    = 'search';
		$object['id']      = sanitize_title( $query );
		$object['url']     = home_url( '/?s=' . $query );
		$object['slug']    = 'search';
		$object['name']    = $text;

		return $object;
	
	}


	/**
	 *
	 *	Get 404 object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_404_object()
	{

		// Cached
		$object = $this->data( '404-obj' );

		if ( $object )
			return $object;

		$object = array();

		$object['type'] = '404';
		$object['id']   = '404';
		$object['name'] = $this->settings( '404/title' );

		// Cache
		$this->data( '404-obj', $object );

		return $object;
	
	}


	/**
	 *
	 *	Get date object
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_date_object()
	{

		$object = array();

		$day_format   = $this->settings( 'date/day_format' );
		$month_format = $this->settings( 'date/month_format' );

		// Set text
		if ( is_day() )
			$text = get_the_date( $day );

		elseif ( is_month() )
			$text = get_the_date( $month_format ? $month_format : 'F Y' );

		else
			$text = get_the_date( 'Y' );

		$object['id']     = strtotime( get_the_date() );
		$object['name']   = $text;
		$object['prefix'] = $this->settings( 'date/prefix' );

		return $object;
	
	}


	/**
	 *
	 *	Get parent object
	 *
	 *	================================================================ 
	 *
	 *	@param		object 		$obj			// Current object
	 *
	 *	@return		object						// Parent object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_parent_object( $obj )
	{

		if ( empty( $obj->parent ) )
			return;

		$queried = false;

		switch ( $obj->type ) {

			// Term
			case 'term' :

				$queried = get_term( $obj->parent, $obj->subtype );

			break;

			// Post
			case 'post' :

				$queried = get_post( $obj->parent );

			break;

		}

		if ( $queried )
			return $this->get_object( $queried );

		return $queried;

	}


	/**
	 *
	 *	Format an item
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$item			// Item to format
	 *	@param		string		$slug			// Item slug
	 *
	 *	@return		array 						// Formatted item
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function format_item( $item, $slug = '' )
	{

		$formatted = array(
			'slug'   => $slug,
			'text'   => '',
			'url'    => '',
			'id'     => '',
			'class'  => '',
			'prefix' => ''
		);

		if ( !is_array( $item ) ) {

			$formatted['text'] = $item;

		} else {

			$formatted = wp_parse_args( $item, $formatted );

		}

		if ( empty( $formatted['text'] ) )
			return;

		return $formatted;

	}


	/**
	 *
	 *	Get home item
	 *
	 *	================================================================ 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_home_item( $obj )
	{

		// Cached
		$home = $this->data( 'home-item/' . $obj->type . '/' . $obj->id );

		if ( $home )
			return $home;

		$name = $this->settings( 'home/title' );
		$url  = '';

		// Get archive page
		$archive_page = $this->settings( 'home/archive' );

		if ( $archive_page ) {

			$page = false;

			$front_page = get_option( 'page_on_front' );

			if ( 'front' == $archive_page ) {

				if ( $front_page )
					$page = get_post( $front_page );

			} elseif ( $archive_page ) {

				if ( 'page_' == substr( $archive_page, 0, 5 ) )
					$page = get_post( substr( $archive_page, 5 ) );

			}

			if ( !empty( $page->ID ) ) {

				if ( !$name )
					$name = get_the_title( $page->ID );

				$url = get_permalink( $page->ID );

			}

		}

		// Set default URL
		if ( !$url )
			$url = home_url( '/' );

		// Set default name
		if ( !$name )
			$name = get_bloginfo( 'name' );

		// Create object
		$home = array(
			'type' => 'home',
			'text' => $name,
			'url'  => $url
		);

		// Cache
		$this->data( 'home-item/' . $obj->type . '/' . $obj->id, $home );

		return $home;

	}


	/**
	 *
	 *	Get base items
	 *
	 *	================================================================ 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_base_items( $obj )
	{

		// Cached
		$items = $this->data( 'base-items/' . $obj->type . '/' . $obj->id );

		if ( $items )
			return $items;

		// Get items
		$items = array();

		// Home
		$items['home'] = $this->get_home_item( $obj );

		// Type
		$method = 'get_' . str_replace( '-', '_', $obj->type ) . '_base_items';

		if ( method_exists( $this, $method ) ) {

			// Custom parent handling
			$base = $this->$method( $obj );

			if ( !empty( $base ) )
				$items = array_merge( $items, $base );

		}

		// Filter
		$items = apply_filters( 'wpb/breadcrumbs/items/base', $items, $obj );

		// Format
		foreach ( $items as $slug => $item ) {

			$items[ $slug ] = $this->format_item( $item, $slug );

		}

		// Cache
		$this->data( 'base-items/' . $obj->type . '/' . $obj->id, $items );

		return $items;

	}


	/**
	 *
	 *	Get post base items
	 *
	 *	================================================================
	 *
	 *	@param		object		$obj		// Current object
	 *	@param		boolean		$parent		// Check for further parents 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_post_base_items( $obj )
	{

		$items = array();

		// Check for blog page
		if ( $this->choice( 'blog/post_type', $obj->subtype ) ) {

			// Get blog
			$blog_obj = $this->get_object( 'blog' );

			if ( $blog_obj ) {

				$items['base/blog'] = array(
					'text' => $blog_obj->name,
					'slug' => $blog_obj->slug,
					'id'   => $blog_obj->id,
					'url'  => $blog_obj->url
				);

			}

		}

		// Check for archive page
		$archive = $this->settings( 'post_type/' . $obj->subtype . '/archive' );

		if ( !$archive )
			return $items;

		// Get archive page
		$page = get_post( $archive );

		if ( !$page )
			return $items;

		// Get object
		$page_obj = $this->get_object( $page );

		if ( empty( $page_obj ) )
			return $items;

		// Check for parents
		if ( $page_obj->parent && $this->choice( 'post_type/' . $obj->subtype . '/archive_parent' ) ) {

			$parents = $this->get_post_parent_items( $page_obj );

			if ( !empty( $parents ) )
				$items = array_merge( $items, $parents );

		}

		// Add base
		$items[ 'base/' . $page_obj->type . '/' . $page_obj->slug ] = array(
			'text' => $page_obj->name,
			'slug' => $page_obj->slug,
			'id'   => $page_obj->id,
			'url'  => $page_obj->url
		);

		return $items;

	}


	/**
	 *
	 *	Get term base items
	 *
	 *	================================================================
	 *
	 *	@param		object		$obj		// Current object
	 *	@param		boolean		$parent		// Check for further parents 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_term_base_items( $obj )
	{

		$items = array();

		// Check for blog page
		if ( $this->choice( 'blog/taxonomy', $obj->subtype ) ) {

			// Get blog
			$blog_obj = $this->get_object( 'blog' );

			if ( $blog_obj ) {

				$items['base/blog'] = array(
					'text' => $blog_obj->name,
					'slug' => $blog_obj->slug,
					'id'   => $blog_obj->id,
					'url'  => $blog_obj->url
				);

			}

		}

		// Check for archive page
		$archive = $this->settings( 'taxonomy/' . $obj->subtype . '/archive' );

		if ( !$archive )
			return $items;

		// Get archive page
		$page = get_post( $archive );

		if ( !$page )
			return $items;

		// Get object
		$page_obj = $this->get_object( $page );

		if ( empty( $page_obj ) )
			return $items;

		// Check for parents
		if ( $page_obj->parent && $this->choice( 'taxonomy/' . $obj->subtype . '/archive_parent' ) ) {

			$parents = $this->get_post_parent_items( $page_obj );

			if ( !empty( $parents ) )
				$items = array_merge( $items, $parents );

		}

		// Add base
		$items[ 'base/' . $page_obj->type . '/' . $page_obj->slug ] = array(
			'text' => $page_obj->name,
			'slug' => $page_obj->slug,
			'id'   => $page_obj->id,
			'url'  => $page_obj->url
		);

		return $items;

	}



	/**
	 *
	 *	Get parent items
	 *
	 *	================================================================
	 *
	 *	@param		object		$obj		// Current object
	 *	@param		boolean		$parent		// Check for further parents 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_parent_items( $obj, $parent = true )
	{

		// Cached
		$items = $this->data( 'parent-items/' . $obj->type . '/' . $obj->id );

		if ( $items )
			return $items;

		// Get items
		$items = array();

		$method = 'get_' . str_replace( '-', '_', $obj->type ) . '_parent_items';

		if ( method_exists( $this, $method ) ) {

			// Custom parent handling
			$items = $this->$method( $obj, $parent );

		}

		// Filter
		$items = apply_filters( 'wpb/breadcrumbs/items/parent', $items, $obj );

		// Format
		foreach ( $items as $slug => $item ) {

			$items[ $slug ] = $this->format_item( $item, $slug );

		}

		// Cache
		$this->data( 'parent-items/' . $obj->type . '/' . $obj->id, $items );

		return $items;

	}


	/**
	 *
	 *	Get post parent items
	 *
	 *	================================================================
	 *
	 *	@param		object		$obj		// Current object
	 *	@param		boolean		$parent		// Check for further parents 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_post_parent_items( $obj, $parent = true )
	{

		$items = array();

		// Check for choice
		$choice = $this->settings( 'post_type/' . $obj->subtype . '/parent' );

		if ( !$choice )
			return $items;

		// Taxonomy
		if ( 'tax_' == substr( $choice, 0, 4 ) ) {

			// Get term
			$tax = substr( $choice, 4 );
			$terms = wp_get_post_terms( $obj->id, $tax );

			if ( empty( $terms ) )
				return $items;

			$term = array_shift( $terms );
			$term_obj = $this->get_object( $term );

			if ( empty( $term_obj ) )
				return $items;

			$term_parent = $this->choice( 'post_type/' . $obj->subtype . '/term_parent' );

			// Get term parents
			if ( $term_parent ) {

				$term_parents = $this->get_parent_items( $term_obj, true );

				// Add parents
				if ( $term_parents )
					$items = array_merge( $items, $term_parents );

			}

			// Add term
			$items[ $term_obj->subtype . '/' . $term_obj->slug ] = array(
				'type' => $term_obj->type,
				'text' => $term_obj->name,
				'url'  => $term_obj->url,
				'slug' => $term_obj->slug
			);

			return $items;

		}

		// Post
		if ( 'post' == $choice ) {

			if ( empty( $obj->parent ) )
				return $items;

			$parent_obj = $this->get_parent_object( $obj );

			if ( !$parent_obj )
				return $items;

			$parent_item = array(
				'type' => $parent_obj->type,
				'slug' => $parent_obj->slug,
				'text' => $parent_obj->name,
				'url'  => $parent_obj->url
			);

			if ( $parent ) {

				$parents = $this->get_parent_items( $parent_obj, true );

				if ( $parents )
					$items = array_merge( $items, $parents );

			}

			$items[ $parent_obj->subtype . '/' . $parent_obj->slug ] = $parent_item;

			return $items;

		}

		return $items;

	}


	/**
	 *
	 *	Get term parent items
	 *
	 *	================================================================
	 *
	 *	@param		object		$obj		// Current object
	 *	@param		boolean		$parent		// Check for further parents 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_term_parent_items( $obj, $parent = true )
	{

		$items = array();

		if ( empty( $obj->parent ) )
			return $items;

		// Check for choice
		$choice = $this->settings( 'taxonomy/' . $obj->subtype . '/parent' );

		if ( !$choice )
			return $items;

		// Get parent
		$parent_obj = $this->get_parent_object( $obj );

		if ( !$parent_obj )
			return $items;

		$parent_item = array(
			'type' => $parent_obj->type,
			'slug' => $parent_obj->slug,
			'text' => $parent_obj->name,
			'url'  => $parent_obj->url
		);

		if ( $parent ) {

			$parents = $this->get_parent_items( $parent_obj, true );

			if ( $parents )
				$items = array_merge( $items, $parents );

		}

		$items[ $parent_obj->subtype . '/' . $parent_obj->slug ] = $parent_item;

		return $items;

	}


	/**
	 *
	 *	Get current items
	 *
	 *	================================================================ 
	 *
	 *	@return		array 					// Breadcrumb items
	 *
	 *	@since		1.0.0
	 *
	 */
	
	protected function get_current_items( $obj )
	{

		// Cached
		$items = $this->data( 'current-items/' . $obj->type . '/' . $obj->id );

		if ( $items )
			return $items;

		// Get items
		$items = array();

		$items['current-obj'] = array(
			'slug'   => $obj->slug,
			'text'   => $obj->name,
			'url'    => $obj->url,
			'type'   => $obj->type,
			'class'  => 'current',
			'prefix' => $obj->prefix
		);

		// Add paged
		$paged = get_query_var( 'paged' );

		if ( $paged ) {

			$items['paged'] = array(
				'text'  => sprintf( __( 'Page %d', 'wpb' ), $paged ),
				'class' => 'paged'
			);

		}

		if ( !$paged && !$this->choice( 'current', 'link' ) )
			$items['current-obj']['url'] = '';

		// Filter
		$items = apply_filters( 'wpb/breadcrumbs/items/current', $items, $obj );

		// Format
		foreach ( $items as $slug => $item ) {

			$items[ $slug ] = $this->format_item( $item, $slug );

		}

		// Cache
		$this->data( 'current-items/' . $obj->type . '/' . $obj->id, $items );

		return $items;

	}	


	/**
	 *
	 *	Get home settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_home_settings_fields()
	{
	
		// Cached
		$settings = $this->data( 'home_settings' );

		if ( $settings )
			return $settings;		

		// Title
		$settings['title'] = array(
			'data' => array(
				'label' => __( 'Home title', 'wpb' ),
				'desc'  => __( 'Leave blank to use the home page title', 'wpb' ),
				'attr'  => array(
					'placeholder' => get_bloginfo( 'name' )
				)
			)
		);

		// Archive
		$archive = array(
			'0' => __( 'None', 'wpb' ),
			'front' => __( 'Front page', 'wpb' )
		);

		$pages = get_pages();

		if ( !empty( $pages ) ) {

			foreach ( $pages as $page ) {

				$archive[ 'page_' . $page->ID ] = get_the_title( $page->ID );

			}

		}

		$settings['archive'] = array(
			'data' => array(
				'label'   => __( 'Home page', 'wpb' ),
				'type'    => 'select',
				'desc'    => sprintf( __( 'The front page can be managed in the <a href="%s">reading settings</a>', 'wpb' ), admin_url( 'options-reading.php' ) ),
				'choices' => $archive
			),
			'default' => '0'
		);

		// Cache
		$this->data( 'home_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get blog settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_blog_settings_fields()
	{

		// Cached
		$settings = $this->data( 'blog_settings' );

		if ( $settings )
			return $settings;

		// Title
		$settings['title'] = array(
			'data' => array(
				'label' => __( 'Blog title', 'wpb' ),
				'desc'  => __( 'Leave blank to use the blog archive page title', 'wpb' ),
				'attr'  => array(
					'placeholder' => __( 'Blog', 'wpb' )
				)
			),
			'default' => ''
		);

		// Archive
		$archive = array(
			'0' => __( 'None', 'wpb' ),
			'posts' => __( 'Posts page', 'wpb' )
		);

		$pages = get_pages();

		if ( !empty( $pages ) ) {

			foreach ( $pages as $page ) {

				$archive[ 'page_' . $page->ID ] = get_the_title( $page->ID );

			}

		}

		$settings['archive'] = array(
			'data' => array(
				'label'   => __( 'Blog archive page', 'wpb' ),
				'type'    => 'select',
				'desc'    => sprintf( __( 'The posts page can be managed in the <a href="%s">reading settings</a>', 'wpb' ), admin_url( 'options-reading.php' ) ),
				'choices' => $archive
			),
			'default' => 'posts'
		);

		// Post type
		$settings['post_type'] = array(
			'data' => array(
				'label'   => __( 'Blog post types', 'wpb' ),
				'type'    => 'checkbox',
				'choices' => $this->get_post_type_choices(),
				'toggle'  => true
			),
			'default' => array( 'post' )
		);

		// Taxonomies
		$settings['taxonomy'] = array(
			'data' => array(
				'label'   => __( 'Blog taxonomies', 'wpb' ),
				'type'    => 'checkbox',
				'choices' => $this->get_taxonomy_choices(),
				'toggle'  => true
			),
			'default' => array( 'category', 'post_tag' )
		);

		// Cache
		$this->data( 'blog_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get author settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_author_settings_fields()
	{

		// Cached
		$settings = $this->data( 'author_settings' );

		if ( $settings )
			return $settings;

		// Format
		$settings['format'] = array(
			'data' => array(
				'label' => __( 'Name format', 'wpb' ),
				'type'  => 'select',
				'choices' => array(
					'display_name' => __( 'Display name', 'wpb' ),
					'user_login'   => __( 'Username', 'wpb' ),
					'nickname'     => __( 'Nickname', 'wpb' ),
					'first_name'   => __( 'First name', 'wpb' ),
					'last_name'    => __( 'Last name', 'wpb' ),
					'full_name'    => __( 'First and last name', 'wpb' )
				)
			),
			'default' => 'display_name'
		);

		// Prefix
		$settings['prefix'] = array(
			'data' => array(
				'label' => __( 'Author prefix', 'wpb' ),
				'attr'  => array(
					'placeholder' => __( 'Author:', 'wpb' )
				)
			),
			'default' => ''
		);

		// Cache
		$this->data( 'author_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get date settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_date_settings_fields()
	{

		// Cached
		$settings = $this->data( 'date_settings' );

		if ( $settings )
			return $settings;

		// Day format
		$settings['day_format'] = array(
			'data' => array(
				'label' => __( 'Day format', 'wpb' ),
				'desc'  => sprintf( 
					__( 'Leave blank to use <a href="%1$s">default date format</a>, e.g. <code>%2$s</code>', 'wpb' ), 
					admin_url( 'options-general.php' ),
					date( get_option( 'date_format' ) )
				),
				'attr'  => array(
					'placeholder' => get_option( 'date_format' )
				)
			),
			'default' => ''
		);

		// Month format
		$settings['month_format'] = array(
			'data' => array(
				'label' => __( 'Month format', 'wpb' ),
				'desc'  => sprintf( __( 'Leave blank to display the month and year, e.g. <code>%s</code>', 'wpb' ), date( 'F Y' ) ),
				'attr'  => array(
					'placeholder' => 'F Y'
				)
			),
			'default' => ''
		);

		// Prefix
		$settings['prefix'] = array(
			'data' => array(
				'label' => __( 'Date prefix', 'wpb' ),
				'attr'  => array(
					'placeholder' => __( 'Date:', 'wpb' )
				)
			),
			'default' => ''
		);

		// Cache
		$this->data( 'date_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get search settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_search_settings_fields()
	{

		// Cached
		$settings = $this->data( 'search_settings' );

		if ( $settings )
			return $settings;

		// Title
		$settings['title'] = array(
			'data' => array(
				'label' => __( 'Search title', 'wpb' ),
				'desc'  => __( 'Use <code><strong>%search_terms%</strong></code> to display the search query and <code><strong>%search_terms_q%</strong></code> to display the search query wrapped in quotes', 'wpb' ),
				'attr'  => array(
					'placeholder' => __( 'e.g. Search results for %search_terms_q%', 'wpb' )
				)
			),
			'default' => __( 'Search results for %search_terms_q%', 'wpb' )
		);

		// Cache
		$this->data( 'search_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get 404 settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_404_settings_fields()
	{

		// Cached
		$settings = $this->data( '404_settings' );

		if ( $settings )
			return $settings;

		// Title
		$settings['title'] = array(
			'data' => array(
				'label' => __( '404 title', 'wpb' ),
				'attr'  => array(
					'placeholder' => wpb( 'settings/get/404/title', '404' )
				)
			),
			'default' => wpb( 'settings/get/404/title' )
		);

		// Cache
		$this->data( '404_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get post type settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_post_type_settings_fields()
	{

		// Cached
		$settings = $this->data( 'post_type_settings' );

		if ( $settings )
			return $settings;

		// Get post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		// Get pages
		$pages = get_pages();

		// Get settings
		$settings = array();

		foreach ( $post_types as $post_type_slug => $post_type ) {

			// Get labels
			$single_label = ( !empty( $post_type->labels->singular_name ) ? $post_type->labels->singular_name : __( 'Post', 'wpb' ) );
			$multi_label  = ( !empty( $post_type->labels->name ) ? $post_type->labels->name : $post_type->label );

			// Archive
			$archive = array(
				'0' => __( 'None', 'wpb' )
			);

			if ( !empty( $post_type->has_archive ) )
				$archive['post_type'] = __( 'Post type', 'wpb' );

			if ( !empty( $pages ) && 'page' != $post_type_slug ) {

				foreach ( $pages as $page ) {

					$archive[ $page->ID ] = get_the_title( $page->ID );

				}

			}

			if ( count( $archive ) > 1 ) {

				$settings[ $post_type_slug ]['archive'] = array(
					'data' => array(
						'label'   => ucfirst( trim( sprintf( __( '%s archive page', 'wpb' ), $multi_label ) ) ),
						'type'    => 'select',
						'choices' => $archive
					),
					'default' => ''
				);

				// Archive parent
				$settings[ $post_type_slug ]['archive_parent'] = array(
					'data' => array(
						'label' => ucfirst( trim( sprintf( __( '%s archive parent', 'wpb' ), $single_label ) ) ),
						'type'  => 'boolean',
						'text'  => __( 'Display archive parent', 'wpb' )
					),
					'default' => false
				);

			}

			// Parent
			$parents = array(
				'0' => __( 'None', 'wpb' )
			);

			if ( !empty( $post_type->hierarchical ) )
				$parents['post'] = $single_label;

			if ( 'attachment' == $post_type_slug )
				$parents['post'] = __( 'Post', 'wpb' );

			$taxonomies = $this->get_taxonomy_choices( $post_type_slug );

			if ( !empty( $taxonomies ) )
				$parents = array_merge( $parents, $taxonomies );

			if ( count( $parents ) > 1 ) {

				$settings[ $post_type_slug ]['parent'] = array(
					'data' => array(
						'label'   => ucfirst( trim( sprintf( __( '%s parent', 'wpb' ), $single_label ) ) ),
						'type'    => 'select',
						'choices' => $parents
					),
					'default' => ( 'page' == $post_type_slug ? 'post' : '' )
				);

			}

			// Term parent
			if ( !empty( $taxonomies ) ) {

				$settings[ $post_type_slug ]['term_parent'] = array(
					'data' => array(
						'label' => ucfirst( trim( sprintf( __( '%s term parent', 'wpb' ), $single_label ) ) ),
						'type'  => 'boolean',
						'text'  => sprintf( __( 'Display %s term parent', 'wpb' ), strtolower( $single_label ) )
					),
					'default' => true
				);

			}

		}

		// Cache
		$this->data( 'post_type_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get taxonomy settings fields
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_taxonomy_settings_fields()
	{

		// Cached
		$settings = $this->data( 'taxonomy_settings' );

		if ( $settings )
			return $settings;

		// Get pages
		$archive_choices = array(
			'0' => __( 'None', 'wpb' )
		);

		$pages = get_pages();

		if ( !empty( $pages ) ) {

			foreach ( $pages as $page ) {

				$archive_choices[ $page->ID ] = get_the_title( $page->ID );

			}

		}

		// Get taxonomies
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

			if ( 'post_format' == $taxonomy_slug )
				continue;

			// Get labels
			$single_label = ( !empty( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : __( 'Term', 'wpb' ) );

			// Parent
			if ( !empty( $taxonomy->hierarchical ) ) {

				$settings[ $taxonomy_slug ]['parent'] = array(
					'data' => array(
						'label' => ucfirst( trim( sprintf( __( '%s parent', 'wpb' ), $single_label ) ) ),
						'type'  => 'boolean',
						'text'  => sprintf( __( 'Display %s parent', 'wpb' ), strtolower( $single_label ) )
					),
					'default' => true
				);

			}

			// Archive
			$settings[ $taxonomy_slug ]['archive'] = array(
				'data' => array(
					'label' => ucfirst( trim( sprintf( __( '%s archive page', 'wpb' ), $single_label ) ) ),
					'type'    => 'select',
					'choices' => $archive_choices
				),
				'default' => ''
			);

			// Archive parent
			$settings[ $taxonomy_slug ]['archive_parent'] = array(
				'data' => array(
					'label' => ucfirst( trim( sprintf( __( '%s archive parent', 'wpb' ), $single_label ) ) ),
					'type'  => 'boolean',
					'text'  => __( 'Display archive parent', 'wpb' )
				),
				'default' => false
			);

			// Prefix
			$settings[ $taxonomy_slug ]['prefix'] = array(
				'data' => array(
					'label' => ucfirst( trim( sprintf( __( '%s prefix', 'wpb' ), $single_label ) ) ),
					'attr'  => array(
						'placeholder' => sprintf( '%s:', ( !empty( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : '' ) )
					)
				),
				'default' => ''
			);

		}

		// Cache
		$this->data( 'taxonomy_settings', $settings );

		return $settings;

	}


	/**
	 *
	 *	Get registered post types as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_post_type_choices() 
	{

		// Get cached
		$choices = $this->data( 'post_type_choices' );

		if ( $choices )
			return $choices;

		$choices = array();

		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $slug => $post_type ) {

			// Skip attachment
			if ( 'attachment' == $slug )
				continue;

			$label = $post_type->label;

			if ( !empty( $post_type->labels->singular_name ) )
				$label = $post_type->labels->singular_name;

			elseif ( !empty( $post_type->labels->name ) )
				$label = $post_type->labels->name;

			$choices[ $slug ] = array(
				'label' => $label
			);

		}

		// Cache
		$this->data( 'post_type_choices', $choices );

		return $choices;

	}



	/**
	 *
	 *	Get registered post types as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_taxonomy_choices( $post_type = '' ) 
	{

		$post_type_key = '/all';

		if ( $post_type )
			$post_type_key = '/' . $post_type;

		// Cached
		$choices = $this->data( 'taxonomy_choices' . $post_type_key );

		if ( $choices )
			return $choices;

		// Get taxonomies
		$taxonomies = ( $post_type ? get_object_taxonomies( $post_type, 'objects' ) : get_taxonomies( array( 'public' => true ), 'objects' ) );

		// Get choices
		$choices = array();

		if ( !empty( $taxonomies ) ) {

			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

				if ( 'post_format' == $taxonomy_slug )
					continue;

				$choices[ ( $post_type ? 'tax_' : '' ) . $taxonomy_slug ] = ( !empty( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : $taxonomy->label );

			}

		}

		// Cache
		$this->data( 'taxonomy_choices' . $post_type_key, $choices );

		return $choices;

	}


}