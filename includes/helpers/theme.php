<?php


/**
 *
 *	Setup the theme
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'after_setup_theme', 'wpb_setup_theme', 5 );

if ( !function_exists( 'wpb_setup_theme' ) ) :

function wpb_setup_theme()
{

	// Title tag
	add_theme_support( 'title-tag' );

	// Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Banners
	if ( function_exists( 'wpb' ) && wpb( 'settings/get', 'banner/enabled' ) ) {

		add_theme_support( 'wpb-banner', wpb( 'settings/get', 'banner/post_types' ) );

	}

	// Custom header
	if ( function_exists( 'wpb' ) && wpb( 'settings/get', 'custom-header/enabled' ) ) {

		$header = wpb( 'settings/get', 'custom-header/size', array() );

		if ( !is_array( $header ) )
			$header = array();

		add_theme_support( 'custom-header', apply_filters( 'wpb/custom-header/args', array_merge( array(
			'flex-width'  => true,
			'flex-height' => true
		), $header ) ) );

	}

	// Page excerpts
	if ( function_exists( 'wpb' ) && wpb( 'settings/get', 'excerpt/manual', 'pages' ) ) {

		add_post_type_support( 'page', 'excerpt' );

	}

	// HTML5
	add_theme_support( 'html5', apply_filters( 'wpb/html5-support', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) ) );

}

endif;


/**
 *
 *	Add assets
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_enqueue_scripts', 'wpb_add_assets', 5 );

if ( !function_exists( 'wpb_add_assets' ) ) :

function wpb_add_assets()
{

	// WordPress
	wp_enqueue_style( 'open-sans' );
	wp_enqueue_style( 'dashicons' );

	// WPB base styles
	if ( function_exists( 'wpb' ) && wpb( 'settings/choice', 'css/stylesheets', 'plugin' ) ) {

		wp_register_style( 'wpb-theme', wpb()->url( 'public/assets/css/wpb-theme.min.css' ), array(), wpb()->data( 'ver' ) );
		wp_enqueue_style( 'wpb-theme' );

	}

	// WPB theme script
	if ( function_exists( 'wpb' ) && wpb( 'settings/choice', 'js/scripts', 'plugin' ) ) {

		wp_register_script( 'wpb-theme', wpb()->url( 'public/assets/js/wpb-theme.js' ), array( 'jquery' ), wpb()->data( 'ver' ), true );
		wp_enqueue_script( 'wpb-theme' );

	}

	// Comments
	if ( is_singular() && comments_open() ) {

		wp_enqueue_script( 'comment-reply' );

	}

}

endif;


/**
 *
 *	Add theme stylesheet
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_enqueue_scripts', 'wpb_add_theme_stylesheet', 999 );

if ( !function_exists( 'wpb_add_theme_stylesheet' ) ) :

function wpb_add_theme_stylesheet()
{

	if ( !function_exists( 'wpb' ) || ( !wpb( 'settings/choice', 'css/stylesheets', 'main' ) && !wpb( 'settings/choice', 'css/stylesheets', 'theme' ) ) )
		return;

	// Get stylesheet
	$stylesheet = wpb( 'stylesheet/theme' );

	if ( !$stylesheet && !wpb( 'settings/choice', 'css/stylesheets', 'main' ) )
		return;

	// Get theme info
	$theme = wp_get_theme();

	// Add stylesheet
	wp_register_style( 'main', $stylesheet, array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'main' );

	// Add main stylesheet
	if ( $stylesheet != get_stylesheet_directory_uri() . '/style.css' && wpb( 'settings/choice', 'css/stylesheets', 'theme' ) )
		wp_enqueue_style( 'theme', get_stylesheet_directory_uri() . '/style.css' );

}

endif;


/**
 *
 *	Add custom CSS
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_enqueue_scripts', 'wpb_add_custom_css', 999 );

if ( !function_exists( 'wpb_add_custom_css' ) ) :

function wpb_add_custom_css()
{

	if ( !function_exists( 'wpb' ) || !wpb( 'settings/choice', 'css/input' ) )
		return;

	$handle = 'theme';

	if ( !wpb( 'settings/choice', 'css/stylesheets', 'theme' ) )
		$handle = 'wpb-theme';

	wp_add_inline_style( $handle, wpb( 'settings/get', 'css/input' ) );

}

endif;


/**
 *
 *	Add custom JavaScript
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_head',   'wpb_add_custom_js', 999 );
add_action( 'wp_footer', 'wpb_add_custom_js', 999 );

if ( !function_exists( 'wpb_add_custom_js' ) ) :

function wpb_add_custom_js()
{

	if ( !function_exists( 'wpb' ) )
		return;

	if ( !wpb( 'settings/choice', 'js/input' ) )
		return;

	if ( wpb( 'settings/choice', 'js/input_pos', ( 'wp_head' == current_filter() ? 'head' : 'footer' ) ) ) {

		?><script type="text/javascript"><?php echo wpb( 'settings/js/input' ); ?></script><?php

	}

}

endif;


/**
 *
 *	Add inline JavaScript
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wp_head', 'wpb_add_js_head', 5 );

if ( !function_exists( 'wpb_add_js_head' ) ) :

function wpb_add_js_head()
{ 

	?><script type="text/javascript">(function(h){h.className = h.className.replace( 'no-js', 'js' ) })(document.documentElement)</script><?php

}

endif;


/**
 *
 *	Add image sizes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'after_setup_theme', 'wpb_add_image_sizes', 5 );

if ( !function_exists( 'wpb_add_image_sizes' ) ) :

function wpb_add_image_sizes() 
{

	if ( !function_exists( 'wpb' ) )
		return;

	// Banner
	$banner_width  = wpb( 'settings/banner/width', 1200 );
	$banner_height = wpb( 'settings/banner/height', 480 );

	if ( !$banner_width || !$banner_height )
		return;

	add_image_size( 'wpb_banner',        $banner_width,                $banner_height,                true );
	add_image_size( 'wpb_banner_large',  round( $banner_width * 1.2 ), round( $banner_height * 1.2 ), true );
	add_image_size( 'wpb_banner_medium', round( $banner_width / 1.2 ), round( $banner_height / 1.2 ), true );
	add_image_size( 'wpb_banner_small',  round( $banner_width / 2 ),   round( $banner_height / 2 ),   true );

}

endif;


/**
 *
 *	Register sidebars
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'init', 'wpb_register_sidebars', 5 );

if ( !function_exists( 'wpb_register_sidebars' ) ) :

function wpb_register_sidebars()
{


	/**
	 *
	 *	Main areas
	 *
	 *
	 *	These are the primary sidebars used by WPB.
	 *
	 *	The main sidebar is used on most pages and the secondary
	 *	sidebar can be used in a template if different widget content is
	 *	needed, such as a contact page.
	 *
	 *	================================================================ */

	// Main sidebar
	$sidebars['sidebar-1'] = array(
		'name'          => __( 'Sidebar #1', 'wpb' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'The main sidebar, displayed on most pages', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Secondary sidebar
	$sidebars['sidebar-2'] = array(
		'name'          => __( 'Sidebar #2', 'wpb' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'The secondary sidebar, displayed on certain pages', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);


	/**
	 *
	 *	Header areas
	 *
	 *
	 *	This allows widget content to be added to the header, rather than
	 *	having to hardcode custom header content.
	 *
	 *	The footer can have up to 4 sidebars in a variety of layouts
	 *
	 *	================================================================ */

	// Header area 1
	$sidebars['header-1'] = array(
		'name'          => __( 'Header #1', 'wpb' ),
		'id'            => 'header-1',
		'description'   => __( 'The first header area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Header area 2
	$sidebars['header-2'] = array(
		'name'          => __( 'Header #2', 'wpb' ),
		'id'            => 'header-2',
		'description'   => __( 'The second header area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Header area 3
	$sidebars['header-3'] = array(
		'name'          => __( 'Header #3', 'wpb' ),
		'id'            => 'header-3',
		'description'   => __( 'The third header area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Header area 4
	$sidebars['header-4'] = array(
		'name'          => __( 'Header #4', 'wpb' ),
		'id'            => 'header-4',
		'description'   => __( 'The fourth header area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);


	/**
	 *
	 *	Footer areas
	 *
	 *
	 *	The footer can have up to 4 sidebars in a variety of layouts
	 *
	 *	================================================================ */

	// Footer area 1
	$sidebars['footer-1'] = array(
		'name'          => __( 'Footer #1', 'wpb' ),
		'id'            => 'footer-1',
		'description'   => __( 'The first footer area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Footer area 2
	$sidebars['footer-2'] = array(
		'name'          => __( 'Footer #2', 'wpb' ),
		'id'            => 'footer-2',
		'description'   => __( 'The second footer area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Footer area 3
	$sidebars['footer-3'] = array(
		'name'          => __( 'Footer #3', 'wpb' ),
		'id'            => 'footer-3',
		'description'   => __( 'The third footer area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	// Footer area 4
	$sidebars['footer-4'] = array(
		'name'          => __( 'Footer #4', 'wpb' ),
		'id'            => 'footer-4',
		'description'   => __( 'The fourth footer area', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);


	/**
	 *
	 *	404 sidebar
	 *
	 *	================================================================ */

	$sidebars['sidebar-404'] = array(
		'name'          => __( '404 Sidebar', 'wpb' ),
		'id'            => 'sidebar-404',
		'description'   => __( 'Displayed on the 404 page', 'wpb' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);


	// Filter
	$sidebars = apply_filters( 'wpb/sidebars', $sidebars );

	// No sidebars
	if ( empty( $sidebars ) )
		return;

	// Register
	foreach ( $sidebars as $sidebar ) {

		register_sidebar( $sidebar );

	}

}

endif;


/**
 *
 *	Add sidebar classes to body element
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'body_class', 'wpb_body_sidebar_classes', 5 );

if ( !function_exists( 'wpb_body_sidebar_classes' ) ) :

function wpb_body_sidebar_classes( $classes = array() )
{

	if ( !function_exists( 'wpb' ) )
		return $classes;

	// Already set
	if ( isset( $classes['wpb-sidebar'] ) )
		return $classes;

	// Get default alignment
	$align = wpb( 'settings/get', 'layout/sidebar_position', 'right' );

	// Get alignment
	$alignments = array( 'right', 'left', 'none' );

	foreach ( $alignments as $alignment ) {

		// Get from page template
		if ( is_page_template( 'pages/sidebar-' . $alignment . '.php' ) )
			$align = $alignment;

		// Get from existing classes
		if ( in_array( 'sidebar-' . $alignment, $classes ) )
			$align = $alignment;

	}

	// Get from filter
	$align = apply_filters( 'wpb/sidebar/align', $align, $classes );

	if ( !$align )
		$align = 'none';

	// Remove existing
	foreach ( $alignments as $alignment ) {

		$found = array_search( 'sidebar-' . $alignment, $classes );

		if ( $found )
			unset( $classes[ $found ] );

	}

	// Add to classes
	$classes['wpb-sidebar'] = 'sidebar-' . $align;

	return $classes;

}

endif;


/**
 *
 *	Remove sidebar alignment
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'wpb/sidebar/align', 'wpb_remove_sidebar_alignment', 5 );

if ( !function_exists( 'wpb_remove_sidebar_alignment' ) ) :

function wpb_remove_sidebar_alignment( $align )
{

	// Search page
	if ( is_search() )
		return '';

	// 404
	if ( is_404() && ( !wpb( 'settings/choice', '404/sidebar', 'enabled' ) || wpb( 'settings/choice', '404/sidebar', 'stacked' ) ) )
		return '';

	return $align;

}

endif;


/**
 *
 *	Modify post classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'post_class', 'wpb_post_classes', 5 );

if ( !function_exists( 'wpb_post_class' ) ) :

function wpb_post_classes( $classes = array() )
{

	if ( !function_exists( 'wpb' ) )
		return $classes;

	// Add banner classes
	if ( current_theme_supports( 'wpb-banner' ) ) {

		// Saved banner
		if ( wpb( 'has_post_banner' ) ) {

			$classes[] = 'has-post-banner';

		} else {

			// Thumbnail fallback
			if ( has_post_thumbnail() && wpb( 'setting/get', 'banner/fallback' ) ) {

				$classes[] = 'has-post-banner';
				$classes[] = 'has-post-thumbnail-banner';

				// Remove thumbnail class
				$thumb = array_search( 'has-post-thumbnail', $classes );

				if ( false !== $thumb )
					unset( $classes[ $thumb ] );

			}

		}

	}

	return $classes;

}

endif;


/**
 *
 *	Modify header widget classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'wpb/classes/get/header-widgets', 'wpb_modify_header_widgets_classes', 5 );

if ( !function_exists( 'wpb_modify_header_widgets_classes' ) ) :

function wpb_modify_header_widgets_classes( $classes = array() )
{

	$classes['layout'] = 'widgets-' . wpb( 'setting/get', 'layout/header_widgets' );

	return $classes;

}

endif;


/**
 *
 *	Modify footer widget classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'wpb/classes/get/footer-widgets', 'wpb_modify_footer_widgets_classes', 5 );

if ( !function_exists( 'wpb_modify_footer_widgets_classes' ) ) :

function wpb_modify_footer_widgets_classes( $classes = array() )
{

	$classes['layout'] = 'widgets-' . wpb( 'setting/get', 'layout/footer_widgets' );

	return $classes;

}

endif;


/**
 *
 *	Modify thumbnail classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'wpb/classes/get/thumbnail', 'wpb_thumbnail_classes', 5 );

if ( !function_exists( 'wpb_thumbnail_classes' ) ) :

function wpb_thumbnail_classes( $classes = array() )
{

	// Style
	if ( !isset( $classes['style'] ) ) {

		$style = wpb( 'settings/get', 'thumbnail/style' );

		if ( $style && 'square' != $style )
			$classes['style'] = 'image-' . $style;

	}

	// Zoom
	if ( !isset( $classes['zoom'] ) ) {

		if ( !wpb( 'settings/choice', 'thumbnail/zoom', 'none' ) )
			$classes['zoom'] = 'image-zoom-' . wpb( 'settings/get', 'thumbnail/zoom' );

	}

	return $classes;

}

endif;


/**
 *
 *	Modify banner classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'wpb/classes/get/banner', 'wpb_banner_classes', 5 );

if ( !function_exists( 'wpb_banner_classes' ) ) :

function wpb_banner_classes( $classes = array() )
{

	// Style
	if ( !isset( $classes['style'] ) ) {

		$style = wpb( 'settings/get', 'banner/style' );

		if ( $style && 'square' != $style )
			$classes['style'] = 'image-' . $style;

	}

	// Zoom
	if ( !isset( $classes['zoom'] ) ) {

		if ( !wpb( 'settings/choice', 'banner/zoom', 'none' ) )
			$classes['zoom'] = 'image-zoom-' . wpb( 'settings/get', 'banner/zoom' );


	}

	return $classes;

}

endif;


/**
 *
 *	Add custom header classes to body element
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'body_class', 'wpb_body_header_classes', 5 );

if ( !function_exists( 'wpb_body_header_classes' ) ) :

function wpb_body_header_classes( $classes = array() )
{

	if ( !current_theme_supports( 'custom-header' ) )
		return $classes;

	if ( !wpb( 'setting/get', 'custom_header/enabled' ) )
		return $classes;

	$image = wpb( 'image/header' );

	if ( $image )
		$classes['custom-header'] = 'custom-header';

	return $classes;

}

endif;


/**
 *
 *	Modify excerpt length
 *
 *	================================================================ 
 *
 *	@param		int			$length			// Default length
 *
 *	@return		int							// Saved length
 *
 *	@since		1.0.0
 *
 */

add_filter( 'excerpt_length', 'wpb_excerpt_length', 5 );

if ( !function_exists( 'wpb_excerpt_length' ) ) :

function wpb_excerpt_length( $length = 30 )
{

	if ( !function_exists( 'wpb' ) )
		return $length;

	$saved = wpb( 'settings/get', 'excerpt/length', $length );

	if ( $saved )
		$length = (int) $saved;

	return $length;

}

endif;


/**
 *
 *	Add "more" text to automatic excerpts
 *
 *	================================================================
 *
 *	@return 	string						// The text
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'excerpt_more', 'wpb_excerpt_more_auto', 5 );

if ( !function_exists( 'wpb_excerpt_more_auto' ) ) :

function wpb_excerpt_more_auto( $more = '' ) 
{

	if ( !function_exists( 'wpb' ) )
		return $more;

	return wpb( 'content/excerpt-more' );

}

endif;


/**
 *
 *	Add "more" text to manual excerpts
 *
 *	================================================================
 *
 *	@return 	string						// The excerpt
 *
 *	@since 		1.0.0
 *
 */

add_filter( 'get_the_excerpt', 'wpb_excerpt_more_manual', 5 );

if ( !function_exists( 'wpb_excerpt_more_manual' ) ) :

function wpb_excerpt_more_manual( $excerpt = '' ) 
{

	if ( !function_exists( 'wpb' ) || !has_excerpt() )
		return $excerpt;

	$excerpt .= wpb( 'content/excerpt-more' );

	return $excerpt;

}

endif;


/**
 *
 *	Output banner in single posts
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'wpb/before/single/content', 'wpb_output_single_banner', 5 );

if ( !function_exists( 'wpb_output_single_banner' ) ) :

function wpb_output_single_banner()
{

	if ( !current_theme_supports( 'wpb-banner' ) )
		return;

	if ( !wpb( 'settings/choice', 'banner/display', 'single' ) )
		return;

	// Output
	echo wpb( 'image/banner', 'class=single-banner&link=' );

}

endif;


/**
 *
 *	Output banner in looped posts
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'wpb/before/loop/content', 'wpb_output_looped_banner', 5 );

if ( !function_exists( 'wpb_output_looped_banner' ) ) :

function wpb_output_looped_banner()
{

	if ( !current_theme_supports( 'wpb-banner' ) )
		return;

	if ( !wpb( 'settings/choice', 'banner/display', 'loop' ) )
		return;

	// Output
	echo wpb( 'image/banner', 'class=loop-banner' );

}

endif;


/**
 *
 *	Output custom header
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'wpb/before/page',         'wpb_output_custom_header', 5 );
add_action( 'wpb/before/page-header',  'wpb_output_custom_header', 5 );
add_action( 'wpb/before/banner',       'wpb_output_custom_header', 5 );
add_action( 'wpb/page-header',         'wpb_output_custom_header', 5 );

if ( !function_exists( 'wpb_output_custom_header' ) ) :

function wpb_output_custom_header()
{

	if ( !current_theme_supports( 'custom-header' ) )
		return;

	if ( !wpb( 'settings/get', 'custom-header/enabled' ) )
		return;

	// Make sure this is the right place
	$action = current_filter();

	if ( 'wpb/before/page' == $action && !wpb( 'settings/choice', 'custom-header/display', 'before' ) )
		return;

	if ( 'wpb/before/page-header' == $action && !wpb(  'settings/choice', 'custom-header/display', 'inside' ) )
		return;

	if ( 'wpb/before/banner' == $action && !wpb( 'settings/choice', 'custom-header/display', 'banner' ) )
		return;

	if ( 'wpb/page-header' == $action && !wpb( 'settings/choice', 'custom-header/display', 'after' ) )
		return;

	// Output
	echo wpb( 'image/header' );

}

endif;


/**
 *
 *	Output author archive avatar
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'wpb/before/archive/header', 'wpb_output_author_archive_avatar', 5 );

if ( !function_exists( 'wpb_output_author_archive_avatar' ) ) :

function wpb_output_author_archive_avatar( $archive = '' )
{

	if ( empty( $archive ) )
		return;

	if ( 'author' != $archive->type )
		return;

	if ( empty( $archive->display['avatar'] ) )
		return; ?>


	<figure<?php wpb( 'classes', 'elem=thumbnail&default=content-thumbnail author-avatar' ); ?>><?php echo $archive->meta['avatar']; ?></figure><?php


}

endif;


/**
 *
 *	Output author archive meta
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */

add_action( 'wpb/after/archive/header', 'wpb_output_author_archive_meta', 5 );

if ( !function_exists( 'wpb_output_author_archive_meta' ) ) :

function wpb_output_author_archive_meta( $archive = '' )
{

	if ( empty( $archive ) )
		return;

	if ( 'author' != $archive->type )
		return;

	// Meta
	if ( !empty( $archive->display['email'] ) || !empty( $archive->display['url'] ) ) { ?>


<div class="content-meta archive-meta"><?php 


		if ( !empty( $archive->display['email'] ) ) { ?>


	<p class="author-email">
		<a href="mailto:<?php esc_attr_e( $archive->meta['email'] ); ?>"<?php if ( !empty( $archive->schema ) ) { ?> itemprop="email"<?php } ?>><?php echo $archive->meta['email']; ?></a>
	</p><?php


		}

		if ( !empty( $archive->display['url'] ) ) { ?>


	<p class="author-url">
		<a href="<?php esc_attr_e( $archive->meta['url'] ); ?>"<?php if ( !empty( $archive->schema ) ) { ?> itemprop="url"<?php } ?>><?php echo $archive->meta['url']; ?></a>
	</p><?php


		} ?>


</div><?php


	}

	// Bio
	if ( !empty( $archive->display['bio'] ) ) { ?>


<div class="archive-description"<?php if ( !empty( $archive->schema ) ) { ?> itemprop="description"<?php } ?>><?php echo wpautop( $archive->meta['bio'] ); ?></div><?php


	}

}

endif;