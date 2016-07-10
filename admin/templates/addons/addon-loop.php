<?php


/**
 *
 *	Looped addon template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


?>


<div<?php wpb( 'admin/addon/attr' ); ?>>

	<header class="wpb-addon-header">

		<span class="wpb-addon-icon"><?php echo wpb( 'admin/addon/icon' ); ?></span>

		<h4 class="wpb-addon-name">
			<a class="wpb-addon-link" href="<?php echo wpb( 'admin/addon/url' ); ?>"><?php echo wpb( 'admin/addon/name' ); ?></a>
		</h4>

		<div class="wpb-addon-meta">

			<span class="wpb-addon-version"><?php printf( __( 'Version %s', 'wpb' ), wpb( 'admin/addon/ver' ) ); ?></span><?php


// Author
$author = wpb( 'admin/addon/author' );

if ( $author ) { ?>


			<span class="wpb-addon-separator">|</span>
			<span class="wpb-addon-author"><?php printf( __( 'By <span class="wpb-addon-author-name">%s</span>', 'wpb' ), $author ); ?></span><?php


} ?>


		</div>

	</header><?php


// Description
$desc = wpb( 'admin/addon/desc' );

if ( $desc ) { ?>


	<section class="wpb-addon-content">
		<div class="wpb-addon-desc"><?php echo $desc; ?></div>
	</section><?php


} ?>


	<footer class="wpb-addon-footer">

		<div class="wpb-addon-actions">
			<span class="spinner"></span>
			<a class="wpb-hide-if-active wpb-addon-activate-link button button-primary" href="<?php echo wpb( 'admin/addon/url/activate' ); ?>" data-wpb-loading-text="<?php esc_attr_e( __( 'Activating&hellip;', 'wpb' ) ); ?>"><?php _e( 'Activate', 'wpb' ); ?></a>
			<a class="wpb-hide-if-inactive wpb-addon-deactivate-link button button-secondary" href="<?php echo wpb( 'admin/addon/url/deactivate' ); ?>" data-wpb-loading-text="<?php esc_attr_e( __( 'Deactivating&hellip;', 'wpb' ) ); ?>"><?php _e( 'Deactivate', 'wpb' ); ?></a>
		</div>

		<div class="wpb-addon-links"><?php echo wpb( 'admin/addon/links' ); ?></div>

	</footer>

</div>