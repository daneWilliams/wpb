<?php


namespace WPB;


/**
 *
 *	Breadcrumbs widget
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

class Breadcrumbs_Widget extends Widget
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
			'breadcrumbs', 
			__( 'Breadcrumbs', 'wpb' ),
			array(
				'description' => __( 'Display breadcrumbs', 'wpb' )
			)
		);

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

		echo wpb( 'breadcrumbs', array(
			'wrapper' => !empty( $settings['wrapper'] ),
			'schema'  => !empty( $settings['schema'] )
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

		// Wrapper
		$this->wpb_register_setting( 'wrapper', array(
			'type'  => 'boolean',
			'label' => __( 'Wrapper', 'wpb' ),
			'text'  => __( 'Wrap breadcrumbs in a page wrapper', 'wpb' )
		), false );

		// Schema
		$this->wpb_register_setting( 'schema', array(
			'type'  => 'boolean',
			'label' => __( 'Schema', 'wpb' ),
			'text'  => __( 'Use schema markup', 'wpb' )
		), true );

	}


}