<?php


defined( 'ABSPATH' ) or die();


/**
 *
 *	Plugin name: 	WPB
 *	Plugin URI:		http://wpb.danewilliams.uk
 *
 *	Description:	A starting point for WordPress development
 *	Version:		0.0.1
 *
 *	Author:			Dane Williams
 *	Author URI:		http://danewilliams.uk
 *
 *	License: 		GNU General Public License v2 or later
 *	License URI: 	http://www.gnu.org/licenses/gpl-2.0.html
 *
 *	Text Domain:	wpb
 *	Domain Path:	/lang
 *
 */


/**
 *
 *	Autoload classes
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

spl_autoload_register( function( $class_name ) {

	$file_name = strtolower( str_replace( array( '_', '\\' ), array( '-', '-' ), $class_name ) );

	if ( 'wpb-' == substr( $file_name, 0, 4 ) )
		$file_name = substr( $file_name, 4 );

	if ( 'wpb' == $file_name ) {

		include_once rtrim( dirname( __FILE__ ), '/' ) . '/includes/class-wpb.php';
		return true;

	}

	// Core class
	if ( wpb()->file( 'includes/class-' . $file_name ) )
		return true;

	// Additional class
	if ( wpb()->file( 'includes/wpb-' . $file_name ) )
		return true;

	// WPB object
	if ( wpb()->file( 'includes/wpb-object-' . $file_name ) )
		return true;

	return false;

});


/**
 *
 *	Wrapper function
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb' ) ) :


function wpb()
{

	global $wpb;

	if ( !$wpb )
		$wpb = new WPB( __FILE__, 'wpb', __( 'WPB', 'wpb' ), '0.0.1' );

	$args = func_get_args();

	if ( empty( $args ) )
		return $wpb;

	return $wpb->request( func_get_args() );

}


endif;


/**
 *
 *	Intialise
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

wpb();
