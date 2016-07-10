<?php

/**
 *
 *	Get supported sharers
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/social/sharers', 'wpb_get_supported_sharers', 5 );

if ( !function_exists( 'wpb_get_supported_sharers' ) ) :

function wpb_get_supported_sharers( $sharers = array() )
{

	// Google+
	$sharers['google-plus'] = array(
		'name' => __( 'Google+', 'wpb' ),
		'base' => 'https://plus.google.com/share?',
		'icon' => 'googleplus',
		'profile' => true,
		'param_url'   => true,
		'param_title' => false
	);

	// Facebook
	$sharers['facebook'] = array(
		'name' => __( 'Facebook', 'wpb' ),
		'base' => 'https://www.facebook.com/sharer/sharer.php?',
		'icon' => 'facebook',
		'profile' => true,
		'param_url'   => 'u',
		'param_title' => false
	);

	// Twitter
	$sharers['twitter'] = array(
		'name' => __( 'Twitter', 'wpb' ),
		'base' => 'https://twitter.com/intent/tweet?',
		'icon' => 'twitter',
		'profile' => true,
		'param_url'   => 'text',
		'param_title' => false
	);

	// Instagram
	$sharers['instagram'] = array(
		'name' => __( 'Instagram', 'wpb' ),
		'base' => '',
		'icon' => 'instagram',
		'profile' => true,
		'param_url'   => '',
		'param_title' => false
	);

	// Pinterest
	$sharers['pinterest'] = array(
		'name' => __( 'Pinterest', 'wpb' ),
		'base' => 'http://pinterest.com/pin/create/button/?',
		'icon' => 'pinterest-p',
		'profile' => true,
		'param_url'   => true,
		'param_title' => 'description'
	);

	// YouTube
	$sharers['youtube'] = array(
		'name' => __( 'YouTube', 'wpb' ),
		'base' => '',
		'icon' => 'youtube',
		'profile' => true,
		'param_url'   => '',
		'param_title' => false
	);

	// LinkedIn
	$sharers['linkedin'] = array(
		'name' => __( 'LinkedIn', 'wpb' ),
		'base' => '',
		'icon' => 'linkedin',
		'profile' => true,
		'param_url'   => '',
		'param_title' => false
	);

	return $sharers;

}

endif;


/**
 *
 *	Get email sharer
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/social/sharers', 'wpb_get_email_sharer', 20 );

if ( !function_exists( 'wpb_get_email_sharer' ) ) :

function wpb_get_email_sharer( $sharers = array() )
{

	$sharers['email'] = array(
		'name' => __( 'Email', 'wpb' ),
		'base' => 'mailto:?',
		'icon' => 'email',
		'param_url'   => 'body',
		'param_title' => 'subject',
		'setting_title' => __( 'Share by email', 'wpb' )
	);

	return $sharers;

}

endif;