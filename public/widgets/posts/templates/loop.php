<?php


/**
 *
 *	Looped post template
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */


global $post, $wpb_widget; ?>


<article<?php wpb( 'classes', 'post', get_post_class( 'loop-content loop-widget-content' ) ); ?>><?php


do_action( 'wpb/before/widget/loop/content' );


// Thumbnail/banner
if ( !empty( $wpb_widget['display_banner'] ) ) {

	echo wpb( 'image/banner' );

} elseif ( !empty( $wpb_widget['display_thumbnail'] ) ) {

	echo wpb( 'image/thumbnail', 'size=thumbnail' );

} ?>


	<header class="content-header loop-header"><?php


do_action( 'wpb/before/widget/loop/header' );


// Title
if ( !empty( $wpb_widget['display_title'] ) ) { ?>


		<h5 class="content-title loop-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h5><?php


}



// Date/author
if ( !empty( $wpb_widget['display_date'] ) ) { ?>


		<div class="content-meta">
			<p class="content-date"><?php echo wpb( 'content/date', ( !empty( $wpb_widget['custom_date'] ) ? 'text=' . $wpb_widget['custom_date'] : '' ) ); ?></p>
		</div><?php


}


do_action( 'wpb/after/widget/loop/header' ); ?>


	</header><!-- /.loop-header --><?php


// Get excerpt
$excerpt = ( !empty( $wpb_widget['display_excerpt'] ) ? get_the_excerpt() : false );

if ( !empty( $wpb_widget['display_excerpt'] ) && !empty( $wpb_widget['custom_excerpts'] ) )
	$excerpt = has_excerpt();

if ( $excerpt ) { ?>


	<section class="content-entry loop-entry"><?php


	do_action( 'wpb/before/widget/loop/entry' );


	if ( empty( $wpb_widget['excerpt_length'] ) )
		the_excerpt();

	else
		echo wp_trim_words( get_the_content(), $wpb_widget['excerpt_length'], wpb( 'content/excerpt-more' ) );


	do_action( 'wpb/after/widget/loop/entry' ); ?>


	</section><!-- /.loop-entry --><?php


}


// Get comments link
$comments = ( !empty( $wpb_widget['display_comments'] ) ? wpb( 'content/comments-link' ) : false );

// Get terms list
$terms = ( !empty( $wpb_widget['display_terms'] ) ? wpb( 'content/terms' )  : false );

// Footer
if ( $comments || $terms ) { ?>


	<footer class="content-footer loop-footer"><?php


	do_action( 'wpb/before/widget/loop/footer' ); ?>


		<div class="content-meta"><?php


	if ( $comments ) { ?>


			<p class="comments-link"><?php echo $comments; ?></p><?php


	}

	if ( $terms ) { ?>


			<p class="terms-list"><?php echo $terms; ?></p><?php


	} ?>


		</div><?php


	do_action( 'wpb/after/widget/loop/footer' ); ?>


	</footer><!-- /.loop-footer --><?php


}


do_action( 'wpb/after/widget/loop/content' ); ?>


</article>