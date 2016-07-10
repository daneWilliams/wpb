<?php


/**
 *
 *	Checkbox setting template
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


	<div class="wpb-setting-input">

		<input type="hidden" name="<?php echo wpb( 'admin/setting/input/name' ); ?>" value="" /><?php


		do_action( 'wpb/admin/before/setting/input' );


		?><div class="wpb-setting-choices"><?php


			foreach ( $choices as $choice ) {


			?><div<?php echo $choice->attr; ?>>

				<input type="checkbox" id="<?php echo $choice->id ?>" name="<?php echo $choice->name; ?>[]" value="<?php esc_attr_e( $choice->value ); ?>"<?php if ( !empty( $choice->checked ) ) { ?> checked="checked"<?php } ?> />
				<label for="<?php echo $choice->id; ?>"><?php echo $choice->label; ?></label><?php

				if ( !empty( $choice->desc ) ) { 

				?><div class="wpb-setting-choice-desc"><?php echo $choice->desc; ?></div><?php

				}

			?></div><?php


			}


		 ?></div><!-- /.wpb-setting-choices --><?php


		do_action( 'wpb/admin/after/setting/input' );


	?></div><!-- /.wpb-setting-input --><?php


	do_action( 'wpb/admin/after/setting' ); ?>


</div><?php


}