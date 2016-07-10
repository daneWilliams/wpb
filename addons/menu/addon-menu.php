<?php


namespace WPB;


/**
 *
 *	Menu addon
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


class Menu_Addon extends Addon
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

		// Get edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'get_edit_menu_walker' ), 20 );

		// Save menu item icon
		add_action( 'wp_update_nav_menu_item', array( $this, 'save_menu_item_icon' ), 20, 3 );

		// Add menu item columns
		add_filter( 'manage_nav-menus_columns', array( $this, 'add_menu_item_columns' ), 20 );

		// Modify setting locations
		add_filter( 'wpb/admin/settings/locations/names', array( $this, 'add_setting_location_names' ), 10, 2 );

		// Add menu walker
		$this->file( 'includes/wpb-walker-nav-menu' );

		add_filter( 'wp_nav_menu_args', array( $this, 'add_nav_menu_walker' ), 20 );

		// Register widget
		add_action( 'widgets_init', array( $this, 'register_widget' ), 5 );

		// Register menu locations
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );

		// Output main menu
		add_action( 'wpb/before/page-header', array( $this, 'output_main_menu' ) );
		add_action( 'wpb/after/page-header', array( $this, 'output_main_menu' ) );
		add_action( 'wpb/page-header', array( $this, 'output_main_menu' ) );

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

		// menu/get
		$this->register_request( 'get', 'request__get_menu', array(
			'id'       => '',
			'location' => '',
			'title'    => '',
			'mobile'   => false,
			'inline'   => false,
			'icons'    => true,
			'desc'     => true,
			'arrows'   => true,
			'schema'   => true,
			'wrapper'  => false,
			'wp_nav_menu' => array(
				'container' => false
			),
			'attr' => array()
		) );

		// menu/main
		$this->register_request( 'main', 'request__main_menu', array(
			'menu_args' => array(
				'mobile'   => NULL,
				'inline'   => NULL,
				'icons'    => NULL,
				'desc'     => NULL,
				'arrows'   => NULL,
				'schema'   => NULL,
				'wrapper'  => NULL
			),
			'echo'   => true,
			'return' => true
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

		// Icons
		$this->register_setting( 'icons', array(
			'type'    => 'checkbox',
			'label'   => __( 'Icons', 'wpb' ),
			'choices' => array(
				'custom' => array(
					'label' => __( 'Enable custom menu icons', 'wpb' ),
					'desc'  => __( 'Adds an extra field to menu items for an icon class', 'wpb' )
				),
				'arrows' => array(
					'label' => __( 'Enable arrow icons', 'wpb' ),
					'desc'  => __( 'Displays directional arrows to indicate sub menus', 'wpb' )
				)
			),
			'location' => 'display'
		), array( 'custom', 'arrows' ) );

		// Schema
		$this->register_setting( 'schema', array(
			'type'  => 'boolean',
			'label' => __( 'Schema', 'wpb' ),
			'text'  => __( 'Use schema markup', 'wpb' ),
			'location' => 'display'
		), true );

		// Main menu display
		$this->register_setting( 'main-menu/display', array(
			'type'    => 'checkbox',
			'label'   => __( 'Main menu display', 'wpb' ),
			'choices' => array(
				'mobile'  => __( 'Display mobile menu', 'wpb' ),
				'inline'  => __( 'Display inline', 'wpb' ),
				'icons'   => __( 'Display custom icons', 'wpb' ),
				'desc'    => __( 'Display link descriptions', 'wpb' ),
				'arrows'  => __( 'Display submenu arrows', 'wpb' ),
				'schema'  => __( 'Use schema markup', 'wpb' ),
				'wrapper' => __( 'Wrap the menu with a page wrapper', 'wpb' )
			),
			'location' => 'main-menu'
		), array( 'mobile', 'inline', 'icons', 'desc', 'arrows', 'schema', 'wrapper' ) );

		// Main menu location
		$this->register_setting( 'main-menu/location', array(
			'type'  => 'radio',
			'label' => __( 'Main menu location', 'wpb' ),
			'choices' => array(
				'after_header'   => __( 'Display after page header', 'wpb' ),
				'before_widgets' => __( 'Display before header widgets', 'wpb' ),
				'after_widgets'  => __( 'Display after header widgets', 'wpb' ),
				'manual'         => array(
					'label' => __( 'Display manually', 'wpb' ),
					'desc'  => sprintf( __( 'The main menu can be displayed with a widget or in a template with <code>%s</code>', 'wpb' ), 'wpb( \'menu/<strong>main</strong>\' )' )
				)
			),
			'location' => 'main-menu'
		), 'after_header' );

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
		$names['addon/menu/display'] = __( 'Display', 'wpb' );

		// Main Menu
		$names['addon/menu/main-menu'] = __( 'Main Menu', 'wpb' );

		return $names;

	}


	/**
	 *
	 *	Register menus
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_menus()
	{

		// Main menu
		register_nav_menu( 'wpb-main', __( 'Main Menu', 'wpb' ) );

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

		$this->file( 'includes/wpb-widget-menu' );
		register_widget( 'WPB\Menu_Widget' );

	}


	/**
	 *
	 *	Request: Get a menu
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$id				// Menu ID
	 *	@param		string		$location		// Menu location
	 *	@param		string		$title			// Menu title, used in mobile menus
	 *	@param		boolean		$mobile			// Enable mobile menu
	 *	@param		boolean		$inline			// Display menu as a navbar
	 *	@param		boolean		$icons			// Display custom icons
	 *	@param		boolean		$desc			// Display link descriptions
	 *	@param		boolean		$arrows			// Display submenu arrows
	 *	@param		boolean		$schema			// Use schema markup
	 *	@param		boolean		$wrapper		// Wrap the menu in a page wrapper
	 *	@param		array 		$nav_menu_args	// Arguments to pass to wp_nav_menu()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__get_menu( $args = array() )
	{

		global $wpb_menu;

		$wpb_menu = $args;

		// Get menu
		$nav_menu_args = array_merge( $args['wp_nav_menu'], array( 'echo' => false ) );

		if ( !empty( $args['id'] ) )
			$nav_menu_args['menu'] = $args['id'];

		$nav_menu = wp_nav_menu( $nav_menu_args );

		if ( !$nav_menu )
			return '';

		// Get classes
		$classes = array( 'nav-menu' );

		if ( !empty( $args['inline'] ) )
			$classes[] = 'nav-bar';

		if ( !empty( $args['mobile'] ) )
			$classes[] = 'nav-mobile';

		// Get attributes
		if ( empty( $args['attr'] ) )
			$args['attr'] = array();

		if ( !empty( $args['attr']['class'] ) ) {

			if ( !is_array( $args['attr']['class'] ) )
				$args['attr']['class'] = explode( ' ', $args['attr']['class'] );

			$classes = array_merge( $classes, $args['attr']['class'] );
			$classes = array_unique( $classes );

		}

		$args['attr']['class'] = wpb( 'classes/get', 'nav-menu', $classes );

		$args['attr']['role'] = 'navigation';

		if ( !empty( $args['title'] ) )
			$args['attr']['wpb-data-title'] = $args['title'];

		// Schema
		if ( $this->choice( 'schema' ) && !empty( $args['schema'] ) ) {

			$args['attr']['itemtype'] = 'http://schema.org/SiteNavigationElement';
			$args['attr'][] = 'itemscope';

		}

		// Build attributes
		$attr = wpb( 'attr', $args['attr'] );

		// Build the HTML
		$html = '<nav ' . $attr . '>';

		if ( !empty( $args['wrapper'] ) ) {

			$html .= '<div';
			$html .= wpb( 'classes/get', 'elem=menu-wrapper,page-wrapper&default=menu-wrapper page-wrapper&attr=1' );
			$html .= '>';

		}

		if ( !empty( $args['mobile'] ) ) {

			$html .= '<a href="#" class="menu-toggle top-level-link">';
				$html .= wpb( 'icon', 'menu' );
				$html .= '<span class="menu-item-text">';
					$html .= __( 'Menu', 'wpb' );
				$html .= '</span>';
			$html .= '</a>';

		}

		$html .= $nav_menu;

		if ( !empty( $args['wrapper'] ) ) {

			$html .= '</div>';

		}

		$html .= '</nav>';

		return $html;	

	}


	/**
	 *
	 *	Request: Output main menu
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__main_menu( $args = array() )
	{

		// Check menu exists
		$locations = get_nav_menu_locations();

		if ( empty( $locations['wpb-main'] ) )
			return;

		// Get arguments
		if ( !is_array( $args['menu_args'] ) )
			$args['menu_args'] = wp_parse_args( $args['menu_args'], array() );

		$display = array( 'mobile', 'inline', 'icons', 'desc', 'arrows', 'schema', 'wrapper' );

		foreach ( $display as $elem ) {

			if ( !isset( $args['menu_args'][ $elem ] ) || is_null( $args['menu_args'][ $elem ] ) )
				$args['menu_args'][ $elem ] = $this->choice( 'main-menu/display', $elem );

		}

		// Add location
		$args['menu_args']['id'] = '';
		$args['menu_args']['location'] = 'wpb-main';

		return wpb( 'menu/get', $args['menu_args'] );

	}


	/**
	 *
	 *	Automatically output the main menu
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_main_menu()
	{

		$action   = current_filter();
		$location = $this->settings( 'main-menu/location' );

		if ( empty( $location ) || 'manual' == $location )
			return;

		if ( !in_array( $action, array( 'wpb/before/page-header', 'wpb/after/page-header', 'wpb/page-header' ) ) )
			return;

		// Before widgets
		if ( 'wpb/before/page-header' == $action && 'before_widgets' != $location )
			return;

		// After widgets
		if ( 'wpb/after/page-header' == $action && 'after_widgets' != $location )
			return;

		// After header
		if ( 'wpb/page-header' == $action && 'after_header' != $location )
			return;

		echo wpb( 'menu/main' );

	}


	/**
	 *
	 *	Add nav menu walker to menu arguments
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_nav_menu_walker( $menu_args = array() )
	{
	
		if ( !empty( $menu_args['walker'] ) )
			return $menu_args;

		if ( class_exists( 'Walker_Nav_Menu' ) )
			$menu_args['walker'] = new Walker_Nav_Menu();

		return $menu_args;		
	
	}
	

	/**
	 *
	 *	Get edit menu walker
	 *
	 *	================================================================
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function get_edit_menu_walker( $walker = '' )
	{

		if ( !$this->choice( 'icons', 'custom' ) )
			return $walker;

		if ( $this->file( 'includes/wpb-walker-nav-menu-edit' ) )
			$walker = 'WPB\Walker_Nav_Menu_Edit';

		return $walker;

	}


	/**
	 *
	 *	Save menu item icon
	 *
	 *	================================================================
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function save_menu_item_icon( $menu_id, $menu_item_db_id, $menu_item_args )
	{

		if ( !$this->choice( 'icons', 'custom' ) )
			return;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;

		// Check referer
		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		$key = '_menu_item_wpb_icon';

		// Sanitize
		if ( !empty( $_POST[ $key ][ $menu_item_db_id ] ) )
			$value = sanitize_text_field( $_POST[ $key ][ $menu_item_db_id ] );

		else
			$value = null;

		// Update
		if ( !is_null( $value ) )
			update_post_meta( $menu_item_db_id, $key, $value );
		
		else
			delete_post_meta( $menu_item_db_id, $key );

	}


	/**
	 *
	 *	Add menu item columns
	 *
	 *	================================================================
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function add_menu_item_columns( $columns = array() )
	{

		// Icon
		if ( $this->choice( 'icons', 'custom' ) )
			$columns['wpb-icon'] = __( 'Icon', 'wpb' );

		return $columns;

	}


	/**
	 *
	 *	Get menu icon field HTML
	 *
	 *	================================================================
	 *
	 *	@since 		1.0.0
	 *
	 */

	public function get_menu_item_icon_field_html( $item, $depth, $args )
	{

		$key   = '_menu_item_wpb_icon';
		$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
		$name  = sprintf( '%s[%s]', $key, $item->ID );
		$value = get_post_meta( $item->ID, $key, true );
		$class = 'field-wpb-icon';
		$label = __( 'Icon', 'wpb' );

		// Build the HTML
		$html  = '<p class="description description-wide ' .  esc_attr( $class ) . '">';

		$html .= sprintf(
			'<label for="%1$s">%2$s<br /><input type="text" id="%1$s" class="widefat %1$s" name="%3$s" value="%4$s" /></label>',
			esc_attr( $id ),
			esc_html( $label ),
			esc_attr( $name ),
			esc_attr( $value )
		);

		$html .= '</p>';

		return $html;

	}


}