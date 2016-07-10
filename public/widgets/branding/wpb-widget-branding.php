<?php


namespace WPB;


/**
 *
 *	Branding widget
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

class Branding_Widget extends Widget
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
			'branding', 
			__( 'Branding', 'wpb' ),
			array(
				'description' => __( 'Display logo, site title and site tagline', 'wpb' )
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

		global $wpb_branding;

		// Get saved title
		if ( empty( $settings['site-title'] ) )
			$settings['site-title'] = get_bloginfo( 'name' );

		// Get saved tagline
		if ( empty( $settings['site-tagline'] ) )
			$settings['site-tagline'] = get_bloginfo( 'description' );

		// Build data
		$data = $settings;

		// Classes
		$classes = array( 'branding' );

		if ( in_array( 'logo', $settings['display'] ) && !empty( $settings['logo'] ) )
			$classes[] = 'with-logo';

		if ( in_array( 'title', $settings['display'] ) && !empty( $settings['site-title'] ) )
			$classes[] = 'with-title';

		if ( in_array( 'tagline', $settings['display'] ) && !empty( $settings['site-tagline'] ) )
			$classes[] = 'with-tagline';

		$data['classes'] = $classes;

		// Modify data
		if ( empty( $data['logo-size'] ) )
			$data['logo-size'] = 'full';

		if ( !in_array( 'logo', $settings['display'] ) )
			$data['logo'] = '';

		if ( !in_array( 'title', $settings['display'] ) )
			$data['site-title'] = '';

		if ( !in_array( 'tagline', $settings['display'] ) )
			$data['site-tagline'] = '';

		// Add URL
		$data['url'] = home_url( '/' );

		// Filter
		$data = apply_filters( 'wpb/widget/branding/data', $data );

		// Get template
		$wpb_branding = $data;
		$template = $this->wpb_dir( 'templates/branding.php' );

		wpb( 'template', 'path=widgets/branding&fallback=' . $template );

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

		// Display
		$this->wpb_register_setting( 'display', array(
			'label' => __( 'Display', 'wpb' ),
			'type'   => 'checkbox',
			'choices' => array(
				'logo'    => __( 'Display logo', 'wpb' ),
				'title'   => __( 'Display site title', 'wpb' ),
				'tagline' => __( 'Display site tagline', 'wpb' )
			)
		), array( 'logo', 'title', 'tagline' ) );

		// Logo
		$this->wpb_register_setting( 'logo', array(
			'label' => __( 'Logo', 'wpb' ),
			'type'  => 'image',
			'button_text'        => __( 'Select logo', 'wpb' ),
			'upload_title_text'  => __( 'Select Logo', 'wpb' ),
			'upload_button_text' => __( 'Set logo', 'wpb' ),
			'button_remove_text' => __( 'Remove logo', 'wpb' )
		) );

		// Logo size
		$this->wpb_register_setting( 'logo-size', array(
			'label'   => __( 'Logo Size', 'wpb' ),
			'type'    => 'select',
			'choices' => $this->get_image_size_choices()
		), 'full' );

		// Site title
		$this->wpb_register_setting( 'site-title', array(
			'label' => __( 'Site Title', 'wpb' ),
			'desc'  => __( 'Leave blank to use saved site title', 'wpb' ),
			'attr'  => array(
				'placeholder' => get_bloginfo( 'name' )
			)
		) );

		// Site tagline
		$this->wpb_register_setting( 'site-tagline', array(
			'label' => __( 'Site Tagline', 'wpb' ),
			'desc'  => __( 'Leave blank to use saved site tagline', 'wpb' ),
			'attr'  => array(
				'placeholder' => get_bloginfo( 'description' )
			)
		) );

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