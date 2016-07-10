<?php


namespace WPB;


/**
 *
 *	Menu widget
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 *	@see		WPB\Widget
 *
 */

class Menu_Widget extends Widget
{


	/**
	 *
	 *	Setup widget
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function __construct()
	{

		parent::__construct( 
			'menu', 
			__( 'Menu', 'wpb' ),
			array(
				'description' => __( 'Display an enhanced menu', 'wpb' )
			)
		);

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

		// Check a menu has been selected
		if ( empty( $instance['menu'] ) )
			return;

		// Carry on with output
		parent::widget( $args, $instance );

	}


	/**
	 *
	 *	Output widget content
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$args			// Display arguments
	 *	@param		array 		$settings		// Current settings values
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_widget_content( $args, $settings = array() ) 
	{

		if ( empty( $settings['menu'] ) )
			return;

		$display = ( !empty( $settings['display'] ) ? $settings['display'] : array() );

		echo wpb( 'menu/get', array( 
			'id'      => $settings['menu'],
			'title'   => $settings['title'],
			'inline'  => in_array( 'inline', $display ),
			'mobile'  => in_array( 'mobile', $display ),
			'icons'   => in_array( 'icons', $display ),
			'desc'    => in_array( 'desc', $display ),
			'arrows'  => in_array( 'arrows', $display ),
			'schema'  => in_array( 'schema', $display ),
			'wrapper' => in_array( 'wrapper', $display )
		) );

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

	public function wpb_register_settings() 
	{

		// Title
		$this->wpb_register_title_setting();

		// Check there are menus
		$menus = $this->get_menus();

		if ( !empty( $menus ) ) {

			// Menu
			$this->wpb_register_setting( 'menu', array(
				'type'    => 'select',
				'label'   => __( 'Menu', 'wpb' ),
				'choices' => $this->get_menu_choices()
			) );

			// Display
			$this->wpb_register_setting( 'display', array(
				'type'    => 'checkbox',
				'label'   => __( 'Display', 'wpb' ),
				'choices' => array(
					'mobile'  => __( 'Display mobile menu', 'wpb' ),
					'inline'  => __( 'Display inline', 'wpb' ),
					'icons'   => __( 'Display custom icons', 'wpb' ),
					'desc'    => __( 'Display link descriptions', 'wpb' ),
					'arrows'  => __( 'Display submenu arrows', 'wpb' ),
					'schema'  => __( 'Use schema markup', 'wpb' ),
					'wrapper' => __( 'Wrap the menu with a page wrapper', 'wpb' )
				)
			), array( 'icons', 'arrows', 'desc', 'schema' ) );

		}

	}


	/**
	 *
	 *	Get menus
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Menus
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_menus() 
	{

		// Get cached
		$menus = $this->wpb_data( 'menus' );

		if ( $menus )
			return $menus;

		// Get menus
		$menus = wp_get_nav_menus();

		// Cache
		$this->wpb_data( 'menus', $menus );

		return $menus;

	}


	/**
	 *
	 *	Get menus as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_menu_choices() 
	{

		// Get cached
		$menus = $this->wpb_data( 'menu_choices' );

		if ( $menus )
			return $menus;

		$menus = $this->get_menus();

		$choices = array(
			'0' => __( '&mdash; Select &mdash;', 'wpb' )
		);

		foreach ( $menus as $menu ) {

			$choices[ $menu->term_id ] = $menu->name;

		}

		// Cache
		$this->wpb_data( 'menu_choices', $choices );

		return $choices;

	}


}