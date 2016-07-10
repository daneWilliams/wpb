<?php


namespace WPB;


/**
 *
 *	Posts widget
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 *	@see		WPB\Widget
 *
 */

class Posts_Widget extends Widget
{


	/**
	 *
	 *	Setup widget
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function __construct()
	{

		parent::__construct( 
			'posts', 
			__( 'Posts', 'wpb' ),
			array(
				'description' => __( 'Display a list of posts', 'wpb' )
			)
		);

	}


	/**
	 *
	 *	Output widget content
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$args			// Display arguments
	 *	@param		array 		$settings		// Current settings values
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_widget_content( $args, $settings = array() ) 
	{

		global $wpb_widget;

		if ( !is_array( $settings['display'] ) )
			$settings['display'] = array();

		if ( !is_array( $settings['excerpts'] ) )
			$settings['excerpts'] = array();

		// Build data
		$data = $settings;

		$data['display_title']     = in_array( 'title', $settings['display'] );
		$data['display_date']      = in_array( 'date', $settings['display'] );
		$data['display_author']    = in_array( 'author', $settings['display'] );
		$data['display_excerpt']   = in_array( 'excerpt', $settings['display'] );
		$data['display_comments']  = in_array( 'comments', $settings['display'] );
		$data['display_terms']     = in_array( 'terms', $settings['display'] );
		$data['display_thumbnail'] = in_array( 'thumbnail', $settings['display'] );
		$data['display_banner']    = in_array( 'banner', $settings['display'] );

		$data['custom_date']     = $settings['date_format'];
		$data['custom_excerpts'] = in_array( 'custom', $settings['excerpts'] );

		// Filter
		$data = apply_filters( 'wpb/widget/posts/data', $data );

		// Get template
		$wpb_widget = $data;
		$template = $this->wpb_dir( 'templates/loop.php' );

		// Build the query
		$query_args = array(
			'post_type'      => $settings['post_type'],
			'posts_per_page' => $settings['limit'],
			'orderby'        => $settings['orderby'],
			'order'          => strtoupper( $settings[ 'order' ] )
		);

		// Taxonomies
		$taxonomies = array();
		$tax_query  = array();

		foreach ( $settings as $key => $value ) {

			if ( 'tax_' != substr( $key, 0, 4 ) )
				continue;

			if ( 'any' == $value )
				continue;

			$tax_query[] = array(
				'taxonomy' => substr( $key, 4 ),
				'field'    => 'term_id',
				'terms'    => $value
			);

		}

		if ( !empty( $tax_query ) )
			$query_args['tax_query'] = $tax_query;

		// Perform the query
		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) : $query->the_post();

				wpb( 'template', 'path=widgets/posts/loop&name=' . get_post_type() . '&fallback=' . $template );

			endwhile;

		endif;

		wp_reset_query();

	}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function wpb_register_settings() 
	{

		// Title
		$this->wpb_register_title_setting();

		// Post type
		$this->wpb_register_setting( 'post_type', array(
			'type'    => 'select',
			'label'   => __( 'Post Type', 'wpb' ),
			'choices' => $this->get_post_type_choices()
		) );

		// Number of posts
		$this->wpb_register_setting( 'limit', array(
			'label' => __( 'Number of Posts', 'wpb' ),
			'attr'  => array(
				'type'  => 'number',
				'class' => 'small-text'
			)
		), 5 );

		// Order by
		$this->wpb_register_setting( 'orderby', array(
			'label'   => __( 'Order By', 'wpb' ),
			'type'    => 'select',
			'choices' => array(
				'title'      => __( 'Title', 'wpb' ),
				'date'       => __( 'Date', 'wpb' ),
				'menu_order' => __( 'Menu order', 'wpb' )
			)
		), 'menu_order' );

		$this->wpb_register_setting( 'post_type', array(
			'type'    => 'select',
			'label'   => __( 'Post Type', 'wpb' ),
			'choices' => $this->get_post_type_choices()
		) );

		// Order
		$this->wpb_register_setting( 'order', array(
			'label'   => __( 'Order', 'wpb' ),
			'type'    => 'select',
			'choices' => array(
				'asc'  => __( 'Ascending', 'wpb' ),
				'desc' => __( 'Descending', 'wpb' )
			)
		), 'asc' );

		// Taxonomies
		$taxonomies = $this->get_taxonomy_choices();

		if ( !empty( $taxonomies ) ) {

			foreach ( $taxonomies as $taxonomy => $terms ) {

				$tax    = get_taxonomy( $taxonomy );
				$labels = get_taxonomy_labels( $tax );
				$label  = ( !empty( $labels->singular_name ) ? $labels->singular_name : $labels->name );

				$this->wpb_register_setting( 'tax_' . $taxonomy, array(
					'type'    => 'select',
					'label'   => $label,
					'choices' => $terms,
					'multi'   => true
				), 'any' );

			}

		}

		// Display
		$this->wpb_register_setting( 'display', array(
			'label'   => __( 'Display', 'wpb' ),
			'type'    => 'checkbox',
			'choices' => $this->get_display_choices()
		), array( 'title' ) );

		// Date format
		$this->wpb_register_setting( 'date_format', array(
			'label' => __( 'Date Format', 'wpb' ),
			'desc'  => __( 'Use <code><strong>%1$s</strong></code> to display the date and <code><strong>%2$s</strong></code> to display the author', 'wpb' ),
			'attr'  => array(
				'placeholder' => __( 'Added on %1$s by %2$s', 'wpb' )
			)
		) );

		// Excerpts
		$this->wpb_register_setting( 'excerpts', array(
			'label'   => __( 'Excerpts', 'wpb' ),
			'type'    => 'checkbox',
			'choices' => array(
				'custom' => __( 'Only display custom excerpts', 'wpb' )
			)
		) );

		// Excerpt length
		$this->wpb_register_setting( 'excerpt_length', array(
			'label' => __( 'Excerpt Length', 'wpb' ),
			'desc'  => __( 'Note: Only applies to automatic excerpts', 'wpb' ),
			'attr'  => array(
				'type'  => 'number',
				'class' => 'small-text',
				'min'   => 1,
				'placeholder' => wpb( 'settings/get', 'excerpt/length' )
			)
		) );

	}


	/**
	 *
	 *	Get registered post types as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_post_type_choices() 
	{

		// Get cached
		$choices = $this->wpb_data( 'post_type_choices' );

		if ( $choices )
			return $choices;

		$choices = array();
		$choices['any'] = __( 'All', 'wpb' );

		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $slug => $post_type ) {

			// Skip attachment
			if ( 'attachment' == $slug )
				continue;

			$label = $post_type->label;

			if ( !empty( $post_type->labels->singular_name ) )
				$label = $post_type->labels->singular_name;

			elseif ( !empty( $post_type->labels->name ) )
				$label = $post_type->labels->name;

			$choices[ $slug ] = array(
				'label' => $label
			);

		}

		// Cache
		$this->wpb_data( 'post_type_choices', $choices );

		return $choices;

	}


	/**
	 *
	 *	Get registered taxonomies/terms as setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_taxonomy_choices() 
	{

		// Get cached
		$choices = $this->wpb_data( 'taxonomy_choices' );

		if ( $choices )
			return $choices;

		$choices  = array();
		$parents  = array();
		$children = array();

		// Get terms
		$get_terms = get_terms();

		foreach ( $get_terms as $term ) {

			// Skip nav_menu
			if ( 'nav_menu' == $term->taxonomy )
				continue;

			// Skip empty
			if ( empty( $term->count ) )
				continue;

			// Parent
			if ( empty( $term->parent ) ) {

				if ( !isset( $choices[ $term->taxonomy ] ) )
					$choices[ $term->taxonomy ]['any'] = __( 'Any', 'wpb' );

				$parents[ $term->taxonomy ][ $term->term_id ] = $term;
				continue;

			}

			// Child
			$children[ $term->taxonomy ][ $term->parent ][ $term->term_id ] = $term;
		}

		// Deal with parents
		$totals = array();

		foreach ( $parents as $taxonomy => $terms ) {

			foreach ( $terms as $term_id => $term ) {

				// Add total
				$totals[ $taxonomy ][ $term_id ] = (float) $term->count;

				// Add child counts
				if ( empty( $children[ $taxonomy ][ $term_id ] ) )
					continue;

				foreach ( $children[ $taxonomy ][ $term_id ] as $child_id => $child_term ) {

					$totals[ $taxonomy ][ $term_id ] = $totals[ $taxonomy ][ $term_id ] + $child_term->count;

				}

			}

		}

		// Deal with children
		if ( !empty( $children ) ) {

			foreach ( $children as $taxonomy => $terms ) {

				foreach ( $terms as $parent_id => $child_terms ) {

					foreach ( $child_terms as $term_id => $term ) {

						// Add total
						if ( !isset( $totals[ $taxonomy ][ $term_id ] ) )
							$totals[ $taxonomy ][ $term_id ] = (float) $term->count;

						// Remove nesting
						if ( !isset( $child_terms[ $term->parent ] ) )
							continue;

						$parent = $this->get_term_ancestor( $term );

						if ( empty( $parent->term_id ) )
							continue;

						// Update total
						if ( !isset( $totals[ $taxonomy ][ $parent->term_id ] ) )
							$totals[ $taxonomy ][ $parent->term_id ] = $parent->count;

						$totals[ $taxonomy ][ $parent->term_id ] = $totals[ $taxonomy ][ $parent->term_id ] + $term->count;

						// Update parent ID
						if ( isset( $terms[ $taxonomy ][ $parent->term_id ][ $term_id ] ) )
							$children[ $taxonomy ][ $parent->term_id ][ $term_id ] = $term;

						// Remove this instance
						unset( $children[ $taxonomy ][ $parent_id ][ $term_id ] );

					}

				}

			}

		}

		// Add as choices
		foreach ( $parents as $taxonomy => $terms ) {

			foreach ( $terms as $term_id => $term ) {

				$choices[ $taxonomy ][ $term_id ] = array(
					'label' => sprintf( '%1$s (%2$d)', $term->name, $totals[ $taxonomy ][ $term_id ] )
				);

				// Add children
				if ( empty( $children[ $taxonomy ][ $term_id ] ) )
					continue;

				foreach ( $children[ $taxonomy ][ $term_id ] as $child_id => $child_term ) {

					$choices[ $taxonomy ][ $child_id ] = array(
						'label' => sprintf( '- %1$s (%2$d)', $child_term->name, $totals[ $taxonomy ][ $child_id ] )
					);

				}

			}

		}

		// Cache
		$this->wpb_data( 'taxonomy_choices', $choices );

		return $choices;

	}


	/**
	 *
	 *	Get term ancestor
	 *
	 *	================================================================ 
	 *
	 *	@param		object		$term			// Term object
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function get_term_ancestor( $term )
	{
	
		// Get the first parent
		$parent = get_term_by( 'id', $term->term_id, $term->taxonomy );

		// Climb up hierarchy
		while ( $parent->parent != '0' ) {

			$term_id = $parent->parent;

			$parent = get_term_by( 'id', $term_id, $term->taxonomy );

		}

		return $parent;	
	
	}
	

	/**
	 *
	 *	Get display setting choices
	 *
	 *	================================================================ 
	 *
	 * 	@return 	array 						// Choices
	 *
	 *	@since		1.0.0
	 *
	 */

	public function get_display_choices() 
	{

		$choices = array();

		$choices['thumbnail'] = __( 'Display thumbnail', 'wpb' );

		if ( wpb( 'settings/get', 'banner/display' ) )
			$choices['banner'] = __( 'Display banner', 'wpb' );

		$choices['title']    = __( 'Display title', 'wpb' );
		$choices['date']     = __( 'Display date', 'wpb' );
		$choices['excerpt']  = __( 'Display excerpt', 'wpb' );
		$choices['terms']    = __( 'Display terms', 'wpb' );
		$choices['comments'] = __( 'Display comments link', 'wpb' );

		return $choices;

	}


}