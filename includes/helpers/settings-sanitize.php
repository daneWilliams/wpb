<?php


/**
 *
 *	Sanitise number setting
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/settings/sanitize/number', 'wpb_setting_sanitize_number', 5, 3 );

if ( !function_exists( 'wpb_setting_sanitize_number' ) ) :

function wpb_setting_sanitize_number( $value, $original, $setting )
{

	return (int) $value;

}

endif;