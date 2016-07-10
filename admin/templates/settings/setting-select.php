<?php


/**
 *
 *	Select setting template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


$choices = wpb( 'admin/setting/choices' );


if ( !empty( $choices ) ) { ?>


<div<?php wpb( 'admin/setting/attr' ); ?>><?php


	do_action( 'wpb/admin/before/setting' );


	?><div class="wpb-setting-label"><?php

		do_action( 'wpb/admin/before/setting/label' );

		?><label for="<?php echo wpb( 'admin/setting/input/id' ); ?>"><?php echo wpb( 'admin/setting/label' ); ?></label><?php

		do_action( 'wpb/admin/after/setting/label' );

	?></div><!-- /.wpb-setting-label -->


	<div class="wpb-setting-input"><?php


		do_action( 'wpb/admin/before/setting/input' );


		?><select<?php wpb( 'admin/setting/input/attr' ); ?>><?php


			foreach ( $choices as $choice ) {

			?><option value="<?php esc_attr_e( $choice->value ); ?>"<?php if ( !empty( $choice->checked ) ) { ?> selected="selected"<?php } ?>><?php echo $choice->label; ?></option><?php

			}


		 ?></select><?php


		do_action( 'wpb/admin/after/setting/input' );


	?></div><!-- /.wpb-setting-input --><?php


	do_action( 'wpb/admin/after/setting' ); ?>


</div><?php


} else {

	do_action( 'wpb/admin/setting/choices/empty' );

}