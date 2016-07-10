<?php


/**
 *
 *	Register requests
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/requests/register', function() {


	/**
	 *
	 *	Get Dashicons
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'dashicons', 'wpb_request__dashicons' );


	/**
	 *
	 *	Get Dashicons headings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'dashicons/headings', 'wpb_request__dashicons_headings' );


	/**
	 *
	 *	Format HTML attributes in an associative array
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$attr			// Original attributes
	 *	@param		array 		$base			// Base attributes
	 *
	 *	@return		array						// Formatted attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'attr/format', 'wpb_request__attr_format', array(
		'attr' => array(),
		'base' => array()
	) );


	/**
	 *
	 *	Get HTML attributes from associative array
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$attr			// Attributes
	 *	@param		array 		$base			// Base attributes
	 *
	 *	@return		string						// HTML string of attributes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'attr', 'wpb_request__attr', array(
		'attr' => array(),
		'base' => array()
	) );



	/**
	 *
	 *	Get component classes
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$component		// Component name, e.g. 'icon'
	 *	@param		string		$class 			// Component class, e.g. 'wordpress'
	 *	@param		string		$prefix			// Component prefix, e.g. 'dashicons'
	 *	@param		array 		$additional		// Additional classes to add
	 *
	 *	@return		array 						// Classes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'classes/component', 'wpb_request__classes_component', array(
		'component'  => '',
		'class'      => '',
		'prefix'     => '',
		'additional' => array()
	) );

	// Wildcard
	wpb()->register( 'classes/component/*', 'classes/component', 'component' );


	/**
	 *
	 *	Get button HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$text			// Button text
	 *	@param		string		$icon			// Icon class
	 *	@param		string		$href			// URL (forces an <a /> element)
	 *	@param		string		$class 			// Button class
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@return		string						// Button HTML
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'button', 'wpb_request__button', array(
		'text'  => '',
		'icon'  => '',
		'href'  => '',
		'class' => '',
		'attr'  => array()
	), true );


	/**
	 *
	 *	Get icon HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$icon			// Icon class
	 *	@param		string		$prefix			// Icon class prefix
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@return		string						// Icon HTML
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'icon', 'wpb_request__icon', array(
		'icon'   => '',
		'prefix' => '',
		'attr'   => array()
	), true );


	/**
	 *
	 *	Get notification HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$text			// Notification text
	 *	@param		string		$class 			// Notification class
 	 *	@param		string		$desc			// Notification description
	 *	@param		string		$icon			// Icon class
	 *	@param		boolean		$dismiss		// Dismissable or not
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@return		string						// Notification HTML
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'notification', 'wpb_request__notification', array(
		'text'    => '',
		'class'   => '',
		'desc'    => '',
		'icon'    => '',
		'dismiss' => false,
		'attr'    => array()
	), true );

	// Wildcard
	wpb()->register( 'notification/*', 'notification', 'class' );


	/**
	 *	
	 *	Get an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		boolean		$object			// Return as an object
	 *
	 *	@return		mixed						// Addon
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'addons/get', 'wpb_request__addons_get', array(
		'slug'   => '',
		'object' => true
	) );

	// Wildcard
	wpb()->register( 'addons/get/*', 'addons/get', 'slug' );


	/**
	 *
	 *	Register an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		string		$path			// Addon path
	 *	@param		array 		$data			// Addon data
	 *
	 *	@return		boolean						// Registered or not
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'addons/register', 'wpb_request__addons_register', array(
		'slug' => '',
		'path' => '',
		'data' => array()
	) );

	// Wildcard
	wpb()->register( 'addons/register/*', 'addons/register', 'slug' );


	/**
	 *	
	 *	Get active addons, or check if an addon is active
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		boolean		$objects		// Return objects
	 *
	 *	@return		mixed						// Array of active addons, or boolean
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'addons/active', 'wpb_request__addons_active', array(
		'slug'    => '',
		'objects' => false
	) );

	// Wildcard
	wpb()->register( 'addons/active/*', 'addons/active', 'slug' );


	/**
	 *	
	 *	Activate an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *
	 *	@return		boolean						// Activated or not
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'addons/activate', 'wpb_request__addons_activate', array(
		'slug' => ''
	) );

	// Wildcard
	wpb()->register( 'addons/activate/*', 'addons/activate', 'slug' );


	/**
	 *	
	 *	Deactivate an addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *
	 *	@return		boolean						// Deactivated or not
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'addons/deactivate', 'wpb_request__addons_deactivate', array(
		'slug' => ''
	) );

	// Wildcard
	wpb()->register( 'addons/deactivate/*', 'addons/deactivate', 'slug' );


	/**
	 *
	 *	Output classes for an element
	 *
	 *	================================================================
	 *
	 *	@param 		string 		$elem			// The element(s)
	 *	@param		mixed		$default		// Default classes
	 *	@param		boolean		$str			// Return as a string
	 *	@param		boolean		$attr			// Return string with class=""
	 *
	 *	@return 	string 						// The classes
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'classes', 'wpb_request__classes_get', array(
		'elem'    => '',
		'default' => '',
		'str'     => true,
		'attr'    => true,
		'echo'    => true,
		'return'  => false
	) );

	// Wildcard
	wpb()->register( 'classes/*', 'classes/output', 'elem' );


	/**
	 *
	 *	Get classes for an element
	 *
	 *	================================================================
	 *
	 *	@param 		string 		$elem			// The element(s)
	 *	@param		mixed		$default		// Default classes
	 *	@param		boolean		$str			// Return as a string
	 *	@param		boolean		$attr			// Return string with class=""
	 *
	 *	@return 	mixed 						// The classes
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'classes/get', 'wpb_request__classes_get', array(
		'elem'    => '',
		'default' => '',
		'str'     => true,
		'attr'    => false,
		'echo'    => false,
		'return'  => true
	) );

	// Wildcard
	wpb()->register( 'classes/get/*', 'classes/get', 'elem' );


	/**
	 *
	 *	Get a formatted date string
	 *
	 *	================================================================ 
	 *
	 *	Available placeholders;
	 *
	 *		$text
	 *			%date%		// Date string
	 *			%author%	// Author string
	 *
	 *		$date_text
	 *			%1$s		// Formatted date, e.g. 31st December 1999
	 *			%2$s		// Timestamp, e.g. 1999-12-31T23:59:59+00:00
	 *
	 *		$date_link_text
	 *			%1$s		// Date string
	 *			%2$s		// Post URL
	 *			%3$s		// Date archive URL
	 *
	 *		$author_text
	 *			%1$s		// Author display name
	 *
	 *		$author_link_text
	 *			%1$s		// Author string
	 *			%2$s		// Author archive URL
	 *
	 *	================================================================ 
	 *
	 *	@param		int			$id					// Post ID
	 *	@param		string		$text				// Text string, with placeholders
	 *	@param		string		$format				// Date format, e.g. 'jS F Y'
	 *	@param		string		$date_link			// Link to the post
	 *	@param		boolean		$author_link		// Link to the author archive page
	 *	@param		string		$date_text			// Date string
	 *	@param		string		$date_link_text		// Linked date string
	 *	@param		string		$author_text		// Author string
	 *	@param		string		$author_link_text	// Linked author string
	 *
	 *	@return		string							// Date string
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'content/date', 'wpb_request__content_date', array(
		'id'               => '',
		'text'             => __( 'Added on %1$s by %2$s', 'wpb' ),
		'format'           => get_option( 'date_format' ),
		'date_link'        => true,
		'author_link'      => true,
		'date_text'        => __( '<time datetime="%2$s">%1$s</time>', 'wpb' ),
		'date_link_text'   => __( '<a class="date-link" href="%2$s">%1$s</a>', 'wpb' ),
		'author_text'      => '%1$s',
		'author_link_text' => __( '<a class="author-link" href="%2$s">%1$s</a>', 'wpb' )
	) );


	/**
	 *
	 *	Get terms list
	 *
	 *	================================================================
	 *
	 *	@param		boolean		$id				// Post ID
	 *	@param		array		$tax			// The taxonomies to use
	 *	@param		string		$both			// Text to display for both category and tag-like terms
	 *	@param		string		$cats			// Text to display for category-like terms
	 *	@param		string		$tags			// Text to display for tag-like terms
	 *
	 *	@return		string						// The terms HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'content/terms', 'wpb_request__content_terms', array(
		'id'   => '',
		'tax'  => array( 'post_tag', 'category' ),
		'both' => __( 'Posted in %1$s and tagged %2$s', 'wpb' ),
		'cats' => __( 'Posted in %s', 'wpb' ),
		'tags' => __( 'Tagged %s', 'wpb' )
	) );


	/**
	 *
	 *	Get comments link
	 *
	 *	================================================================
	 *
	 *	@param		int			$id				// Post ID
	 *	@param		string		$text			// The text to display
	 *	@param		string		$zero			// The text to display for 0 comments
	 *	@param		string		$one			// The text to display for 1 comment
	 *	@param		string		$none			// The text to display if comments are disabled
	 *	@param		boolean		$link			// Link to #comments
	 *
	 *	@return		string						// The link HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'content/comments-link', 'wpb_request__content_comments_link', array(
		'id'   => '',
		'text' => __( '%1$s comments', 'wpb' ),
		'zero' => __( 'No comments', 'wpb' ),
		'one'  => __( '1 comment', 'wpb' ),
		'none' => '',
		'link' => true
	) );


	/**
	 *
	 *	Get edit button HTML
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$id				// Post ID
	 *	@param		string		$text			// Button text. %1$s: edit_item label, %2$s: singular_name label
	 *	@param		string		$icon			// Icon class
	 *	@param		string		$class 			// Button class
	 *
	 *	@return		string						// Button HTML
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'content/edit-button', 'wpb_request__content_edit_button', array(
		'id'    => '',
		'text'  => '%1$s',
		'icon'  => 'edit',
		'class' => 'tertiary'
	) );


	/**
	 *
	 *	Get archive data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$type			// Archive type; post_type, term, author, date
	 *	@param		string		$id				// Object ID or slug
	 *
	 *	@return		object						// Archive data
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'content/archive', 'wpb_request__content_archive', array(
		'type' => '',
		'id'   => ''
	) );


	/**
	 *
	 *	Get excerpt 'more' text
	 *
	 *	================================================================
	 *
	 *	@param		string		$text			// 'Continue reading' text
	 *	@param		string		$dots			// [...] text
	 *	@param		string		$arrow			// Arrow text
	 *
	 *	@return 	string						// The full text
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'content/excerpt-more', 'wpb_request__content_excerpt_more', array(
		'text'  => 'saved',
		'dots'  => 'saved',
		'arrow' => 'saved'
	) );


	/**
	 *
	 *	Output single navigation
	 *
	 *	================================================================
	 *
	 *	@param 		string		$prev_text		// Previous text
	 *	@param		string		$next_text		// Next text
	 *	@param		string		$taxonomy		// Taxonomy
	 *	@param		array		$excluded		// Excluded categories
	 *	@param		boolean		$attr			// HTML attributes
	 *
	 *	@return		string						// Navigation HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/single', 'wpb_request__nav_single', array(
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'taxonomy'  => '',
		'excluded'  => array(),
		'attr'      => array(),
		'echo'      => true
	) );


	/**
	 *
	 *	Output loop navigation
	 *
	 *	================================================================
	 *
	 *	@param 		string		$prev_text		// Previous text
	 *	@param		string		$next_text		// Next text
	 *	@param		boolean		$attr			// HTML attributes
	 *
	 *	@return		string						// Navigation HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/loop', 'wpb_request__nav_loop', array(
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'attr'      => array(),
		'echo'      => true
	) );


	/**
	 *
	 *	Output image navigation
	 *
	 *	================================================================
	 *
	 *	@param		mixed		$size			// Image size
	 *	@param 		mixed		$prev_text		// Previous text
	 *	@param		mixed		$next_text		// Next text
	 *	@param		array		$attr			// HTML attributes
	 *
	 *	@return		string						// Navigation HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/image', 'wpb_request__nav_image', array(
		'size'      => 'medium',
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'attr'      => array(),
		'echo'      => true
	) );


	/**
	 *
	 *	Output paged navigation
	 *
	 *	================================================================
	 *
	 *	@param 		string		$prev_text		// Previous text
	 *	@param		string		$next_text		// Next text
	 *
	 *	@return		string						// Navigation HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/paged', 'wpb_request__nav_paged', array(
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'attr'      => array(),
		'echo'      => true
	) );


	/**
	 *
	 *	Output comments navigation
	 *
	 *	================================================================
	 *
	 *	@param 		string		$prev_text		// Previous text
	 *	@param		string		$next_text		// Next text
	 *	@param		array 		$attr			// HTML attributes
	 *
	 *	@return		string						// Navigation HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/comments', 'wpb_request__nav_comments', array(
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'attr'      => array(),
		'echo'      => true
	) );


	/**
	 *
	 *	Return adjacent post link
	 *
	 *	================================================================
	 *
	 *	@param		string		$format			// Link anchor format
	 *	@param		string		$link			// Link permalink format
	 *	@param		boolean		$same_term		// Whether link should be in a same taxonomy term
	 *	@param		mixed		$excluded		// Array or comma-separated list of excluded term IDs
	 *	@param		boolean		$prev			// Whether to display link to previous or next post
	 *	@param		string		$taxonomy		// Taxonomy, if $same_term is true
	 *
	 *	@return 	string 						// Previous/next post link HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/adjacent', 'wpb_request__get_adjacent_post_link', array(
		'format'    => '%link',
		'link'      => '%title',
		'same_term' => false,
		'excluded'  => array(),
		'prev'      => true,
		'taxonomy'  => ''
	) );


	/**
	 *
	 *	Return adjacent image link
	 *
	 *	================================================================
	 *
	 *	@param		boolean		$prev			// Return previous link
	 *	@param		string		$size			// Image size
	 *	@param		boolean		$text			// Display text
	 *
	 *	@return 	string 						// Previous/next image link HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'nav/image/adjacent', 'wpb_request__get_adjacent_image_link', array(
		'prev' => true,
		'size' => 'medium',
		'text' => false
	) );


	/**
	 *
	 *	Get image HTML
	 *
	 *	================================================================
	 *
	 *	@param 		string 		$size			// Image size
	 *	@param		int			$id				// Image or post ID
	 *	@param		string		$link			// Link to image size, post ID, or none
	 *	@param		string		$target			// Link target attribute
	 *	@param		boolean		$caption		// Display caption, if one exists
	 *	@param		string		$wrapper		// Wrapper element
	 *	@param		mixed		$class 			// Additional classes to add to wrapper
	 *
	 *	@return 	string 						// The image
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'image', 'wpb_request__image', array(
		'size'    => '',
		'id'      => '',
		'link'    => 'full',
		'target'  => '',
		'title'   => false,
		'alt'     => true,
		'caption' => false,
		'wrapper' => 'figure',
		'sizes'   => true,
		'class'   => ''
	) );


	/**
	 *
	 *	Get post thumbnail HTML
	 *
	 *	================================================================
	 *
	 *	@param 		string 		$size			// Image size
	 *	@param		int			$id				// Post ID
	 *	@param		boolean		$link			// Link to post or not
	 *	@param		mixed		$class 			// Additional classes to add to wrapper
	 *
	 *	@return 	string 						// The image HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'image/thumbnail', 'wpb_request__image_thumbnail', array(
		'id'    => '',
		'link'  => true,
		'class' => '',
		'size'  => ''
	) );


	/**
	 *
	 *	Get post banner HTML
	 *
	 *	================================================================
	 *
	 *	@param		int			$id				// Post ID
	 *	@param		boolean		$link			// Link to post or not
	 *	@param		mixed		$class 			// Additional classes to add to wrapper
	 *	@param		string		$size			// Image size
	 *
	 *	@return 	string 						// The image HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'image/banner', 'wpb_request__image_banner', array(
		'id'    => '',
		'link'  => true,
		'class' => '',
		'size'  => 'wpb_banner'
	) );


	/**
	 *
	 *	Get custom header image HTML
	 *
	 *	================================================================
	 *
	 *	@param		mixed		$class 			// Additional classes to add to wrapper
	 *
	 *	@return 	string 						// The image HTML
	 *
	 *	@since 		1.0.0
	 *
	 */

	wpb()->register( 'image/header', 'wpb_request__image_header', array(
		'class' => ''
	) );


	/**
	 *
	 *	Get registered image sizes
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Sizes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'image/sizes', 'wpb_request__image_sizes' );


	/**
	 *
	 *	Get registered image sizes
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Sizes
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'image/sizes', 'wpb_request__image_sizes' );


	/**
	 *
	 *	Check if a post has a banner image
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$post			// Post ID or WP_Post object
	 *
	 *	@return		boolean						// Has banner or not
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'has_post_banner', 'wpb_request__has_post_banner', array(
		'post' => null
	) );


	/**
	 *
	 *	Get post banner ID
	 *
	 *	================================================================ 
	 *
	 *	@param		mixed		$post			// Post ID or WP_Post object
	 *
	 *	@return		int							// Banner ID
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'get_post_banner_id', 'wpb_request__get_post_banner_id', array(
		'post' => null
	) );


	/**
	 *
	 *	Get theme stylesheet URL
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// Stylesheet URL
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'stylesheet/theme', 'wpb_request__stylesheet_theme' );


	/**
	 *
	 *	Get plugin stylesheet URL
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// Stylesheet URL
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'stylesheet/plugin', 'wpb_request__stylesheet_plugin' );


	/**
	 *
	 *	Load template part
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$path			// File path/name, relative to templates folder
	 *	@param		string		$name			// Additional file name
	 *	@param		string		$fallback		// Fallback file path
	 *
	 *	@return		string						// Loaded template file path
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'template', 'wpb_request__template', array(
		'path'     => '',
		'name'     => '',
		'fallback' => ''
	) );


	/**
	 *
	 *	Get template directories
	 *
	 *	================================================================ 
	 *
	 *	@return		array						// Directories
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'template/dirs', 'wpb_request__template_dirs' );


	/**
	 *
	 *	Get setting(s)
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key(s)
	 *	@param		mixed		$default		// Default value
	 *	@param		boolean		$objects		// Return objects
	 *	@param		boolean		$format			// Format values
	 *	@param		string		$group			// Group
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/get', 'wpb_request__settings_get', array(
		'key'     => '',
		'default' => NULL,
		'objects' => false,
		'format'  => true,
		'group'   => 'plugin'
	) );

	// Wildcard
	wpb()->register( 'settings/get/*', 'settings/get', 'key' );


	/**
	 *
	 *	Check if a setting value has been chosen
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$key			// Setting key
	 *	@param		mixed		$value			// Value to check
	 *	@param		boolean		$default		// Default return value if setting doesn't exist
	 *	@param		string		$group			// Group
	 *
	 *	@return		boolean						// Chosen or not
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/choice', 'wpb_request__settings_choice', array(
		'key'     => '',
		'value'   => true,
		'default' => false,
		'group'   => ''
	) );

	// Wildcard
	wpb()->register( 'settings/choice/*', 'settings/choice', 'key' );


	/**
	 *
	 *	Register setting
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key(s)
	 *	@param		array		$data			// Settings data
	 *	@param		mixed		$default		// Default value
	 *	@param		string		$group			// Group
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/register', 'wpb_request__settings_register', array(
		'key'     => '',
		'data'    => array(),
		'default' => NULL,
		'group'   => ''
	) );

	// Wildcard
	wpb()->register( 'settings/register/*', 'settings/register', 'key' );


	/**
	 *
	 *	Save settings
	 *
	 *	================================================================ 
	 *
	 *	@param		array		$values			// Setting values
	 *	@param		boolean		$validate		// Validate or not
	 *	@param		boolean		$sanitize		// Sanitise or not
	 *	@param		string		$group			// Group
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/save', 'wpb_request__settings_save', array(
		'values'   => array(),
		'validate' => true,
		'sanitize' => true,
		'group'    => ''
	) );


	/**
	 *
	 *	Add setting validation error
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Setting key
	 *	@param		string		$error			// Error message
	 *	@param		string		$group			// Group
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/error', 'wpb_request__settings_error', array(
		'key'   => '',
		'error' => '',
		'group' => ''
	) );


	/**
	 *
	 *	Get addon setting(s)
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$addon			// Addon slug
	 *	@param		string		$key			// Setting key(s)
	 *	@param		mixed		$default		// Default value
	 *	@param		boolean		$objects		// Return objects
	 *	@param		boolean		$format			// Format values
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/addon/get', 'wpb_request__settings_addon_get', array(
		'addon'   => '',
		'key'     => '',
		'default' => NULL,
		'objects' => false,
		'format'  => true
	) );

	// Wildcard
	wpb()->register( 'settings/addon/get/*', 'settings/addon/get', 'addon' );


	/**
	 *
	 *	Register an addon setting
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$addon			// Addon slug
	 *	@param		string		$key			// Setting key(s)
	 *	@param		boolean		$data			// Settings data
	 *	@param		mixed		$default		// Default value
	 *
	 *	@return 	mixed 						// Setting(s)
	 *
	 *	@since		1.0.0
	 *
	 */

	wpb()->register( 'settings/addon/register', 'wpb_request__settings_addon_register', array(
		'addon'   => '',
		'key'     => '',
		'data'    => array(),
		'default' => NULL
	) );

	// Wildcard
	wpb()->register( 'settings/addon/register/*', 'settings/addon/register', 'addon' );


}, 5 );