<?php


/**
 *
 *	Icon setting template
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


		<div class="wpb-setting-icon-input">
			<input<?php wpb( 'admin/setting/input/attr' ); ?> />
			<button type="button" data-wpb-target="#<?php echo wpb( 'admin/setting/input/id' ); ?>" class="button wpb-icon-button-toggle hide-if-no-js"><?php _e( 'Select icon', 'wpb' ); ?></button>
		</div><?php


		do_action( 'wpb/admin/after/setting/input' );

	?></div><!-- /.wpb-setting-input --><?php


do_action( 'wpb/admin/after/setting' ); ?>


</div>