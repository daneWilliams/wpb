<?php


/**
 *
 *	Branding template
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


global $wpb_branding; ?>


<a href="<?php echo $wpb_branding['url']; ?>" class="<?php echo implode( ' ', $wpb_branding['classes'] ); ?>"><?php


// Logo
if ( !empty( $wpb_branding['logo'] ) ) {

	echo wpb( 'image', array(
		'id'      => $wpb_branding['logo'],
		'size'    => $wpb_branding['logo-size'],
		'link'    => false,
		'wrapper' => 'span',
		'class'   => 'site-logo'
	) );

}

// Title
if ( !empty( $wpb_branding['site-title'] ) ) {

	echo '<h1 class="site-title">';
	echo $wpb_branding['site-title'];
	echo '</h1>';

}

// Tagline
if ( !empty( $wpb_branding['site-tagline'] ) ) {

	echo '<div class="site-tagline">';
	echo $wpb_branding['site-tagline'];
	echo '</div>';

} ?>


</a>