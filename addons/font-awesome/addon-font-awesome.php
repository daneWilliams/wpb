<?php


namespace WPB;


/**
 *
 *	Font Awesome addon
 *
 *	================================================================ 
 *
 *	@version	1.0.0
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class FA_Addon extends Addon
{


	/**
	 *
	 *	Setup the addon
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$slug			// Addon slug
	 *	@param		array 		$data			// Addon data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $slug, $data = array() )
	{

		parent::__construct( $slug, $data );

		// Font Awesome version
		$this->data( 'fa_version', '4.6.1' );

		// Icon prefix
		$this->data( 'icon_prefix', 'fa' );

		// CDN paths
		$this->data( 'cdn_stylesheet', 'https://maxcdn.bootstrapcdn.com/font-awesome/[fa_version]/css/font-awesome.min.css' );

	}


	/**
	 *
	 *	Fired when the addon is initialised
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function init() 
	{

		// Update icon prefix
		add_filter( 'wpb/classes/prefix/icon', array( $this, 'set_icon_prefix' ), 999 );

		// Replace Dashicons
		add_filter( 'wpb/classes/single/icon', array( $this, 'replace_dashicons' ), 10, 2 );

		// Add assets
		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ), 1 );

	}


	/**
	 *
	 *	Tell WPB there are settings
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function has_settings()
	{

		return true;
	
	}


	/**
	 *
	 *	Register settings
	 *
	 *	================================================================ 
	 *
	 *	@see		WPB\Addon::register_setting()
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function register_settings()
	{

		// Stylesheet
		$this->register_setting( 'stylesheet', array(
			'type'    => 'radio',
			'label'   => __( 'Stylesheet', 'wpb' ),
			'choices' => array(
				'cdn'   => __( 'Use <abbr title="Content Delivery Network">CDN</abbr> to load cached stylesheet', 'wpb' ),
				'local' => __( 'Load stylesheet manually', 'wpb' )
			)
		), 'cdn' );

		// Use prefix
		$this->register_setting( 'use_prefix', array(
			'label' => __( 'Prefix', 'wpb' ),
			'type'  => 'boolean',
			'text'  => __( 'Use Font Awesome prefix', 'wpb' ),
			'desc'  => sprintf(
				__( 'If enabled, all <code>%1$s</code> requests without a prefix explicitly defined will use the <code>%2$s-*</code> class prefix', 'wpb' ),
				'icon',
				'fa'
			)
		), true );

		// Replace Dashicons
		$this->register_setting( 'replace_dashicons', array(
			'label' => __( 'Dashicons', 'wpb' ),
			'type'  => 'boolean',
			'text'  => __( 'Replace Dashicons', 'wpb' ),
			'desc'  => sprintf(
				__( 'If enabled, Dashicons will be replaced with Font Awesome alternatives. Does not apply to admin pages.', 'wpb' ),
				'icon/*'
			)
		), true );
	
	}


	/**
	 *
	 *	Add assets
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function add_assets()
	{

		// Stylesheet
		if ( $this->choice( 'stylesheet', 'cdn' ) ) {

			$stylesheet = str_replace( '[fa_version]', $this->data( 'fa_version' ), $this->data( 'cdn_stylesheet' ) );

			wp_register_style( 'wpb-fa', $stylesheet, array(), $this->data( 'fa_version' ) );
			wp_enqueue_style( 'wpb-fa' );

			// Icon size
			wp_add_inline_style( 'wpb-fa', '.fa { font-size: 14px; }' );

		}

	}


	/**
	 *
	 *	Set icon prefix
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function set_icon_prefix( $prefix = '' )
	{

		$fa_prefix = $this->data( 'icon_prefix' );

		if ( $this->choice( 'use_prefix' ) )
			return $fa_prefix;

		if ( $this->choice( 'replace_dashicons' ) && 'dashicons' == $prefix )
			return $fa_prefix;

		return $prefix;

	}


	/**
	 *
	 *	Replace Dashicons
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function replace_dashicons( $class, $prefix = '' )
	{

		if ( !$this->choice( 'replace_dashicons' ) )
			return $class;

		// Get mapped Dashicons
		$dashicons = $this->dashicons();

		if ( isset( $dashicons[ $class ] ) )
			return $dashicons[ $class ];

		return $class;

	}


	/**
	 *
	 *	Get mapped Dashicons
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function dashicons()
	{

		$dashicons = $this->data( 'dashicons' );

		if ( !empty( $dashicons ) )
			return $dashicons;

		$dashicons = array(
			'menu'                    => 'bars',
			'admin-home'              => 'home',
			'admin-site'              => 'globe',
			'admin-post'              => 'thumb-tack',
			'admin-media'             => 'music',
			'admin-links'             => 'link',
			'admin-page'              => 'file',
			'admin-comments'          => 'comments',
			'admin-appearance'        => 'paint-brush',
			'admin-plugins'           => 'plug',
			'admin-users'             => 'user',
			'admin-tools'             => 'wrench',
			'admin-settings'          => 'sliders',
			'admin-network'           => 'key',
			'admin-generic'           => 'cog',
			'admin-collapse'          => 'chevron-circle-left',
			'admin-multisite'         => 'sitemap',
			'welcome-write-blog'      => 'pencil',
			'welcome-add-page'        => 'plus',
			'welcome-widgets-menus'   => 'list',
			'welcome-view-site'       => 'eye',
			'welcome-comments'        => 'comments',
			'welcome-learn-more'      => 'graduation-cap',
			'format-aside'            => 'file-text',
			'format-image'            => 'image',
			'format-gallery'          => 'image',
			'format-video'            => 'film',
			'format-status'           => 'comment',
			'format-quote'            => 'quote-left',
			'format-chat'             => 'comments',
			'format-audio'            => 'music',
			'images-alt'              => 'image',
			'video-alt'               => 'video-camera',
			'video-alt2'              => 'video-camera',
			'video-alt3'              => 'youtube-play',
			'media-archive'           => 'file-archive-o',
			'media-audio'             => 'file-sound-o',
			'media-code'              => 'file-code-o',
			'media-default'           => 'file-o',
			'media-document'          => 'file-text-o',
			'media-interactive'       => 'file-powerpoint-o',
			'media-spreadsheet'       => 'file-excel-o',
			'media-video'             => 'file-video-o',
			'playlist-audio'          => 'music',
			'playlist-video'          => 'youtube-play',
			'controls-play'           => 'play',
			'controls-pause'          => 'pause',
			'controls-forward'        => 'forward',
			'controls-skipforward'    => 'fast-forward',
			'controls-back'           => 'backward',
			'controls-skipbackward'   => 'fast-backward',
			'controls-repeat'         => 'retweet',
			'controls-volumeon'       => 'volume-up',
			'controls-volumeoff'      => 'volume-off',
			'image-crop'              => 'crop',
			'image-rotate-left'       => 'rotate-left',
			'image-rotate-right'      => 'rotate-right',
			'image-flip-vertical'     => 'arrows-v',
			'image-flip-horizontal'   => 'arrows-h',
			'redo'                    => 'refresh',
			'editor-bold'             => 'bold',
			'editor-italic'           => 'italic',
			'editor-ul'               => 'list-ul',
			'editor-ol'               => 'list-ol',
			'editor-alignleft'        => 'align-left',
			'editor-alignright'       => 'align-right',
			'editor-aligncenter'      => 'align-center',
			'editor-insert-more'      => 'ellipsis-h',
			'editor-spellcheck'       => 'check',
			'editor-expand'           => 'expand',
			'editor-contract'         => 'compress',
			'editor-kitchensink'      => 'tasks',
			'editor-underline'        => 'underline',
			'editor-justify'          => 'align-justify',
			'editor-textcolor'        => 'eyedroppper',
			'editor-paste-word'       => 'paste',
			'editor-paste-text'       => 'paste',
			'editor-removeformatting' => 'eraser',
			'editor-video'            => 'film',
			'editor-customchar'       => 'font',
			'editor-outdent'          => 'outdent',
			'editor-indent'           => 'indent',
			'editor-help'             => 'question-circle',
			'editor-strikethrough'    => 'strikethrough',
			'editor-unlink'           => 'unlink',
			'editor-rtl'              => 'paragraph',
			'editor-break'            => 'level-down',
			'editor-code'             => 'code',
			'editor-paragraph'        => 'paragraph',
			'align-none'              => 'align-justify',
			'calendar-alt'            => 'calendar',
			'visibility'              => 'eye',
			'post-status'             => 'thumb-tack',
			'external'                => 'external-link',
			'arrow-up-alt'            => 'arrow-up',
			'arrow-down-alt'          => 'arrow-down',
			'arrow-right-alt'         => 'arrow-right',
			'arrow-left-alt'          => 'arrow-left',
			'arrow-up-alt2'           => 'angle-up',
			'arrow-down-alt2'         => 'angle-down',
			'arrow-right-alt2'        => 'angle-right',
			'arrow-left-alt2'         => 'angle-left',
			'leftright'               => 'arrows-h',
			'randomize'               => 'random',
			'list-view'               => 'list',
			'exerpt-view'             => 'th-list',
			'grid-view'               => 'th-large',
			'share'                   => 'share-alt',
			'share-alt2'              => 'share',
			'email'                   => 'envelope',
			'email-alt'               => 'envelope-o',
			'facebook-alt'            => 'facebook-official',
			'googleplus'              => 'google-plus',
			'networking'              => 'sitemap',
			'hammer'                  => 'gavel',
			'art'                     => 'paint-brush',
			'migrate'                 => 'sign-out',
			'performance'             => 'dashboard',
			'universal-access'        => 'child',
			'universal-access-alt'    => 'child',
			'tickets'                 => 'ticket',
			'nametag'                 => 'barcode',
			'megaphone'               => 'bullhorn',
			'schedule'                => 'calendar',
			'wordpress-alt'           => 'wordpress',
			'pressthis'               => 'wordpress',
			'update'                  => 'refresh',
			'screen-options'          => 'th-large',
			'cart'                    => 'shopping-cart',
			'feedback'                => 'list-alt',
			'translation'             => 'language',
			'category'                => 'folder',
			'tagcloud'                => 'tags',
			'text'                    => 'file-text',
			'yes'                     => 'check',
			'no'                      => 'times',
			'no-alt'                  => 'exclamation',
			'plus-alt'                => 'plus-circle',
			'dismiss'                 => 'times-circle',
			'marker'                  => 'circle-o',
			'star-filled'             => 'star',
			'star-empty'              => 'star-o',
			'location'                => 'map-marker',
			'location-alt'            => 'location-arrow',
			'vault'                   => 'lock',
			'shield-alt'              => 'shield',
			'sos'                     => 'life-ring',
			'slides'                  => 'image',
			'analytics'               => 'line-chart',
			'chart-pie'               => 'pie-chart',
			'chart-bar'               => 'bar-chart',
			'chart-line'              => 'area-chart',
			'chart-area'              => 'area-chart',
			'groups'                  => 'users',
			'businessman'             => 'user'	,
			'id'                      => 'user',
			'id-alt'                  => 'user',
			'products'                => 'shopping-cart',
			'awards'                  => 'trophy',
			'forms'                   => 'tasks',
			'testimonial'             => 'comment',
			'portfolio'               => 'briefcase',
			'book-alt'                => 'book',
			'backup'                  => 'history',
			'lightbulb'               => 'lightbulb-o',
			'smartphone'              => 'mobile',
			'index-card'              => 'folder-o',
			'store'                   => 'home',
			'album'                   => 'headphones',
			'palmtree'                => 'tree',
			'tickets-alt'             => 'ticket',
			'smiley'                  => 'smile-o'
		);

		// Filter
		$dashicons = apply_filters( 'wpb/dashicons/font-awesome', $dashicons );

		// Cache
		$this->data( 'dashicons', $dashicons );

		return $dashicons;

	}



}