<?php


namespace WPB;


/**
 *
 *	Contact widget
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

class Contact_Widget extends Widget
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
			'contact', 
			__( 'Contact', 'wpb' ),
			array(
				'description' => __( 'Output contact details', 'wpb' )
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

		// Check a profile has been selected
		if ( empty( $instance['profile'] ) )
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

		if ( empty( $settings['profile'] ) )
			return;

		// Get icons
		$icons = array();

		foreach ( $settings as $key => $value ) {

			if ( 'icon/' == substr( $key, 0, 5 ) )
				$icons[ substr( $key, 5 ) ] = $value;

		}

		// Get labels
		$labels = array();

		foreach ( $settings as $key => $value ) {

			if ( 'label/' == substr( $key, 0, 6 ) )
				$labels[ substr( $key, 6 ) ] = $value;

		}

		// Output
		wpb( 'addon/contact/output', array(
			'profile' => $settings['profile'],
			'fields'  => $settings['display'],
			'icons'   => $icons,
			'labels'  => $labels
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

		// Profile
		$this->wpb_register_setting( 'profile', array(
			'label'   => __( 'Profile', 'wpb' ),
			'type'    => 'select',
			'choices' => $this->get_profile_setting_choices()
		), wpb( 'settings/addon/get/contact', 'default_profile' ) );

		// Display
		$this->wpb_register_setting( 'display', array(
			'label'   => __( 'Display', 'wpb' ),
			'type'    => 'checkbox',
			'choices' => array(
				'org_name'        => __( 'Display organisation name', 'wpb' ),
				'contact_name'    => __( 'Display contact name', 'wpb' ),
				'job_title'       => __( 'Display job title', 'wpb' ),
				'email'           => __( 'Display email address', 'wpb' ),
				'phone'           => __( 'Display phone number', 'wpb' ),
				'fax'             => __( 'Display fax number', 'wpb' ),
				'address'         => __( 'Display address', 'wpb' ),
				'address_country' => __( 'Display country', 'wpb' ),
				'website'         => __( 'Display website', 'wpb' ),
				'icons' => array(
					'label' => __( 'Display icons', 'wpb' ),
					'attr'  => array(
						'class' => 'wpb-contact-widget-display-icons'
					)
				),
				'labels' => array(
					'label' => __( 'Display labels', 'wpb' ),
					'attr'  => array(
						'class' => 'wpb-contact-widget-display-labels'
					)
				)
			)
		), array( 'org_name', 'contact_name', 'job_title', 'email', 'phone', 'fax', 'address', 'address_country', 'website' ) );

		// contactPoint icon
		$this->wpb_register_setting( 'icon/contactPoint', array(
			'label' =>  __( 'Contact Person Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/contactPoint' ) );

		// Organisation icon
		$this->wpb_register_setting( 'icon/org_name', array(
			'label' =>  __( 'Organisation Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/org_name' ) );

		// Email icon
		$this->wpb_register_setting( 'icon/email', array(
			'label' =>  __( 'Email Address Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/email' ) );

		// Phone icon
		$this->wpb_register_setting( 'icon/telephone', array(
			'label' =>  __( 'Phone Number Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/telephone' ) );

		// Fax icon
		$this->wpb_register_setting( 'icon/faxNumber', array(
			'label' =>  __( 'Fax Number Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/faxNumber' ) );

		// Website icon
		$this->wpb_register_setting( 'icon/url', array(
			'label' =>  __( 'Website Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/url' ) );

		// Address icon
		$this->wpb_register_setting( 'icon/PostalAddress', array(
			'label' =>  __( 'Address Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/PostalAddress' ) );

		// Country icon
		$this->wpb_register_setting( 'icon/addressCountry', array(
			'label' =>  __( 'Country Icon', 'wpb' ),
			'type'  => 'icon'
		), wpb( 'settings/addon/get/contact', 'icon/addressCountry' ) );

		// Contact name label
		$this->wpb_register_setting( 'label/contact_name', array(
			'label' => __( 'Contact Name Label', 'wpb' ),
			'attr'  => array(
				'class' => 'regular-text wpb-contact-widget-label-input',
				'placeholder' => __( 'e.g. Name', 'wpb' )
			)
		) );

		// Email label
		$this->wpb_register_setting( 'label/email', array(
			'label' => __( 'Email Address Label', 'wpb' ),
			'attr'  => array(
				'class' => 'regular-text wpb-contact-widget-label-input',
				'placeholder' => __( 'e.g. Email', 'wpb' )
			)
		) );

		// Phone label
		$this->wpb_register_setting( 'label/phone', array(
			'label' => __( 'Phone Number Label', 'wpb' ),
			'attr'  => array(
				'class' => 'regular-text wpb-contact-widget-label-input',
				'placeholder' => __( 'e.g. Phone', 'wpb' )
			)
		) );

		// Fax label
		$this->wpb_register_setting( 'label/fax', array(
			'label' => __( 'Fax Number Label', 'wpb' ),
			'attr'  => array(
				'class' => 'regular-text wpb-contact-widget-label-input',
				'placeholder' => __( 'e.g. Fax', 'wpb' )
			)
		) );

		// Website label
		$this->wpb_register_setting( 'label/website', array(
			'label' => __( 'Website Label', 'wpb' ),
			'attr'  => array(
				'class' => 'regular-text wpb-contact-widget-label-input',
				'placeholder' => __( 'e.g. Website', 'wpb' )
			)
		) );

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
		$choices = $this->wpb_data( 'profile_choices' );

		if ( $choices )
			return $choices;

		$choices = array();

		// Get profiles
		$profiles = wpb( 'addon/contact/profiles' );

		if ( !empty( $profiles ) ) {

			foreach ( $profiles as $post_id => $profile ) {

				$choices[ $post_id ] = $profile->name;

			}

		}

		// Cache
		$this->wpb_data( 'profile_choices', $choices );

		return $choices;

	}



}