<?php
/**
 * LocalNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package LocalNews
 */
use LocalNews\CustomizerDefault as LND;
if ( ! defined( 'LOCAL_NEWS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	$theme_info = wp_get_theme();
	define( 'LOCAL_NEWS_VERSION', $theme_info->get( 'Version' ) );
}

if ( ! defined( 'LOCAL_NEWS_PREFIX' ) ) {
	// Replace the prefix of theme if changed.
	define( 'LOCAL_NEWS_PREFIX', 'local_news_' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function local_news_setup() {
	$lnprefix = 'local-news-';
	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	* If you're building a theme based on LocalNews, use a find and replace
	* to change 'localnews' to the name of your theme in all the template files.
	*/
	load_theme_textdomain( 'localnews', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );

	/*
	* Enable support for Post Thumbnails on posts and pages.
	*
	* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	*/
	add_theme_support( 'post-thumbnails' );
	add_image_size( $lnprefix . 'featured', 820, 545, true );
	add_image_size( $lnprefix . 'list', 600, 400, true );
	add_image_size( $lnprefix . 'thumb', 300, 200, true );
	add_image_size( $lnprefix . 'small', 150, 95, true );
	add_image_size( $lnprefix . 'grid', 400, 250, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Top Header', 'localnews' ),
			'menu-2' => esc_html__( 'Main Header', 'localnews' ),
			'menu-3' => esc_html__( 'Bottom Footer', 'localnews' )
		)
	);

	/*
	* Switch default core markup for search form, comment form, and comments
	* to output valid HTML5.
	*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			LOCAL_NEWS_PREFIX . 'custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 100,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'local_news_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function local_news_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'local_news_content_width', 640 );
}
add_action( 'after_setup_theme', 'local_news_content_width', 0 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

add_filter( 'get_the_archive_title_prefix', 'local_news_prefix_string' );
function local_news_prefix_string($prefix) {
	$archive_page_title_prefix = LND\local_news_get_customizer_option( 'archive_page_title_prefix' );
	if( $archive_page_title_prefix ) return apply_filters( 'local_news_archive_page_title_prefix', $prefix );
	return apply_filters( 'local_news_archive_page_title_prefix', false );
}

if( ! function_exists( 'localnews_set_transient' ) ) :
	/**
	 * Set transient required for theme
	 * 
	 * @package 1.0.0
	 * @since 1.0.0
	 */
	function localnews_set_transient() {
		set_transient( 'localnews_show_review_notice', 'hide', WEEK_IN_SECONDS );
	}
add_action( 'after_switch_theme', 'localnews_set_transient' );
endif;