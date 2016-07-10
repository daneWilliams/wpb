<?php


/**
 *
 *	Get dashicons
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_request__dashicons' ) ) :

function wpb_request__dashicons()
{

	// Cached
	$dashicons = wpb()->data( 'dashicons' );

	if ( $dashicons )
		return $dashicons;

	// Get Dashicons
	$dashicons = apply_filters( 'wpb/dashicons', array() );

	if ( !is_array( $dashicons ) )
		$dashicons = array();

	$dashicons = array_unique( $dashicons );

	// Cache
	wpb()->data( 'dashicons', $dashicons );

	return $dashicons;

}

endif;


/**
 *
 *	Get dashicons headings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_request__dashicons_headings' ) ) :

function wpb_request__dashicons_headings()
{

	// Cached
	$headings = wpb()->data( 'dashicons-headings' );

	if ( $headings )
		return $headings;

	// Get headings
	$headings = apply_filters( 'wpb/dashicons/headings', array() );

	if ( !is_array( $headings ) )
		$headings = array();

	// Cache
	wpb()->data( 'dashicons-headings', $headings );

	return $headings;

}

endif;



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

if ( !function_exists( 'wpb_request__attr_format' ) ) :

function wpb_request__attr_format( $args )
{

	$attr      = wp_parse_args( $args['attr'], array() );
	$formatted = $args['base'];

	if ( empty( $attr ) )
		return $formatted;

	foreach ( $attr as $key => $value ) {

		$key = esc_attr( trim( $key ) );

		// Deal with data
		if ( 'data-' == substr( $key, 0, 5 ) ) {

			$formatted['data'][ substr( $key, 5 ) ] = esc_attr( trim( $value ) );
			continue;

		}

		if ( 'data' == $key ) {

			if ( empty( $value ) )
				continue;

			$data = wp_parse_args( $value, array() );

			foreach ( $data as $data_key => $data_value ) {

				$formatted['data'][ trim( $data_key ) ] = esc_attr( trim( $data_value ) );

			}

			continue;

		}

		// Deal with classes
		if ( 'class' == $key ) {

			if ( empty( $value ) )
				continue;

			if ( !is_array( $value ) )
				$value = explode( ' ', $value );

			$value = array_map( 'trim', $value );
			$value = array_map( 'esc_attr', $value );

			if ( isset( $formatted['class'] ) )
				$value = array_merge( $formatted['class'], $value );

			$value = array_unique( $value );

			$formatted['class'] = $value;
			continue;

		}

		// Deal with booleans
		if ( is_bool( $value ) ) {

			$formatted[ $key ] = $value;
			continue;

		}

		// Escape value
		$formatted[ $key ] = esc_attr( $value );

	}

	return $formatted;

}

endif;


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

if ( !function_exists( 'wpb_request__attr' ) ) :

function wpb_request__attr( $args )
{

	// Format
	$formatted = wpb( 'attr/format', $args['attr'], $args['base'] );

	if ( empty( $formatted ) )
		return;

	$str = '';

	foreach ( $formatted as $key => $value ) {

		if ( empty( $value ) )
			continue;

		switch ( $key ) {

			// Data attributes
			case 'data' :

				if ( is_array( $value ) ) {

					foreach ( $value as $data_key => $data_value ) {

						$str .= ' data-' . $data_key . '="' . $data_value . '"';

					}

				} else {

					$str .= ' ' . $value;

				}

			break;

			// Default
			default :

				if ( is_array( $value ) )
					$value = implode( ' ', $value );

				if ( is_int( $key ) ) {

					$str .= ' ' . $value;

				} elseif ( is_bool( $value ) && $value ) {

					$str .= ' ' . $key;

				} else {

					$str .= ' ' . $key . '="' . $value . '"';

				}

			break;

		}

	}

	return $str;

}

endif;


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
 *	@return 	string 						// The classes
 *
 *	@since 		1.0.0
 *
 */

if ( !function_exists( 'wpb_request__classes_get' ) ) :

function wpb_request__classes_get( $args ) 
{

	$class_str = '';
	$classes   = array();

	// Get the default classes
	if ( !empty( $args['default'] ) ) {

		if ( !is_array( $args['default'] ) )
			$args['default'] = explode( ' ', $args['default'] );

		$classes = $args['default'];

	}

	// Get the element(s) classes
	if ( !empty( $args['elem'] ) ) {

		if ( !is_array( $args['elem'] ) )
			$args['elem'] = array_map( 'trim', explode( ',', $args['elem'] ) );

		foreach ( $args['elem'] as $element ) {

			$classes = apply_filters( 'wpb/classes/get/' . $element, $classes );

		}

	}

	$classes = array_map( 'trim', $classes );
	$classes = array_unique( $classes );

	// Return as array
	if ( !$args['str'] )
		return $classes;

	// Add the classes to the string
	if ( !empty( $classes ) ) {

		if ( $args['attr'] )
			$class_str .= ' class="';

		$class_str .= implode( ' ', $classes );

		if ( $args['attr'] )
			$class_str .= '"';

	}

	// Filter
	if ( !empty( $args['elem'] ) ) {

		foreach ( $args['elem'] as $element ) {

			$class_str = apply_filters( 'wpb/classes/str/' . $element, $class_str );

		}

	}

	return $class_str;

}

endif;


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

if ( !function_exists( 'wpb_request__classes_component' ) ) :

function wpb_request__classes_component( $args )
{

	extract( $args );

	$classes = array();

	// Add component class
	$classes['component'] = $component;

	// Get prefix
	$prefix = apply_filters( 'wpb/classes/prefix/' . $component, $prefix );

	// Add prefix
	if ( !$prefix )
		$prefix = $component;

	$classes['prefix'] = $prefix;

	// Add single class(es)
	if ( !empty( $class ) ) {

		if ( !is_array( $class ) )
			$class = explode( ' ', $class );

	}

	if ( !empty( $class ) ) {

		foreach ( $class as $classname ) {

			$classname = apply_filters( 'wpb/classes/single/' . $component, $classname, $prefix );

			if ( $classname != $prefix )
				$classname = $prefix . '-' . $classname;

			$classes[] = $classname;

		}

	}

	// Filter
	$classes = apply_filters( 'wpb/classes/component/' . $component, $classes );

	// Add additional classes
	if ( !empty( $additional ) ) {

		if ( !is_array( $additional ) )
			$additional = explode( ' ', $additional );

		$classes = array_merge( $classes, $additional );

	}

	// Clean up
	$classes = array_map( 'trim', $classes );
	$classes = array_unique( $classes );

	return $classes;

}

endif;


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

if ( !function_exists( 'wpb_request__button' ) ) :

function wpb_request__button( $args )
{

	extract( $args );

	if ( empty( $attr ) )
		$attr = array();

	// Get classes
	$classes = wpb( 'classes/component/button', $class, '', ( !empty( $attr['class'] ) ? $attr['class'] : '' ) );

	$attr['class'] = $classes;

	// Get the element
	$elem = 'button';

	if ( !empty( $href ) || !empty( $attr['href'] ) ) {

		$elem = 'a';

		if ( empty( $attr['href'] ) )
			$attr['href'] = $href;

		if ( !empty( $attr['type'] ) )
			unset( $attr['type'] );

	} else {

		if ( empty( $attr['type'] ) )
			$attr['type'] = 'button';

	}

	// Get attributes
	$attr = wpb( 'attr', $attr );

	// Build the HTML
	$html = '<' . $elem . $attr . '>';

	if ( $icon )
		$html .= wpb( 'icon', $icon );

	if ( $text ) {

		$html .= '<span class="button-text">';
		$html .= trim( $text );
		$html .= '</span>';

	}

	$html .= '</' . $elem . '>';

	$html = apply_filters( 'wpb/component/button', $html, $args );

	return $html;

}

endif;


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

if ( !function_exists( 'wpb_request__icon' ) ) :

function wpb_request__icon( $args )
{

	extract( $args );

	if ( empty( $attr ) )
		$attr = array();

	// Get classes
	$classes = wpb( 'classes/component/icon', $icon, $prefix, ( !empty( $attr['class'] ) ? $attr['class'] : '' ) );

	$attr['class'] = $classes;

	// Get attributes
	$attr = wpb( 'attr', $attr );

	// Build the HTML
	$html = '<i' . $attr . '></i>';
	$html = apply_filters( 'wpb/component/icon', $html, $args );

	return $html;

}

endif;


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
 *	@param		boolean		$dismiss		// Dismissible or not
 *	@param		array 		$attr			// HTML attributes
 *
 *	@return		string						// Notification HTML
 *
 *	@since		1.0.0
 *
 */

if ( !function_exists( 'wpb_request__notification' ) ) :

function wpb_request__notification( $args )
{

	extract( $args );

	if ( empty( $attr ) )
		$attr = array();

	// Get classes
	$classes = wpb( 'classes/component/notification', $class, '', ( !empty( $attr['class'] ) ? $attr['class'] : '' ) );

	$attr['class'] = $classes;

	// Get attributes
	if ( !empty( $dismiss ) )
		$attr['data-wpb-dismiss'] = true;

	$attr = wpb( 'attr', $attr );

	// Build the HTML
	$html = '<div' . $attr . '>';

	if ( !empty( $dismiss ) ) {

		$html .= '<a href="#" class="notification-close">&times;</a>';

	}

	if ( $icon ) {

		$html .= wpb( 'icon', array( 'icon' => $icon, 'attr' => array( 'class' => 'notification-icon' ) ) );

	}

	$html .= wpautop( trim( $text ) );

	if ( !empty( $desc ) ) {

		$html .= '<div class="notification-desc">';
		$html .= wpautop( trim( $desc ) );
		$html .= '</div>';

	}

	$html .= '</div>';

	$html = apply_filters( 'wpb/component/notification', $html, $args );

	return $html;

}

endif;


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

if ( !function_exists( 'wpb_request__content_date' ) ) :

function wpb_request__content_date( $args ) 
{

	extract( $args );

	// Get the post
	if ( empty( $id ) ) {

		global $post;

		$id = get_the_ID();

	} else {

		$post = get_post( $id );

	}

	if ( !$post || !$id )
		return;

	// Get date string
	if ( $date_text ) {

		$date_text = sprintf( 
			$date_text, 
			get_the_date( $format, $id ),
			esc_attr( get_the_date( 'c', $id ) )
		);

		if ( $date_link && $date_link_text ) {

			$date_text = sprintf(
				$date_link_text,
				$date_text,
				esc_url( get_permalink() ),
				esc_url( get_day_link( get_the_date( 'Y', $id ), get_the_date( 'm', $id ), get_the_date( 'd', $id ) ) )
			);
		
		}

	}

	// Get author string
	if ( $author_text ) {

		$author_text = sprintf(
			$author_text,
			get_the_author_meta( 'display_name', $post->post_author )
		);

		if ( $author_link && $author_link_text ) {

			$author_text = sprintf(
				$author_link_text,
				$author_text,
				get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) )
			);

		}

	}

	// Put it together
	$text = sprintf( $text, $date_text, $author_text );

	return trim( $text );

}

endif;


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


if ( !function_exists( 'wpb_request__content_terms' ) ) :

function wpb_request__content_terms( $args ) 
{

	extract( $args );

	// Get the post
	if ( empty( $id ) )
		$id = get_the_ID();

	if ( !$id )
		return;

	// Get the taxonomies
	if ( empty( $tax ) ) {

		// Get all taxonomies
		$tax_args = array( 
			'public'      => true,
			'object_type' => array( $post->post_type )
		);

		$tax = get_taxonomies( $tax_args, 'objects' );

	}

	// No taxonomies
	if ( empty( $tax ) )
		return;

	if ( !is_array( $tax ) )
		$tax = array_map( 'trim', explode( ',', $tax ) );

	// Get taxonomy objects
	$objects = array();

	foreach ( $tax as $slug ) {

		$taxonomy = get_taxonomy( $slug );

		if ( $taxonomy )
			$objects[ $slug ] = $taxonomy;

	}

	// No objects
	if ( empty( $objects ) )
		return;

	// Separate by hierarchy
	$cat_objects = array();
	$tag_objects = array();

	foreach ( $objects as $slug => $taxonomy ) {

		// Get terms
		$terms = get_the_terms( $id, $slug );

		if ( !$terms )
			continue;

		foreach ( $terms as $term ) {

			$term_link = '<a href="' . get_term_link( $term ) . '">' . $term->name . '</a>';

			if ( $taxonomy->hierarchical )
				$cat_objects[ $slug . '__' . $term->slug ] = $term_link;

			else
				$tag_objects[ $slug . '__' . $term->slug ] = $term_link;

		}

	}

	// No terms
	if ( empty( $cat_objects ) && empty( $tag_objects ) )
		return;

	$cats_str = implode( ', ', $cat_objects );
	$tags_str = implode( ', ', $tag_objects );

	// Build the string
	if ( $cats_str && $tags_str )
		return sprintf( $both, $cats_str, $tags_str );

	elseif ( $tags_str )
		return sprintf( $tags, $tags_str );

	return sprintf( $cats, $cats_str );

}

endif;


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

if ( !function_exists( 'wpb_request__content_comments_link' ) ) :

function wpb_request__content_comments_link( $args ) 
{

	extract( $args );

	// Get the post
	if ( empty( $id ) )
		$id = get_the_ID();

	if ( !$id )
		return;

	// Get number of comments
	$number = (int) get_comments_number( $id );

	// Comments disabled/none exist
	if ( !$number && !comments_open( $id ) )
		return sprintf( $none, $number );

	// Get URL
	$url = get_comments_link( $id );

	// Get string
	$str = $text;

	if ( !$number && !empty( $zero ) ) {

		$str = $zero;
		$url = str_replace( '#comments', '#respond', $url );

	}

	if ( 1 == $number && !empty( $one ) )
		$str = $one;

	if ( $link )
		$str = '<a href="' . $url . '">' . $str . '</a>';

	return sprintf( $str, $number );

}

endif;


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


if ( !function_exists( 'wpb_request__content_edit_button' ) ) :

function wpb_request__content_edit_button( $args ) 
{

	extract( $args );

	// Get the post
	if ( empty( $id ) )
		$id = get_the_ID();

	if ( !$id )
		return;

	// Get the edit URL
	$url = get_edit_post_link( $id );

	if ( !$url )
		return;

	// Get the post type
	$post_type     = get_post_type( $id );
	$post_type_obj = get_post_type_object( $post_type );

	// Get the labels
	$label_edit = '';
	$label_type = $post_type;

	if ( !empty( $post_type_object->labels->edit_item ) )
		$label_edit = $post_type_obj->labels->edit_item;

	if ( !empty( $post_type_obj->labels->singular_name ) )
		$label_type = $post_type_obj->labels->singular_name;

	if ( !$label_edit )
		$label_edit = sprintf( __( 'Edit %s', 'wpb' ), $label_type );

	// Get button arguments
	$button_args = array(
		'href'  => $url,
		'text'  => sprintf( $text, $label_edit, $label_type ),
		'icon'  => $icon,
		'class' => $class,
		'attr'  => array(
			'class' => 'button-edit'
		)
	);

	// Get the button
	$button = wpb( 'button', $button_args );

	return '<span class="edit-button-container">' . $button . '</span>';

}

endif;


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

if ( !function_exists( 'wpb_request__content_archive' ) ) :

function wpb_request__content_archive( $args ) 
{

	$object = false;

	$archive = array(
		'type'  => '',
		'id'    => $args['id'],
		'title' => '',
		'desc'  => '',
		'obj'   => ''
	);

	// Get type
	$types = array( 'post_type', 'term', 'author', 'date', '404', 'search' );

	if ( !empty( $args['type'] ) && in_array( $args['type'], $types ) )
		$archive['type'] = $args['type'];

	if ( !$archive['type'] ) {

		$archive['id']   = '';
		$archive['type'] = 'post_type';

		if ( is_404() )
			$archive['type']= '404';

		elseif ( is_search() )
			$archive['type'] = 'search';

		elseif ( is_author() )
			$archive['type'] = 'author';

		elseif ( is_category() || is_tag() || is_tax() )
			$archive['type'] = 'term';

		elseif ( is_date() )
			$archive['type'] = 'date';

	}

	// Get data
	switch ( $archive['type'] ) {

		// Post type
		case 'post_type' :

			$object = ( !$archive['id'] ? get_queried_object() : get_post_type_object( $archive['id'] ) );

			if ( $object ) {

				// Get title format
				$type_title = wpb( 'settings/get', 'archive/post-type/title' );

				// Get label
				$label = ( !empty( $object->labels->name ) ? $object->labels->name : $object->label );

				// Set data
				$archive['id'] = $object->name;

				$archive['title'] = ( $type_title ? str_replace( '%post_type%', $label, $type_title ) : $label );
				$archive['desc']  = $object->description;

			}

		break;

		// Author
		case 'author' :

			$object = ( !$archive['id'] ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_user_by( 'id', $archive['id'] ) );

			if ( $object ) {

				// Get title format
				$author_title = wpb( 'settings/get', 'archive/author/title' );

				// Get meta
				$archive_meta = wpb( 'settings/get', 'archive/author/meta', array() );

				// Set data
				$archive['id'] = $object->ID;

				$archive['title'] = ( $author_title ? str_replace( '%author%', $object->display_name, $author_title ) : $object->display_name );

				$archive['meta'] = array(
					'avatar' => get_avatar( $object->ID, 150 ),
					'bio'    => get_the_author_meta( 'description', $object->ID ),
					'email'  => $object->user_email,
					'url'    => $object->user_url
				);

				$archive['display'] = array(
					'avatar' => ( in_array( 'avatar', $archive_meta ) ? $archive['meta']['avatar'] : '' ),
					'bio'    => ( in_array( 'bio', $archive_meta )    ? $archive['meta']['bio']    : '' ),
					'email'  => ( in_array( 'email', $archive_meta )  ? $archive['meta']['email']  : '' ),
					'url'    => ( in_array( 'url', $archive_meta )    ? $archive['meta']['url']    : '' )
				);

				$archive['schema'] = wpb( 'settings/choice', 'archive/author/schema' );

			}

		break;

		// Term
		case 'term' :

			$object = ( !$archive['id']  ? get_queried_object() : get_term( $archive['id'] ) );

			if ( $object ) {

				// Get title format
				$term_title = wpb( 'settings/get', 'archive/term/title' );

				// Get taxonomy
				$taxonomy = get_taxonomy( $object->taxonomy );

				// Get taxonomy label
				$label = ( !empty( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : ( !empty( $taxonomy->singular_label ) ? $taxonomy->singular_label : '' ) );

				if ( !$label )
					$label = ( !empty( $taxonomy->labels->name ) ? $taxonomy->labels->name : $taxonomy->label );

				// Set data
				$archive['id'] = $object->term_id;

				$archive['title'] = ( $term_title ? str_replace( array( '%term%', '%taxonomy%' ), array( $object->name, $label ), $term_title ) : $object->name );
				$archive['desc']  = $object->description;

				$archive['taxonomy'] = $taxonomy;

			}

		break;

		// Date
		case 'date' :

			// Set data
			if ( is_day() )
				$archive['title'] = get_the_date();

			elseif ( is_month() )
				$archive['title'] = get_the_date( 'F Y' );

			else
				$archive['title'] = get_the_date( 'Y' );

			$archive['timestamp'] = strtotime( get_the_date() . ', ' . get_the_time() );

		break;

		// 404
		case '404' :

			// Set data
			$archive['title']  = wpb( 'settings/get', '404/title' );
			$archive['desc']   = wpb( 'settings/get', '404/desc' );
			$archive['search'] = wpb( 'settings/get', '404/search' );

		break;

		// Search
		case 'search' :

			// Get title format
			$search_title = wpb( 'settings/get', 'search/title' );
			$search_results_title = wpb( 'settings/get', 'search/results-title' );

			// Set data
			$archive['query'] = get_search_query();
			$archive['title'] = ( $search_title ? $search_title : __( 'Search', 'wpb' ) );

			if ( $archive['query'] ) {

				if ( !$search_results_title )
					$search_results_title = __( 'Search results for %search_terms_q%', 'wpb' );

				$archive['title'] = str_replace( array( '%search_terms%', '%search_terms_q%' ), array( $archive['query'], '<q>' . $archive['query'] . '</q>' ), $search_results_title );

			}					

		break;

	}

	// Format
	if ( $archive['desc'] )
		$archive['desc'] = wpautop( $archive['desc'] );

	// Filter
	$archive = apply_filters( 'wpb/archive/object', $archive );

	// Add object
	if ( $object )
		$archive['obj'] = $object;

	return (object) $archive;

}

endif;


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

if ( !function_exists( 'wpb_request__content_excerpt_more' ) ) :

function wpb_request__content_excerpt_more( $args ) 
{

	// Get values
	$link  = true;
	$text  = wpb( 'settings/get', 'excerpt/more_text', __( 'Continue reading', 'wpb' ) );

	$text  = apply_filters( 'wpb/excerpt/more/text', $text, $args['text'] );
	$dots  = apply_filters( 'wpb/excerpt/more/dots', '&hellip;', $args['dots'] );
	$arrow = apply_filters( 'wpb/excerpt/more/arrow', '&rarr;', $args['arrow'] );

	// Check for custom link text
	if ( wpb( 'settings/choice', 'excerpt/more_link', 'custom' ) ) {

		$saved = get_post_meta( get_the_ID(), '_wpb_excerpt_link', true );

		if ( $saved )
			$text = $saved;

	}

	// Check for link
	if ( !$text ) {

		$link = false;

	} elseif ( has_excerpt() ) {

		if ( !wpb( 'settings/choice', 'excerpt/more_link', 'manual' ) )
			$link = false;

	} elseif ( !wpb( 'settings/choice', 'excerpt/more_link', 'auto', true ) ) {

		$link = false;

	}

	// Check for arrow
	if ( !$link ) {

		$arrow = '';

	} elseif ( !empty( $saved ) ) {

		if ( !wpb( 'settings/choice', 'excerpt/more_meta', 'arrow_custom' ) )
			$arrow = '';

	} elseif ( !wpb( 'settings/choice', 'excerpt/more_meta', 'arrow_auto', true ) ) {

		$arrow = '';

	}

	// Check for dots
	if ( has_excerpt() ) {

		if ( !wpb( 'settings/choice', 'excerpt/more_meta', 'dots_manual' ) )
			$dots = '';

	} elseif ( !wpb( 'settings/choice', 'excerpt/more_meta', 'dots_auto', true ) ) {

		$dots = '';

	}

	// Nothing to display
	if ( !$dots && !$link )
		return '';

	// Build the string
	$more = ' ';

	$more .= '<span class="content-more">';

	if ( $dots ) {

		$more .= '<span class="content-more-dots">' . $dots . '</span>';

	}

	if ( $link ) {

		if ( $dots )
			$more .= ' ';

		$more .= '<span class="content-more-text">';

		$more .= '<a href="' . esc_url( get_permalink() ) . '">' . $text . '</a>';

		if ( $arrow )
			$more .= ' <span class="content-meta">' . $arrow . '</span>';

		$more .= '</span>';

	}

	$more .= '</span>';

	return $more;

}

endif;


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

if ( !function_exists( 'wpb_request__image' ) ) :

function wpb_request__image( $args ) 
{

	// Get registered image sizes
	$sizes = wpb( 'image/sizes' );

	// Default to full size
	if ( !$args['size'] || ( !is_array( $args['size'] ) && !isset( $sizes[ $args['size'] ] ) ) )
		$args['size'] = 'full';

	// Get the image
	if ( !empty( $args['id'] ) ) {

		$img_id     = $args['id'];
		$img_src    = wp_get_attachment_image_src( $args['id'], $args['size'] );
		$img_srcset = wp_get_attachment_image_srcset( $args['id'], $args['size'] );
		$img_sizes  = wp_get_attachment_image_sizes( $args['id'], $args['size'] );

	}

	// Check for featured post image
	if ( empty( $img_src ) ) {

		if ( !has_post_thumbnail( $args['id'] ) )
			return '';

		$post_id    = $args['id'];
		$img_id     = get_post_thumbnail_id( $args['id'] );
		$img_src    = wp_get_attachment_image_src( $img_id, $args['size'] );
		$img_srcset = wp_get_attachment_image_srcset( $img_id, $args['size'] );
		$img_sizes  = wp_get_attachment_image_sizes( $img_id, $args['size'] );

	}

	$img_post = get_post( $img_id );

	if ( !$img_post )
		return '';

	// Get the classes
	$classes = wpb( 'classes/get', 'elem=image&default=content-image&str=' );

	if ( !empty( $args['class'] ) ) {

		if ( !is_array( $args['class'] ) )
			$args['class'] = explode( ' ', $args['class'] );

		$classes = array_merge( $classes, $args['class'] );

	}

	// Get the link
	$link = false;
	$img_link = false;

	if ( !empty( $args['link'] ) ) {

		// Post link
		if ( is_numeric( $args['link'] ) || 'post' == $args['link'] ) {

			$link = get_permalink( ( 'post' == $args['link'] ? $post_id : $args['link'] ) );

		} else {

			// Size link
			if ( isset( $sizes[ $args['link'] ] ) ) {

				$link_src = wp_get_attachment_image_src( $img_id, $args['link'] );

				$link = $link_src[0];
				$img_link = true;

			} else {

				// Normal link
				$link = $args['link'];

			}

		}

	}

	// Get the title
	$title = false;

	if ( !empty( $args['title'] ) ) {

		$title = $img_post->post_title;

		// Custom title
		if ( !is_bool( $args['title'] ) ) {

			$title = $args['title'];

		}

	}

	// Get the alt tag
	$alt = false;

	if ( !empty( $args['alt'] ) ) {

		$alt = get_post_meta( $img_post->ID, '_wp_attachment_image_alt', true );

		// Custom alt
		if ( !is_bool( $args['alt'] ) ) {

			$alt = $args['alt'];

		}

	}

	// Get the caption
	$caption = false;

	if ( !empty( $args['caption'] ) ) {

		$caption = $img_post->post_excerpt;

	}

	// Build the HTML
	$html = '';

	if ( !empty( $args['wrapper'] ) )
		$html .= '<' . $args['wrapper'] . ' class="' . implode( ' ', $classes ) . '">';

	if ( $title ) {

		$html .= '<h4 class="content-title image-title">';
		$html .= $title;
		$html .= '</h4>';

	}

	if ( $link ) {

		$html .= '<a href="' . $link . '" target="' . esc_attr( $args['target'] ) . '" class="' . ( $img_link ? 'image-link' : 'content-link' ) . '">';

	}

	$img_attr = array(
		'src="' . $img_src[0] . '"',
		'width="' . $img_src[1] . '"',
		'height="' . $img_src[2] . '"',
		'alt="' . esc_attr( $alt ) . '"'
	);

	if ( !empty( $args['sizes'] ) ) {

		$img_attr[] = 'srcset="' . esc_attr( $img_srcset ) . '"';
		$img_attr[] = 'sizes="' . esc_attr( $img_sizes ) . '"';

	}

	$html .= '<img ' . implode( ' ', $img_attr ) . ' />';

	if ( $link ) {

		$html .= '</a>';

	}

	if ( $caption ) {

		$html .= '<' . ( 'figure' == $args['wrapper'] ? 'figcaption' : 'div' ) . ' class="image-caption">';
		$html .= wpautop( $caption );
		$html .= '</' . ( 'figure' == $args['wrapper'] ? 'figcaption' : 'div' ) . '>';

	}

	if ( !empty( $args['wrapper'] ) )
		$html .= '</' . $args['wrapper'] . '>';

	return $html;

}

endif;


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

if ( !function_exists( 'wpb_request__nav_single' ) ) :

function wpb_request__nav_single( $args ) 
{

	// Image
	if ( wp_attachment_is_image() )
		return wpb( 'nav/image', $args );

	// Same term
	$same_term = false;

	if ( !empty( $args['taxonomy'] ) ) {

		$same_term = true;

	}

	// Get previous and next
	$prev_text = $args['prev_text'] ? $args['prev_text'] . ' ' : '';
	$next_text = $args['next_text'] ? ' ' . $args['next_text'] : '';

	$prev = wpb_request__get_adjacent_post_link( array(
		'format'    => '%link', 
		'link'      => $prev_text . '<span class="text">%title</span>', 
		'same_term' => $same_term, 
		'excluded'  => $args['excluded'], 
		'prev'      => true,
		'taxonomy'  => $args['taxonomy']
	) );

	$next = wpb_request__get_adjacent_post_link( array(
		'format'    => '%link', 
		'link'      => '<span class="text">%title</span>' . $next_text,
		'same_term' => $same_term, 
		'excluded'  => $args['excluded'], 
		'prev'      => false,
		'taxonomy'  => $args['taxonomy']
	) );

	// No links
	if ( !$prev && !$next )
		return;

	if ( $prev )
		$prev = '<li class="' . wpb( 'classes/get', 'nav-prev', 'nav-prev' ) . '">' . $prev . '</li>';

	if ( $next )
		$next = '<li class="' . wpb( 'classes/get', 'nav-next', 'nav-next' ) . '">' . $next . '</li>';

	// Get classes
	$classes = wpb( 'classes/get', 'nav, nav-single', wpb( 'classes/component/nav', 'content single' ) );

	if ( isset( $args['attr']['class'] ) )
		$args['attr']['class'] = $args['attr']['class'] + $classes;

	else
		$args['attr']['class'] = $classes;

	// Build HTML
	$nav_str  = '<nav' . wpb( 'attr', $args['attr'] ) . '>';
	$nav_str .= '<ul>';
	$nav_str .= $prev;
	$nav_str .= $next;
	$nav_str .= '</ul>';
	$nav_str .= '</nav>';

	return $nav_str;

}

endif;


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

if ( !function_exists( 'wpb_request__nav_loop' ) ) :

function wpb_request__nav_loop( $args ) 
{

	global $wp_query;

	if ( !$wp_query || $wp_query->max_num_pages < 2 )
		return;

	// Get the current page
	$current_page = max( 1, get_query_var( 'paged' ) );

	// Format
	if ( get_option( 'permalink_structure' ) && !is_search() )
		$format = 'page/%#%/';
	else
		$format = ( is_home() || is_front_page() ? '?' : '&amp;' ) . 'paged=%#%';

	// Merge arguments
	$loop_args = array(
		'type'    => 'list',
		'base'    => get_pagenum_link(1) . '%_%',
		'format'  => $format,
		'current' => $current_page,
		'total'   => $wp_query->max_num_pages
	);

	$nav_args = $args;
	unset( $nav_args['attr'], $nav_args['echo'] );

	$nav_args = array_merge( $nav_args, $loop_args );

	// Get the links
	$nav = paginate_links( $nav_args );

	// No links, bail
	if ( !$nav )
		return;

	// Get classes
	$classes = wpb( 'classes/get', 'nav, nav-loop', wpb( 'classes/component/nav', 'content loop' ) );

	if ( isset( $args['attr']['class'] ) )
		$args['attr']['class'] = $args['attr']['class'] + $classes;

	else
		$args['attr']['class'] = $classes;

	// Build HTML
	$nav_str = '<nav' . wpb( 'attr', $args['attr'] ) . '>';
	$nav_str .= $nav;
	$nav_str .= '</nav>';

	return $nav_str;

}

endif;


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

if ( !function_exists( 'wpb_request__nav_image' ) ) :

function wpb_request__nav_image( $args ) 
{

	// Get previous and next
	$prev = wpb_request__get_adjacent_image_link( array( 'prev' => true,  'size' => $args['size'], 'text' => $args['prev_text'] ) );
	$next = wpb_request__get_adjacent_image_link( array( 'prev' => false, 'size' => $args['size'], 'text' => $args['next_text'] ) );

	// No links
	if ( !$prev && !$next )
		return;

	if ( $prev )
		$prev = '<div class="' . wpb( 'classes/get', 'nav-prev', 'nav-prev' ) . '">' . $prev . '</div>';

	if ( $next )
		$next = '<div class="' . wpb( 'classes/get', 'nav-next', 'nav-next' ) . '">' . $next . '</div>';

	// Get classes
	$classes = wpb( 'classes/get', 'nav, nav-single', wpb( 'classes/component/nav', 'content single image' ) );

	if ( isset( $args['attr']['class'] ) )
		$args['attr']['class'] = $args['attr']['class'] + $classes;
	else
		$args['attr']['class'] = $classes;

	// Build HTML
	$nav_str = '<nav' . wpb( 'attr', $args['attr'] ) . '>';
	$nav_str .= $prev;
	$nav_str .= $next;
	$nav_str .= '</nav>';

	return $nav_str;

}

endif;


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

if ( !function_exists( 'wpb_request__nav_paged' ) ) :

function wpb_request__nav_paged( $args ) 
{

	$nav_args = array(
		'before'           => '',
		'after'            => '',
		'echo'             => false,
		'nextpagelink'     => $args['prev_text'],
		'previouspagelink' => $args['next_text']
	);

	$nav = wp_link_pages( $nav_args );

	// No navigation, bail
	if ( !$nav )
		return;

	// Wrap the current page number in a <span />
	$nav = preg_replace( '~([0-9]+)(?!(?>[^<]*(?:<(?!/?a\b)[^<]*)*)</a>)~i','<span class="page-numbers current">$0</span>', $nav );

	// Add .page-numbers class to the links
	$nav = preg_replace( '/(<a\b[^><]*)>/i', '$1 class="page-numbers">', $nav );

	// Get classes
	$classes = wpb( 'classes/get', 'nav, page-nav', wpb( 'classes/component/nav', 'content page' ) );

	if ( isset( $args['attr']['class'] ) )
		$args['attr']['class'] = $args['attr']['class'] + $classes;
	else
		$args['attr']['class'] = $classes;

	// Build HTML
	$nav_str = '<nav' . wpb( 'attr', $args['attr'] ) . '>';
	$nav_str .= $nav;
	$nav_str .= '</nav>';

	return $nav_str;

}

endif;


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

if ( !function_exists( 'wpb_request__nav_comments' ) ) :

function wpb_request__nav_comments( $args ) 
{

	global $wp_query;

	// No navigation, bail
	if ( !$wp_query || $wp_query->max_num_comment_pages < 2 )
		return;

	$nav_args = $args;
	unset( $nav_args['attr'], $nav_args['echo'] );

	$nav_args['type'] = 'list';
	$nav_args['echo'] = false;

	$nav = paginate_comments_links( $nav_args );

	// No navigation, bail
	if ( !$nav )
		return;

	// Get classes
	$classes = wpb( 'classes/get', 'nav, comments-nav', wpb( 'classes/component/nav', 'content comments' ) );

	if ( isset( $args['attr']['class'] ) )
		$args['attr']['class'] = $args['attr']['class'] + $classes;
	else
		$args['attr']['class'] = $classes;

	// Build HTML
	$nav_str = '<nav' . wpb( 'attr', $args['attr'] ) . '>';
	$nav_str .= $nav;
	$nav_str .= '</nav>';

	return $nav_str;

}

endif;


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

if ( !function_exists( 'wpb_request__get_adjacent_post_link' ) ) :

function wpb_request__get_adjacent_post_link( $args ) 
{

	extract( $args );

	if ( $prev && is_attachment() ) {

		$post = get_post( get_post()->post_parent );

	} else {

		$curr = get_post();

		if ( $curr ) {

			if ( 'post' != $curr->post_type && 'category' == $taxonomy ) {

				$same_term = false;
				$taxonomy  = '';

			}

		}

		if ( 'post_category' == $taxonomy || !$taxonomy )
			$taxonomy = 'category';

		$post = get_adjacent_post( $same_term, $excluded, $prev, $taxonomy );

	}

	if ( !$post ) {

		$output = '';

	} else {

		$title = $post->post_title;

		if ( empty( $post->post_title ) )
			$title = $prev ? __( 'Previous Post', 'wpb' ) : __( 'Next Post', 'wpb' );

		$title = apply_filters( 'the_title', $title, $post->ID );
		$date  = mysql2date( get_option( 'date_format' ), $post->post_date );
		$rel   = $prev ? 'prev' : 'next';

		$string = '<a href="' . get_permalink( $post ) . '" rel="'.$rel.'">';
		$inlink = str_replace( '%title', $title, $link );
		$inlink = str_replace( '%date', $date, $inlink );
		$inlink = $string . $inlink . '</a>';

		$output = str_replace( '%link', $inlink, $format );

	}

	$adjacent = $prev ? 'previous' : 'next';

	return apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post );

}

endif;


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

if ( !function_exists( 'wpb_request__get_adjacent_image_link' ) ) :

function wpb_request__get_adjacent_image_link( $args ) 
{

	global $post;

	extract( $args );

	$post = get_post( $post );

	$attachments = array_values(
		get_children( array(
			'post_parent' => $post->post_parent, 
			'post_status' => 'inherit', 
			'post_type' => 'attachment', 
			'post_mime_type' => 'image', 
			'order' => 'ASC', 
			'orderby' => 'menu_order ID'
		) )
	);

	foreach ( $attachments as $k => $attachment )

		if ( $attachment->ID == $post->ID )
			break;

	$k = $prev ? $k - 1 : $k + 1;

	if ( isset( $attachments[ $k ] ) ) {

		$attachment = $attachments[ $k ];

		// Get caption
		$caption = false;

		if ( !empty( $attachment->post_excerpt ) )
			$caption = $attachment->post_excerpt;

		else
			$caption = $attachment->post_title;

		if ( $text ) {

			if ( $prev )
				$caption = $text . ' ' . $caption;

			else
				$caption .= ' ' . $text;

		}

		if ( $caption ) {

			$caption = '<a href="' . get_permalink( $attachment->ID ) . '">' . $caption . '</a>';

		}

		// Return image
		return wpb( 'image', array(
			'id'      => $attachment->ID,
			'link'    => $attachment->ID,
			'size'    => $size,
			'caption' => $caption
		) );

	}

}

endif;


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

if ( !function_exists( 'wpb_request__image_thumbnail' ) ) :

function wpb_request__image_thumbnail( $args ) 
{

	// Get post ID
	$post_id = ( $args['id'] ? $args['id'] : get_the_ID() );

	// Check for banner
	if ( ( !$args['size'] || in_array( $args['size'], array( 'banner', 'wpb_banner' ) ) ) && current_theme_supports( 'wpb-banner' ) ) {

		if ( wpb( 'setting/choice', 'banner/display', 'thumbnails' ) ) {

			$banner = wpb( 'image/banner', array( 'id' => $args['id'], 'link' => $args['link'] ) );

			if ( $banner )
				return $banner;

		}

	}

	// Get classes
	$classes = wpb( 'classes/get', 'elem=thumbnail&default=content-thumbnail&str=' );

	if ( !empty( $args['class'] ) ) {

		if ( !is_array( $args['class'] ) )
			$args['class'] = explode( ' ', $args['class'] );

		$classes = array_merge( $classes, $args['class'] );

	}

	// Get image arguments
	$img_args = array(
		'size'    => ( $args['size'] ? $args['size'] : 'thumbnail' ),
		'id'      => get_post_thumbnail_id( $post_id ),
		'link'    => ( $args['link'] ? $post_id : false ),
		'title'   => false,
		'caption' => false,
		'wrapper' => 'figure',
		'class'   => $classes
	);

	return wpb( 'image', $img_args );

}

endif;


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

if ( !function_exists( 'wpb_request__image_banner' ) ) :

function wpb_request__image_banner( $args ) 
{

	// Check theme support
	if ( !current_theme_supports( 'wpb-banner' ) )
		return '';

	// Get post ID
	$post_id = ( !empty( $args['id'] ) ? $args['id'] : get_the_ID() );

	// Get image ID
	$img_id = wpb( 'get_post_banner_id', $post_id );

	if ( !$img_id && wpb( 'setting/get', 'banner/fallback' ) )
		$img_id = get_post_thumbnail_id( $post_id );

	if ( !$img_id )
		return '';

	// Get classes
	$classes = wpb( 'classes/get', 'elem=banner&default=content-banner&str=' );

	if ( !empty( $args['class'] ) ) {

		if ( !is_array( $args['class'] ) )
			$args['class'] = explode( ' ', $args['class'] );

		foreach ( $args['class'] as $i => $class ) {

			$args['class'][ $i ] = str_replace( 'thumbnail', 'banner', $class );

		}

		$classes = array_merge( $classes, $args['class'] );

	}

	// Get image arguments
	$img_args = array(
		'size'    => ( !empty( $args['size'] ) ? ( 'banner' == $args['size'] ? 'wpb_banner' : $args['size'] ) : 'wpb_banner' ),
		'id'      => $img_id,
		'link'    => ( $args['link'] ? $post_id : false ),
		'title'   => false,
		'caption' => false,
		'wrapper' => 'figure',
		'class'   => $classes
	);

	return wpb( 'image', $img_args );

}

endif;


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

if ( !function_exists( 'wpb_request__image_header' ) ) :

function wpb_request__image_header( $args ) 
{

	// Check theme support
	if ( !current_theme_supports( 'custom-header' ) )
		return '';

	// Check for image
	$header   = get_custom_header();
	$featured = false;
	$banner   = false;

	// Check for featured image
	if ( wpb( 'setting/choice', 'custom-header/override', 'featured' ) && has_post_thumbnail() )
		$featured = true;

	// Check for banner
	if ( wpb( 'setting/choice', 'custom-header/override', 'banner' ) && wpb( 'has_post_banner' ) )
		$banner = true;

	if ( empty( $header->attachment_id ) && !$featured && !$banner )
		return '';

	// Get size
	$size = 'full';

	if ( isset( $header->width ) && isset( $header->height ) )
		$size = array( $header->width, $header->height );

	$saved = wpb( 'settings/get', 'custom-header/size' );

	if ( !empty( $saved['height'] ) )
		$size = array( $saved['width'], $saved['height'] );

	// Get classes
	$classes = wpb( 'classes/get', 'elem=custom-header&default=custom-header-image&str=' );

	if ( !empty( $args['class'] ) ) {

		if ( !is_array( $args['class'] ) )
			$args['class'] = explode( ' ', $args['class'] );

		$classes = array_merge( $classes, $args['class'] );

	}

	// Banner
	if ( $banner ) {

		return wpb( 'image/banner', array(
			'link'  => false,
			'class' => $classes,
			'size'  => $size
		) );

	}

	// Featured image
	if ( $featured ) {

		return wpb( 'image', array(
			'link'  => false,
			'class' => $classes,
			'size'  => $size
		) );

	}

	// Header image
	return wpb( 'image', array(
		'link'  => false,
		'class' => $classes,
		'id'    => $header->attachment_id,
		'size'  => $size
	) );

}

endif;


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

if ( !function_exists( 'wpb_request__image_sizes' ) ) :

function wpb_request__image_sizes() 
{

	global $_wp_additional_image_sizes;

	// Get cached
	$sizes = wpb()->data( 'image/sizes' );

	if ( !empty( $sizes ) )
		return $sizes;

	$sizes = array();

	// Set full
	$sizes['full']['width']  = '';
	$sizes['full']['height'] = '';
	$sizes['full']['crop']   = false;

	// Get sizes
	foreach ( get_intermediate_image_sizes() as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);

		}

	}

	// Cache
	wpb()->data( 'image/sizes', $sizes );

	return $sizes;

}

endif;


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

if ( !function_exists( 'wpb_request__has_post_banner' ) ) :

function wpb_request__has_post_banner( $args ) 
{

	return (bool) wpb( 'get_post_banner_id', $args );

}

endif;


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

if ( !function_exists( 'wpb_request__get_post_banner_id' ) ) :

function wpb_request__get_post_banner_id( $args ) 
{

	$post = get_post( $args['post'] );

	if ( !$post )
		return '';

	return get_post_meta( $post->ID, '_wpb_banner_id', true );

}

endif;


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

if ( !function_exists( 'wpb_request__stylesheet_theme' ) ) :

function wpb_request__stylesheet_theme()
{

	// Cached
	$stylesheet = wpb()->data( 'stylesheets/theme' );

	if ( !is_null( $stylesheet ) )
		return $stylesheet;

	// Default to theme stylesheet
	$stylesheet = get_stylesheet_directory_uri() . '/style.css';

	// Filter
	$stylesheet = apply_filters( 'wpb/stylesheet/theme', $stylesheet );

	// Cache
	wpb()->data( 'stylesheets/theme', $stylesheet );

	return $stylesheet;

}

endif;


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

if ( !function_exists( 'wpb_request__template' ) ) :

function wpb_request__template( $args ) 
{

	if ( empty( $args['path'] ) )
		return false;

	// Get directories
	$dirs = wpb( 'template/dirs' );

	// Fallback to WordPress
	if ( empty( $dirs ) && !$args['fallback'] )			
		return get_template_part( 'templates/' . $args['path'], $args['name'] );

	// Search for template file
	$found = false;

	if ( !empty( $dirs ) ) {

		foreach ( $dirs as $dir ) {

			if ( !empty( $args['name'] ) ) {

				$file = $dir . $args['path'] . '-' . $args['name'] . '.php';

				if ( file_exists( $file ) ) {

					$found = $file;
					break;

				}

			}

			$file = $dir . $args['path'] . '.php';

			if ( file_exists( $file ) ) {

				$found = $file;
				break;

			}

		}

	}

	if ( !$found ) {

		if ( !$args['fallback'] )
			return false;

		if ( !file_exists( $args['fallback'] ) )
			return false;

		$file = $args['fallback'];

	}

	// Include the template
	include $file;

	return $file;

}

endif;


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

if ( !function_exists( 'wpb_request__template_dirs' ) ) :

function wpb_request__template_dirs()
{

	// Cached
	$dirs = wpb()->data( 'template/dirs' );

	if ( !is_null( $dirs ) )
		return $dirs;

	// Get directories
	$dirs = array(
		'theme'  => get_stylesheet_directory() . '/templates',
		'parent' => get_template_directory() . '/templates',
		'plugin' => wpb()->dir( 'public/templates' )
	);

	// Filter
	$dirs = apply_filters( 'wpb/template/dirs', $dirs );

	// Format
	foreach ( $dirs as $i => $dir ) {

		$dirs[ $i ] = trailingslashit( str_replace( '\\', '/', $dir ) );

	}

	// Cache
	wpb()->data( 'template/dirs', $dirs );

	return $dirs;

}

endif;


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

if ( !function_exists( 'wpb_request__settings_get' ) ) :

function wpb_request__settings_get( $args ) 
{

	return wpb( ':settings/get', $args['key'], $args['default'], $args['objects'], $args['format'], $args['group'] );

}

endif;


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

if ( !function_exists( 'wpb_request__settings_choice' ) ) :

function wpb_request__settings_choice( $args ) 
{

	return wpb( ':settings/choice', $args['key'], $args['value'], $args['default'], $args['group'] );

}

endif;


/**
 *
 *	Register a setting
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

if ( !function_exists( 'wpb_request__settings_register' ) ) :

function wpb_request__settings_register( $args ) 
{

	return wpb( ':settings/register', $args['key'], $args['data'], $args['default'], $args['group'] );

}

endif;


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

if ( !function_exists( 'wpb_request__settings_save' ) ) :

function wpb_request__settings_save( $args ) 
{

	return wpb( ':settings/save', $args['values'], $args['validate'], $args['sanitize'], $args['group'] );

}

endif;


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

if ( !function_exists( 'wpb_request__settings_error' ) ) :

function wpb_request__settings_error( $args ) 
{

	return wpb( ':settings/add_error', $args['key'], $args['error'], $args['group'] );

}

endif;


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

if ( !function_exists( 'wpb_request__settings_addon_get' ) ) :

function wpb_request__settings_addon_get( $args ) 
{

	return wpb( ':addons/get_settings', $args['addon'], $args['key'], $args['default'], $args['objects'], $args['format'] );

}

endif;


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

if ( !function_exists( 'wpb_request__settings_addon_register' ) ) :

function wpb_request__settings_addon_register( $args ) 
{

	return wpb( ':addons/register_setting', $args['addon'], $args['key'], $args['data'], $args['default'] );

}

endif;


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

if ( !function_exists( 'wpb_request__addons_register' ) ) :

function wpb_request__addons_register( $args ) 
{

	return wpb( ':addons/register_addon', $args['slug'], $args['path'], $args['data'] );

}

endif;


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

if ( !function_exists( 'wpb_request__addons_get' ) ) :

function wpb_request__addons_get( $args ) 
{

	// Return all
	if ( empty( $args['slug'] ) ) {

		if ( empty( $args['object'] ) )
			return wpb( ':addons/get_registered' );

		return wpb( ':addons/get_registered_objects' );

	}

	// Return single
	return wpb( ':addons/get_addon', $args['slug'], $args['object'] );

}

endif;


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

if ( !function_exists( 'wpb_request__addons_activate' ) ) :

function wpb_request__addons_activate( $args ) 
{

	if ( empty( $args['slug'] ) )
		return false;

	return wpb( ':addons/activate', $args['slug'] );

}

endif;


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

if ( !function_exists( 'wpb_request__addons_deactivate' ) ) :

function wpb_request__addons_deactivate( $args ) 
{

	if ( empty( $args['slug'] ) )
		return false;

	return wpb( ':addons/deactivate', $args['slug'] );

}

endif;


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

if ( !function_exists( 'wpb_request__addons_active' ) ) :

function wpb_request__addons_active( $args ) 
{

	// Get active addons
	if ( empty( $args['slug'] ) ) {

		if ( empty( $args['objects'] ) )
			return wpb( ':addons/get_active' );

		return wpb( ':addons/get_active_objects' );

	}

	// Check if addon is active
	return wpb( ':addons/is_active', $args['slug'] );

}

endif;