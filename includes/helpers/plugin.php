<?php


/**
 *
 *	Register Branding widget
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'widgets_init', 'wpb_register_widget__branding', 5 );

if ( !function_exists( 'wpb_register_widget__branding' ) ) :

function wpb_register_widget__branding() 
{

	wpb()->file( 'public/widgets/branding/wpb-widget-branding' );
	register_widget( 'WPB\Branding_Widget' );

}

endif;


/**
 *
 *	Register Posts widget
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'widgets_init', 'wpb_register_widget__posts', 5 );

if ( !function_exists( 'wpb_register_widget__posts' ) ) :

function wpb_register_widget__posts() 
{

	wpb()->file( 'public/widgets/posts/wpb-widget-posts' );
	register_widget( 'WPB\Posts_Widget' );

}

endif;


/**
 *
 *	Register Image widget
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'widgets_init', 'wpb_register_widget__image', 5 );

if ( !function_exists( 'wpb_register_widget__image' ) ) :

function wpb_register_widget__image() 
{

	wpb()->file( 'public/widgets/image/wpb-widget-image' );
	register_widget( 'WPB\Image_Widget' );

}

endif;


/**
 *
 *	Register Copyright widget
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'widgets_init', 'wpb_register_widget__copyright', 5 );

if ( !function_exists( 'wpb_register_widget__copyright' ) ) :

function wpb_register_widget__copyright() 
{

	wpb()->file( 'public/widgets/copyright/wpb-widget-copyright' );
	register_widget( 'WPB\Copyright_Widget' );

}

endif;


/**
 *
 *	Register Breadcrumbs addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__breadcrumbs', 5 );

if ( !function_exists( 'wpb_register_addon__breadcrumbs' ) ) :

function wpb_register_addon__breadcrumbs() 
{

	wpb( 'addons/register', 'breadcrumbs', wpb()->dir( 'addons/breadcrumbs' ), array(
		'name' => __( 'Breadcrumbs', 'wpb' ),
		'desc' => __( 'Display breadcrumbs', 'wpb' ),
		'ver'  => '0.0.1',
		'icon' => 'admin-links',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Register Contact addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__contact', 5 );

if ( !function_exists( 'wpb_register_addon__contact' ) ) :

function wpb_register_addon__contact() 
{

	wpb( 'addons/register', 'contact', wpb()->dir( 'addons/contact' ), array(
		'name' => __( 'Contact', 'wpb' ),
		'desc' => __( 'Manage contact details which can be used throughout the site', 'wpb' ),
		'icon' => 'phone',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Register Font Awesome addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__font_awesome', 5 );

if ( !function_exists( 'wpb_register_addon__font_awesome' ) ) :

function wpb_register_addon__font_awesome() 
{

	wpb( 'addons/register', 'fa', wpb()->dir( 'addons/font-awesome/addon-font-awesome.php' ), array(
		'name' => __( 'Font Awesome', 'wpb' ),
		'desc' => __( 'Integrate Font Awesome icons with WPB', 'wpb' ),
		'icon' => 'flag',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Register Gravity Forms addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__gravityforms', 5 );

if ( !function_exists( 'wpb_register_addon__gravityforms' ) ) :

function wpb_register_addon__gravityforms() 
{

	wpb( 'addons/register', 'gravityforms', wpb()->dir( 'addons/gravityforms' ), array(
		'name' => __( 'Gravity Forms', 'wpb' ),
		'desc' => __( 'Integrate Gravity Forms with WPB', 'wpb' ),
		'icon' => 'feedback',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Register Social addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__social', 5 );

if ( !function_exists( 'wpb_register_addon__social' ) ) :

function wpb_register_addon__social() 
{

	wpb( 'addons/register', 'social', wpb()->dir( 'addons/social' ), array(
		'name' => __( 'Social', 'wpb' ),
		'desc' => __( 'Display social sharing buttons and profile links', 'wpb' ),
		'icon' => 'share',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Register Menu addon
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_action( 'wpb/addons/register', 'wpb_register_addon__menu', 5 );

if ( !function_exists( 'wpb_register_addon__menu' ) ) :

function wpb_register_addon__menu() 
{

	wpb( 'addons/register', 'menu', wpb()->dir( 'addons/menu' ), array(
		'name' => __( 'Menus', 'wpb' ),
		'desc' => __( 'Display enhanced navigation menus', 'wpb' ),
		'icon' => 'menu',
		'author' => 'Dane Williams',
		'author_url' => 'http://danewilliams.uk'
	) );

}

endif;


/**
 *
 *	Get default Dashicons
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/dashicons', 'wpb_get_default_dashicons', 5 );

if ( !function_exists( 'wpb_get_default_dashicons' ) ) :

function wpb_get_default_dashicons( $dashicons = array() )
{

	$dashicons[] = 'menu';
	$dashicons[] = 'admin-site';
	$dashicons[] = 'dashboard';
	$dashicons[] = 'admin-post';
	$dashicons[] = 'admin-media';
	$dashicons[] = 'admin-links';
	$dashicons[] = 'admin-page';
	$dashicons[] = 'admin-comments';
	$dashicons[] = 'admin-appearance';
	$dashicons[] = 'admin-plugins';
	$dashicons[] = 'admin-users';
	$dashicons[] = 'admin-tools';
	$dashicons[] = 'admin-settings';
	$dashicons[] = 'admin-network';
	$dashicons[] = 'admin-home';
	$dashicons[] = 'admin-generic';
	$dashicons[] = 'admin-collapse';
	$dashicons[] = 'filter';
	$dashicons[] = 'admin-customizer';
	$dashicons[] = 'admin-multisite';

	$dashicons[] = 'welcome-write-blog';
	$dashicons[] = 'welcome-add-page';
	$dashicons[] = 'welcome-view-site';
	$dashicons[] = 'welcome-widgets-menus';
	$dashicons[] = 'welcome-comments';
	$dashicons[] = 'welcome-learn-more';

	$dashicons[] = 'format-aside';
	$dashicons[] = 'format-image';
	$dashicons[] = 'format-gallery';
	$dashicons[] = 'format-video';
	$dashicons[] = 'format-status';
	$dashicons[] = 'format-quote';
	$dashicons[] = 'format-chat';
	$dashicons[] = 'format-audio';
	$dashicons[] = 'camera';
	$dashicons[] = 'images-alt';
	$dashicons[] = 'images-alt2';
	$dashicons[] = 'video-alt';
	$dashicons[] = 'video-alt2';
	$dashicons[] = 'video-alt3';

	$dashicons[] = 'media-archive';
	$dashicons[] = 'media-audio';
	$dashicons[] = 'media-code';
	$dashicons[] = 'media-default';
	$dashicons[] = 'media-document';
	$dashicons[] = 'media-interactive';
	$dashicons[] = 'media-spreadsheet';
	$dashicons[] = 'media-text';
	$dashicons[] = 'media-video';
	$dashicons[] = 'playlist-audio';
	$dashicons[] = 'playlist-video';
	$dashicons[] = 'controls-play';
	$dashicons[] = 'controls-pause';
	$dashicons[] = 'controls-forward';
	$dashicons[] = 'controls-skipforward';
	$dashicons[] = 'controls-back';
	$dashicons[] = 'controls-skipback';
	$dashicons[] = 'controls-repeat';
	$dashicons[] = 'controls-volumeon';
	$dashicons[] = 'controls-volumeoff';

	$dashicons[] = 'image-crop';
	$dashicons[] = 'image-rotate';
	$dashicons[] = 'image-rotate-left';
	$dashicons[] = 'image-rotate-right';
	$dashicons[] = 'image-flip-vertical';
	$dashicons[] = 'image-flip-horizontal';
	$dashicons[] = 'image-filter';
	$dashicons[] = 'undo';
	$dashicons[] = 'redo';

	$dashicons[] = 'editor-bold';
	$dashicons[] = 'editor-italic';
	$dashicons[] = 'editor-ul';
	$dashicons[] = 'editor-ol';
	$dashicons[] = 'editor-quote';
	$dashicons[] = 'editor-alignleft';
	$dashicons[] = 'editor-aligncenter';
	$dashicons[] = 'editor-alignright';
	$dashicons[] = 'editor-insertmore';
	$dashicons[] = 'editor-spellcheck';
	$dashicons[] = 'editor-expand';
	$dashicons[] = 'editor-contract';
	$dashicons[] = 'editor-kitchensink';
	$dashicons[] = 'editor-underline';
	$dashicons[] = 'editor-justify';
	$dashicons[] = 'editor-textcolor';
	$dashicons[] = 'editor-paste-word';
	$dashicons[] = 'editor-paste-text';
	$dashicons[] = 'editor-removeformatting';
	$dashicons[] = 'editor-video';
	$dashicons[] = 'editor-customchar';
	$dashicons[] = 'editor-outdent';
	$dashicons[] = 'editor-indent';
	$dashicons[] = 'editor-help';
	$dashicons[] = 'editor-strikethrough';
	$dashicons[] = 'editor-unlink';
	$dashicons[] = 'editor-rtl';
	$dashicons[] = 'editor-break';
	$dashicons[] = 'editor-code';
	$dashicons[] = 'editor-paragraph';
	$dashicons[] = 'editor-table';

	$dashicons[] = 'align-left';
	$dashicons[] = 'align-right';
	$dashicons[] = 'align-center';
	$dashicons[] = 'align-none';
	$dashicons[] = 'lock';
	$dashicons[] = 'unlock';
	$dashicons[] = 'calendar';
	$dashicons[] = 'calendar-alt';
	$dashicons[] = 'visibility';
	$dashicons[] = 'hidden';
	$dashicons[] = 'post-status';
	$dashicons[] = 'edit';
	$dashicons[] = 'trash';
	$dashicons[] = 'sticky';

	$dashicons[] = 'external';
	$dashicons[] = 'arrow-up';
	$dashicons[] = 'arrow-down';
	$dashicons[] = 'arrow-right';
	$dashicons[] = 'arrow-left';
	$dashicons[] = 'arrow-up-alt';
	$dashicons[] = 'arrow-down-alt';
	$dashicons[] = 'arrow-right-alt';
	$dashicons[] = 'arrow-left-alt';
	$dashicons[] = 'arrow-up-alt2';
	$dashicons[] = 'arrow-down-alt2';
	$dashicons[] = 'arrow-right-alt2';
	$dashicons[] = 'arrow-left-alt2';
	$dashicons[] = 'sort';
	$dashicons[] = 'leftright';
	$dashicons[] = 'randomize';
	$dashicons[] = 'list-view';
	$dashicons[] = 'exerpt-view';
	$dashicons[] = 'grid-view';

	$dashicons[] = 'share';
	$dashicons[] = 'share-alt';
	$dashicons[] = 'share-alt2';
	$dashicons[] = 'twitter';
	$dashicons[] = 'rss';
	$dashicons[] = 'email';
	$dashicons[] = 'email-alt';
	$dashicons[] = 'facebook';
	$dashicons[] = 'facebook-alt';
	$dashicons[] = 'googleplus';
	$dashicons[] = 'networking';

	$dashicons[] = 'hammer';
	$dashicons[] = 'art';
	$dashicons[] = 'migrate';
	$dashicons[] = 'performance';
	$dashicons[] = 'universal-access';
	$dashicons[] = 'universal-access-alt';
	$dashicons[] = 'tickets';
	$dashicons[] = 'nametag';
	$dashicons[] = 'clipboard';
	$dashicons[] = 'heart';
	$dashicons[] = 'megaphone';
	$dashicons[] = 'schedule';

	$dashicons[] = 'wordpress';
	$dashicons[] = 'wordpress-alt';
	$dashicons[] = 'pressthis';
	$dashicons[] = 'update';
	$dashicons[] = 'screenoptions';
	$dashicons[] = 'info';
	$dashicons[] = 'cart';
	$dashicons[] = 'feedback';
	$dashicons[] = 'cloud';
	$dashicons[] = 'translation';

	$dashicons[] = 'tag';
	$dashicons[] = 'category';

	$dashicons[] = 'archive';
	$dashicons[] = 'tagcloud';
	$dashicons[] = 'text';

	$dashicons[] = 'yes';
	$dashicons[] = 'no';
	$dashicons[] = 'no-alt';
	$dashicons[] = 'plus';
	$dashicons[] = 'plus-alt';
	$dashicons[] = 'minus';
	$dashicons[] = 'dismiss';
	$dashicons[] = 'marker';
	$dashicons[] = 'star-filled';
	$dashicons[] = 'star-half';
	$dashicons[] = 'star-empty';
	$dashicons[] = 'flag';
	$dashicons[] = 'warning';

	$dashicons[] = 'location';
	$dashicons[] = 'location-alt';
	$dashicons[] = 'vault';
	$dashicons[] = 'shield';
	$dashicons[] = 'shield-alt';
	$dashicons[] = 'sos';
	$dashicons[] = 'search';
	$dashicons[] = 'slides';
	$dashicons[] = 'analytics';
	$dashicons[] = 'chart-pie';
	$dashicons[] = 'chart-bar';
	$dashicons[] = 'chart-line';
	$dashicons[] = 'chart-area';
	$dashicons[] = 'groups';
	$dashicons[] = 'businessman';
	$dashicons[] = 'id';
	$dashicons[] = 'id-alt';
	$dashicons[] = 'products';
	$dashicons[] = 'awards';
	$dashicons[] = 'forms';
	$dashicons[] = 'testimonial';
	$dashicons[] = 'portfolio';
	$dashicons[] = 'book';
	$dashicons[] = 'book-alt';
	$dashicons[] = 'download';
	$dashicons[] = 'upload';
	$dashicons[] = 'backup';
	$dashicons[] = 'clock';
	$dashicons[] = 'lightbulb';
	$dashicons[] = 'microphone';
	$dashicons[] = 'desktop';
	$dashicons[] = 'tablet';
	$dashicons[] = 'smartphone';
	$dashicons[] = 'phone';
	$dashicons[] = 'index-card';
	$dashicons[] = 'carrot';
	$dashicons[] = 'building';
	$dashicons[] = 'store';
	$dashicons[] = 'album';
	$dashicons[] = 'palmtree';
	$dashicons[] = 'tickets-alt';
	$dashicons[] = 'money';
	$dashicons[] = 'smiley';
	$dashicons[] = 'thumbs-up';
	$dashicons[] = 'thumbs-down';
	$dashicons[] = 'layout';

	return $dashicons;

}

endif;


/**
 *
 *	Default to Dashicons prefix
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/classes/prefix/icon', 'wpb_default_icon_prefix', 20 );

if ( !function_exists( 'wpb_default_icon_prefix' ) ) :

function wpb_default_icon_prefix( $prefix = '' )
{

	if ( !$prefix )
		return 'dashicons';

	return $prefix;

}

endif;


/**
 *
 *	Get default dashicons headings
 *
 *	================================================================ 
 *
 *	@since		1.0.0
 *
 */

add_filter( 'wpb/dashicons/headings', 'wpb_get_default_dashicons_headings', 5 );

if ( !function_exists( 'wpb_get_default_dashicons_headings' ) ) :

function wpb_get_default_dashicons_headings( $headings = array() )
{

	$headings['menu']               = __( 'Admin Menu', 'wpb' );
	$headings['welcome-write-blog'] = __( 'Welcome Screen', 'wpb' );
	$headings['format-aside']       = __( 'Post Formats', 'wpb' );
	$headings['media-archive']      = __( 'Media', 'wpb' );
	$headings['image-crop']         = __( 'Image Editing', 'wpb' );
	$headings['editor-bold']        = __( 'TinyMCE', 'wpb' );
	$headings['align-left']         = __( 'Posts Screen', 'wpb' );
	$headings['external']           = __( 'Sorting', 'wpb' );
	$headings['share']              = __( 'Social', 'wpb' );
	$headings['hammer']             = __( 'WordPress.org Specific: Jobs, Profiles, WordCamps', 'wpb' );
	$headings['wordpress']          = __( 'Products', 'wpb' );
	$headings['tag']                = __( 'Taxonomies', 'wpb' );
	$headings['archive']            = __( 'Widgets', 'wpb' );
	$headings['yes']                = __( 'Notifications', 'wpb' );
	$headings['location']           = __( 'Misc', 'wpb' );

	return $headings;

}

endif;