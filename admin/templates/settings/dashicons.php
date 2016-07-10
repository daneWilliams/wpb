<?php


/**
 *
 *	Dashicons menu
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


$dashicons = wpb( 'dashicons' );
$headings  = wpb( 'dashicons/headings' );


if ( !empty( $dashicons ) ) {

	$dashicons_count = count( $dashicons );
	$dashicons_i     = 0;

	$headings_count = count( $headings );
	$headings_i     = 0; 

	$buttons_open = false;
	$section_open = false; ?>


<div id="wpb-dashicons-menu" class="wpb-dashicons-menu">

	<div class="wpb-dashicons-container"><?php


	foreach ( $dashicons as $i => $dashicon ) {

		$dashicons_i++;

		if ( !empty( $headings ) && isset( $headings[ $dashicon ] ) ) {

			if ( $buttons_open ) { ?></div><!-- /.wpb-dashicons-buttons --><?php }
			if ( $section_open ) { ?></div><!-- /.wpb-dashicons-section --><?php }

			$section_open = true;
			$buttons_open = true;
			$headings_i++; ?>


		<div class="wpb-dashicons-section">
			<div class="wpb-dashicons-heading"><?php echo $headings[ $dashicon ]; ?></div>
			<div class="wpb-dashicons-buttons"><?php


		} ?>


		<button data-wpb-icon="<?php esc_attr_e( $dashicon ); ?>" type="button" class="button wpb-dashicons-button"><span class="dashicons dashicons-<?php esc_attr_e( $dashicon ); ?>"></span></button><?php


	}

	if ( $buttons_open ) { ?></div><!-- /.wpb-dashicons-buttons --><?php }
	if ( $section_open ) { ?></div><!-- /.wpb-dashicons-section --><?php } ?>


	</div><!-- /.wpb-dashicons-container -->

</div><!-- /#wpb-dashicons-menu --><?php


}