<?php


/**
 *
 *	Admin page wrapper template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


?>


<form<?php wpb( 'admin/page/attr' ); ?>><?php


do_action( 'wpb/admin/before/page' ); ?>


	<header id="wpb-admin-page-header"<?php wpb( 'classes', '#wpb-admin-page-header' ); ?>><?php


do_action( 'wpb/admin/page/before/header' );

wpb( 'admin/page/header' );

do_action( 'wpb/admin/page/after/header' );


	?></header><!-- /#wpb-admin-page-header -->


	<main id="wpb-admin-page-content"<?php wpb( 'classes', '#wpb-admin-page-content' ); ?>><?php


do_action( 'wpb/admin/page/before/content' );

wpb( 'admin/page/content' );

do_action( 'wpb/admin/page/after/content' );


	?></main><!-- /#wpb-admin-page-content -->


	<footer id="wpb-admin-page-footer"<?php wpb( 'classes', '#wpb-admin-page-footer' ); ?>><?php


do_action( 'wpb/admin/page/before/footer' );

wpb( 'admin/page/footer' );

do_action( 'wpb/admin/page/after/footer' );


	?></footer><!-- /#wpb-admin-page-footer --><?php


do_action( 'wpb/admin/after/page' );


 ?></form><!-- /#wpb-admin-page -->