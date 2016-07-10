<?php


namespace WPB;


/**
 *
 *	WPB menu walker class
 *
 *
 *	Modifies menu structure and classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */


class Walker_Nav_Menu extends \Walker_Nav_Menu
{


	/**
	 *
	 *	Modify menu items
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function display_element( $el, &$children, $max_depth, $depth = 0, $args, &$output ) 
	{

		$id = $this->db_fields['id'];

		$orig_classes = $el->classes;

		// Add selected class
		$selected = false;
		$selected_classes = array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
			'current_page_item',
			'current-page-parent',
			'current_page_parent',
			'current-page-ancestor',
			'current_page_ancestor',
			'current-post-parent',
			'current-post-ancestor'
		);

		foreach ( $selected_classes as $class ) {

			if ( in_array( $class, $el->classes ) ) {

				$el->classes[] = 'menu-item-selected';

				break;

			}

		}

		// Add depth class
		if ( !$depth ) {

			$el->classes[] = 'top-level';

		} else {

			$el->classes[] = 'sub-level';
			$el->classes[] = 'depth-' . $depth;

		}

		// Remove unwanted classes
		$unwanted_classes = array( 
			'menu-item-type-post_type', 
			'menu-item-object-page',
			'menu-item-object-post',
			'menu-item-object-custom',
			'menu-item-object-category',
			'page_item',
			'menu-item-type-custom',
			'menu-item-type-taxonomy'
		);

		$unwanted_classes = array_merge( $unwanted_classes, $selected_classes );

		$el->classes = array_diff( $el->classes, $unwanted_classes );

		parent::display_element( $el, $children, $max_depth, $depth, $args, $output );

	}


	/**
	 *
	 *	Add icons, descriptions and wrap text in <span class="menu-text" />
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) 
	{

		global $wp_query, $wpb_menu;

		$indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$a_attr  = array();
		$li_attr = array();

		// Check for schema
		$schema = false;

		if ( !empty( $wpb_menu['schema'] ) || ( !$wpb_menu && wpb( 'settings/admin/get/menu', 'schema' ) ) )
			$schema = true;

		// Check for icon
		$icon = false;

		if ( !empty( $wpb_menu['icons'] ) || ( !$wpb_menu && wpb( 'settings/addon/choice/menu', 'icons', 'custom' ) ) )
			$icon = true;

		if ( $icon )
			$icon = get_post_meta( $object->db_id, '_menu_item_wpb_icon', true );

		// Check for description
		$desc = ( !empty( $object->description ) ? esc_attr( $object->description ) : '' );

		if ( isset( $wpb_menu['desc'] ) && !$wpb_menu['desc'] )
			$desc = false;

		// Get classes
		$li_classes = ( !empty( $object->classes ) ? (array) $object->classes : array() );
		$li_classes = apply_filters( 'nav_menu_css_class', array_filter( $li_classes ), $object );

		if ( $icon ) {

			$a_classes[]  = 'menu-link-with-icon';
			$li_classes[] = 'menu-item-with-icon';

		}

		if ( $desc ) {

			$li_classes[] = 'menu-item-with-desc';
			$a_classes[]  = 'menu-link-with-desc';

		}

		if ( !$depth ) {

			$a_classes[]  = 'top-level-link';
			$li_classes[] = 'top-level';

		} else {

			$a_classes[]  = 'sub-level-link';
			$li_classes[] = 'sub-level';

		}

		// Get schema
		if ( $schema ) {

			$a_attr['itemprop'] = 'url';

		}

		// Get attributes
		$a_attr['class']  = array_unique( $a_classes );
		$li_attr['class'] = array_unique( $li_classes );

		if ( !empty( $object->attr_title ) )
			$a_attr['title'] = $object->attr_title;

		if ( !empty( $object->target ) )
			$a_attr['target'] = $object->target;

		if ( !empty( $object->xfn ) )
			$a_attr['rel'] = $object->xfn;

		if ( !empty( $object->url ) )
			$a_attr['href'] = $object->url;

		// Build HTML
		$output .= $indent;
		$output .= '<li' . wpb( 'attr', $li_attr ) .'>';

		$prepend = '<span class="menu-item-text"' . ( $schema ? ' itemprop="name"' : '' ) .'>';
		$append  = '</span>';

		if ( $desc )
			$append .= '<span class="menu-item-desc"' . ( $schema ? ' itemprop="description"' : '' ) . '>' . $desc . '</span>';

		$item_output = '';

		$item_output .= $args->before;
		$item_output .= '<a '. wpb( 'attr', $a_attr ) .'>';

		$item_output .= $args->link_before;

		if ( $icon ) {

			$icon_args = array(
				'icon' => $icon,
				'attr' => array(
					'class' => 'menu-item-icon'
				)
			);

			$item_output .= wpb( 'icon', $icon_args );
		}

		$item_output .= $prepend . apply_filters( 'the_title', $object->title, $object->ID ) . $append;

		// Toggle arrow icons
		if ( in_array( 'menu-item-has-children', $li_classes ) ) {

			if ( !empty( $wpb_menu['arrows'] ) || ( !$wpb_menu && wpb( 'settings/addon/choice/menu', 'icons', 'arrows' ) ) ) {

				$item_output .= '<span class="menu-toggle-icon">';

				if ( !$depth ) {

					$item_output .= wpb( 'icon', array( 'icon' => 'arrow-down-alt2', 'attr' => array( 'class' => 'menu-toggle-icon-closed' ) ) );
					$item_output .= wpb( 'icon', array( 'icon' => 'arrow-up-alt2', 'attr' => array( 'class' => 'menu-toggle-icon-open' ) ) );

				} else {

					$item_output .= wpb( 'icon', array( 'icon' => 'arrow-right-alt2', 'attr' => array( 'class' => 'menu-toggle-icon-closed' ) ) );
					$item_output .= wpb( 'icon', array( 'icon' => 'arrow-left-alt2', 'attr' => array( 'class' => 'menu-toggle-icon-open' ) ) );

				}

				$item_output .= '</span>';

			}

		}

		$item_output .= $args->link_after;

		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $object, $depth, $args, $current_object_id );

	}


}