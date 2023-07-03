<?php
/**
 * Includes theme defaults and starter functions
 * 
 * @package LocalNews
 * @since 1.0.0
 */
 namespace LocalNews\CustomizerDefault;

 if( !function_exists( 'local_news_get_customizer_option' ) ) :
    /**
     * Gets customizer "theme_mods" value
     * 
     */
    function local_news_get_customizer_option( $key ) {
        return get_theme_mod( $key, local_news_get_customizer_default( $key ) );
    }
 endif;

 if( !function_exists( 'local_news_get_multiselect_tab_option' ) ) :
    /**
     * Gets customizer "multiselect combine tab" value
     * 
     */
    function local_news_get_multiselect_tab_option( $key ) {
        $value = local_news_get_customizer_option( $key );
        if( !$value["desktop"] && !$value["tablet"] && !$value["mobile"] ) return apply_filters( "local_news_get_multiselect_tab_option", false );
        return apply_filters( "local_news_get_multiselect_tab_option", true );
    }
 endif;

 if( !function_exists( 'local_news_get_customizer_default' ) ) :
    /**
     * Gets customizer "theme_mods" value
     * 
     */
    function local_news_get_customizer_default($key) {
        $array_defaults = apply_filters( 'local_news_get_customizer_defaults', array(
            'theme_color'   => '#d95f3d',
            'site_background_color'  => json_encode(array(
                'type'  => 'solid',
                'solid' => '#f6f6f6',
                'gradient'  => null
            )),
            'preloader_option'  => false,
            'website_layout'    => 'full-width--layout',
            'frontpage_sidebar_layout'  => 'right-sidebar',
            'frontpage_sidebar_sticky_option'    => false,
            'archive_sidebar_layout'    => 'right-sidebar',
            'archive_sidebar_sticky_option'    => false,
            'single_sidebar_layout' => 'right-sidebar',
            'single_sidebar_sticky_option'    => false,
            'page_sidebar_layout'   => 'right-sidebar',
            'page_sidebar_sticky_option'    => false,
            'preset_color_1'    => '#c2113c',
            'preset_color_2'    => '#f4be3e',
            'preset_color_3'    => '#d95f3d',
            'preset_color_4'    => '#000000',
            'preset_color_5'    => '#545454',
            'preset_color_6'    => '#333333',
            'preset_gradient_1'   => 'linear-gradient(90deg, #c2113c 0%, #f4be3e 100%)',
            'preset_gradient_2' => 'linear-gradient(90deg, #ffafbd 0%, #ffc3a0 100%)',
            'preset_gradient_3'  => 'linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%)',
            'preset_gradient_4'   => 'linear-gradient(90deg, #cc2b5e 0%, #753a88 100%)',
            'preset_gradient_5' => 'linear-gradient(90deg, #ee9ca7 0%, #ffdde1 100%)',
            'preset_gradient_6'  => 'linear-gradient(90deg, #42275a 0%, #734b6d 100%)',
            'post_title_hover_effects'  => 'one',
            'site_image_hover_effects'  => 'one',
            'site_breadcrumb_option'    => true,
            'site_breadcrumb_type'  => 'default',
            'site_schema_ready' => true,
            'site_date_format'  => 'theme_format',
            'site_date_to_show' => 'published',
            'site_title_hover_textcolor'=> '#d95f3d',
            'site_description_color'    => '#fff',
            'homepage_content_order'    => array( 
                array( 'value'  => 'full_width_section', 'option'   => true ),
                array( 'value'  => 'leftc_rights_section', 'option'    => false ),
                array( 'value'   => 'lefts_rightc_section', 'option' => false ),
                array( 'value'   => 'latest_posts', 'option'    => true ),
                array( 'value' => 'bottom_full_width_section', 'option'  => true )
            ),
            'local_news_site_logo_width'    => array(
                'desktop'   => 230,
                'tablet'    => 200,
                'smartphone'    => 200
            ),
            'site_title_typo'    => array(
                'font_family'   => array( 'value' => 'Heebo', 'label' => 'Heebo' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Bold 700' ),
                'font_size'   => array(
                    'desktop' => 45,
                    'tablet' => 43,
                    'smartphone' => 40
                ),
                'line_height'   => array(
                    'desktop' => 45,
                    'tablet' => 42,
                    'smartphone' => 40,
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'top_header_option' => true,
            'top_header_responsive_option' => true,
            'top_header_menu_option'    => true,
            'top_header_social_option'  => true,
            'top_header_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => null,
                'gradient'  => null
            )),
            'header_ads_banner_responsive_option'  => array(
                'desktop'   => true,
                'tablet'   => true,
                'mobile'   => true
            ),
            'header_ads_banner_type'    => 'custom',
            'header_ads_banner_custom_image'  => '',
            'header_ads_banner_custom_url'  => '',
            'header_ads_banner_custom_target'  => '_self',
            'header_sidebar_toggle_option'  => false,
            'header_search_option'  => true,
            'header_theme_mode_toggle_option'  => true,
            'theme_header_sticky'  => true,
            'header_vertical_padding'   => array(
                'desktop' => 35,
                'tablet' => 30,
                'smartphone' => 30
            ),
            'header_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => null,
                'gradient'  => null,
                'image'     => array( 'media_id' => 0, 'media_url' => '' )
            )),
            'header_menu_hover_effect'  => 'none',
            'header_menu_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => null,
                'gradient'  => null
            )),
            'header_menu_bottom_border'    => array( "type"  => "none", "width"   => "3", "color"   => "--theme-color-red" ),
            'ticker_news_option'  => true,
            'social_icons_target' => '_blank',
            'social_icons' => json_encode(array(
                array(
                    'icon_class'    => 'fab fa-facebook-f',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-instagram',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-twitter',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-youtube',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                )
            )),
            'ticker_news_categories' => '[]',
            'ticker_news_title' => esc_html__( 'Headlines', 'localnews' ),
            'main_banner_option'    => true,
            'main_banner_slider_categories' => '[]',
            'main_banner_slider_numbers'    => 3,
            'main_banner_slider_categories_option'  => true,
            'main_banner_slider_date_option'  => true,
            'main_banner_slider_excerpt_option' => true,
            'main_banner_latest_tab_title'   => array( "icon"  => "far fa-clock", "text"   => esc_html__( 'Latest', 'localnews' ) ), 
            'main_banner_popular_tab_title'   => array( "icon"  => "fas fa-fire", "text"   => esc_html__( 'Popular', 'localnews' ) ),
            'main_banner_popular_tab_categories'   => '[]',
            'main_banner_comments_tab_title'   => array( "icon"  => "far fa-comments", "text"   => esc_html__( 'Comments', 'localnews' ) ),
            'banner_section_order'  => array( 
                array( 'value'  => 'banner_slider', 'option'   => true ),
                array( 'value'  => 'tab_slider', 'option'    => true )
            ),
            'full_width_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-grid',
                    'option'    => true,
                    'layout'    => 'one',
                    'title'     => esc_html__( 'Latest posts', 'localnews' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'query' => array(
                        'order' => 'date-desc',
                        'count' => 3,
                        'categories' => [],
                        'ids' => []
                    ),
                    'buttonOption' => false,
                    'viewallOption'=> false,
                    'viewallUrl'   => ''
                ),
                array(
                    'type'  => 'ad-block',
                    'option'    => false,
                    'title'     => esc_html__( 'Advertisement Banner', 'localnews' ),
                    'media' => ['media_url' => '','media_id'=> 0],
                    'url'   =>  '',
                    'targetAttr'    => '_blank',
                    'relAttr'   => 'nofollow'
                )
            )),
            'leftc_rights_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-filter',
                    'option'    => true,
                    'layout'    => 'one',
                    'title'     => esc_html__( 'Latest posts', 'localnews' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'query' => array(
                        'order' => 'date-desc',
                        'count' => 5,
                        'categories' => [],
                        'ids' => []
                    ),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => ''
                )
            )),
            'lefts_rightc_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-list',
                    'option'    => true,
                    'layout'    => 'one',
                    'column'    => 'two',
                    'title'     => esc_html__( 'Latest posts', 'localnews' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'query' => array(
                        'order' => 'date-desc',
                        'count' => 4,
                        'categories' => [],
                        'ids' => []
                    ),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => ''
                )
            )),
            'bottom_full_width_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-carousel',
                    'option'    => true,
                    'layout'    => 'one',
                    'title'     => esc_html__( 'Latest posts', 'localnews' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => false,
                    'columns' => 4,
                    'query' => array(
                        'order' => 'date-desc',
                        'count' => 8,
                        'categories' => [],
                        'ids' => []
                    ),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'dots' => true,
                    'loop' => false,
                    'arrows' => true,
                    'auto' => false
                )
            )),
            'footer_option' => false,
            'footer_widget_column'  => 'column-three',
            'footer_top_border'    => array( "type"  => "solid", "width"   => "5", "color"   => "--theme-color-red" ),
            'bottom_footer_option'  => true,
            'bottom_footer_social_option'   => false,
            'bottom_footer_menu_option'     => false,
            'bottom_footer_site_info'   => esc_html__( 'LocalNews - Modern WordPress Theme. All Rights Reserved  %year%..', 'localnews' ),
            'single_post_element_order'    => array(
                array( 'value'  => 'categories', 'option' => true ),
                array( 'value'  => 'title', 'option' => true ),
                array( 'value'  => 'meta', 'option' => true ),
                array( 'value'  => 'thumbnail', 'option' => true )
            ),
            'single_post_meta_order'    => array(
                array( 'value'  => 'author', 'option' => true ),
                array( 'value'  => 'date', 'option' => true ),
                array( 'value'  => 'comments', 'option' => true ),
                array( 'value'  => 'read-time', 'option' => true )
            ),
            'single_post_related_posts_option'  => true,
            'single_post_related_posts_title'   => esc_html__( 'Related News', 'localnews' ),
            'single_post_related_posts_popup_option'=> true,
            'archive_page_title_prefix'   => false,
            'archive_excerpt_length'   => 20,
            'archive_post_element_order'    => array(
                array( 'value'  => 'title', 'option' => true ),
                array( 'value'  => 'meta', 'option' => true ),
                array( 'value'  => 'excerpt', 'option' => true ),
                array( 'value'  => 'button', 'option' => true )
            ),
            'archive_post_meta_order'    => array(
                array( 'value'  => 'author', 'option' => true ),
                array( 'value'  => 'date', 'option' => true ),
                array( 'value'  => 'comments', 'option' => true ),
                array( 'value'  => 'read-time', 'option' => true )
            ),
            'stt_responsive_option'    => array(
                'desktop'   => true,
                'tablet'   => true,
                'mobile'   => false
            ),
            'stt_font_size' => array(
                'desktop' => 16,
                'tablet' => 14,
                'smartphone' => 12
            ),
            'stt_alignment' => 'right',
            'stt_padding' => array( 'desktop'   => array( 'top'=>'8px', 'right'	=> '20px', 'bottom'	=> '8px', 'left'	=> '20px' ), 'tablet'   => array( 'top'=>'8px', 'right'	=> '20px', 'bottom'	=> '8px', 'left'	=> '20px' ), 'smartphone'   => array( 'top'=>'8px', 'right'	=> '20px', 'bottom'	=> '8px', 'left'	=> '20px' ) ),
            'stt_border'    => array( "type"  => "none", "width"   => "1", "color"   => "#000000" ),
            'stt_color_group' => array( 'color'   => "#fff", 'hover'   => "#000" ),
            'stt_background_color_group' => array( 'color'   => "#d95f3d", 'hover'   => "#d95f3d" )
        ));
        return $array_defaults[$key];
    }
 endif;