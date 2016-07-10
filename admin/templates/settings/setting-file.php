<?php


/**
 *
 *	File setting template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


?>


<div<?php wpb( 'admin/setting/attr' ); ?>><?php


do_action( 'wpb/admin/before/setting' );


	?><div class="wpb-setting-label"><?php

		do_action( 'wpb/admin/before/setting/label' );

		?><label for="<?php echo wpb( 'admin/setting/input/id' ); ?>"><?php echo wpb( 'admin/setting/label' ); ?></label><?php

		do_action( 'wpb/admin/after/setting/label' );

	?></div><!-- /.wpb-setting-label -->


	<div class="wpb-setting-input"><?php

		do_action( 'wpb/admin/before/setting/input' ); ?>


		<span class="wpb-file-preview"><span class="dashicons dashicons-media-default"></span> <span class="wpb-file-link"><?php echo wpb( 'admin/setting/file/link' ); ?></span></span>

		<button<?php wpb( 'admin/setting/button/attr' ); ?>><?php echo wpb( 'admin/setting/button/text' ); ?></button>
		<button<?php wpb( 'admin/setting/button/attr', 'slug=remove' ); ?>><?php echo wpb( 'admin/setting/button/remove/text' ); ?></button> 
		<input<?php wpb( 'admin/setting/input/attr' ); ?>>

		<span class="wpb-file-none"><?php _e( 'No file selected', 'wpb' ); ?></span><?php

		do_action( 'wpb/admin/after/setting/input' );

	?></div><!-- /.wpb-setting-input --><?php


do_action( 'wpb/admin/after/setting' ); ?>


</div>