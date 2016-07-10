<?php


/**
 *
 *	Addon details template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


$addon = wpb( ':addons/get_addon', wpb( 'admin/addon' ), true ); ?>


<div<?php wpb( 'admin/addon/attr' ); ?>><?php


// Description
$desc = wpb( 'admin/addon/desc' );

if ( $desc ) { ?>


	<div class="wpb-addon-content wpb-addon-details-section">

		<div class="wpb-addon-desc"><?php echo $desc; ?></div><?php


	do_action( 'wpb/admin/addon/details/content' ); ?>


	</div><?php


} ?>


	<div class="wpb-addon-details-section">

		<h3 class="wpb-addon-details-title"><?php _e( 'Information', 'wpb' ); ?></h3>

		<table class="wpb-addon-details-table">
			<tbody>

				<tr>
					<th><?php _e( 'Version', 'wpb' ); ?></th>
					<td><?php echo wpb( 'admin/addon/ver' ); ?></td>
				</tr><?php


$website = wpb( 'admin/addon/website' );

if ( $website ) { ?>


				<tr>
					<th><?php _e( 'Wehsbite', 'wpb' ); ?></th>
					<td><?php echo $website; ?></td>
				</tr><?php


} ?>


				<tr>
					<th><?php _e( 'Author', 'wpb' ); ?></th>
					<td><?php echo wpb( 'admin/addon/author' ); ?></td>
				</tr>

			</tbody>
		</table>

	</div>


	<div class="wpb-addon-details-section">

	<h3 class="wpb-addon-details-title"><?php _e( 'Details', 'wpb' ); ?></h3>

		<table class="wpb-addon-details-table">
			<tbody>
				<tr>
					<th><?php _e( 'Status', 'wpb' ); ?></th>
					<td>
						<span class="wpb-hide-if-active"><span class="dashicons dashicons-no"></span> <?php _e( 'Inactive', 'wpb' ); ?></span>
						<span class="wpb-hide-if-inactive"><span class="dashicons dashicons-yes"></span> <?php _e( 'Active', 'wpb' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Path', 'wpb' ); ?></th>
					<td><code><?php echo $addon->data( 'path' ); ?></code></td>
				</tr>
			</tbody>
		</table>

	</div>


</div>