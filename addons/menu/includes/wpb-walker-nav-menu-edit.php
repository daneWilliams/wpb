<?php


namespace WPB;


/**
 *
 *	WPB menu edit walker class
 *
 *
 *	Modifies menu structure and classes
 *
 *	================================================================
 *
 *	@since 		1.0.0
 *
 */


class Walker_Nav_Menu_Edit extends \Walker_Nav_Menu_Edit 
{


	// Start the element output
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) 
	{

		$item_output = '';

		parent::start_el( $item_output, $item, $depth, $args, $id );

		// Add fields
		$addon = wpb( 'addons/get/menu' );

		if ( $addon ) {

			// Icon
			$icon_field = $addon->get_menu_item_icon_field_html( $item, $depth, $args );

			if ( $icon_field ) {

				$item_output = preg_replace(
					'/(?=<p[^>]+class="[^"]*field-move)/',
					$icon_field,
					$item_output
				);

			}

		}

		$output .= $item_output;

	}


}