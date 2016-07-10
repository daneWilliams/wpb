<?php


/**
 *
 *	Get admin pages
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/admin/pages', 'wpb_get_admin_pages', 5 );

if ( !function_exists( 'wpb_get_admin_pages' ) ) :

function wpb_get_admin_pages( $pages = array() ) {

	// Index
	$pages['index'] = array(
		'title' => __( 'WPB', 'wpb' ),
		'desc'  => __( 'A starting point for WordPress development', 'wpb' ),
		'menu'  => __( 'WPB', 'wpb' ),
		'slug'  => 'index',
		'icon'  => 'hammer'
	);

	// Addons
	$pages['addons'] = array(
		'title' => __( 'Addons', 'wpb' ),
		'menu'  => __( 'Addons', 'wpb' ),
		'slug'  => 'addons',
		'icon'  => 'admin-plugins'
	);

	// Settings
	$pages['settings'] = array(
		'title' => __( 'Settings', 'wpb' ),
		'menu'  => __( 'Settings', 'wpb' ),
		'slug'  => 'settings',
		'icon'  => 'admin-generic'
	);

	// Settings
	$pages['tools'] = array(
		'title' => __( 'Tools', 'wpb' ),
		'menu'  => __( 'Tools', 'wpb' ),
		'slug'  => 'tools',
		'icon'  => 'admin-tools'
	);

	return $pages;


}

endif;


/**
 *
 *	Add page wrapper classes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/classes/get/#wpb-admin-page', 'wpb_admin_page_wrapper_classes', 5 );

if ( !function_exists( 'wpb_admin_page_wrapper_classes' ) ) :

function wpb_admin_page_wrapper_classes( $classes = array() )
{

	$classes[] = 'wrap';
	$classes[] = 'wpb-wrap';

	return $classes;

}

endif;


/**
 *
 *	Modify component classes
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/classes/component/notification', 'wpb_admin_component_classes', 5 );

if ( !function_exists( 'wpb_admin_component_classes' ) ) :

function wpb_admin_component_classes( $classes = array() )
{

	$prefix = 'wpb';

	if ( empty( $classes ) )
		return $classes;

	foreach ( $classes as $i => $class ) {

		if ( !substr( $class, 0, 4 ) != $prefix . '-' )
			$classes[ $i ] = $prefix . '-' . $class;

	}

	return $classes;

}

endif;


/**
 *
 *	Output dashicons
 *
 *	================================================================
 *
 *	@since		1.0.0
 *
 */

add_action( 'admin_footer', 'wpb_admin_output_dashicons', 5 );

if ( !function_exists( 'wpb_admin_output_dashicons' ) ) :

function wpb_admin_output_dashicons()
{

	// Dashicons
	wpb()->file( 'admin/templates/settings/dashicons', false, true );

}

endif;