<?php


namespace WPB;


/**
 *
 *	Gravity Forms addon
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


class GravityForms_Addon extends Addon
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

		// Disable Gravity Forms styles
		add_filter( 'pre_option_rg_gforms_disable_css', array( $this, 'gforms_disable_css' ), 5 ); 

		// Add assets
		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ), 4 );

		// Add icon setting
		add_filter( 'gform_form_settings', array( $this, 'form_icon_settings' ), 5, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_form_icon_settings' ) );

		// Modify buttons HTML
		add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 5, 2 );
		add_filter( 'gform_previous_button', array( $this, 'form_prev_button' ), 5, 2 );
		add_filter( 'gform_next_button', array( $this, 'form_next_button' ), 5, 2 );

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

		// Styles
		$this->register_setting( 'styles', array(
			'type'    => 'checkbox',
			'label'   => __( 'Styles', 'wpb' ),
			'choices' => array(
				'addon'   => __( 'Use addon styles', 'wpb' ),
				'disable' => __( 'Disable Gravity Forms styles', 'wpb' )
			)
		), array( 'addon', 'disable' ) );
	
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

		if ( $this->choice( 'styles', 'addon' ) ) {

			wp_register_style( 'wpb-gforms', $this->url( 'assets/css/gravityforms.min.css' ), array(), $this->data( 'ver' ) );
			wp_enqueue_style( 'wpb-gforms' );

		}

	}


	/**
	 *
	 *	Disable Gravity Forms CSS
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function gforms_disable_css( $value )
	{
	
		if ( $this->choice( 'styles', 'disable' ) )
			return true;

		return $value;
	
	}


	/**
	 *
	 *	Get submit button HTML
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function form_submit_button( $button, $form )
	{

		$text = '<span class="text">' . $form['button']['text'] . '</span>';

		// Add icon
		if ( !empty( $form['wpb_button_icon'] ) ) {

			$text = wpb( 'icon', $form['wpb_button_icon'] ) . $text;

		}

		$classes = wpb( 'classes/component/button', 'primary' );
		$classes = implode( ' ', $classes );

		$button = str_replace( 
			array( '<input', "class='", ' />' ), 
			array( '<button', "class='" . $classes . " ", '>' . $text . '</button>' ),
			$button
		);

		return $button;

	}


	/**
	 *
	 *	Get previous button HTML
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function form_prev_button( $button, $form )
	{

		$text = sprintf( '<span class="text">%1$s</span>', __( 'Previous', 'wpb' ) );

		// Add icon
		if ( !empty( $form['wpb_button_prev_icon'] ) ) {

			$text = wpb( 'icon', $form['wpb_button_prev_icon'] ) . $text;

		}

		$button = str_replace( 
			array( '<input', ' />' ), 
			array( '<button', '>' . $text . '</button>' ),
			$button
		);

		return $button;

	}


	/**
	 *
	 *	Get next button HTML
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function form_next_button( $button, $form )
	{

		$text = sprintf( '<span class="text">%1$s</span>', __( 'Next', 'wpb' ) );

		// Add icon
		if ( !empty( $form['wpb_button_next_icon'] ) ) {

			$text .= wpb( 'icon/get', $form['wpb_button_next_icon'] );

		}

		$button = str_replace( 
			array( '<input', ' />' ), 
			array( '<button', '>' . $text . '</button>' ),
			$button
		);

		return $button;

	}


	/**
	 *
	 *	Add button icon form settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function form_icon_settings( $settings, $form ) 
	{

		$icon_settings = array(
			'wpb_button_icon'      => __( 'Submit icon', 'wpb' ),
			'wpb_button_prev_icon' => __( 'Previous icon', 'wpb' ),
			'wpb_button_next_icon' => __( 'Next icon', 'wpb' )
		);

		foreach ( $icon_settings as $setting_slug => $setting_label ) {

			$settings['Form Button'][ $setting_slug ] = '
				<tr>
					<th><label for="' . $setting_slug . '">' . $setting_label . '</label></th>
					<td><input type="text" value="' . rgar( $form, $setting_slug ) . '" name="' . $setting_slug . '"></td>
				</tr>';

		}

		return $settings;
	
	}


	/**
	 *
	 *	Save icon form settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function save_form_icon_settings( $form ) 
	{

		$form['wpb_button_icon']      = rgpost( 'wpb_button_icon' );
		$form['wpb_button_prev_icon'] = rgpost( 'wpb_button_prev_icon' );
		$form['wpb_button_next_icon'] = rgpost( 'wpb_button_next_icon' );

		return $form;
	
	}


}