<?php
/**
 * Header hooks and functions
 * 
 * @package LocalNews
 * @since 1.0.0
 */
use LocalNews\CustomizerDefault as LND;
 if( ! function_exists( 'local_news_header_site_branding_part' ) ) :
    /**
     * Header site branding element
     * 
     * @since 1.0.0
     */
     function local_news_header_site_branding_part() {
         ?>
            <div class="site-branding">
                <?php
                    the_custom_logo();
                    if ( is_front_page() && is_home() ) :
                ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php
                    else :
                ?>
                        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                    endif;
                    $local_news_description = get_bloginfo( 'description', 'display' );
                    if ( $local_news_description || is_customize_preview() ) :
                ?>
                    <p class="site-description" itemprop="description"><?php echo apply_filters( 'local_news_bloginfo_description', esc_html( $local_news_description ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php endif; ?>
            </div><!-- .site-branding -->
         <?php
     }
    add_action( 'local_news_header__site_branding_section_hook', 'local_news_header_site_branding_part', 10 );
 endif;

 if( ! function_exists( 'local_news_header_ads_banner_part' ) ) :
    /**
     * Header ads banner element
     * 
     * @since 1.0.0
     */
     function local_news_header_ads_banner_part() {
        if( ! LND\local_news_get_multiselect_tab_option( 'header_ads_banner_responsive_option' ) ) return;
        $header_ads_banner_type = LND\local_news_get_customizer_option( 'header_ads_banner_type' );
        ?>
            <div class="ads-banner">
                <?php
                    switch( $header_ads_banner_type ) {
                        case 'sidebar' : dynamic_sidebar( 'ads-banner-sidebar' );
                                    break;
                        default: $header_ads_banner_custom_image = LND\local_news_get_customizer_option( 'header_ads_banner_custom_image' );
                            $header_ads_banner_custom_url = LND\local_news_get_customizer_option( 'header_ads_banner_custom_url' );
                            $header_ads_banner_custom_target = LND\local_news_get_customizer_option( 'header_ads_banner_custom_target' );
                            if( ! empty( $header_ads_banner_custom_image ) ) :
                            ?>
                                <a href="<?php echo esc_url( $header_ads_banner_custom_url ); ?>" target="<?php echo esc_html( $header_ads_banner_custom_target ); ?>"><img src="<?php echo esc_url( wp_get_attachment_url( $header_ads_banner_custom_image ) ); ?>"></a>
                            <?php
                            endif;
                    }
                ?>        
            </div><!-- .ads-banner -->
        <?php
     }
    add_action( 'local_news_header__site_branding_section_hook', 'local_news_header_ads_banner_part', 20 );
 endif;

 if( ! function_exists( 'local_news_header_sidebar_toggle_part' ) ) :
    /**
     * Header sidebar toggle element
     * 
     * @since 1.0.0
     */
     function local_news_header_sidebar_toggle_part() {
         if( ! LND\local_news_get_customizer_option( 'header_sidebar_toggle_option' ) ) return;
         ?>
            <div class="sidebar-toggle-wrap">
                <a class="sidebar-toggle-trigger" href="javascript:void(0);">
                    <div class="ln_sidetoggle_menu_burger">
                      <span></span>
                      <span></span>
                      <span></span>
                  </div>
                </a>
                <div class="sidebar-toggle dark_bk hide">
                  <div class="ln-container">
                    <div class="row">
                      <?php dynamic_sidebar( 'header-toggle-sidebar' ); ?>
                    </div>
                  </div>
                </div>
            </div>
         <?php
     }
    add_action( 'local_news_header__menu_section_hook', 'local_news_header_sidebar_toggle_part', 30 );
 endif;

 if( ! function_exists( 'local_news_header_menu_part' ) ) :
    /**
     * Header menu element
     * 
     * @since 1.0.0
     */
    function local_news_header_menu_part() {
      ?>
        <nav id="site-navigation" class="main-navigation <?php echo esc_attr( 'hover-effect--' . LND\local_news_get_customizer_option( 'header_menu_hover_effect' ) ); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <div id="ln_menu_burger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="menu_txt"><?php esc_html_e( 'Menu', 'localnews' ); ?></span></button>
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-2',
                        'menu_id'        => 'header-menu',
                    )
                );
            ?>
        </nav><!-- #site-navigation -->
      <?php
    }
    add_action( 'local_news_header__menu_section_hook', 'local_news_header_menu_part', 40 );
 endif;

 if( ! function_exists( 'local_news_header_search_part' ) ) :
   /**
    * Header search element
    * 
    * @since 1.0.0
    */
    function local_news_header_search_part() {
        if( ! LND\local_news_get_customizer_option( 'header_search_option' ) ) return;
        ?>
            <div class="search-wrap">
                <button class="search-trigger">
                    <i class="fas fa-search"></i>
                </button>
                <div class="search-form-wrap hide">
                    <?php echo get_search_form(); ?>
                </div>
            </div>
        <?php
    }
   add_action( 'local_news_header__menu_section_hook', 'local_news_header_search_part', 50 );
endif;

if( ! function_exists( 'local_news_header_theme_mode_icon_part' ) ) :
    /**
     * Header theme mode element
     * 
     * @since 1.0.0
     */
     function local_news_header_theme_mode_icon_part() {
        if( ! LND\local_news_get_customizer_option( 'header_theme_mode_toggle_option' ) ) return;
        ?>
            <div class="mode_toggle_wrap">
                <input class="mode_toggle" type="checkbox">
            </div>
        <?php
     }
    add_action( 'local_news_header__menu_section_hook', 'local_news_header_theme_mode_icon_part', 60 );
 endif;

 if( ! function_exists( 'local_news_ticker_news_part' ) ) :
    /**
     * Ticker news element
     * 
     * @since 1.0.0
     */
     function local_news_ticker_news_part() {
        if( ! is_front_page() || ! LND\local_news_get_customizer_option('ticker_news_option' ) ) return;
        $ticker_news_categories = json_decode( LND\local_news_get_customizer_option( 'ticker_news_categories' ) );
        $ticker_args = array(
            'order' => 'desc',
            'orderby' => 'date',
            'posts_per_page'    => 6
        );
        if( $ticker_news_categories ) $ticker_args['category_name'] = local_news_get_categories_for_args($ticker_news_categories);
         ?>
            <div class="ticker-news-wrap local-news-ticker <?php echo esc_attr( 'layout--one' ); ?>">
                <?php
                    $ticker_news_title = LND\local_news_get_customizer_option('ticker_news_title');
                    if( $ticker_news_title ) {
                        ?>
                        <div class="ticker_label_title ticker-title local-news-ticker-label">
                            <span class="ticker_label_title_string"><?php echo esc_html( $ticker_news_title ); ?></span>
                            <div class="rim_wrap">
                                <div class="rim-1"></div>
                                <div class="rim-2"></div>
                                <div class="rim-3"></div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
                <div class="local-news-ticker-box">
                  <?php
                    $local_news_direction = 'left';
                    $local_news_dir = 'ltr';
                    if( is_rtl() ){
                      $local_news_direction = 'right';
                      $local_news_dir = 'ltr';
                    }
                  ?>

                    <ul class="ticker-item-wrap" direction="<?php echo esc_attr($local_news_direction); ?>" dir="<?php echo esc_attr($local_news_dir); ?>">
                        <?php get_template_part( 'template-parts/ticker-news/template', 'one', $ticker_args ); ?>
                    </ul>
                </div>
                <div class="local-news-ticker-controls">
                    <button class="local-news-ticker-pause"><i class="fas fa-pause"></i></button>
                </div>
            </div>
         <?php
     }
    add_action( 'local_news_after_header_hook', 'local_news_ticker_news_part', 10 );
 endif;
 