<?php
use LocalNews\CustomizerDefault as LND;
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
add_action( 'customize_preview_init', function() {
    wp_enqueue_script( 
        'local-news-customizer-preview',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-preview.min.js',
        ['customize-preview'],
        LOCAL_NEWS_VERSION,
        true
    );
    // localize scripts
	wp_localize_script( 
        'local-news-customizer-preview',
        'localNewsPreviewObject', array(
            '_wpnonce'	=> wp_create_nonce( 'local-news-customizer-nonce' ),
            'ajaxUrl' => admin_url('admin-ajax.php')
        )
    );
});

add_action( 'customize_controls_enqueue_scripts', function() {
    $buildControlsDeps = apply_filters(  'local_news_customizer_build_controls_dependencies', array(
        'react',
        'wp-blocks',
        'wp-editor',
        'wp-element',
        'wp-i18n',
        'wp-polyfill',
        'wp-components'
    ));
	wp_enqueue_style( 
        'local-news-customizer-control',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-controls.min.css', 
        array('wp-components'),
        LOCAL_NEWS_VERSION,
        'all'
    );
    wp_enqueue_script( 
        'local-news-customizer-control',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-extends.min.js',
        $buildControlsDeps,
        LOCAL_NEWS_VERSION,
        true
    );
    wp_localize_script( 
        'local-news-customizer-control', 
        'customizerControlsObject', array(
            'categories'    => local_news_get_multicheckbox_categories_simple_array()
        )
    );
    // localize scripts
    wp_localize_script( 
        'local-news-customizer-extras', 
        'customizerExtrasObject', array(
            '_wpnonce'	=> wp_create_nonce( 'local-news-customizer-controls-nonce' ),
            'ajaxUrl' => admin_url('admin-ajax.php')
        )
    );
});

if( !function_exists( 'local_news_customizer_about_theme_panel' ) ) :
    /**
     * Register blog archive section settings
     * 
     */
    function local_news_customizer_about_theme_panel( $wp_customize ) {
        /**
         * About theme section
         * 
         * @since 1.0.0
         */
        $wp_customize->add_section( LOCAL_NEWS_PREFIX . 'about_section', array(
            'title' => esc_html__( 'About Theme / Pro Features', 'localnews' ),
            'priority'  => 1
        ));

        // theme upgrade info box
        $wp_customize->add_setting( 'theme_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'theme_upgrade_info', array(
                'label'	      => esc_html__( 'Premium Features', 'localnews' ),
                'description' => esc_html__( 'Our premium plans comes with full featured and highly customizable customizer. With unlimited social icons, unlimited homepage news sections, unlimited advertisement area and advanced control fields with 600+ typography features. More section layouts, 18 + widget layouts', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'about_section',
                'settings'    => 'theme_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Premium', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // theme documentation info box
        $wp_customize->add_setting( 'site_documentation_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'site_documentation_info', array(
                'label'	      => esc_html__( 'Theme Documentation', 'localnews' ),
                'description' => esc_html__( 'We have well prepared documentation which includes overall instructions and recommendations that are required in this theme.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'about_section',
                'settings'    => 'site_documentation_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Documentation', 'localnews' ),
                        'url'   => esc_url( '//doc.blazethemes.com/local-news' )
                    )
                )
            ))
        );

        // theme documentation info box
        $wp_customize->add_setting( 'site_support_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'site_support_info', array(
                'label'	      => esc_html__( 'Theme Support', 'localnews' ),
                'description' => esc_html__( 'We provide 24/7 support regarding any theme issue. Our support team will help you to solve any kind of issue. Feel free to contact us.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'about_section',
                'settings'    => 'site_support_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'Support Form', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/support' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_about_theme_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_global_panel' ) ) :
    /**
     * Register global options settings
     * 
     */
    function local_news_customizer_global_panel( $wp_customize ) {
        /**
         * Global panel
         * 
         * @package LocalNews
         * @since 1.0.0
         */
        $wp_customize->add_panel( 'local_news_global_panel', array(
            'title' => esc_html__( 'Global', 'localnews' ),
            'priority'  => 5
        ));

        // section- seo/misc settings section
        $wp_customize->add_section( 'local_news_seo_misc_section', array(
            'title' => esc_html__( 'SEO / Misc', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));

        // site schema ready option
        $wp_customize->add_setting( 'site_schema_ready', array(
            'default'   => LND\local_news_get_customizer_default( 'site_schema_ready' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport'    => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Toggle_Control( $wp_customize, 'site_schema_ready', array(
                'label'	      => esc_html__( 'Make website schema ready', 'localnews' ),
                'section'     => 'local_news_seo_misc_section',
                'settings'    => 'site_schema_ready'
            ))
        );

        // site date to show
        $wp_customize->add_setting( 'site_date_to_show', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'site_date_to_show' )
        ));
        $wp_customize->add_control( 'site_date_to_show', array(
            'type'      => 'select',
            'section'   => 'local_news_seo_misc_section',
            'label'     => esc_html__( 'Date to display', 'localnews' ),
            'description' => esc_html__( 'Whether to show date published or modified date.', 'localnews' ),
            'choices'   => array(
                'published'  => __( 'Published date', 'localnews' ),
                'modified'   => __( 'Modified date', 'localnews' )
            )
        ));

        // site date format
        $wp_customize->add_setting( 'site_date_format', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'site_date_format' )
        ));
        $wp_customize->add_control( 'site_date_format', array(
            'type'      => 'select',
            'section'   => 'local_news_seo_misc_section',
            'label'     => esc_html__( 'Date format', 'localnews' ),
            'description' => esc_html__( 'Date format applied to single and archive pages.', 'localnews' ),
            'choices'   => array(
                'theme_format'  => __( 'Default by theme', 'localnews' ),
                'default'   => __( 'Wordpress default date', 'localnews' )
            )
        ));

        // preset colors header
        $wp_customize->add_setting( 'preset_colors_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'preset_colors_heading', array(
                'label'	      => esc_html__( 'Theme Presets', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_colors_heading'
            ))
        );

        // primary preset color
        $wp_customize->add_setting( 'preset_color_1', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_1' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_1', array(
                'label'	      => esc_html__( 'Color 1', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_1',
                'variable'   => '--ln-global-preset-color-1'
            ))
        );

        // secondary preset color
        $wp_customize->add_setting( 'preset_color_2', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_2' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_2', array(
                'label'	      => esc_html__( 'Color 2', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_2',
                'variable'   => '--ln-global-preset-color-2'
            ))
        );

        // tertiary preset color
        $wp_customize->add_setting( 'preset_color_3', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_3' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_3', array(
                'label'	      => esc_html__( 'Color 3', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_3',
                'variable'   => '--ln-global-preset-color-3'
            ))
        );

        // primary preset link color
        $wp_customize->add_setting( 'preset_color_4', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_4' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_4', array(
                'label'	      => esc_html__( 'Color 4', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_4',
                'variable'   => '--ln-global-preset-color-4'
            ))
        );

        // secondary preset link color
        $wp_customize->add_setting( 'preset_color_5', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_5' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_5', array(
                'label'	      => esc_html__( 'Color 5', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_5',
                'variable'   => '--ln-global-preset-color-5'
            ))
        );
        
        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_6', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_color_6' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'local_news_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_6', array(
                'label'	      => esc_html__( 'Color 6', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_6',
                'variable'   => '--ln-global-preset-color-6'
            ))
        );

        // gradient preset colors header
        $wp_customize->add_setting( 'gradient_preset_colors_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'gradient_preset_colors_heading', array(
                'label'	      => esc_html__( 'Gradient Presets', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'gradient_preset_colors_heading'
            ))
        );

        // gradient color 1
        $wp_customize->add_setting( 'preset_gradient_1', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_1' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_1', array(
                'label'	      => esc_html__( 'Gradient 1', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_1',
                'variable'   => '--ln-global-preset-gradient-color-1'
            ))
        );
        
        // gradient color 2
        $wp_customize->add_setting( 'preset_gradient_2', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_2' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_2', array(
                'label'	      => esc_html__( 'Gradient 2', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_2',
                'variable'   => '--ln-global-preset-gradient-color-2'
            ))
        );

        // gradient color 3
        $wp_customize->add_setting( 'preset_gradient_3', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_3' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_3', array(
                'label'	      => esc_html__( 'Gradient 3', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_3',
                'variable'   => '--ln-global-preset-gradient-color-3'
            ))
        );

        // gradient color 4
        $wp_customize->add_setting( 'preset_gradient_4', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_4' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_4', array(
                'label'	      => esc_html__( 'Gradient 4', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_4',
                'variable'   => '--ln-global-preset-gradient-color-4'
            ))
        );

        // gradient color 5
        $wp_customize->add_setting( 'preset_gradient_5', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_5' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_5', array(
                'label'	      => esc_html__( 'Gradient 5', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_5',
                'variable'   => '--ln-global-preset-gradient-color-5'
            ))
        );

        // gradient color 6
        $wp_customize->add_setting( 'preset_gradient_6', array(
            'default'   => LND\local_news_get_customizer_default( 'preset_gradient_6' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_6', array(
                'label'	      => esc_html__( 'Gradient 6', 'localnews' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_6',
                'variable'   => '--ln-global-preset-gradient-color-6'
            ))
        );

        // section- preloader section
        $wp_customize->add_section( 'local_news_preloader_section', array(
            'title' => esc_html__( 'Preloader', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));

        // preloader option
        $wp_customize->add_setting( 'preloader_option', array(
            'default'   => LND\local_news_get_customizer_default('preloader_option'),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'preloader_option', array(
                'label'	      => esc_html__( 'Enable site preloader', 'localnews' ),
                'section'     => 'local_news_preloader_section',
                'settings'    => 'preloader_option'
            ))
        );

        // preloader upgrade info box
        $wp_customize->add_setting( 'preloader_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'preloader_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( '29 preloader choices.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'preloader_section',
                'settings'    => 'preloader_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
        
        // section- website layout section
        $wp_customize->add_section( 'local_news_website_layout_section', array(
            'title' => esc_html__( 'Website Layout', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));
        
        // website layout heading
        $wp_customize->add_setting( 'website_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'website_layout_header', array(
                'label'	      => esc_html__( 'Website Layout', 'localnews' ),
                'section'     => 'local_news_website_layout_section',
                'settings'    => 'website_layout_header'
            ))
        );

        // website layout
        $wp_customize->add_setting( 'website_layout',
            array(
            'default'           => LND\local_news_get_customizer_default( 'website_layout' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Image_Control( $wp_customize, 'website_layout',
            array(
                'section'  => 'local_news_website_layout_section',
                'choices'  => array(
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/boxed-width.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/full-width.jpg'
                    )
                )
            )
        ));

        // website layout upgrade info box
        $wp_customize->add_setting( 'website_layout_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'website_layout_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Default theme mode, website frame, width, color.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'website_layout_section',
                'settings'    => 'website_layout_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
        
        // section- animation section
        $wp_customize->add_section( 'local_news_animation_section', array(
            'title' => esc_html__( 'Animation / Hover Effects', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));
        
        // post title animation effects 
        $wp_customize->add_setting( 'post_title_hover_effects', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'post_title_hover_effects' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'post_title_hover_effects', array(
            'type'      => 'select',
            'section'   => 'local_news_animation_section',
            'label'     => esc_html__( 'Post title hover effects', 'localnews' ),
            'description' => esc_html__( 'Applied to post titles listed in archive pages.', 'localnews' ),
            'choices'   => array(
                'none' => esc_html__( 'None', 'localnews' ),
                'one'  => esc_html__( 'Effect One', 'localnews' )
            )
        ));

        // site image animation effects 
        $wp_customize->add_setting( 'site_image_hover_effects', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'site_image_hover_effects' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'site_image_hover_effects', array(
            'type'      => 'select',
            'section'   => 'local_news_animation_section',
            'label'     => esc_html__( 'Image hover effects', 'localnews' ),
            'description' => esc_html__( 'Applied to post thumbanails listed in archive pages.', 'localnews' ),
            'choices'   => array(
                'none' => __( 'None', 'localnews' ),
                'one'  => __( 'Effect One', 'localnews' )
            )
        ));

        // animation upgrade info box
        $wp_customize->add_setting( 'animation_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'animation_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( '5 effects choices.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'animation_section',
                'settings'    => 'animation_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // section- social icons section
        $wp_customize->add_section( 'local_news_social_icons_section', array(
            'title' => esc_html__( 'Social Icons', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));
        
        // social icons setting heading
        $wp_customize->add_setting( 'social_icons_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'social_icons_settings_header', array(
                'label'	      => esc_html__( 'Social Icons Settings', 'localnews' ),
                'section'     => 'local_news_social_icons_section',
                'settings'    => 'social_icons_settings_header'
            ))
        );

        // social icons target attribute value
        $wp_customize->add_setting( 'social_icons_target', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'social_icons_target' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'social_icons_target', array(
            'type'      => 'select',
            'section'   => 'local_news_social_icons_section',
            'label'     => esc_html__( 'Social Icon Link Open in', 'localnews' ),
            'description' => esc_html__( 'Sets the target attribute according to the value.', 'localnews' ),
            'choices'   => array(
                '_blank' => __( 'Open link in new tab', 'localnews' ),
                '_self'  => __( 'Open link in same tab', 'localnews' )
            )
        ));

        // social icons items
        $wp_customize->add_setting( 'social_icons', array(
            'default'   => LND\local_news_get_customizer_default( 'social_icons' ),
            'sanitize_callback' => 'local_news_sanitize_repeater_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Custom_Repeater( $wp_customize, 'social_icons', array(
                'label'         => esc_html__( 'Social Icons', 'localnews' ),
                'description'   => esc_html__( 'Hold bar icon and drag vertically to re-order the icons', 'localnews' ),
                'section'       => 'local_news_social_icons_section',
                'settings'      => 'social_icons',
                'row_label'     => 'inherit-icon_class',
                'add_new_label' => esc_html__( 'Add New Icon', 'localnews' ),
                'fields'        => array(
                    'icon_class'   => array(
                        'type'          => 'fontawesome-icon-picker',
                        'label'         => esc_html__( 'Social Icon', 'localnews' ),
                        'description'   => esc_html__( 'Select from dropdown.', 'localnews' ),
                        'default'       => esc_attr( 'fab fa-instagram' )

                    ),
                    'icon_url'  => array(
                        'type'      => 'url',
                        'label'     => esc_html__( 'URL for icon', 'localnews' ),
                        'default'   => ''
                    ),
                    'item_option'             => 'show'
                )
            ))
        );

        // social icons upgrade info box
        $wp_customize->add_setting( 'social_icons_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'social_icons_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Unlimited social icons.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'social_icons_section',
                'settings'    => 'social_icons_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
        
        // section- sidebar options section
        $wp_customize->add_section( 'local_news_sidebar_options_section', array(
            'title' => esc_html__( 'Sidebar Options', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));

        // frontpage sidebar layout heading
        $wp_customize->add_setting( 'frontpage_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'frontpage_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Frontpage Sidebar Layouts', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'frontpage_sidebar_layout_header'
            ))
        );

        // frontpage sidebar layout
        $wp_customize->add_setting( 'frontpage_sidebar_layout',
            array(
            'default'           => LND\local_news_get_customizer_default( 'frontpage_sidebar_layout' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Image_Control( $wp_customize, 'frontpage_sidebar_layout',
            array(
                'section'  => 'local_news_sidebar_options_section',
                'choices'  => array(
                    'no-sidebar' => array(
                        'label' => esc_html__( 'No Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/no_sidebar.jpg'
                    ),
                    'left-sidebar' => array(
                        'label' => esc_html__( 'Left Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/left_sidebar.jpg'
                    ),
                    'right-sidebar' => array(
                        'label' => esc_html__( 'Right Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/right_sidebar.jpg'
                    ),
                    'both-sidebar' => array(
                        'label' => esc_html__( 'Both Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/both_sidebar.jpg'
                    )
                )
            )
        ));

        // frontpage sidebar sticky option
        $wp_customize->add_setting( 'frontpage_sidebar_sticky_option', array(
            'default'   => LND\local_news_get_customizer_default( 'frontpage_sidebar_sticky_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'frontpage_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'frontpage_sidebar_sticky_option'
            ))
        );

        // archive sidebar layouts heading
        $wp_customize->add_setting( 'archive_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'archive_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Archive/Blog Sidebar Layouts', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'archive_sidebar_layout_header'
            ))
        );

        // archive sidebar layout
        $wp_customize->add_setting( 'archive_sidebar_layout',
            array(
            'default'           => LND\local_news_get_customizer_default( 'archive_sidebar_layout' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Image_Control( $wp_customize, 'archive_sidebar_layout',
            array(
                'section'  => 'local_news_sidebar_options_section',
                'choices'  => array(
                    'no-sidebar' => array(
                        'label' => esc_html__( 'No Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/no_sidebar.jpg'
                    ),
                    'left-sidebar' => array(
                        'label' => esc_html__( 'Left Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/left_sidebar.jpg'
                    ),
                    'right-sidebar' => array(
                        'label' => esc_html__( 'Right Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/right_sidebar.jpg'
                    ),
                    'both-sidebar' => array(
                        'label' => esc_html__( 'Both Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/both_sidebar.jpg'
                    )
                )
            )
        ));

        // archive sidebar sticky option
        $wp_customize->add_setting( 'archive_sidebar_sticky_option', array(
            'default'   => LND\local_news_get_customizer_default( 'archive_sidebar_sticky_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'archive_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'archive_sidebar_sticky_option'
            ))
        );

        // single sidebar layouts heading
        $wp_customize->add_setting( 'single_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'single_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Post Sidebar Layouts', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'single_sidebar_layout_header'
            ))
        );

        // single sidebar layout
        $wp_customize->add_setting( 'single_sidebar_layout',
            array(
            'default'           => LND\local_news_get_customizer_default( 'single_sidebar_layout' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Image_Control( $wp_customize, 'single_sidebar_layout',
            array(
                'section'  => 'local_news_sidebar_options_section',
                'choices'  => array(
                    'no-sidebar' => array(
                        'label' => esc_html__( 'No Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/no_sidebar.jpg'
                    ),
                    'left-sidebar' => array(
                        'label' => esc_html__( 'Left Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/left_sidebar.jpg'
                    ),
                    'right-sidebar' => array(
                        'label' => esc_html__( 'Right Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/right_sidebar.jpg'
                    ),
                    'both-sidebar' => array(
                        'label' => esc_html__( 'Both Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/both_sidebar.jpg'
                    )
                )
            )
        ));

        // single sidebar sticky option
        $wp_customize->add_setting( 'single_sidebar_sticky_option', array(
            'default'   => LND\local_news_get_customizer_default( 'single_sidebar_sticky_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'single_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'single_sidebar_sticky_option'
            ))
        );

        // page sidebar layouts heading
        $wp_customize->add_setting( 'page_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'page_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Page Sidebar Layouts', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'page_sidebar_layout_header'
            ))
        );

        // page sidebar layout
        $wp_customize->add_setting( 'page_sidebar_layout',
            array(
            'default'           => LND\local_news_get_customizer_default( 'page_sidebar_layout' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Image_Control( $wp_customize, 'page_sidebar_layout',
            array(
                'section'  => 'local_news_sidebar_options_section',
                'choices'  => array(
                    'no-sidebar' => array(
                        'label' => esc_html__( 'No Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/no_sidebar.jpg'
                    ),
                    'left-sidebar' => array(
                        'label' => esc_html__( 'Left Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/left_sidebar.jpg'
                    ),
                    'right-sidebar' => array(
                        'label' => esc_html__( 'Right Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/right_sidebar.jpg'
                    ),
                    'both-sidebar' => array(
                        'label' => esc_html__( 'Both Sidebar', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/both_sidebar.jpg'
                    )
                )
            )
        ));

        // page sidebar sticky option
        $wp_customize->add_setting( 'page_sidebar_sticky_option', array(
            'default'   => LND\local_news_get_customizer_default( 'page_sidebar_sticky_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'page_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'localnews' ),
                'section'     => 'local_news_sidebar_options_section',
                'settings'    => 'page_sidebar_sticky_option'
            ))
        );

        // section- breadcrumb options section
        $wp_customize->add_section( 'local_news_breadcrumb_options_section', array(
            'title' => esc_html__( 'Breadcrumb Options', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));

        // breadcrumb option
        $wp_customize->add_setting( 'site_breadcrumb_option', array(
            'default'   => LND\local_news_get_customizer_default( 'site_breadcrumb_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'site_breadcrumb_option', array(
                'label'	      => esc_html__( 'Show breadcrumb trails', 'localnews' ),
                'section'     => 'local_news_breadcrumb_options_section',
                'settings'    => 'site_breadcrumb_option'
            ))
        );

        // breadcrumb type 
        $wp_customize->add_setting( 'site_breadcrumb_type', array(
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'default'   => LND\local_news_get_customizer_default( 'site_breadcrumb_type' )
        ));
        $wp_customize->add_control( 'site_breadcrumb_type', array(
            'type'      => 'select',
            'section'   => 'local_news_breadcrumb_options_section',
            'label'     => esc_html__( 'Breadcrumb type', 'localnews' ),
            'description' => esc_html__( 'If you use other than "default" one you will need to install and activate respective plugins Breadcrumb NavXT, Yoast SEO and Rank Math SEO', 'localnews' ),
            'choices'   => array(
                'default' => __( 'Default', 'localnews' ),
                'bcn'  => __( 'NavXT', 'localnews' ),
                'yoast'  => __( 'Yoast SEO', 'localnews' ),
                'rankmath'  => __( 'Rank Math', 'localnews' )
            )
        ));

        // breadcrumb upgrade info box
        $wp_customize->add_setting( 'breadcrumb_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'breadcrumb_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Position, Design tab - text, link, background color.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'breadcrumb_options_section',
                'settings'    => 'breadcrumb_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // section- scroll to top options
        $wp_customize->add_section( 'local_news_stt_options_section', array(
            'title' => esc_html__( 'Scroll To Top', 'localnews' ),
            'panel' => 'local_news_global_panel'
        ));

        // scroll to top section tab
        $wp_customize->add_setting( 'stt_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'stt_section_tab', array(
                'section'     => 'local_news_stt_options_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );

        // Resposive vivibility option
        $wp_customize->add_setting( 'stt_responsive_option', array(
            'default' => LND\local_news_get_customizer_default( 'stt_responsive_option' ),
            'sanitize_callback' => 'local_news_sanitize_responsive_multiselect_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Responsive_Multiselect_Tab_Control( $wp_customize, 'stt_responsive_option', array(
                'label'	      => esc_html__( 'Scroll To Top Visibility', 'localnews' ),
                'section'     => 'local_news_stt_options_section',
                'settings'    => 'stt_responsive_option'
            ))
        );

        // stt font size
        $wp_customize->add_setting( 'stt_font_size', array(
            'default'   => LND\local_news_get_customizer_default( 'stt_font_size' ),
            'sanitize_callback' => 'local_news_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Responsive_Range_Control( $wp_customize, 'stt_font_size', array(
                    'label'	      => esc_html__( 'Font size (px)', 'localnews' ),
                    'section'     => 'local_news_stt_options_section',
                    'settings'    => 'stt_font_size',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // archive pagination type
        $wp_customize->add_setting( 'stt_alignment', array(
            'default' => LND\local_news_get_customizer_default( 'stt_alignment' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Tab_Control( $wp_customize, 'stt_alignment', array(
                'label'	      => esc_html__( 'Button Align', 'localnews' ),
                'section'     => 'local_news_stt_options_section',
                'settings'    => 'stt_alignment',
                'choices' => array(
                    array(
                        'value' => 'left',
                        'label' => esc_html__('Left', 'localnews' )
                    ),
                    array(
                        'value' => 'center',
                        'label' => esc_html__('Center', 'localnews' )
                    ),
                    array(
                        'value' => 'right',
                        'label' => esc_html__('Right', 'localnews' )
                    )
                )
            ))
        );

        // stt border
        $wp_customize->add_setting( 'stt_border', array( 
            'default' => LND\local_news_get_customizer_default( 'stt_border' ),
            'sanitize_callback' => 'local_news_sanitize_array',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Border_Control( $wp_customize, 'stt_border', array(
                'label'       => esc_html__( 'Border', 'localnews' ),
                'section'     => 'local_news_stt_options_section',
                'settings'    => 'stt_border',
                'tab'   => 'design'
            ))
        );
        
        // stt padding
        $wp_customize->add_setting( 'stt_padding', array( 
            'default' => LND\local_news_get_customizer_default( 'stt_padding' ),
            'sanitize_callback' => 'local_news_sanitize_box_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Responsive_Box_Control( $wp_customize, 'stt_padding', array(
                'label'       => esc_html__( 'Padding', 'localnews' ),
                'section'     => 'local_news_stt_options_section',
                'settings'    => 'stt_padding',
                'tab'   => 'design'
            ))
        );

        // stt label color
        $wp_customize->add_setting( 'stt_color_group', array(
            'default'   => LND\local_news_get_customizer_default( 'stt_color_group' ),
            'sanitize_callback' => 'local_news_sanitize_color_group_picker_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Color_Group_Picker_Control( $wp_customize, 'stt_color_group', array(
                'label'     => esc_html__( 'Icon Color', 'localnews' ),
                'section'   => 'local_news_stt_options_section',
                'settings'  => 'stt_color_group',
                'tab'   => 'design'
            ))
        );

        // breadcrumb link color
        $wp_customize->add_setting( 'stt_background_color_group', array(
            'default'   => LND\local_news_get_customizer_default( 'stt_background_color_group' ),
            'sanitize_callback' => 'local_news_sanitize_color_group_picker_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Color_Group_Picker_Control( $wp_customize, 'stt_background_color_group', array(
                'label'     => esc_html__( 'Background', 'localnews' ),
                'section'   => 'local_news_stt_options_section',
                'settings'  => 'stt_background_color_group',
                'tab'   => 'design'
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_global_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_site_identity_panel' ) ) :
    /**
     * Register site identity settings
     * 
     */
    function local_news_customizer_site_identity_panel( $wp_customize ) {
        /**
         * Register "Site Identity Options" panel
         * 
         */
        $wp_customize->add_panel( 'local_news_site_identity_panel', array(
            'title' => esc_html__( 'Site Identity', 'localnews' ),
            'priority' => 5
        ));
        $wp_customize->get_section( 'title_tagline' )->panel = 'local_news_site_identity_panel'; // assing title tagline section to site identity panel
        $wp_customize->get_section( 'title_tagline' )->title = esc_html__( 'Logo & Site Icon', 'localnews' ); // modify site logo label

        /**
         * Site Title Section
         * 
         * panel - local_news_site_identity_panel
         */
        $wp_customize->add_section( 'local_news_site_title_section', array(
            'title' => esc_html__( 'Site Title & Tagline', 'localnews' ),
            'panel' => 'local_news_site_identity_panel',
            'priority'  => 30,
        ));
        $wp_customize->get_control( 'blogname' )->section = 'local_news_site_title_section';
        $wp_customize->get_control( 'display_header_text' )->section = 'local_news_site_title_section';
        $wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display site title', 'localnews' );
        $wp_customize->get_control( 'blogdescription' )->section = 'local_news_site_title_section';
        
        // site logo width
        $wp_customize->add_setting( 'local_news_site_logo_width', array(
            'default'   => LND\local_news_get_customizer_default( 'local_news_site_logo_width' ),
            'sanitize_callback' => 'local_news_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Responsive_Range_Control( $wp_customize, 'local_news_site_logo_width', array(
                    'label'	      => esc_html__( 'Logo Width (px)', 'localnews' ),
                    'section'     => 'title_tagline',
                    'settings'    => 'local_news_site_logo_width',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 400,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // site title section tab
        $wp_customize->add_setting( 'site_title_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'site_title_section_tab', array(
                'section'     => 'local_news_site_title_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );

        // blog description option
        $wp_customize->add_setting( 'blogdescription_option', array(
            'default'        => true,
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'blogdescription_option', array(
            'label'    => esc_html__( 'Display site description', 'localnews' ),
            'section'  => 'local_news_site_title_section',
            'type'     => 'checkbox',
            'priority' => 50
        ));

        $wp_customize->get_control( 'header_textcolor' )->section = 'local_news_site_title_section';
        $wp_customize->get_control( 'header_textcolor' )->priority = 60;
        $wp_customize->get_control( 'header_textcolor' )->label = esc_html__( 'Site Title Color', 'localnews' );

        // header text hover color
        $wp_customize->add_setting( 'site_title_hover_textcolor', array(
            'default' => LND\local_news_get_customizer_default( 'site_title_hover_textcolor' ),
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Default_Color_Control( $wp_customize, 'site_title_hover_textcolor', array(
                'label'      => esc_html__( 'Site Title Hover Color', 'localnews' ),
                'section'    => 'local_news_site_title_section',
                'settings'   => 'site_title_hover_textcolor',
                'priority'    => 70,
                'tab'   => 'design'
            ))
        );

        // site description color
        $wp_customize->add_setting( 'site_description_color', array(
            'default' => LND\local_news_get_customizer_default( 'site_description_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Default_Color_Control( $wp_customize, 'site_description_color', array(
                'label'      => esc_html__( 'Site Description Color', 'localnews' ),
                'section'    => 'local_news_site_title_section',
                'settings'   => 'site_description_color',
                'priority'    => 70,
                'tab'   => 'design'
            ))
        );

        // site title typo
        $wp_customize->add_setting( 'site_title_typo', array(
            'default'   => LND\local_news_get_customizer_default( 'site_title_typo' ),
            'sanitize_callback' => 'local_news_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Typography_Control( $wp_customize, 'site_title_typo', array(
                'label'	      => esc_html__( 'Site Title Typography', 'localnews' ),
                'section'     => 'local_news_site_title_section',
                'settings'    => 'site_title_typo',
                'tab'   => 'design',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_site_identity_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_top_header_panel' ) ) :
    /**
     * Register header options settings
     * 
     */
    function local_news_customizer_top_header_panel( $wp_customize ) {
        /**
         * Top header section
         * 
         */
        $wp_customize->add_section( 'local_news_top_header_section', array(
            'title' => esc_html__( 'Top Header', 'localnews' ),
            'priority'  => 68
        ));
        
        // section tab
        $wp_customize->add_setting( 'top_header_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'top_header_section_tab', array(
                'section'     => 'local_news_top_header_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );
        
        // Top header option
        $wp_customize->add_setting( 'top_header_option', array(
            'default'         => LND\local_news_get_customizer_default( 'top_header_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Toggle_Control( $wp_customize, 'top_header_option', array(
                'label'	      => esc_html__( 'Show top header', 'localnews' ),
                'description' => esc_html__( 'Toggle to enable or disable top header bar', 'localnews' ),
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_option'
            ))
        );

        // Top header menu option
        $wp_customize->add_setting( 'top_header_menu_option', array(
            'default'         => LND\local_news_get_customizer_default( 'top_header_menu_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'top_header_menu_option', array(
                'label'	      => esc_html__( 'Show menu items', 'localnews' ),
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_menu_option',
            ))
        );

        // Redirect top header menu link
        $wp_customize->add_setting( 'top_header_menu_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'top_header_menu_redirects', array(
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_menu_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'menu_locations',
                        'label' => esc_html__( 'Manage menu from here', 'localnews' )
                    )
                )
            ))
        );

        // top header social option
        $wp_customize->add_setting( 'top_header_social_option', array(
            'default'   => LND\local_news_get_customizer_default( 'top_header_social_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'top_header_social_option', array(
                'label'	      => esc_html__( 'Show social icons', 'localnews' ),
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_social_option',
            ))
        );

        // Redirect header social icons link
        $wp_customize->add_setting( 'top_header_social_icons_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'top_header_social_icons_redirects', array(
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_social_icons_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'local_news_social_icons_section',
                        'label' => esc_html__( 'Manage social icons', 'localnews' )
                    )
                )
            ))
        );

        // Top header background colors group control
        $wp_customize->add_setting( 'top_header_background_color_group', array(
            'default'   => LND\local_news_get_customizer_default( 'top_header_background_color_group' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Color_Group_Control( $wp_customize, 'top_header_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'localnews' ),
                'section'     => 'local_news_top_header_section',
                'settings'    => 'top_header_background_color_group',
                'tab'   => 'design'
            ))
        );

        // top header upgrade info box
        $wp_customize->add_setting( 'top_header_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'top_header_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Menu, Icon color.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'top_header_section',
                'settings'    => 'top_header_upgrade_info',
                'tab'   => 'design',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

    }
    add_action( 'customize_register', 'local_news_customizer_top_header_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_header_panel' ) ) :
    /**
     * Register header options settings
     * 
     */
    function local_news_customizer_header_panel( $wp_customize ) {
        /**
         * Header panel
         * 
         */
        $wp_customize->add_panel( 'local_news_header_panel', array(
            'title' => esc_html__( 'Theme Header', 'localnews' ),
            'priority'  => 69
        ));
        
        // Header ads banner section
        $wp_customize->add_section( 'local_news_header_ads_banner_section', array(
            'title' => esc_html__( 'Ads Banner', 'localnews' ),
            'panel' => 'local_news_header_panel',
            'priority'  => 5
        ));

        // Header Ads Banner setting heading
        $wp_customize->add_setting( 'local_news_header_ads_banner_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'local_news_header_ads_banner_header', array(
                'label'	      => esc_html__( 'Ads Banner Setting', 'localnews' ),
                'section'     => 'local_news_header_ads_banner_section',
                'settings'    => 'local_news_header_ads_banner_header'
            ))
        );

        // Resposive vivibility option
        $wp_customize->add_setting( 'header_ads_banner_responsive_option', array(
            'default' => LND\local_news_get_customizer_default( 'header_ads_banner_responsive_option' ),
            'sanitize_callback' => 'local_news_sanitize_responsive_multiselect_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Responsive_Multiselect_Tab_Control( $wp_customize, 'header_ads_banner_responsive_option', array(
                'label'	      => esc_html__( 'Ads Banner Visibility', 'localnews' ),
                'section'     => 'local_news_header_ads_banner_section',
                'settings'    => 'header_ads_banner_responsive_option'
            ))
        );

        // Header ads banner type
        $wp_customize->add_setting( 'header_ads_banner_type', array(
            'default' => LND\local_news_get_customizer_default( 'header_ads_banner_type' ),
            'sanitize_callback' => 'local_news_sanitize_select_control'
        ));
        $wp_customize->add_control( 'header_ads_banner_type', array(
            'type'      => 'select',
            'section'   => 'local_news_header_ads_banner_section',
            'label'     => __( 'Ads banner type', 'localnews' ),
            'description' => __( 'Choose to display ads content from.', 'localnews' ),
            'choices'   => array(
                'custom' => esc_html__( 'Custom', 'localnews' ),
                'sidebar' => esc_html__( 'Ads Banner Sidebar', 'localnews' )
            ),
        ));

        // ads image field
        $wp_customize->add_setting( 'header_ads_banner_custom_image', array(
            'default' => LND\local_news_get_customizer_default( 'header_ads_banner_custom_image' ),
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'header_ads_banner_custom_image', array(
            'section' => 'local_news_header_ads_banner_section',
            'mime_type' => 'image',
            'label' => esc_html__( 'Ads Image', 'localnews' ),
            'description' => esc_html__( 'Recommended size for ad image is 900 (width) * 350 (height)', 'localnews' ),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'custom' ) {
                    return true;
                }
                return false;
            }
        )));

        // ads url field
        $wp_customize->add_setting( 'header_ads_banner_custom_url', array(
            'default' => LND\local_news_get_customizer_default( 'header_ads_banner_custom_url' ),
            'sanitize_callback' => 'esc_url_raw',
        ));
          
        $wp_customize->add_control( 'header_ads_banner_custom_url', array(
            'type'  => 'url',
            'section'   => 'local_news_header_ads_banner_section',
            'label'     => esc_html__( 'Ads url', 'localnews' ),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'custom' ) {
                    return true;
                }
                return false;
            }
        ));

        // ads link show on
        $wp_customize->add_setting( 'header_ads_banner_custom_target', array(
            'default' => LND\local_news_get_customizer_default( 'header_ads_banner_custom_target' ),
            'sanitize_callback' => 'local_news_sanitize_select_control'
        ));
        
        $wp_customize->add_control( 'header_ads_banner_custom_target', array(
            'type'      => 'select',
            'section'   => 'local_news_header_ads_banner_section',
            'label'     => __( 'Open Ads link on', 'localnews' ),
            'choices'   => array(
                '_self' => esc_html__( 'Open in same tab', 'localnews' ),
                '_blank' => esc_html__( 'Open in new tab', 'localnews' )
            ),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'custom' ) {
                    return true;
                }
                return false;
            }
        ));

        // Redirect ads banner sidebar link
        $wp_customize->add_setting( 'header_ads_banner_sidebar_redirect', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'header_ads_banner_sidebar_redirect', array(
                'section'     => 'local_news_header_ads_banner_section',
                'settings'    => 'header_ads_banner_sidebar_redirect',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-ads-banner-sidebar',
                        'label' => esc_html__( 'Manage ads banner sidebar from here', 'localnews' )
                    )
                ),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'sidebar' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        /**
         * Header content section
         * 
         */
        $wp_customize->add_section( 'local_news_main_header_section', array(
            'title' => esc_html__( 'Main Header', 'localnews' ),
            'panel' => 'local_news_header_panel',
            'priority'  => 10
        ));

        // section tab
        $wp_customize->add_setting( 'main_header_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'main_header_section_tab', array(
                'section'     => 'local_news_main_header_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );

        // redirect site logo section
        $wp_customize->add_setting( 'header_site_logo_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'header_site_logo_redirects', array(
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_site_logo_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'title_tagline',
                        'label' => esc_html__( 'Manage Site Logo', 'localnews' )
                    )
                )
            ))
        );

        // redirect site title section
        $wp_customize->add_setting( 'header_site_title_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'header_site_title_redirects', array(
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_site_title_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'local_news_site_title_section',
                        'label' => esc_html__( 'Manage site & Tagline', 'localnews' )
                    )
                )
            ))
        );

        // header sidebar toggle button option
        $wp_customize->add_setting( 'header_sidebar_toggle_option', array(
            'default'         => LND\local_news_get_customizer_default( 'header_sidebar_toggle_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'header_sidebar_toggle_option', array(
                'label'	      => esc_html__( 'Show sidebar toggle button', 'localnews' ),
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_sidebar_toggle_option'
            ))
        );

        // redirect sidebar toggle button link
        $wp_customize->add_setting( 'header_sidebar_toggle_button_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'header_sidebar_toggle_button_redirects', array(
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_sidebar_toggle_button_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-header-toggle-sidebar',
                        'label' => esc_html__( 'Manage sidebar from here', 'localnews' )
                    )
                )
            ))
        );

        // header search option
        $wp_customize->add_setting( 'header_search_option', array(
            'default'   => LND\local_news_get_customizer_default( 'header_search_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'header_search_option', array(
                'label'	      => esc_html__( 'Show search icon', 'localnews' ),
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_search_option'
            ))
        );
        
        // header theme mode toggle option
        $wp_customize->add_setting( 'header_theme_mode_toggle_option', array(
            'default'   => LND\local_news_get_customizer_default( 'header_theme_mode_toggle_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'header_theme_mode_toggle_option', array(
                'label'	      => esc_html__( 'Show dark/light toggle icon', 'localnews' ),
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_theme_mode_toggle_option'
            ))
        );

        // header sticky option
        $wp_customize->add_setting( 'theme_header_sticky', array(
            'default'   => LND\local_news_get_customizer_default( 'theme_header_sticky' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'theme_header_sticky', array(
                'label'	      => esc_html__( 'Enable header section sticky', 'localnews' ),
                'section'     => 'local_news_main_header_section',
                'settings'    => 'theme_header_sticky'
            ))
        );

        // header general upgrade info box
        $wp_customize->add_setting( 'header_general_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'header_general_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( '2 layouts.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'main_header_section',
                'settings'    => 'header_general_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // header top and bottom padding
        $wp_customize->add_setting( 'header_vertical_padding', array(
            'default'   => LND\local_news_get_customizer_default( 'header_vertical_padding' ),
            'sanitize_callback' => 'local_news_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Responsive_Range_Control( $wp_customize, 'header_vertical_padding', array(
                    'label'	      => esc_html__( 'Vertical Padding (px)', 'localnews' ),
                    'section'     => 'local_news_main_header_section',
                    'settings'    => 'header_vertical_padding',
                    'unit'        => 'px',
                    'tab'   => 'design',
                    'input_attrs' => array(
                    'max'         => 500,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // Header background colors setting
        $wp_customize->add_setting( 'header_background_color_group', array(
            'default'   => LND\local_news_get_customizer_default( 'header_background_color_group' ),
            'sanitize_callback' => 'local_news_sanitize_color_image_group_control',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Color_Image_Group_Control( $wp_customize, 'header_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'localnews' ),
                'section'     => 'local_news_main_header_section',
                'settings'    => 'header_background_color_group',
                'tab'   => 'design'
            ))
        );

        // header design upgrade info box
        $wp_customize->add_setting( 'header_design_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'header_design_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Toggle bar, icon color.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'main_header_section',
                'settings'    => 'header_design_upgrade_info',
                'tab'   => 'design',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
        
        /**
         * Menu Options Section
         * 
         * panel - local_news_header_options_panel
         */
        $wp_customize->add_section( 'local_news_header_menu_option_section', array(
            'title' => esc_html__( 'Menu Options', 'localnews' ),
            'panel' => 'local_news_header_panel',
            'priority'  => 30,
        ));
        
        // header menu hover effect
        $wp_customize->add_setting( 'header_menu_hover_effect', array(
            'default' => LND\local_news_get_customizer_default( 'header_menu_hover_effect' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Radio_Tab_Control( $wp_customize, 'header_menu_hover_effect', array(
                'label'	      => esc_html__( 'Hover Effect', 'localnews' ),
                'section'     => 'local_news_header_menu_option_section',
                'settings'    => 'header_menu_hover_effect',
                'choices' => array(
                    array(
                        'value' => 'none',
                        'label' => esc_html__('None', 'localnews' )
                    ),
                    array(
                        'value' => 'one',
                        'label' => esc_html__('One', 'localnews' )
                    )
                )
            ))
        );

        // header menu background color group
        $wp_customize->add_setting( 'header_menu_background_color_group', array(
            'default'   => LND\local_news_get_customizer_default( 'header_menu_background_color_group' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Color_Group_Control( $wp_customize, 'header_menu_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'localnews' ),
                'section'     => 'local_news_header_menu_option_section',
                'settings'    => 'header_menu_background_color_group'
            ))
        );

        // menu border bottom
        $wp_customize->add_setting( 'header_menu_bottom_border', array( 
            'default' => LND\local_news_get_customizer_default( 'header_menu_bottom_border' ),
            'sanitize_callback' => 'local_news_sanitize_array',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Border_Control( $wp_customize, 'header_menu_bottom_border', array(
                'label'       => esc_html__( 'Border Bottom', 'localnews' ),
                'section'     => 'local_news_header_menu_option_section',
                'settings'    => 'header_menu_bottom_border'
            ))
        );

        // menu upgrade info box
        $wp_customize->add_setting( 'menu_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'menu_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Menu, hover, active, border colors and main, sub menu typography .', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'header_menu_option_section',
                'settings'    => 'menu_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_header_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_ticker_news_panel' ) ) :
    /**
     * Register header options settings
     * 
     */
    function local_news_customizer_ticker_news_panel( $wp_customize ) {
        // ticker news section
        $wp_customize->add_section( 'local_news_ticker_news_section', array(
            'title' => esc_html__( 'Ticker News', 'localnews' ),
            'priority'  => 20
        ));
        // preloader option
        $wp_customize->add_setting( 'ticker_news_option', array(
            'default'   => LND\local_news_get_customizer_default('ticker_news_option'),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'ticker_news_option', array(
                'label'	      => esc_html__( 'Enable ticker news', 'localnews' ),
                'section'     => 'local_news_ticker_news_section',
                'settings'    => 'ticker_news_option'
            ))
        );

        // Ticker News title
        $wp_customize->add_setting( 'ticker_news_title', array(
            'default' => LND\local_news_get_customizer_default( 'ticker_news_title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 'ticker_news_title', array(
            'type'      => 'text',
            'section'   => 'local_news_ticker_news_section',
            'label'     => esc_html__( 'Ticker title', 'localnews' )
        ));
        // Ticker News categories
        $wp_customize->add_setting( 'ticker_news_categories', array(
            'default' => LND\local_news_get_customizer_default( 'ticker_news_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Multiselect_Control( $wp_customize, 'ticker_news_categories', array(
                'label'     => esc_html__( 'Posts Categories', 'localnews' ),
                'section'   => 'local_news_ticker_news_section',
                'settings'  => 'ticker_news_categories',
                'choices'   => local_news_get_multicheckbox_categories_simple_array()
            ))
        );

        // ticker upgrade info box
        $wp_customize->add_setting( 'ticker_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'ticker_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( '2 layouts, display option, post order, count, controller hide.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'ticker_news_section',
                'settings'    => 'ticker_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_ticker_news_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_main_banner_panel' ) ) :
    /**
     * Register main banner section settings
     * 
     */
    function local_news_customizer_main_banner_panel( $wp_customize ) {
        /**
         * Main Banner section
         * 
         */
        $wp_customize->add_section( 'local_news_main_banner_section', array(
            'title' => esc_html__( 'Main Banner', 'localnews' ),
            'priority'  => 70
        ));

        // main banner section tab
        $wp_customize->add_setting( 'main_banner_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'main_banner_section_tab', array(
                'section'     => 'local_news_main_banner_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );

        // Main Banner option
        $wp_customize->add_setting( 'main_banner_option', array(
            'default'   => LND\local_news_get_customizer_default( 'main_banner_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Toggle_Control( $wp_customize, 'main_banner_option', array(
                'label'	      => esc_html__( 'Show main banner', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_option'
            ))
        );

        // main banner slider setting heading
        $wp_customize->add_setting( 'main_banner_slider_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'main_banner_slider_settings_header', array(
                'label'	      => esc_html__( 'Slider Setting', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_slider_settings_header',
                'type'        => 'section-heading',
            ))
        );

        // Main Banner slider number of posts
        $wp_customize->add_setting( 'main_banner_slider_numbers', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_slider_numbers' ),
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Range_Control( $wp_customize, 'main_banner_slider_numbers', array(
                'label'	      => esc_html__( 'Number of posts to display', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_slider_numbers',
                'input_attrs' => array(
                    'min'   => 1,
                    'max'   => 4,
                    'step'  => 1,
                    'reset' => false
                )
            ))
        );
        
        // Main Banner slider categories
        $wp_customize->add_setting( 'main_banner_slider_categories', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_slider_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Multiselect_Control( $wp_customize, 'main_banner_slider_categories', array(
                'label'     => esc_html__( 'Posts Categories', 'localnews' ),
                'section'   => 'local_news_main_banner_section',
                'settings'  => 'main_banner_slider_categories',
                'choices'   => local_news_get_multicheckbox_categories_simple_array()
            ))
        );

        // Main Banner slider categories option
        $wp_customize->add_setting( 'main_banner_slider_categories_option', array(
            'default'   => LND\local_news_get_customizer_default( 'main_banner_slider_categories_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'main_banner_slider_categories_option', array(
                'label'	      => esc_html__( 'Show post categories', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_slider_categories_option'
            ))
        );

        // Main Banner slider date option
        $wp_customize->add_setting( 'main_banner_slider_date_option', array(
            'default'   => LND\local_news_get_customizer_default( 'main_banner_slider_date_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'main_banner_slider_date_option', array(
                'label'	      => esc_html__( 'Show post date', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_slider_date_option'
            ))
        );

        // Main Banner slider excerpt option
        $wp_customize->add_setting( 'main_banner_slider_excerpt_option', array(
            'default'   => LND\local_news_get_customizer_default( 'main_banner_slider_excerpt_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'main_banner_slider_excerpt_option', array(
                'label'	      => esc_html__( 'Show post excerpt', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_slider_excerpt_option'
            ))
        );

        // Main banner tabs setting heading
        $wp_customize->add_setting( 'main_banner_tabs_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'main_banner_tabs_settings_header', array(
                'label'	      => esc_html__( 'Tabs Setting', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_tabs_settings_header',
                'type'        => 'section-heading'
            ))
        );

        // Main Banner Latest tabs title
        $wp_customize->add_setting( 'main_banner_latest_tab_title', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_latest_tab_title' ),
            'sanitize_callback' => 'local_news_sanitize_custom_text_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Icon_Text_Control( $wp_customize, 'main_banner_latest_tab_title', array(
                'label'     => esc_html__( 'Latest tab title', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_latest_tab_title'
            ))
        );

        // Main Banner Popular tabs title
        $wp_customize->add_setting( 'main_banner_popular_tab_title', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_popular_tab_title' ),
            'sanitize_callback' => 'local_news_sanitize_custom_text_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Icon_Text_Control( $wp_customize, 'main_banner_popular_tab_title', array(
                'label'     => esc_html__( 'Popular tab title', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_popular_tab_title'
            ))
        );

        // Main Banner Popular tabs categories
        $wp_customize->add_setting( 'main_banner_popular_tab_categories', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_popular_tab_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Multiselect_Control( $wp_customize, 'main_banner_popular_tab_categories', array(
                'label'     => esc_html__( 'Popular Categories', 'localnews' ),
                'section'   => 'local_news_main_banner_section',
                'settings'  => 'main_banner_popular_tab_categories',
                'choices'   => local_news_get_multicheckbox_categories_simple_array()
            ))
        );

        // Main Banner Comments tabs title
        $wp_customize->add_setting( 'main_banner_comments_tab_title', array(
            'default' => LND\local_news_get_customizer_default( 'main_banner_comments_tab_title' ),
            'sanitize_callback' => 'local_news_sanitize_custom_text_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Icon_Text_Control( $wp_customize, 'main_banner_comments_tab_title', array(
                'label'     => esc_html__( 'Comments tab title', 'localnews' ),
                'section'     => 'local_news_main_banner_section',
                'settings'    => 'main_banner_comments_tab_title'
            ))
        );
        
        // banner general upgrade info box
        $wp_customize->add_setting( 'banner_general_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'banner_general_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Slider options, excerpt length, post order, count and icons selector.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'main_banner_section',
                'settings'    => 'banner_general_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // banner section order
        $wp_customize->add_setting( 'banner_section_order', array(
            'default'   => LND\local_news_get_customizer_default( 'banner_section_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'banner_section_order', array(
                'label'         => esc_html__( 'Column Re-order', 'localnews' ),
                'section'       => 'local_news_main_banner_section',
                'settings'      => 'banner_section_order',
                'tab'   => 'design',
                'fields'    => array(
                    'banner_slider'  => array(
                        'label' => esc_html__( 'Banner Slider Column', 'localnews' )
                    ),
                    'tab_slider'  => array(
                        'label' => esc_html__( 'Tabbed Posts Column', 'localnews' )
                    )
                )
            ))
        );

        // banner design upgrade info box
        $wp_customize->add_setting( 'banner_design_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'banner_design_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Popular, latest, comments reorder.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'main_banner_section',
                'settings'    => 'banner_design_upgrade_info',
                'tab'   => 'design',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_main_banner_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_footer_panel' ) ) :
    /**
     * Register footer options settings
     * 
     */
    function local_news_customizer_footer_panel( $wp_customize ) {
        /**
         * Theme Footer Section
         * 
         * panel - local_news_footer_panel
         */
        $wp_customize->add_section( 'local_news_footer_section', array(
            'title' => esc_html__( 'Theme Footer', 'localnews' ),
            'priority'  => 74
        ));
        
        // section tab
        $wp_customize->add_setting( 'footer_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Tab_Control( $wp_customize, 'footer_section_tab', array(
                'section'     => 'local_news_footer_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'localnews' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'localnews' )
                    )
                )
            ))
        );

        // Footer Option
        $wp_customize->add_setting( 'footer_option', array(
            'default'   => LND\local_news_get_customizer_default( 'footer_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport'   => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Toggle_Control( $wp_customize, 'footer_option', array(
                'label'	      => esc_html__( 'Enable footer section', 'localnews' ),
                'section'     => 'local_news_footer_section',
                'settings'    => 'footer_option',
                'tab'   => 'general'
            ))
        );

        /// Add the footer layout control.
        $wp_customize->add_setting( 'footer_widget_column', array(
            'default'           => LND\local_news_get_customizer_default( 'footer_widget_column' ),
            'sanitize_callback' => 'local_news_sanitize_select_control',
            'transport'   => 'postMessage'
            )
        );
        $wp_customize->add_control( new Local_News_WP_Radio_Image_Control(
            $wp_customize,
            'footer_widget_column',
            array(
                'section'  => 'local_news_footer_section',
                'tab'   => 'general',
                'choices'  => array(
                    'column-one' => array(
                        'label' => esc_html__( 'Column One', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/footer_column_one.jpg'
                    ),
                    'column-two' => array(
                        'label' => esc_html__( 'Column Two', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/footer_column_two.jpg'
                    ),
                    'column-three' => array(
                        'label' => esc_html__( 'Column Three', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/footer_column_three.jpg'
                    ),
                    'column-four' => array(
                        'label' => esc_html__( 'Column Four', 'localnews' ),
                        'url'   => '%s/assets/images/customizer/footer_column_four.jpg'
                    )
                )
            )
        ));
        
        // Redirect widgets link
        $wp_customize->add_setting( 'footer_widgets_redirects', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'footer_widgets_redirects', array(
                'label'	      => esc_html__( 'Widgets', 'localnews' ),
                'section'     => 'local_news_footer_section',
                'settings'    => 'footer_widgets_redirects',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-1',
                        'label' => esc_html__( 'Manage footer widget one', 'localnews' )
                    ),
                    'footer-column-two' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-2',
                        'label' => esc_html__( 'Manage footer widget two', 'localnews' )
                    ),
                    'footer-column-three' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-3',
                        'label' => esc_html__( 'Manage footer widget three', 'localnews' )
                    ),
                    'footer-column-four' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-4',
                        'label' => esc_html__( 'Manage footer widget four', 'localnews' )
                    )
                )
            ))
        );

        // footer border top
        $wp_customize->add_setting( 'footer_top_border', array( 
            'default' => LND\local_news_get_customizer_default( 'footer_top_border' ),
            'sanitize_callback' => 'local_news_sanitize_array',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Border_Control( $wp_customize, 'footer_top_border', array(
                'label'       => esc_html__( 'Border Top', 'localnews' ),
                'section'     => 'local_news_footer_section',
                'settings'    => 'footer_top_border',
                'tab'   => 'design'
            ))
        );

        // footer upgrade info box
        $wp_customize->add_setting( 'footer_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'footer_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Design tab - border width, color, text color, background color, gradient, image.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'footer_section',
                'settings'    => 'footer_upgrade_info',
                'tab'   => 'design',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_footer_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_bottom_footer_panel' ) ) :
    /**
     * Register bottom footer options settings
     * 
     */
    function local_news_customizer_bottom_footer_panel( $wp_customize ) {
        /**
         * Bottom Footer Section
         * 
         * panel - local_news_footer_panel
         */
        $wp_customize->add_section( 'local_news_bottom_footer_section', array(
            'title' => esc_html__( 'Bottom Footer', 'localnews' ),
            'priority'  => 75
        ));

        // Bottom Footer Option
        $wp_customize->add_setting( 'bottom_footer_option', array(
            'default'         => LND\local_news_get_customizer_default( 'bottom_footer_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Toggle_Control( $wp_customize, 'bottom_footer_option', array(
                'label'	      => esc_html__( 'Enable bottom footer', 'localnews' ),
                'section'     => 'local_news_bottom_footer_section',
                'settings'    => 'bottom_footer_option'
            ))
        );

        // Main Banner slider categories option
        $wp_customize->add_setting( 'bottom_footer_social_option', array(
            'default'   => LND\local_news_get_customizer_default( 'bottom_footer_social_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'bottom_footer_social_option', array(
                'label'	      => esc_html__( 'Show bottom social icons', 'localnews' ),
                'section'     => 'local_news_bottom_footer_section',
                'settings'    => 'bottom_footer_social_option'
            ))
        );

        // Main Banner slider categories option
        $wp_customize->add_setting( 'bottom_footer_menu_option', array(
            'default'   => LND\local_news_get_customizer_default( 'bottom_footer_menu_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'bottom_footer_menu_option', array(
                'label'	      => esc_html__( 'Show bottom footer menu', 'localnews' ),
                'section'     => 'local_news_bottom_footer_section',
                'settings'    => 'bottom_footer_menu_option'
            ))
        );
        // copyright text
        $wp_customize->add_setting( 'bottom_footer_site_info', array(
            'default'    => LND\local_news_get_customizer_default( 'bottom_footer_site_info' ),
            'sanitize_callback' => 'wp_kses_post'
        ));
        $wp_customize->add_control( 'bottom_footer_site_info', array(
                'label'	      => esc_html__( 'Copyright Text', 'localnews' ),
                'type'  => 'textarea',
                'description' => esc_html__( 'Add %year% to retrieve current year.', 'localnews' ),
                'section'     => 'local_news_bottom_footer_section'
            )
        );

        // bottom footer upgrade info box
        $wp_customize->add_setting( 'bottom_footer_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'bottom_footer_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Edit full copyright text, Design tab - text color, link color, background color, gradient.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'bottom_footer_section',
                'settings'    => 'bottom_footer_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_bottom_footer_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_front_sections_panel' ) ) :
    /**
     * Register front sections settings
     * 
     */
    function local_news_customizer_front_sections_panel( $wp_customize ) {
        // Front sections panel
        $wp_customize->add_panel( 'local_news_front_sections_panel', array(
            'title' => esc_html__( 'Front sections', 'localnews' ),
            'priority'  => 71
        ));

        // full width content section
        $wp_customize->add_section( 'local_news_full_width_section', array(
            'title' => esc_html__( 'Full Width', 'localnews' ),
            'panel' => 'local_news_front_sections_panel',
            'priority'  => 10
        ));

        // full width repeater control
        $wp_customize->add_setting( 'full_width_blocks', array(
            'default'   => LND\local_news_get_customizer_default( 'full_width_blocks' ),
            'sanitize_callback' => 'local_news_sanitize_repeater_control',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Block_Repeater_Control( $wp_customize, 'full_width_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'localnews' ),
                'description' => esc_html__( 'Click on eye icon to show or hide block', 'localnews' ),
                'section'     => 'local_news_full_width_section',
                'settings'    => 'full_width_blocks'
            ))
        );

        // full width upgrade info box
        $wp_customize->add_setting( 'full_width_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'full_width_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Unlimited blocks, list, grid, filter, carousel, ads, shortcode block and design tab - padding, margin, background color, gradient, image.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'full_width_section',
                'settings'    => 'full_width_upgrade_info',
                'priority'  => 40,
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // Left content -right sidebar section
        $wp_customize->add_section( 'local_news_leftc_rights_section', array(
            'title' => esc_html__( 'Left Content  - Right Sidebar', 'localnews' ),
            'panel' => 'local_news_front_sections_panel',
            'priority'  => 10
        ));

        // redirect to manage sidebar
        $wp_customize->add_setting( 'leftc_rights_section_sidebar_redirect', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));
    
        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'leftc_rights_section_sidebar_redirect', array(
                'label'	      => esc_html__( 'Widgets', 'localnews' ),
                'section'     => 'local_news_leftc_rights_section',
                'settings'    => 'leftc_rights_section_sidebar_redirect',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-front-right-sidebar',
                        'label' => esc_html__( 'Manage right sidebar', 'localnews' )
                    )
                )
            ))
        );

        // Block Repeater control
        $wp_customize->add_setting( 'leftc_rights_blocks', array(
            'sanitize_callback' => 'local_news_sanitize_repeater_control',
            'default'   => LND\local_news_get_customizer_default( 'leftc_rights_blocks' ),
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Block_Repeater_Control( $wp_customize, 'leftc_rights_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'localnews' ),
                'description' => esc_html__( 'Click on eye icon to show or hide block', 'localnews' ),
                'section'     => 'local_news_leftc_rights_section',
                'settings'    => 'leftc_rights_blocks'
            ))
        );

        // leftc rights upgrade info box
        $wp_customize->add_setting( 'leftc_rights_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'leftc_rights_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Unlimited blocks, list, grid, filter, carousel, ads, shortcode block and design tab - padding, margin, background color, gradient, image.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'leftc_rights_section',
                'settings'    => 'leftc_rights_upgrade_info',
                'priority'  => 40,
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        /**
         * Left sidebar - Right content section
         * 
         */
        $wp_customize->add_section( 'local_news_lefts_rightc_section', array(
            'title' => esc_html__( 'Left Sidebar - Right Content', 'localnews' ),
            'panel' => 'local_news_front_sections_panel',
            'priority'  => 10
        ));

        // redirect to manage sidebar
        $wp_customize->add_setting( 'lefts_rightc_section_sidebar_redirect', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'lefts_rightc_section_sidebar_redirect', array(
                'label'	      => esc_html__( 'Widgets', 'localnews' ),
                'section'     => 'local_news_lefts_rightc_section',
                'settings'    => 'lefts_rightc_section_sidebar_redirect',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-front-left-sidebar',
                        'label' => esc_html__( 'Manage left sidebar', 'localnews' )
                    )
                )
            ))
        );

        // Block Repeater control
        $wp_customize->add_setting( 'lefts_rightc_blocks', array(
            'sanitize_callback' => 'local_news_sanitize_repeater_control',
            'default'   => LND\local_news_get_customizer_default( 'lefts_rightc_blocks' ),
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Block_Repeater_Control( $wp_customize, 'lefts_rightc_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'localnews' ),
                'description' => esc_html__( 'Click on eye icon to show or hide block', 'localnews' ),
                'section'     => 'local_news_lefts_rightc_section',
                'settings'    => 'lefts_rightc_blocks'
            ))
        );

        // lefts rightc upgrade info box
        $wp_customize->add_setting( 'lefts_rightc_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'lefts_rightc_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Unlimited blocks, list, grid, filter, carousel, ads, shortcode block and design tab - padding, margin, background color, gradient, image.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'lefts_rightc_section',
                'settings'    => 'lefts_rightc_upgrade_info',
                'priority'  => 40,
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // Bottom Full Width content section
        $wp_customize->add_section( 'local_news_bottom_full_width_section', array(
            'title' => esc_html__( 'Bottom Full Width', 'localnews' ),
            'panel' => 'local_news_front_sections_panel',
            'priority'  => 50
        ));

        // bottom full width blocks control
        $wp_customize->add_setting( 'bottom_full_width_blocks', array(
            'sanitize_callback' => 'local_news_sanitize_repeater_control',
            'default'   => LND\local_news_get_customizer_default( 'bottom_full_width_blocks' ),
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control( 
            new Local_News_WP_Block_Repeater_Control( $wp_customize, 'bottom_full_width_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'localnews' ),
                'description' => esc_html__( 'Click on eye icon to show or hide block', 'localnews' ),
                'section'     => 'local_news_bottom_full_width_section',
                'settings'    => 'bottom_full_width_blocks'
            ))
        );

        // bottom full width upgrade info box
        $wp_customize->add_setting( 'bottom_full_width', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'bottom_full_width', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Unlimited blocks, list, grid, filter, carousel, ads, shortcode block and design tab - padding, margin, background color, gradient, image.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'bottom_full_width_section',
                'settings'    => 'bottom_full_width',
                'priority'  => 40,
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        // front sections reorder section
        $wp_customize->add_section( 'local_news_front_sections_reorder_section', array(
            'title' => esc_html__( 'Reorder sections', 'localnews' ),
            'panel' => 'local_news_front_sections_panel',
            'priority'  => 60
        ));
        
        /**
         * Frontpage sections options
         * 
         * @package LocalNews
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'homepage_content_order', array(
            'default'   => LND\local_news_get_customizer_default( 'homepage_content_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'homepage_content_order', array(
                'label'         => esc_html__( 'Section Re-order', 'localnews' ),
                'description'   => esc_html__( 'Hold item and drag vertically to re-order the items', 'localnews' ),
                'section'       => 'local_news_front_sections_reorder_section',
                'settings'      => 'homepage_content_order',
                'fields'    => array(
                    'full_width_section'  => array(
                        'label' => esc_html__( 'Full width Section', 'localnews' )
                    ),
                    'leftc_rights_section'  => array(
                        'label' => esc_html__( 'Left Content - Right Sidebar', 'localnews' )
                    ),
                    'lefts_rightc_section'  => array(
                        'label' => esc_html__( 'Left Sidebar - Right Content', 'localnews' )
                    ),
                    'bottom_full_width_section'  => array(
                        'label' => esc_html__( 'Bottom Full width Section', 'localnews' )
                    ),
                    'latest_posts'  => array(
                        'label' => esc_html__( 'Latest Posts / Page Content', 'localnews' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_front_sections_panel', 10 );
endif;

if( !function_exists( 'local_news_customizer_blog_post_archive_panel' ) ) :
    /**
     * Register global options settings
     * 
     */
    function local_news_customizer_blog_post_archive_panel( $wp_customize ) {
        // Blog/Archive/Single panel
        $wp_customize->add_panel( 'local_news_blog_post_archive_panel', array(
            'title' => esc_html__( 'Blog / Archive / Single', 'localnews' ),
            'priority'  => 72
        ));
        
        // blog / archive section
        $wp_customize->add_section( 'local_news_blog_archive_section', array(
            'title' => esc_html__( 'Blog / Archive', 'localnews' ),
            'panel' => 'local_news_blog_post_archive_panel',
            'priority'  => 10
        ));

        // archive title prefix option
        $wp_customize->add_setting( 'archive_page_title_prefix', array(
            'default' => LND\local_news_get_customizer_default( 'archive_page_title_prefix' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'archive_page_title_prefix', array(
                'label'	      => esc_html__( 'Show archive title prefix', 'localnews' ),
                'section'     => 'local_news_blog_archive_section',
                'settings'    => 'archive_page_title_prefix'
            ))
        );

        // archive excerpt length
        $wp_customize->add_setting( 'archive_excerpt_length', array(
            'default' => LND\local_news_get_customizer_default( 'archive_excerpt_length' ),
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Range_Control( $wp_customize, 'archive_excerpt_length', array(
                'label'	      => esc_html__( 'No.  of words in excerpt', 'localnews' ),
                'section'     => 'local_news_blog_archive_section',
                'settings'    => 'archive_excerpt_length',
                'input_attrs' => array(
                    'min'   => 1,
                    'max'   => 100,
                    'step'  => 1,
                    'reset' => true
                )
            ))
        );
        
        // archive elements sort
        $wp_customize->add_setting( 'archive_post_element_order', array(
            'default'   => LND\local_news_get_customizer_default( 'archive_post_element_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'archive_post_element_order', array(
                'label'         => esc_html__( 'Elements show/hide', 'localnews' ),
                'section'       => 'local_news_blog_archive_section',
                'settings'      => 'archive_post_element_order',
                'tab'   => 'general',
                'fields'    => array(
                    'title'  => array(
                        'label' => esc_html__( 'Title', 'localnews' )
                    ),
                    'meta'  => array(
                        'label' => esc_html__( 'Meta', 'localnews' )
                    ),
                    'excerpt'  => array(
                        'label' => esc_html__( 'Excerpt', 'localnews' )
                    ),
                    'button'  => array(
                        'label' => esc_html__( 'Button', 'localnews' )
                    ),
                )
            ))
        );

        // Redirect continue reading button
        $wp_customize->add_setting( 'archive_button_redirect', array(
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Redirect_Control( $wp_customize, 'archive_button_redirect', array(
                'section'     => 'local_news_blog_archive_section',
                'settings'    => 'archive_button_redirect',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'local_news_buttons_section',
                        'label' => esc_html__( 'Edit button styles', 'localnews' )
                    )
                )
            ))
        );

        // archive meta sort
        $wp_customize->add_setting( 'archive_post_meta_order', array(
            'default'   => LND\local_news_get_customizer_default( 'archive_post_meta_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'archive_post_meta_order', array(
                'label'         => esc_html__( 'Meta show/hide', 'localnews' ),
                'section'       => 'local_news_blog_archive_section',
                'settings'      => 'archive_post_meta_order',
                'tab'   => 'general',
                'fields'    => array(
                    'author'  => array(
                        'label' => esc_html__( 'Author Name', 'localnews' )
                    ),
                    'date'  => array(
                        'label' => esc_html__( 'Published/Modified Date', 'localnews' )
                    ),
                    'comments'  => array(
                        'label' => esc_html__( 'Comments Number', 'localnews' )
                    ),
                    'read-time'  => array(
                        'label' => esc_html__( 'Read Time', 'localnews' )
                    )
                )
            ))
        );

        // archive upgrade info box
        $wp_customize->add_setting( 'archive_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'archive_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( '2 layouts, ajax load more, elements and meta sortable.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'blog_archive_section',
                'settings'    => 'archive_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );

        //  single post section
        $wp_customize->add_section( 'local_news_single_post_section', array(
            'title' => esc_html__( 'Single Post', 'localnews' ),
            'panel' => 'local_news_blog_post_archive_panel',
            'priority'  => 20
        ));
        
        // single elements sort
        $wp_customize->add_setting( 'single_post_element_order', array(
            'default'   => LND\local_news_get_customizer_default( 'single_post_element_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'single_post_element_order', array(
                'label'         => esc_html__( 'Elements show/hide', 'localnews' ),
                'section'       => 'local_news_single_post_section',
                'settings'      => 'single_post_element_order',
                'tab'   => 'general',
                'fields'    => array(
                    'categories'  => array(
                        'label' => esc_html__( 'Categories', 'localnews' )
                    ),
                    'title'  => array(
                        'label' => esc_html__( 'Title', 'localnews' )
                    ),
                    'meta'  => array(
                        'label' => esc_html__( 'Meta', 'localnews' )
                    ),
                    'thumbnail'  => array(
                        'label' => esc_html__( 'Featured Image', 'localnews' )
                    )
                )
            ))
        );

        // single meta sort
        $wp_customize->add_setting( 'single_post_meta_order', array(
            'default'   => LND\local_news_get_customizer_default( 'single_post_meta_order' ),
            'sanitize_callback' => 'local_news_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Local_News_WP_Item_Sortable_Control( $wp_customize, 'single_post_meta_order', array(
                'label'         => esc_html__( 'Meta show/hide', 'localnews' ),
                'section'       => 'local_news_single_post_section',
                'settings'      => 'single_post_meta_order',
                'tab'   => 'general',
                'fields'    => array(
                    'author'  => array(
                        'label' => esc_html__( 'Author Name', 'localnews' )
                    ),
                    'date'  => array(
                        'label' => esc_html__( 'Published/Modified Date', 'localnews' )
                    ),
                    'comments'  => array(
                        'label' => esc_html__( 'Comments Number', 'localnews' )
                    ),
                    'read-time'  => array(
                        'label' => esc_html__( 'Read Time', 'localnews' )
                    )
                )
            ))
        );

        // single post related news heading
        $wp_customize->add_setting( 'single_post_related_posts_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Section_Heading_Control( $wp_customize, 'single_post_related_posts_header', array(
                'label'	      => esc_html__( 'Related News', 'localnews' ),
                'section'     => 'local_news_single_post_section',
                'settings'    => 'single_post_related_posts_header'
            ))
        );

        // related news option
        $wp_customize->add_setting( 'single_post_related_posts_option', array(
            'default'   => LND\local_news_get_customizer_default( 'single_post_related_posts_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Simple_Toggle_Control( $wp_customize, 'single_post_related_posts_option', array(
                'label'	      => esc_html__( 'Show related news', 'localnews' ),
                'section'     => 'local_news_single_post_section',
                'settings'    => 'single_post_related_posts_option'
            ))
        );

        // related news title
        $wp_customize->add_setting( 'single_post_related_posts_title', array(
            'default' => LND\local_news_get_customizer_default( 'single_post_related_posts_title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'single_post_related_posts_title', array(
            'type'      => 'text',
            'section'   => 'local_news_single_post_section',
            'label'     => esc_html__( 'Related news title', 'localnews' )
        ));

        // show related posts on popup
        $wp_customize->add_setting( 'single_post_related_posts_popup_option', array(
            'default'   => LND\local_news_get_customizer_default( 'single_post_related_posts_popup_option' ),
            'sanitize_callback' => 'local_news_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Checkbox_Control( $wp_customize, 'single_post_related_posts_popup_option', array(
                'label'	      => esc_html__( 'Show related post on popup box', 'localnews' ),
                'section'     => 'local_news_single_post_section',
                'settings'    => 'single_post_related_posts_popup_option'
            ))
        );

        // single upgrade info box
        $wp_customize->add_setting( 'single_upgrade_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Local_News_WP_Info_Box_Control( $wp_customize, 'single_upgrade_info', array(
                'label'	      => esc_html__( 'More Features', 'localnews' ),
                'description' => esc_html__( 'Design tab- title, meta content typography, related posts layouts, filter by, post count, thumb hide, elements, meta sortable.', 'localnews' ),
                'section'     => LOCAL_NEWS_PREFIX . 'single_post_section',
                'settings'    => 'single_upgrade_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Pro', 'localnews' ),
                        'url'   => esc_url( '//blazethemes.com/theme/local-news-pro' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'local_news_customizer_blog_post_archive_panel', 10 );
endif;

// extract to the customizer js
$localNewsAddAction = function() {
    $action_prefix = "wp_ajax_" . "local_news_";
    // site logo styles
    add_action( $action_prefix . 'site_logo_styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_site_logo_width_fnc("body .site-branding img.custom-logo", 'local_news_site_logo_width');
		$site_logo_styles = ob_get_clean();
		echo apply_filters( 'local_news_site_logo_styles', wp_strip_all_tags($site_logo_styles) );
		wp_die();
	});
    // site title typo
    add_action( $action_prefix . 'site_title_typo', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_get_typo_style( "--site-title", 'site_title_typo' );
		$site_title_typo = ob_get_clean();
		echo apply_filters( 'local_news_site_title_typo', wp_strip_all_tags($site_title_typo) );
		wp_die();
	});
    // site background color
    add_action( $action_prefix . 'site_background_color', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_get_background_style_var('--site-bk-color', 'site_background_color');
		$site_background_color = ob_get_clean();
		echo apply_filters( 'local_news_site_background_color', wp_strip_all_tags($site_background_color) );
		wp_die();
	});
    // top header styles
    add_action( $action_prefix . 'top_header_styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_get_background_style('.ln_main_body .site-header.layout--default .top-header','top_header_background_color_group');
		$top_header_styles = ob_get_clean();
		echo apply_filters( 'local_news_top_header_styles', wp_strip_all_tags($top_header_styles) );
		wp_die();
	});
    // header styles
    add_action( $action_prefix . 'header_styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_get_background_style('body .site-header.layout--default .site-branding-section', 'header_background_color_group');
			local_news_header_padding('--header-padding', 'header_vertical_padding');
		$header_styles = ob_get_clean();
		echo apply_filters( 'local_news_header_styles', wp_strip_all_tags($header_styles) );
		wp_die();
	});
    // header menu styles
    add_action( $action_prefix . 'header_menu_styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_get_background_style('.ln_main_body .site-header.layout--default .menu-section','header_menu_background_color_group');
		$header_menu_styles = ob_get_clean();
		echo apply_filters( 'local_news_header_menu_styles', wp_strip_all_tags($header_menu_styles) );
		wp_die();
	});
    // header border styles
    add_action( $action_prefix . 'header_border_styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
            local_news_border_option('body .menu-section', 'header_menu_bottom_border', 'border-bottom');
        $header_border_bottom_styles = ob_get_clean();
		echo apply_filters( 'local_news_header_border_styles', wp_strip_all_tags($header_border_bottom_styles) );
		wp_die();
	});
    // stt buttons styles
    add_action( $action_prefix . 'stt_buttons__styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
			local_news_visibility_options('body #ln-scroll-to-top.show','stt_responsive_option');
			local_news_font_size_style("--move-to-top-font-size", 'stt_font_size');
			local_news_border_option('body #ln-scroll-to-top', 'stt_border');
			local_news_get_responsive_spacing_style( 'body #ln-scroll-to-top' , 'stt_padding', 'padding' );
			local_news_text_color_var('--move-to-top-color','stt_color_group');
			local_news_text_color_var('--move-to-top-background-color','stt_background_color_group');
        $local_news_stt_buttons__styles = ob_get_clean();
		echo apply_filters( 'local_news_stt_buttons__styles', wp_strip_all_tags($local_news_stt_buttons__styles) );
		wp_die();
	});
    // footer styles
    add_action( $action_prefix . 'footer__styles', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
			local_news_border_option('body .site-footer.dark_bk','footer_top_border', 'border-top');
        $local_news_footer__styles = ob_get_clean();
		echo apply_filters( 'local_news_footer__styles', wp_strip_all_tags($local_news_footer__styles) );
		wp_die();
	});
    // typography fonts url
    add_action( $action_prefix . 'typography_fonts_url', function() {
        check_ajax_referer( 'local-news-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
			echo esc_url( local_news_typo_fonts_url() );
        $local_news_typography_fonts_url = ob_get_clean();
		echo apply_filters( 'local_news_typography_fonts_url', esc_url($local_news_typography_fonts_url) );
		wp_die();
	});
};
$localNewsAddAction();

add_action( 'wp_ajax_local_news_customizer_reset_to_default', function () {
    check_ajax_referer( 'local-news-customizer-controls-nonce', 'security' );
    /**
     * Filter the settings that will be removed.
     *
     * @param array $settings Theme modifications.
     * @return array
     * @since 1.1.0
     */
    remove_theme_mods();
    wp_send_json_success();
});