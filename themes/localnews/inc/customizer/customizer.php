<?php
/**
 * LocalNews Theme Customizer
 *
 * @package LocalNews
 */
use LocalNews\CustomizerDefault as LND;
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function local_news_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->get_section( 'background_image' )->title = esc_html__( 'Background', 'localnews' );
    $wp_customize->remove_control( 'background_color' );
    
	require get_template_directory() . '/inc/customizer/custom-controls/section-heading/section-heading.php'; // section heading control
	require get_template_directory() . '/inc/customizer/custom-controls/repeater/repeater.php'; // repeater control
    require get_template_directory() . '/inc/customizer/custom-controls/radio-image/radio-image.php'; // radio image control
	require get_template_directory() . '/inc/customizer/custom-controls/redirect-control/redirect-control.php'; // redirect control
    require get_template_directory() . '/inc/customizer/base.php'; // base class
    // icon text control
    class Local_News_WP_Icon_Text_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'icon-text';
    }

    // color group control
    class Local_News_WP_Color_Group_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'color-group';
    }

    // color image group control
    class Local_News_WP_Color_Image_Group_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'color-image-group';
    }

    // color picker control
    class Local_News_WP_Color_Picker_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'color-picker';
    }

    // preset color picker control
    class Local_News_WP_Preset_Color_Picker_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'preset-color-picker';
        public $variable = '--ln-global-preset-color-1';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->variable ) {
                $this->json['variable'] = $this->variable;
            }
        }
    }
    
    // preset gradient picker control
    class Local_News_WP_Preset_Gradient_Picker_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'preset-gradient-picker';
        public $variable = '--ln-global-preset-gradient-color-1';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->variable ) {
                $this->json['variable'] = $this->variable;
            }
        }
    }

    // multiselect control
    class Local_News_WP_Multiselect_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'multiselect';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // range control
    class Local_News_WP_Range_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'range';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['input_attrs'] = $this->input_attrs;
        }
    }

    // responsive range control
    class Local_News_WP_Responsive_Range_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'responsive-range';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['input_attrs'] = $this->input_attrs;
        }
    }

    // responsive box control
    class Local_News_WP_Responsive_Box_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'responsive-box';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['input_attrs'] = $this->input_attrs;
        }
    }

    // toggle control 
    class Local_News_WP_Toggle_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'toggle-button';
        public $tab = 'general';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // checkbox control 
    class Local_News_WP_Checkbox_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'checkbox';
        public $tab = 'general';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // simple toggle control 
    class Local_News_WP_Simple_Toggle_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'simple-toggle';
    }

    // block repeater control 
    class Local_News_WP_Block_Repeater_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'block-repeater';
        public $tab = 'general';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // item sortable control 
    class Local_News_WP_Item_Sortable_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'item-sortable';
        public $fields;

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['fields'] = $this->fields;
        }
    }

    // typography control 
    class Local_News_WP_Typography_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'typography';
        public $fields;

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['fields'] = $this->fields;
        }
    }

    // color group picker control - renders color and hover color control
    class Local_News_WP_Color_Group_Picker_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'color-group-picker';
    }

    // border control - renders border property control
    class Local_News_WP_Border_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'border';
    }

    // section tab control - renders section tab control
    class Local_News_WP_Section_Tab_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'section-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // radio tab control
    class Local_News_WP_Radio_Tab_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'radio-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // radio tab control
    class Local_News_WP_Responsive_Multiselect_Tab_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'responsive-multiselect-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // tab group control
    class Local_News_WP_Default_Color_Control extends WP_Customize_Color_Control {
        /**
         * Additional variabled
         * 
         */
        public $tab = 'general';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab && $this->type != 'section-tab' ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // info box control
    class Local_News_WP_Info_Box_Control extends Local_News_WP_Base_Control {
        // control type
        public $type = 'info-box';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // register control type
    $wp_customize->register_control_type( 'Local_News_WP_Radio_Image_Control' );

    // active menu color
    $wp_customize->add_setting( 'theme_color', array(
        'default'   => LND\local_news_get_customizer_default( 'theme_color' ),
        'transport' => 'postMessage',
        'sanitize_callback' => 'local_news_sanitize_color_picker_control'
    ));
    $wp_customize->add_control( 
        new Local_News_WP_Color_Picker_Control( $wp_customize, 'theme_color', array(
            'label'	      => esc_html__( 'Theme Color', 'localnews' ),
            'section'     => 'colors',
            'settings'    => 'theme_color'
        ))
    );
    
    // site background color
    $wp_customize->add_setting( 'site_background_color', array(
        'default'   => LND\local_news_get_customizer_default( 'site_background_color' ),
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control( 
        new Local_News_WP_Color_Group_Control( $wp_customize, 'site_background_color', array(
            'label'	      => esc_html__( 'Background Color', 'localnews' ),
            'section'     => 'background_image',
            'settings'    => 'site_background_color',
            'priority'  => 1
        ))
    );
}
add_action( 'customize_register', 'local_news_customize_register' );

add_filter( LOCAL_NEWS_PREFIX . 'unique_identifier', function($identifier) {
    $ln_delimeter = '-';
    $ln_prefix = 'customize';
    $ln_sufix = 'control';
    $identifier_id = [$ln_prefix,$identifier,$ln_sufix];
    return implode($ln_delimeter,$identifier_id);
});

require get_template_directory() . '/inc/customizer/handlers.php'; // customizer handlers
require get_template_directory() . '/inc/customizer/selective-refresh.php'; // selective refresh
require get_template_directory() . '/inc/customizer/sanitize-functions.php'; // sanitize functions