<?php


namespace WPB;


/**
 *
 *	Image widget
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

class Image_Widget extends Widget
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
			'image', 
			__( 'Image', 'wpb' ),
			array(
				'description' => __( 'Display an image', 'wpb' )
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

		echo wpb( 'image', array(
			'id'     => $settings['image'],
			'size'   => $settings['image-size'],
			'link'   => ( 'custom' == $settings['link/type'] ? $settings['link/url'] : ( 'image' == $settings['link/type'] ? 'full' : false ) ),
			'target' => $settings['link/target']
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

		// Image
		$this->wpb_register_setting( 'image', array(
			'label' => __( 'Image', 'wpb' ),
			'type'  => 'image'
		) );

		// Image size
		$this->wpb_register_setting( 'image-size', array(
			'label'   => __( 'Logo Size', 'wpb' ),
			'type'    => 'select',
			'choices' => $this->get_image_size_choices()
		), 'full' );

		// Link type
		$this->wpb_register_setting( 'link/type', array(
			'type'    => 'radio',
			'label'   => __( 'Link:', 'wpb' ),
			'choices' => array(
				'none'   => __( 'None', 'wpb' ),
				'image'  => __( 'Full size image', 'wpb' ),
				'custom' => __( 'Custom URL', 'wpb' )
			)
		), 'none' );

		// Link URL
		$this->wpb_register_setting( 'link/url', array(
			'label' => __( 'Custom Link URL:', 'wpb' ),
			'attr'  => array(
				'type' => 'url',
				'placeholder' => 'http://'
			)
		) );

		// Link target
		$this->wpb_register_setting( 'link/target', array(
			'type'    => 'select',
			'label'   => __( 'Link Target:', 'wpb' ),
			'choices' => array(
				'_self'  => __( 'Open in the same window', 'wpb' ),
				'_blank' => __( 'Open in a new window', 'wpb' )
			)
		), '_self' );

	}


	/**
	 *
	 *	Get registered image sizes as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_image_size_choices() 
	{

		// Get cached
		$choices = $this->wpb_data( 'image_size_choices' );

		if ( $choices )
			return $choices;

		$sizes   = wpb( 'image/sizes' );
		$choices = array();

		if ( !empty( $sizes ) ) {

			foreach ( $sizes as $size => $data ) {

				$choices[ $size ] = ucwords( str_replace( '_', ' ', $size ) );

			}

		}

		// Cache
		$this->wpb_data( 'image_size_choices', $choices );

		return $choices;

	}


}