<?php


/**
 *
 *	Index page template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


// Get page
$page = wpb( 'admin/page' ); ?>


<div class="wpb-boxes">


	<div class="wpb-box-container"><?php


// Info
$info = $page->data( 'info' ); ?>


		<div class="wpb-box" id="wpb-index-info">

			<header class="wpb-box-header">
				<button class="wpb-box-toggle hide-if-no-js"><span class="screen-reader-text"><?php _e( 'Toggle information', 'wpb' ); ?></span></button>
				<h3 class="wpb-box-title"><span class="dashicons dashicons-info"></span> <?php _e( 'Information', 'wpb' ); ?></h3>
			</header><?php


if ( empty( $info ) ) { ?>


			<section class="wpb-box-content">
				<p class="wpb-box-content-empty description"><?php _e( 'No information available', 'wpb' ); ?></p>
			</section><?php


} else { ?>


			<section class="wpb-box-content wpb-box-table">

				<table cellspacing="0" cellpadding="0">

					<tbody><?php


	foreach ( $info as $slug => $data ) { ?>


						<tr class="wpb-index-info-<?php echo $slug; ?>">
							<th class="wpb-info-label"><?php echo $data['label']; ?></th>
							<td class="wpb-info-value"><?php echo $data['value']; ?></td>
						</tr><?php


	} ?>


					</tbody>

				</table>

			</section><?php


} ?>


		</div><!-- /#wpb-index-info --><?php



// Tools
$tools = $page->data( 'tools-links' ); ?>


		<div class="wpb-box" id="wpb-index-tools">

			<header class="wpb-box-header">
				<button class="wpb-box-toggle hide-if-no-js"><span class="screen-reader-text"><?php _e( 'Toggle tools', 'wpb' ); ?></span></button>
				<h3 class="wpb-box-title"><span class="dashicons dashicons-admin-tools"></span> <?php _e( 'Tools', 'wpb' ); ?></h3>
			</header><?php


if ( empty( $tools ) ) { ?>


			<section class="wpb-box-content">
				<p class="wpb-box-content-empty description"><?php _e( 'There are no tools', 'wpb' ); ?></p>
			</section><?php


} else { ?>


			<section class="wpb-box-content wpb-box-table">

				<table cellspacing="0" cellpadding="0">

					<tbody><?php


	foreach ( $tools as $link ) { ?>


						<tr>
							<th class="wpb-table-link">
								<a href="<?php echo $link['url']; ?>"><?php echo $link['text']; ?></a>
							</th>
						</tr><?php


	} ?>


					</tbody>

				</table>

			</section><?php


} ?>

			<footer class="wpb-box-footer">

				<div class="wpb-box-buttons">
					<a class="button button-secondary" href="<?php echo admin_url( 'admin.php?page=wpb-tools' ); ?>"><?php _e( 'View all tools', 'wpb' ); ?></a>
				</div>

			</footer>

		</div><!-- /#wpb-index-tools -->


	</div>


	<div class="wpb-box-container"><?php


// Addons
$addons = $page->data( 'addons-links' ); ?>


		<div class="wpb-box" id="wpb-index-addons">

			<header class="wpb-box-header">
				<button class="wpb-box-toggle hide-if-no-js"><span class="screen-reader-text"><?php _e( 'Toggle addons', 'wpb' ); ?></span></button>
				<h3 class="wpb-box-title"><span class="dashicons dashicons-admin-plugins"></span> <?php _e( 'Addons', 'wpb' ); ?></h3>
			</header><?php


if ( empty( $addons ) ) { ?>


			<section class="wpb-box-content">
				<p class="wpb-box-content-empty description"><?php _e( 'There are no active addons', 'wpb' ); ?></p>
			</section><?php


} else { ?>


			<section class="wpb-box-content wpb-box-table">

				<table cellspacing="0" cellpadding="0">

					<tbody><?php


	foreach ( $addons as $slug => $link ) { ?>


						<tr class="wpb-addon-<?php echo ( !empty( $link['active'] ) ? 'active' : 'inactive' ); ?>">
							<th class="wpb-table-link">
								<a href="<?php echo $link['url']; ?>"><?php echo $link['text']; ?></a>
							</th>
							<td class="wpb-addon-status"><?php echo $link['status']; ?></td>
						</tr><?php


	} ?>


					</tbody>

				</table>

			</section><?php


} ?>


			<footer class="wpb-box-footer">

				<div class="wpb-box-buttons">
					<a class="button button-secondary" href="<?php echo wpb( ':addons/admin_url' ); ?>"><?php _e( 'Manage addons', 'wpb' ); ?></a>
				</div>

			</footer>

		</div><!-- /#wpb-index-addons -->


	</div>


	<div class="wpb-box-container"><?php


// Settings
$settings = $page->data( 'settings-links' ); ?>


		<div class="wpb-box" id="wpb-index-settings">

			<header class="wpb-box-header">
				<button class="wpb-box-toggle hide-if-no-js"><span class="screen-reader-text"><?php _e( 'Toggle settings', 'wpb' ); ?></span></button>
				<h3 class="wpb-box-title"><span class="dashicons dashicons-admin-generic"></span> <?php _e( 'Settings', 'wpb' ); ?></h3>
			</header><?php


if ( empty( $settings ) ) { ?>


			<section class="wpb-box-content">
				<p class="wpb-box-content-empty description"><?php _e( 'There are no registered settings', 'wpb' ); ?></p>
			</section><?php


} else { ?>


			<section class="wpb-box-content wpb-box-table">

				<table cellspacing="0" cellpadding="0">

					<tbody><?php


	foreach ( $settings as $link ) { ?>


						<tr>
							<th class="wpb-table-link">
								<a href="<?php echo $link['url']; ?>"><?php echo $link['text']; ?></a>
							</th>
						</tr><?php


	} ?>


					</tbody>

				</table>

			</section><?php


} ?>


			<footer class="wpb-box-footer">

				<div class="wpb-box-buttons">
					<a class="button button-secondary" href="<?php echo admin_url( 'admin.php?page=wpb-settings' ); ?>"><?php _e( 'View all settings', 'wpb' ); ?></a>
				</div>

			</footer>

		</div><!-- /#wpb-index-settings -->

	</div>



</div>