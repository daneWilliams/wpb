<?php


/**
 *
 *	Image settings: Thumbnails
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_thumbnail_settings', 5 );

if ( !function_exists( 'wpb_register_thumbnail_settings' ) ) :

function wpb_register_thumbnail_settings()
{

	// Thumbnail style
	wpb( 'settings/register', 'thumbnail/style', array(
		'type'    => 'radio',
		'label'   => __( 'Thumbnail style', 'wpb' ),
		'choices' => array(
			'square'  => array(
				'label' => __( 'Square', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-square"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			),
			'circle'  => array(
				'label' => __( 'Circular', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-circle"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			),
			'rounded' => array(
				'label' => __( 'Rounded', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-rounded"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			)
		),
		'inline' => true,
		'location' => 'images/thumbnails'
	), 'square' );

	// Thumbnail zoom
	wpb( 'settings/register', 'thumbnail/zoom', array(
		'type'    => 'radio',
		'label'   => __( 'Thumbnail zoom', 'wpb' ),
		'choices' => array(
			'none' => array(
				'label' => __( 'No zoom', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-zoom-none"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			),
			'in' => array(
				'label' => __( 'Zoom in', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-zoom-in"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			),
			'out' => array(
				'label' => __( 'Zoom out', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-thumbnail-preview wpb-image-zoom-out"><img src="' . wpb()->url( 'admin/assets/img/thumbnail-preview.png' ) . '" /></span>'
			)
		),
		'inline' => true,
		'location' => 'images/thumbnails'
	), 'none' );

}

endif;


/**
 *
 *	Image settings: Banners
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_banner_settings', 5 );

if ( !function_exists( 'wpb_register_banner_settings' ) ) :

function wpb_register_banner_settings()
{

	// Banners
	wpb( 'settings/register', 'banner/enabled', array(
		'type'  => 'boolean',
		'label' => __( 'Banner images', 'wpb' ),
		'text'  => __( 'Enable banner images', 'wpb' ),
		'desc'  => __( 'Adds theme support for banner images', 'wpb' ),
		'location' => 'images/banners'
	), false );

	// Banner meta box
	wpb( 'settings/register', 'banner/metabox', array(
		'type'  => 'boolean',
		'label' => __( 'Banner meta box', 'wpb' ),
		'text'  => __( 'Enable banner meta box', 'wpb' ),
		'desc'  => __( 'Adds a meta box below the featured image on post edit screens', 'wpb' ),
		'location' => 'images/banners'
	), true );

	// Banner post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	if ( !empty( $post_types ) ) {

		$post_type_choices  = array();
		$post_type_defaults = array();

		foreach ( $post_types as $post_type_slug => $post_type ) {

			if ( 'attachment' == $post_type_slug )
				continue;

			$post_type_defaults[] = $post_type_slug;

			$post_type_choices[ $post_type_slug ] = ( !empty( $post_type->labels->singular_name ) ? $post_type->labels->singular_name : $post_type->label );

		}

		wpb( 'settings/register', 'banner/post_types', array(
			'type'    => 'checkbox',
			'label'   => __( 'Banner post types', 'wpb' ),
			'choices' => $post_type_choices,
			'location' => 'images/banners'
		), $post_type_defaults );

	}

	// Display
	wpb( 'settings/register', 'banner/display', array(
		'type'    => 'checkbox',
		'label'   => __( 'Banner display', 'wpb' ),
		'choices' => array(
			'single' => array(
				'label' => __( 'Display in single posts', 'wpb' ),
				'desc'  => __( 'Inserts the banner before the page title on single posts', 'wpb' )
			),
			'loop' => array(
				'label' => __( 'Display in looped posts', 'wpb' ),
				'desc'  => __( 'Inserts the banner before the post titles in a loop, e.g. on an archive page', 'wpb' )
			),
			'thumbnails' => array(
				'label' => __( 'Display instead of thumbnails', 'wpb' ),
				'desc'  => sprintf(
					__( 'If a banner image has been set, it will be displayed instead of <code>%s</code>', 'wpb' ),
					'wpb( \'image/thumbnail\' )'
				)
			)
		),
		'location' => 'images/banners'
	), array( 'single', 'thumbnails' ) );

	// Banner style
	wpb( 'settings/register', 'banner/style', array(
		'type'    => 'radio',
		'label'   => __( 'Banner style', 'wpb' ),
		'choices' => array(
			'square'  => array(
				'label' => __( 'Square', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-square"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			),
			'circle'  => array(
				'label' => __( 'Circular', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-circle"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			),
			'rounded' => array(
				'label' => __( 'Rounded', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-rounded"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			)
		),
		'inline' => true,
		'location' => 'images/banners'
	), 'square' );

	// Banner zoom
	wpb( 'settings/register', 'banner/zoom', array(
		'type'    => 'radio',
		'label'   => __( 'Banner zoom', 'wpb' ),
		'choices' => array(
			'none' => array(
				'label' => __( 'No zoom', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-zoom-none"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			),
			'in' => array(
				'label' => __( 'Zoom in', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-zoom-in"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			),
			'out' => array(
				'label' => __( 'Zoom out', 'wpb' ),
				'desc'  => '<span class="wpb-image-preview wpb-banner-preview wpb-image-zoom-out"><img src="' . wpb()->url( 'admin/assets/img/banner-preview.png' ) . '" /></span>'
			)
		),
		'inline' => true,
		'location' => 'images/banners'
	), 'none' );

	// Banner fallback
	wpb( 'settings/register', 'banner/fallback', array(
		'type'  => 'boolean',
		'label' => __( 'Banner fallback', 'wpb' ),
		'text'  => __( 'Use the featured image if a banner has not been set', 'wpb' ),
		'location' => 'images/banners'
	), true );

}

endif;


/**
 *
 *	Image settings: Custom Header
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_header_image_settings', 5 );

if ( !function_exists( 'wpb_register_header_image_settings' ) ) :

function wpb_register_header_image_settings()
{

	// Custom header
	wpb( 'settings/register', 'custom-header/enabled', array(
		'type'  => 'boolean',
		'label' => __( 'Header images', 'wpb' ),
		'text'  => __( 'Enable header images', 'wpb' ),
		'desc'  => __( 'Adds theme support for custom header images', 'wpb' ),
		'location' => 'images/custom-header'
	), false );

	// Header display
	wpb( 'settings/register', 'custom-header/display', array(
		'type'    => 'radio',
		'label'   => __( 'Header display', 'wpb' ),
		'choices' => array(
			'before' => array(
				'label' => __( 'Display before the page header', 'wpb' ),
				'desc'  => sprintf(
					__( 'Hooks into <code>%s</code>', 'wpb' ),
					'wpb/before/page'
				)
			),
			'after'  => array(
				'label' => __( 'Display before the page content', 'wpb' ),
				'desc'  => sprintf(
					__( 'Hooks into <code>%s</code>', 'wpb' ),
					'wpb/page-header'
				)
			),
			'inside' => array(
				'label' => __( 'Display inside the page header', 'wpb' ),
				'desc'  => sprintf(
					__( 'Hooks into <code>%s</code>', 'wpb' ),
					'wpb/before/page-header'
				)
			),
			'banner' => array(
				'label' => __( 'Display inside the banner', 'wpb' ),
				'desc'  => sprintf(
					__( 'Hooks into <code>%s</code>', 'wpb' ),
					'wpb/before/banner'
				)
			)
		),
		'location' => 'images/custom-header'
	), 'before' );

	// Header override
	wpb( 'settings/register', 'custom-header/override', array(
		'type'  => 'checkbox',
		'label' => __( 'Header override', 'wpb' ),
		'choices' => array(
			'featured' => array(
				'label' => __( 'Use the featured image', 'wpb' ),
				'desc'  => __( 'If a featured image has been set, it will be displayed instead of the header image', 'wpb' )
			),
			'banner' => array(
				'label' => __( 'Use the banner image', 'wpb' ),
				'desc'  => __( 'If banner images are enabled and one has been set, it will be displayed instead of the header image', 'wpb' )
			)
		),
		'location' => 'images/custom-header'
	), array() );

}

endif;


/**
 *
 *	Register excerpt settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_excerpt_settings', 5 );

if ( !function_exists( 'wpb_register_excerpt_settings' ) ) :

function wpb_register_excerpt_settings()
{

	// Length
	wpb( 'settings/register', 'excerpt/length', array(
		'type'  => 'number',
		'label' => __( 'Excerpt length', 'wpb' ),
		'desc'  => __( 'The maximum number of words in automatic excerpts. Leave blank to use WordPress default.', 'wpb' ),
		'attr'  => array(
			'size' => 4
		),
		'location' => 'content/excerpts'
	), 40 );

	// More link
	wpb( 'settings/register', 'excerpt/more_link', array(
		'type'    => 'checkbox',
		'label'   => __( 'Excerpt &quot;more&quot; link', 'wpb' ),
		'choices' => array(
			'auto'   => __( 'Display link after automatic excerpts', 'wpb' ),
			'manual' => __( 'Display link after manual excerpts', 'wpb' ),
			'custom' => array(
				'label' => __( 'Enable custom link text', 'wpb' ),
				'desc'  => __( 'Adds an extra field to the excerpt box on the post edit screen', 'wpb' )
			)
		),
		'location' => 'content/excerpts'
	), array( 'auto' ) );

	// More text
	wpb( 'settings/register', 'excerpt/more_text', array(
		'label' => __( 'Excerpt link text', 'wpb' ),
		'desc'  => __( 'Leave blank to not display a link by default. This will be overridden by custom link text.', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'Continue reading', 'wpb' ),
		),
		'location' => 'content/excerpts'
	), __( 'Continue reading', 'wpb' ) );

	// More meta
	wpb( 'settings/register', 'excerpt/more_meta', array(
		'type'    => 'checkbox',
		'label'   => __( 'Excerpt &quot;more&quot; meta', 'wpb' ),
		'choices' => array(
			'dots_auto' => sprintf( 
				__( 'Display <code>%1$s</code> after automatic excerpts', 'wpb' ), 
				apply_filters( 'wpb/excerpt/more/dots', '&hellip;' )
			),
			'dots_manual' => sprintf( 
				__( 'Display <code>%1$s</code> after manual excerpts', 'wpb' ), 
				apply_filters( 'wpb/excerpt/more/dots', '&hellip;' )
			),
			'arrow_auto' => sprintf( 
				__( 'Display <code>%1$s</code> after automatic excerpt links', 'wpb' ),
				apply_filters( 'wpb/excerpt/more/arrow', '&rarr;' )
			),
			'arrow_custom' => sprintf( 
				__( 'Display <code>%1$s</code> after custom excerpt links', 'wpb' ),
				apply_filters( 'wpb/excerpt/more/arrow', '&rarr;' )
			)
		),
		'location' => 'content/excerpts'
	), array( 'dots_auto', 'arrow_auto', 'arrow_custom' ) );

	// Manual excerpts
	wpb( 'settings/register', 'excerpt/manual', array(
		'type'    => 'checkbox',
		'label'   => __( 'Manual excerpts', 'wpb' ),
		'choices' => array(
			'wysiwyg' => __( 'Enable rich text editor for manual excerpts', 'wpb' ),
			'pages'   => __( 'Enable manual page excerpts', 'wpb' )
		),
		'location' => 'content/excerpts'
	), array( 'pages' ) );

}

endif;


/**
 *
 *	Register 404 settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_404_settings', 5 );

if ( !function_exists( 'wpb_register_404_settings' ) ) :

function wpb_register_404_settings()
{

	// Title
	wpb( 'settings/register', '404/title', array(
		'label' => __( '404 title', 'wpb' ),
		'location' => 'content/404'
	), __( 'Page not found', 'wpb' ) );

	// Text
	wpb( 'settings/register', '404/desc', array(
		'type'  => 'textarea',
		'label' => __( '404 description', 'wpb' ),
		'location' => 'content/404'
	), __( 'Sorry, the page you are looking for could not be found. Perhaps searching will help.', 'wpb' ) );

	// Search
	wpb( 'settings/register', '404/search', array(
		'type'  => 'boolean',
		'label' => __( '404 search', 'wpb' ),
		'text'  => __( 'Display search form on 404 page', 'wpb' ),
		'location' => 'content/404'
	), true );

	// Sidebar
	wpb( 'settings/register', '404/sidebar', array(
		'type'    => 'checkbox',
		'label'   => __( '404 sidebar', 'wpb' ),
		'choices' => array(
			'enabled' => __( 'Enable sidebar on 404 page', 'wpb' ),
			'stacked' => __( 'Display sidebar below content', 'wpb' )
		),
		'location' => 'content/404'
	), array( 'stacked' ) );

}

endif;


/**
 *
 *	Register search settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_search_settings', 5 );

if ( !function_exists( 'wpb_register_search_settings' ) ) :

function wpb_register_search_settings()
{

	// Title
	wpb( 'settings/register', 'search/title', array(
		'label' => __( 'Search title', 'wpb' ),
		'location' => 'content/search'
	), __( 'Search', 'wpb' ) );

	// Results title
	wpb( 'settings/register', 'search/results_title', array(
		'label' => __( 'Search results title', 'wpb' ),
		'desc'  => __( 'Use <code><strong>%search_terms%</strong></code> to display the search query and <code><strong>%search_terms_q%</strong></code> to display the search query wrapped in quotes', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'e.g. Search results for %search_terms_q%', 'wpb' )
		),
		'location' => 'content/search'
	), __( 'Search results for %search_terms_q%', 'wpb' ) );

	// Placeholder
	wpb( 'settings/register', 'search/placeholder', array(
		'label' => __( 'Search placeholder', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'e.g. Search...', 'wpb' )
		),
		'location' => 'content/search'
	), __( 'Search...', 'wpb' ) );

}

endif;


/**
 *
 *	Register term archive settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_term_archive_settings', 5 );

if ( !function_exists( 'wpb_register_term_archive_settings' ) ) :

function wpb_register_term_archive_settings()
{

	// Title
	wpb( 'settings/register', 'archive/term/title', array(
		'label' => __( 'Term title', 'wpb' ),
		'desc'  => __( 'Use <code><strong>%term%</strong></code> to display the term name and <code><strong>%taxonomy%</strong></code> to display the taxonomy name', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'e.g. %taxonomy%: %term%', 'wpb' )
		),
		'location' => 'content/term'
	), '%term%' );

	// Description
	wpb( 'settings/register', 'archive/term/desc', array(
		'type'  => 'boolean',
		'label' => __( 'Term description', 'wpb' ),
		'text'  => __( 'Display term description', 'wpb' ),
		'location' => 'content/term'
	), true );

}

endif;


/**
 *
 *	Register post type archive settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_post_type_archive_settings', 5 );

if ( !function_exists( 'wpb_register_post_type_archive_settings' ) ) :

function wpb_register_post_type_archive_settings()
{

	// Title
	wpb( 'settings/register', 'archive/post_type/title', array(
		'label' => __( 'Post type title', 'wpb' ),
		'desc'  => __( 'Use <code><strong>%post_type%</strong></code> to display the post type label', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'e.g. Archive: %post_type%', 'wpb' )
		),
		'location' => 'content/post-type'
	), '%post_type%' );

	// Description
	wpb( 'settings/register', 'archive/post_type/desc', array(
		'type'  => 'boolean',
		'label' => __( 'Post type description', 'wpb' ),
		'text'  => __( 'Display post type description', 'wpb' ),
		'location' => 'content/post-type'
	), true );

}

endif;


/**
 *
 *	Register author archive settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_author_archive_settings', 5 );

if ( !function_exists( 'wpb_register_author_archive_settings' ) ) :

function wpb_register_author_archive_settings()
{

	// Title
	wpb( 'settings/register', 'archive/author/title', array(
		'label' => __( 'Author title', 'wpb' ),
		'desc'  => __( 'Use <code><strong>%author%</strong></code> to display the author name', 'wpb' ),
		'attr'  => array(
			'placeholder' => __( 'e.g. Author: %author%', 'wpb' )
		),
		'location' => 'content/author'
	), '%author%' );

	// Meta
	wpb( 'settings/register', 'archive/author/meta', array(
		'type'    => 'checkbox',
		'label'   => __( 'Author information', 'wpb' ),
		'choices' => array(
			'avatar' => __( 'Display avatar', 'wpb' ),
			'bio'    => __( 'Display biographical information', 'wpb' ),
			'email'  => __( 'Display email address', 'wpb' ),
			'url'    => __( 'Display website', 'wpb' )
		),
		'location' => 'content/author'
	), array( 'bio' ) );

	// Schema
	wpb( 'settings/register', 'archive/author/schema', array(
		'label' => __( 'Author schema', 'wpb' ),
		'type'  => 'boolean',
		'text'  => __( 'Use schema markup', 'wpb' ),
		'location' => 'content/author'
	), true );

}

endif;



/**
 *
 *	Register sidebar settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_sidebar_settings', 5 );

if ( !function_exists( 'wpb_register_sidebar_settings' ) ) :

function wpb_register_sidebar_settings()
{

	// Sidebar position
	$choices = array(
		'left'  => __( 'Left', 'wpb' ),
		'right' => __( 'Right', 'wpb' ),
	);

	foreach ( $choices as $slug => $label ) {

		$choices[ $slug ] = array(
			'label' => $label,
			'desc'  =>'<img class="wpb-sidebar-position-' . $slug . '" src="' . wpb()->url( 'admin/assets/img/sidebar-position-' . $slug . '.png' ) . '" />'
		);

	}

	wpb( 'settings/register', 'layout/sidebar_position', array(
		'type'     => 'radio',
		'label'    => __( 'Sidebar Position', 'wpb' ),
		'desc'     => __( 'Note: This can be overridden with page templates', 'wpb' ),
		'inline'   => true,
		'choices'  => $choices,
		'location' => 'layout/sidebar',
	), 'right' );

}

endif;


/**
 *
 *	Register widget layout settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_widget_layout_settings', 5 );

if ( !function_exists( 'wpb_register_widget_layout_settings' ) ) :

function wpb_register_widget_layout_settings()
{

	// Layout
	$choices = array(
		'equal'   => __( 'Equal width', 'wpb' ),
		'stacked' => __( 'Stacked', 'wpb' ),
		'grid'    => __( 'Grid', 'wpb' ),
		'mixed'   => __( 'Mixed', 'wpb' )
	);

	foreach ( $choices as $slug => $label ) {

		$choices[ $slug ] = array(
			'label' => $label,
			'desc'  => ''
		);

		for ( $i = 4; $i > 1; $i-- ) {

			$choices[ $slug ]['desc'] .= '<img class="wpb-widgets-layout-img wpb-widgets-layout-' . $i . '" src="' . wpb()->url( 'admin/assets/img/widgets-layout-' . $slug . '-' . $i . '.png' ) . '" />';

		}

	}

	// Header widgets
	wpb( 'settings/register', 'header/widgets_layout', array(
		'type'     => 'radio',
		'label'    => __( 'Header Layout', 'wpb' ),
		'inline'   => true,
		'choices'  => $choices,
		'location' => 'layout/widgets',
	), 'stacked' );

	// Footer widgets
	wpb( 'settings/register', 'footer/widgets_layout', array(
		'type'     => 'radio',
		'label'    => __( 'Footer Layout', 'wpb' ),
		'inline'   => true,
		'choices'  => $choices,
		'location' => 'layout/widgets',
	), 'equal' );

}

endif;


/**
 *
 *	Register CSS settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_css_settings', 5 );

if ( !function_exists( 'wpb_register_css_settings' ) ) :

function wpb_register_css_settings()
{

	// Stylesheets
	$choices = array(
		'plugin' => apply_filters( 'wpb/theme/plugin-stylesheet', wpb()->url( 'public/assets/css/wpb-theme.min.css' ) ),
		'main'   => apply_filters( 'wpb/theme/stylesheet', get_stylesheet_directory_uri() . '/style.css' ),
		'theme'  => get_stylesheet_directory_uri() . '/style.css'
	);

	if ( $choices['theme'] == $choices['main'] )
		unset( $choices['theme'] );

	foreach ( $choices as $choice => $url ) {

		$choices[ $choice ] = array(
			'label' => sprintf( __( 'Load %s stylesheet', 'wpb' ), $choice ),
			'desc'  => '<code>' . str_replace( content_url( '/' ), '', $url ) . '</code>'
		);

	}

	wpb( 'settings/register', 'css/stylesheets', array(
		'type'     => 'checkbox',
		'label'    => __( 'Stylesheets', 'wpb' ),
		'choices'  => $choices,
		'location' => 'assets/css'
	), array( 'plugin', 'main', 'theme' ) );

	// Input
	wpb( 'settings/register', 'css/input', array(
		'type'     => 'code',
		'label'    => __( 'Custom CSS', 'wpb' ),
		'lang'     => 'css',
		'location' => 'assets/css'
	) );

}

endif;


/**
 *
 *	Register JavaScript settings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/settings/register', 'wpb_register_js_settings', 5 );

if ( !function_exists( 'wpb_register_js_settings' ) ) :

function wpb_register_js_settings()
{

	// Scripts
	$choices = array(
		'plugin' => apply_filters( 'wpb/theme/plugin-script', wpb()->url( 'public/assets/js/wpb-theme.js' ) ),
		'theme'  => apply_filters( 'wpb/theme/script', get_stylesheet_directory_uri() . '/assets/js/theme.js' )
	);

	foreach ( $choices as $choice => $url ) {

		$choices[ $choice ] = array(
			'label' => sprintf( __( 'Load %s JavaScript', 'wpb' ), $choice ),
			'desc'  => '<code>' . str_replace( content_url( '/' ), '', $url ) . '</code>'
		);

	}

	wpb( 'settings/register', 'js/scripts', array(
		'type'     => 'checkbox',
		'label'    => __( 'Scripts', 'wpb' ),
		'choices'  => $choices,
		'location' => 'assets/js'
	), array( 'plugin', 'theme' ) );

	// Input
	wpb( 'settings/register', 'js/input', array(
		'type'     => 'code',
		'label'    => __( 'Custom JavaScript', 'wpb' ),
		'lang'     => 'javascript',
		'location' => 'assets/js'
	) );

	// Input position
	wpb( 'settings/register', 'js/input_pos', array(
		'type'    => 'radio',
		'choices' => array(
			'head'   => __( 'Insert custom JavaScript before closing <code>&lt;/head&gt;</code>', 'wpb' ),
			'body'   => __( 'Insert custom JavaScript after opening <code>&lt;body&gt;</code>', 'wpb' ),
			'footer' => __( 'Insert custom JavaScript before closing <code>&lt;/body&gt;</code>', 'wpb' )
		),
		'location' => 'assets/js'
	), 'footer' );

}

endif;