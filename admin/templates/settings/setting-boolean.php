<?php


/**
 *
 *	Boolean setting template
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

		do_action( 'wpb/admin/before/setting/input' );

		?><div class="wpb-setting-choice">
			<input type="hidden" name="<?php echo wpb( 'admin/setting/input/name' ); ?>" value="" />
			<input<?php wpb( 'admin/setting/input/attr' ); ?> /> 
			<label for="<?php echo wpb( 'admin/setting/input/id' ); ?>"><?php echo wpb( 'admin/setting/data/text' ); ?></label>
		</div><?php

		do_action( 'wpb/admin/after/setting/input' );

	?></div><!-- /.wpb-setting-input --><?php


do_action( 'wpb/admin/after/setting' ); ?>


</div>