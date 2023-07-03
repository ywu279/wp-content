<?php
/**
 * Handle the wigets files and hooks
 * 
 * @package LocalNews
 * @since 1.0.0
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function local_news_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'localnews' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// left sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Left Sidebar', 'localnews' ),
			'id'            => 'left-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// header toggle sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Toggle Sidebar', 'localnews' ),
			'id'            => 'header-toggle-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// header ads banner sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Ads Banner Sidebar', 'localnews' ),
			'id'            => 'ads-banner-sidebar',
			'description'   => esc_html__( 'Add widgets suitable for displaying ads here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// front right sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Frontpage - Middle Right Sidebar', 'localnews' ),
			'id'            => 'front-right-sidebar',
			'description'   => esc_html__( 'Add widgets suitable for middle right here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// front left sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Frontpage - Middle Left Sidebar', 'localnews' ),
			'id'            => 'front-left-sidebar',
			'description'   => esc_html__( 'Add widgets suitable for middle left here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// footer sidebar - column 1
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar - Column 1', 'localnews' ),
			'id'            => 'footer-sidebar--column-1',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// footer sidebar - column 2
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar - Column 2', 'localnews' ),
			'id'            => 'footer-sidebar--column-2',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// footer sidebar - column 3
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar - Column 3', 'localnews' ),
			'id'            => 'footer-sidebar--column-3',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// footer sidebar - column 4
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar - Column 4', 'localnews' ),
			'id'            => 'footer-sidebar--column-4',
			'description'   => esc_html__( 'Add widgets here.', 'localnews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Register custom widgets
    register_widget( 'Local_News_Widget_Title_Widget' ); // custom widget title
	register_widget( 'Local_News_Posts_List_Widget' ); // post lists widget
	register_widget( 'Local_News_Category_Collection_Widget' ); // category collection widget
	register_widget( 'Local_News_Author_Info_Widget' ); // author info widget
	register_widget( 'Local_News_Banner_Ads_Widget' ); // banner ad widget
	register_widget( 'Local_News_Popular_Posts_Widget' ); // popular posts widget
	register_widget( 'Local_News_Tabbed_Posts_Widget' ); // tabbed posts widget
	register_widget( 'Local_News_Carousel_Widget' ); // carousel widget
	register_widget( 'Local_News_Social_Icons_Widget' ); // social icons widget
}
add_action( 'widgets_init', 'local_news_widgets_init' );

// includes files
require LOCAL_NEWS_INCLUDES_PATH .'widgets/widget-fields.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/category-collection.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/posts-list.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/author-info.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/banner-ads.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/popular-posts.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/tabbed-posts.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/carousel.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/social-icons.php';
require LOCAL_NEWS_INCLUDES_PATH .'widgets/widget-title.php';

function local_news_widget_scripts($hook) {
    if( $hook !== "widgets.php" ) {
        return;
    }
    wp_enqueue_style( 'local-news-widget', get_template_directory_uri() . '/inc/widgets/assets/widgets.css', array(), LOCAL_NEWS_VERSION );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/lib/fontawesome/css/all.min.css', array(), '5.15.3', 'all' );
	wp_enqueue_media();
	wp_enqueue_script( 'local-news-widget', get_template_directory_uri() . '/inc/widgets/assets/widgets.js', array( 'jquery' ), LOCAL_NEWS_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'local_news_widget_scripts' );