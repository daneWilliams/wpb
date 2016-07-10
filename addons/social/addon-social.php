<?php


namespace WPB;


/**
 *
 *	Social addon
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


class Social_Addon extends Addon
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

		// Load helpers
		$this->file( 'includes/helpers/sharers' );

		// Add assets
		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ) );

		// Modify setting locations
		add_filter( 'wpb/admin/settings/locations/names', array( $this, 'add_setting_location_names' ), 10, 2 );

		// Output buttons
		add_action( 'wpb/after/single/header', array( $this, 'output_buttons_single_auto' ) );
		add_action( 'wpb/after/single/entry', array( $this, 'output_buttons_single_auto' ) );
		add_action( 'wpb/after/single/content', array( $this, 'output_buttons_single_auto' ) );

		add_action( 'wpb/after/loop/header', array( $this, 'output_buttons_loop_auto' ) );
		add_action( 'wpb/after/loop/entry', array( $this, 'output_buttons_loop_auto' ) );
		add_action( 'wpb/after/loop/content', array( $this, 'output_buttons_loop_auto' ) );

		// Register widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ), 5 );

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

		// social/share
		$this->register_request( 'share', array( $this, 'request__share_buttons' ), array(
			'type'     => '',
			'title'    => '',
			'button'   => NULL,
			'icon'     => NULL,
			'text'     => NULL,
			'icons'    => NULL,
			'small'    => NULL,
			'circular' => NULL,
			'sharers'  => array()
		), true );

		$this->register_request( 'share/*', 'share', 'type' );

		// social/profiles
		$this->register_request( 'profiles', array( $this, 'request__profiles' ), array(
			'inline'   => true,
			'buttons'  => NULL,
			'text'     => NULL,
			'icons'    => NULL,
			'small'    => NULL,
			'circular' => NULL
		), true );

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

		// Stylesheet
		$this->register_setting( 'stylesheet', array(
			'type'  => 'boolean',
			'label' => __( 'Stylesheet', 'wpb' ),
			'text'  => __( 'Use addon styles', 'wpb' ),
			'location' => 'styles'
		), true );

		// Share buttons
		$this->register_setting( 'share/buttons', array(
			'label'   => __( 'Share buttons', 'wpb' ),
			'type'    => 'checkbox',
			'choices' => array(
				'text'     => __( 'Display button text', 'wpb' ),
				'icons'    => __( 'Display button icons', 'wpb' ),
				'small'    => __( 'Display small buttons', 'wpb' ),
				'circular' => __( 'Display circular buttons', 'wpb' )
			),
			'desc'  => sprintf(
				__( 'Buttons can be displayed in a template with <code>%1$s</code> or a shortcode with <code>%2$s</code>', 'wpb' ),
				'wpb( \'<strong>social/share</strong>\' );',
				'[<strong>wpb-social-share</strong>]'
			),
			'location' => 'styles'
		), array( 'text', 'icons', 'buttons' ) );

		// Profile links
		$this->register_setting( 'profiles', array(
			'label'   => __( 'Profile links', 'wpb' ),
			'type'    => 'checkbox',
			'choices' => array(
				'text'     => __( 'Display link text', 'wpb' ),
				'icons'    => __( 'Display link icons', 'wpb' ),
				'buttons'  => __( 'Display links as buttons', 'wpb' ),
				'small'    => __( 'Display small buttons', 'wpb' ),
				'circular' => __( 'Display circular buttons', 'wpb' )
			),
			'desc'  => sprintf(
				__( 'Links can be displayed in a template with <code>%1$s</code> or a shortcode with <code>%2$s</code>', 'wpb' ),
				'wpb( \'<strong>social/profiles</strong>\' );',
				'[<strong>wpb-social-profiles</strong>]'
			),
			'location' => 'styles'
		), array( 'text', 'icons' ) );

		// Share icon
		$this->register_setting( 'share/icon', array(
			'label' => __( 'Share icon', 'wpb' ),
			'type'  => 'icon',
			'location' => 'share'
		), 'share' );

		// Share title
		$this->register_setting( 'share/title', array(
			'label' => __( 'Share title', 'wpb' ),
			'attr'  => array(
				'placeholder' => __( 'e.g. Share', 'wpb' )
			),
			'location' => 'share'
		) );

		// Share button
		$this->register_setting( 'share/title/button', array(
			'type'  => 'boolean',
			'label' => __( 'Share button', 'wpb' ),
			'text'  => __( 'Display share title as a button', 'wpb' ),
			'location' => 'share'
		) );

		// Single display
		$this->register_setting( 'single/share/auto', array(
			'label'   => __( 'Single share display', 'wpb' ),
			'type'    => 'radio',
			'choices' => array(
				'none' => array(
					'label' => __( 'Manual', 'wpb' ),
					'desc'  => sprintf(
						__( 'Can be displayed in a template with <code>%1$s</code> or a shortcode with <code>%2$s</code>', 'wpb' ),
						'wpb( \'social/share/<strong>single</strong>\' );',
						'[wpb-social-share <strong>type="single"</strong>]'
					)
				),
				'header' => array(
					'label' => __( 'Display buttons after header', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/single/header'
					)
				),
				'entry' => array(
					'label' => __( 'Display buttons after content', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/single/entry'
					)
				),
				'footer' => array(
					'label' => __( 'Display buttons after footer', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/single/content'
					)
				)
			),
			'location' => 'single'
		), 'entry' );

		// Single share title
		$this->register_setting( 'single/share/title', array(
			'label' => __( 'Single share title', 'wpb' ),
			'desc'  => sprintf( __( 'Use <code><strong>%s</strong></code> to display the post type label. Leave blank to use the default share title.', 'wpb' ), '%post_type%' ),
			'attr'  => array(
				'placeholder' => __( 'e.g. Share this %post_type%', 'wpb' )
			),
			'location' => 'single'
		), __( 'Share', 'wpb' ) );

		// Single share button
		$this->register_setting( 'single/share/title/button', array(
			'type'    => 'radio',
			'label'   => __( 'Single share button', 'wpb' ),
			'choices' => array(
				'default' => __( 'Use default share button setting', 'wpb' ),
				'text'    => __( 'Display single share title as text', 'wpb' ),
				'button'  =>__( 'Display single share title as a button', 'wpb' )
			),
			'location' => 'single'
		), 'default' );


		// Looped display
		$this->register_setting( 'loop/share/auto', array(
			'label'   => __( 'Looped share display', 'wpb' ),
			'type'    => 'radio',
			'choices' => array(
				'none' => array(
					'label' => __( 'Manual', 'wpb' ),
					'desc'  => sprintf(
						__( 'Can be displayed in a template with <code>%1$s</code> or a shortcode with <code>%2$s</code>', 'wpb' ),
						'wpb( \'social/share/<strong>loop</strong>\' );',
						'[wpb-social-share <strong>type="loop"</strong>]'
					)
				),
				'header' => array(
					'label' => __( 'Display buttons after header', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/loop/header'
					)
				),
				'entry' => array(
					'label' => __( 'Display buttons after content', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/loop/entry'
					)
				),
				'footer' => array(
					'label' => __( 'Display buttons after footer', 'wpb' ),
					'desc'  => sprintf(
						__( 'Hooks into <code>%s</code>', 'wpb' ),
						'wpb/after/loop/content'
					)
				)
			),
			'location' => 'loop'
		), 'none' );

		// Looped share title
		$this->register_setting( 'loop/share/title', array(
			'label' => __( 'Looped share title', 'wpb' ),
			'desc'  => sprintf( __( 'Use <code><strong>%s</strong></code> to display the post type label. Leave blank to use the default share title.', 'wpb' ), '%post_type%' ),
			'attr'  => array(
				'placeholder' => __( 'e.g. Share this %post_type%', 'wpb' )
			),
			'location' => 'loop'
		) );

		// Looped share button
		$this->register_setting( 'loop/share/title/button', array(
			'type'    => 'radio',
			'label'   => __( 'Looped share button', 'wpb' ),
			'choices' => array(
				'default' => __( 'Use default share button setting', 'wpb' ),
				'text'    => __( 'Display looped share title as text', 'wpb' ),
				'button'  =>__( 'Display looped share title as a button', 'wpb' )
			),
			'location' => 'loop'
		), 'default' );

		// Sharers
		$sharers = $this->get_sharers();

		if ( !empty( $sharers ) ) {

			foreach ( $sharers as $slug => $sharer ) {

				// Icon
				$this->register_setting( $slug . '/icon', array(
					'label' => sprintf( __( '%s icon', 'wpb' ), $sharer['name'] ),
					'type'  => 'icon',
					'location' => $slug
				), $sharer['icon'] );

				// Profile
				if ( !empty( $sharer['settings']['profile'] ) ) {

					// URL
					$this->register_setting( $slug . '/profile/url', array(
						'label' => sprintf( __( '%s profile URL', 'wpb' ), $sharer['name'] ),
						'attr'  => array(
							'placeholder' => 'http://',
							'type' => 'url'
						),
						'location' => $slug
					) );

					// Link text
					$this->register_setting( $slug . '/profile/text', array(
						'label' => sprintf( __( '%s profile link text', 'wpb' ), $sharer['name'] ),
						'attr'  => array(
							'placeholder' => sprintf( __( 'e.g. Find me on %s', 'wpb' ), $sharer['name'] )
						),
						'location' => $slug
					), $sharer['name'] );

				}

				// Share
				if ( !empty( $sharer['settings']['share'] ) ) {

					// Share enabled
					$this->register_setting( $slug . '/share/enabled', array(
						'label' => sprintf( __( '%s share button', 'wpb' ), $sharer['name'] ),
						'type'  => 'boolean',
						'text'  => sprintf( __( 'Enable %s share button', 'wpb' ), $sharer['name'] ),
						'location' => $slug
					), true );

					// Share title
					$this->register_setting( $slug . '/share/title', array(
						'label' => sprintf( __( '%s share title', 'wpb' ), $sharer['name'] ),
						'attr'  => array(
							'placeholder' => sprintf( __( 'e.g. %s', 'wpb' ), $sharer['settings']['title'] )
						),
						'location' => $slug
					), $sharer['settings']['title'] );

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

		// Styles
		$names['addon/social/styles'] = __( 'Styles', 'wpb' );

		// Share
		$names['addon/social/share'] = __( 'Share', 'wpb' );

		// Single content
		$names['addon/social/single'] = __( 'Single Content', 'wpb' );

		// Looped content
		$names['addon/social/loop'] = __( 'Looped Content', 'wpb' );

		// Sharers
		$sharers = $this->get_sharers();

		foreach ( $sharers as $slug => $sharer ) {

			if ( !empty( $sharer['name'] ) )
				$names[ 'addon/social/' . $slug ] = $sharer['name'];

		}

		return $names;
	
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

			wp_enqueue_style( 'wpb-social', $this->url( 'assets/css/wpb-social.min.css' ), array(), $this->data( 'ver' ) );

		}

		// Script
		wp_enqueue_script( 'wpb-social', $this->url( 'assets/js/wpb-social.js' ), array( 'jquery' ), $this->data( 'ver' ), true );

	}


	/**
	 *
	 *	Register widgets
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_widgets()
	{

		// Profiles
		$this->file( 'includes/wpb-widget-social-profiles' );
		register_widget( 'WPB\Social_Profiles_Widget' );

	}


	/**
	 *
	 *	Get sharers
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_sharers()
	{
	
		// Cached
		$sharers = $this->data( '_sharers' );

		if ( !empty( $sharers ) )
			return $sharers;

		// Get from filter
		$sharers = apply_filters( 'wpb/social/sharers', array() );

		// Format
		if ( !empty( $sharers ) ) {

			$formatted = array();

			foreach ( $sharers as $slug => $data ) {

				if ( empty( $data['name'] ) )
					continue;

				$sharer = array(
					'slug'  => $slug,
					'name'  => $data['name'],
					'base'  => $data['base'],
					'icon'  => ( !empty( $data['icon'] ) ? $data['icon'] : '' ),
					'params' => array(
						'url'   => '',
						'title' => ''
					),
					'settings' => array(
						'title'   => sprintf( __( 'Share on %s', 'wpb' ), $data['name'] ),
						'profile' => false,
						'share'   => true
					)
				);

				if ( empty( $data['base'] ) )
					$sharer['settings']['share'] = false;

				// Set URL parameter
				if ( !empty( $data['param_url'] ) ) {

					$url = ( true === $data['param_url'] ? 'url' : $data['param_url'] );

					$sharer['params']['url'] = $url;

				}

				// Set title parameter
				if ( !empty( $data['param_title'] ) ) {

					$title = ( true === $data['param_title'] ? 'title' : $data['param_title'] );

					$sharer['params']['title'] = $title;

				}

				// Set default setting values
				if ( !empty( $data['setting_title'] ) )
					$sharer['settings']['title'] = $data['setting_title'];

				if ( isset( $data['profile'] ) )
					$sharer['settings']['profile'] = $data['profile'];

				// Add to formatted
				$formatted[ $slug ] = $sharer;

			}

			$sharers = $formatted;

		}

		// Cache
		$this->data( '_sharers', $sharers );

		return $sharers;
	
	}


	/**
	 *
	 *	Get current details to share
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_share_details()
	{

		// Get post type
		$post_type = get_post_type();

		$post_type_obj   = get_post_type_object( $post_type );
		$post_type_label = ( 
			isset( $post_type_obj->labels->singular_name ) 
			? $post_type_obj->labels->singular_name : ( 
				isset( $post_type_obj->label ) 
				? $post_type_obj->label 
				: '' 
			) 
		);

		// Default
		$details = array(
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'post_type' => $post_type,
			'post_type_label' => $post_type_label
		);

		return $details;
	
	}


	/**
	 *
	 *	Request: Get profile links HTML
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__profiles( $args = array() )
	{	

		// Get sharers
		$sharers = $this->get_sharers();

		if ( empty( $sharers ) )
			return;

		$links = array();

		foreach ( $sharers as $slug => $sharer ) {

			if ( empty( $sharer['settings']['profile'] ) )
				continue;

			$link = array(
				'icon' => $this->settings( $slug . '/icon' ),
				'text' => $this->settings( $slug . '/profile/text' ),
				'url'  => $this->settings( $slug . '/profile/url' )
			);

			if ( empty( $link['url'] ) )
				continue;

			$links[ $slug ] = $link;

		}

		if ( empty( $links ) )
			return;

		// Get arguments
		if ( is_null( $args['buttons'] ) )
			$args['buttons'] = $this->choice( 'profiles', 'buttons' );

		if ( is_null( $args['text'] ) )
			$args['text'] = $this->choice( 'profiles', 'text' );

		if ( is_null( $args['icons'] ) )
			$args['icons'] = $this->choice( 'profiles', 'icons' );

		if ( is_null( $args['small'] ) )
			$args['small'] = $this->choice( 'profiles', 'small' );

		if ( is_null( $args['circular'] ) )
			$args['circular'] = $this->choice( 'profiles', 'circular' );

		// Get the attributes
		$classes = array( 'profile-links' );

		if ( !empty( $args['inline'] ) )
			$classes[] = 'profile-links-inline';

		if ( !$args['text'] )
			$classes[] = 'profile-links-no-text';

		if ( $args['buttons'] ) {

			$classes[] = 'profile-links-buttons';

			if ( $args['small'] )
				$classes[] = 'profile-buttons-small';

			if ( $args['circular'] )
				$classes[] = 'profile-buttons-circular';

		}

		$attr = array(
			'class' => wpb( 'classes/get', 'social-profiles' , $classes )
		);

		// Build the HTML
		$html  = '<nav' . wpb( 'attr', $attr ) .'>';
		$html .= '<ul class="profile-link-items">';

		foreach ( $links as $slug => $link ) {

			$html .= '<li class="profile-link profile-link-' . esc_attr( $slug ) . '">';

			if ( $args['buttons'] ) {

				$button_args = array(
					'text'  => $link['text'],
					'icon'  => $link['icon'],
					'href'  => $link['url'],
					'class' => array( 'profile', 'profile-' . $slug ),
					'attr'  => array(
						'title'  => $link['text'],
						'target' => '_blank'
					)
				);

				if ( $this->choice( 'profiles', 'small' ) )
					$button_args['class'][] = 'small';

				if ( !$args['text'] )
					$button_args['text'] = '';

				if ( !$args['icons'] )
					$button_args['icon'] = '';

				if ( $button_args['icon'] )
					$button_args['class'][] = 'with-icon';

				$html .= wpb( 'button', $button_args );

			} else {

				$html .= '<a href="' . esc_attr( $link['url'] ) . '" title="' . esc_attr( $link['text'] ) . '" target="_blank">';

				if ( $args['icons'] && $link['icon'] )
					$html .= wpb( 'icon', $link['icon'] );

				if ( $args['text'] ) {

					$html .= '<span class="link-text">';
					$html .= $link['text'];
					$html .= '</span>';

				}

				$html .= '</a>';

			}

			$html .= '</li>';

			$html .= PHP_EOL;

		}

		$html .= '</ul>';
		$html .= '</nav>';

		return $html;	

	}


	/**
	 *
	 *	Request: Get buttons HTML
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request__share_buttons( $args = array() )
	{	

		// Get share details
		$sharers = $this->get_sharers( $args['sharers'] );
		$details = $this->get_share_details();

		if ( empty( $sharers ) )
			return;

		// Get share title
		$share_title  = ( !empty( $args['title'] )    ? $args['title']  : $this->settings( 'share/title' ) );
		$share_icon   = ( !is_null( $args['icon'] )   ? $args['icon']   : $this->settings( 'share/icon' ) );
		$share_button = ( !is_null( $args['button'] ) ? $args['button'] : $this->settings( 'share/title/button' ) );

		if ( !empty( $args['type'] ) ) {

			$share_type_title = $this->settings( $args['type'] . '/share/title' );

			if ( $share_type_title )
				$share_title = str_replace( '%post_type%', strtolower( $details['post_type_label'] ), $share_type_title );

			if ( $this->choice( $args['type'] . '/share/title/button', 'button' ) )
				$share_button = true;

			elseif ( $this->choice( $args['type'] . '/share/title/button', 'text' ) )
				$share_button = false;

		}

		// Get arguments
		if ( is_null( $args['text'] ) )
			$args['text'] = $this->choice( 'share/buttons', 'text' );

		if ( is_null( $args['icons'] ) )
			$args['icons'] = $this->choice( 'share/buttons', 'icons' );

		if ( is_null( $args['small'] ) )
			$args['small'] = $this->choice( 'share/buttons', 'small' );

		if ( is_null( $args['circular'] ) )
			$args['circular'] = $this->choice( 'share/buttons', 'circular' );

		// Get buttons
		$buttons = array();

		if ( $share_button ) {

			$buttons['share'] = array(
				'type'  => 'button',
				'class' => array( 'share', 'share-main', 'secondary' ),
				'text'  => $share_title,
				'icon'  => $share_icon,
				'attr'  => array(
					'title' => $share_title
				)
			);

		}

		foreach ( $sharers as $slug => $sharer ) {

			if ( empty( $sharer['params']['url'] ) || empty( $sharer['base'] ) )
				continue;

			if ( !$this->choice( $slug . '/share/enabled' ) )
				continue;

			// Get URL
			$href  = $sharer['base'];
			$href .= $sharer['params']['url'];
			$href .= '=';
			$href .= urlencode( $details['url'] );

			// Add title
			if ( !empty( $details['title'] ) && !empty( $sharer['params']['title'] ) ) {

				$href .= '&amp;';
				$href .= $sharer['params']['title'];
				$href .= '=';
				$href .= urlencode( $details['title'] );

			}

			// Get button text
			$title = $this->settings( $slug . '/share/title' );
			$text  = ( $title ? $title : $sharer['name'] );

			// Get button
			$buttons[ $slug ] = array(
				'href'  => $href,
				'text'  => $text,
				'class' => array( 'share', 'share-' . $slug ),
				'icon'  => $this->settings( $slug . '/icon' ),
				'attr'  => array(
					'title'  => $title,
					'target' => '_blank'
				)
			);

		}

		if ( empty( $buttons ) )
			return '';

		foreach ( $buttons as $button_slug => $button_args ) {

			if ( !$args['text'] )
				$button_args['text'] = '';

			if ( $args['small'] )
				$button_args['class'][] = 'small';

			if ( !$args['icons'] )
				$button_args['icon'] ='' ;

			if ( $button_args['icon'] )
				$button_args['class'][] = 'with-icon';

			$buttons[ $button_slug ] = wpb( 'button', $button_args );

		}

		// Get the classes
		$classes = array( 'share-buttons' );

		if ( !empty( $args['class'] ) ) {

			if ( !is_array( $args['class'] ) )
				$args['class'] = array_map( 'trim', explode( ' ', $args['class'] ) );

			$classes = array_merge( $classes, $args['class'] );

		}

		if ( !$args['text'] )
			$classes[] = 'share-buttons-no-text';

		if ( $args['circular'] )
			$classes[] = 'share-buttons-circular';

		// Get the attributes
		$attr = array(
			'class' => wpb( 'classes/get', 'share-buttons' , $classes )
		);

		// Build the HTML
		$html = '<div' . wpb( 'attr', $attr ) .'>';

		if ( $share_title && !$share_button ) {

			$html .= '<div class="share-label">' . $share_title . '</div>';

		}

		if ( $share_button ) {

			$share_button = $buttons['share'];
			unset( $buttons['share'] );

			$html .= $share_button;

		}

		$html .= '<div class="share-buttons-container">';
		$html .= implode( PHP_EOL, $buttons );
		$html .= '</div>';

		$html .= '</div>';

		return $html;	
	
	}


	/**
	 *
	 *	Add share buttons classes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function add_share_buttons_classes( $classes = array() )
	{
	
		// No text
		if ( !$this->choice( 'share/buttons', 'text' ) )
			$classes[] = 'share-buttons-no-text';

		// No icons
		if ( !$this->choice( 'share/buttons', 'icons' ) )
			$classes[] = 'share-buttons-no-icons';

		// Small buttons
		if ( $this->choice( 'share/buttons', 'small' ) )
			$classes[] = 'share-buttons-small';

		return $classes;
	
	}


	/**
	 *
	 *	Single buttons output
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_buttons_single_auto()
	{

		if ( $this->choice( 'single/share/auto', 'none', true ) )
			return;

		$action = current_filter();

		if ( 'wpb/after/single/header' == $action && !$this->choice( 'single/share/auto', 'header' ) )
			return;

		if ( 'wpb/after/single/entry' == $action && !$this->choice( 'single/share/auto', 'entry' ) )
			return;

		if ( 'wpb/after/single/content' == $action && !$this->choice( 'single/share/auto', 'footer' ) )
			return;

		// Output buttons
		echo wpb( 'social/share/single' );

	}


	/**
	 *
	 *	Loop buttons output
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function output_buttons_loop_auto()
	{

		if ( $this->choice( 'loop/share/auto', 'none', true ) )
			return;

		$action = current_filter();

		if ( 'wpb/after/loop/header' == $action && !$this->choice( 'loop/share/auto', 'header' ) )
			return;

		if ( 'wpb/after/loop/entry' == $action && !$this->choice( 'loop/share/auto', 'entry' ) )
			return;

		if ( 'wpb/after/loop/content' == $action && !$this->choice( 'loop/share/auto', 'footer' ) )
			return;

		// Output buttons
		echo wpb( 'social/share/loop' );

	}


}