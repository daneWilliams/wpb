<?php


namespace WPB;


/**
 *
 *	Social widget: Profiles
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

class Social_Profiles_Widget extends Widget
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
			'social-profiles', 
			__( 'Social Profiles', 'wpb' ),
			array(
				'description' => __( 'Output social profile links', 'wpb' )
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

		$args = array(
			'inline'   => in_array( 'inline', $settings['display'] ),
			'buttons'  => in_array( 'buttons', $settings['display'] ),
			'text'     => in_array( 'text', $settings['display'] ),
			'icons'    => in_array( 'icons', $settings['display'] ),
			'small'    => in_array( 'small', $settings['display'] ),
			'circular' => in_array( 'circular', $settings['display'] )
		);

		echo wpb( 'social/profiles', $args );

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

		//Display
		$this->wpb_register_setting( 'display', array(
			'label' => __( 'Display', 'wpb' ),
			'type'  => 'checkbox',
			'choices' => array(
				'inline'   => __( 'Display inline', 'wpb' ),
				'text'     => __( 'Display link text', 'wpb' ),
				'icons'    => __( 'Display link icons', 'wpb' ),
				'buttons'  => __( 'Display links as buttons', 'wpb' ),
				'small'    => __( 'Display small buttons', 'wpb' ),
				'circular' => __( 'Display circular buttons', 'wpb' )
			)
		), array( 'inline', 'text', 'icons', 'buttons' ) );

	}


}