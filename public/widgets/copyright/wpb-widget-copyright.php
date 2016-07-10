<?php


namespace WPB;


/**
 *
 *	Copyright widget
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

class Copyright_Widget extends Widget
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
			'copyright', 
			__( 'Copyright', 'wpb' ),
			array(
				'description' => __( 'Display a copyright message', 'wpb' )
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

		$year = ( !empty( $settings['year'] ) ? $settings['year'] : date( 'Y' ) );
		$str  = '';

		if ( !empty( $settings['text'] ) )
			$str .= $settings['text'] . ' ';

		if ( !empty( $settings['symbol'] ) )
			$str .= '&copy; ';

		$str .= $year . ' ';

		if ( !empty( $settings['message'] ) )
			$str .= $settings['message'];

		// Output
		echo '<div class="copyright">';
		echo trim( $str );
		echo '</div>';

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

		// Symbol
		$this->wpb_register_setting( 'symbol', array(
			'label' => __( 'Copyright', 'wpb' ),
			'type'  => 'boolean',
			'text'  => __( 'Display <strong>&copy;</strong> symbol', 'wpb' )
		), true );

		// Year
		$this->wpb_register_setting( 'year', array(
			'label' => __( 'Year', 'wpb' ),
			'type'  => 'number',
			'desc'  => __( 'Leave blank to display current year', 'wpb' ),
			'attr'  => array(
				'class' => 'small-text',
				'placeholder' => date( 'Y' )
			)
		) );

		// Text
		$this->wpb_register_setting( 'text', array(
			'label' => __( 'Text', 'wpb' ),
			'desc'  => __( 'Text will be displayed before the year', 'wpb' ),
			'attr'  => array(
				'placeholder' => __( 'Copyright', 'wpb' ) 
			) 
		) );

		// Message
		$this->wpb_register_setting( 'message', array(
			'label' => __( 'Message', 'wpb' ),
			'desc'  => __( 'Message will be displayed after the year', 'wpb' ),
			'attr'  => array(
				'class' => 'widefat',

			)
		) );

	}


}