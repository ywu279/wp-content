<?php
/**
 * Includes all the frontpage sections html functions
 * 
 * @package LocalNews
 * @since 1.0.0
 */
use LocalNews\CustomizerDefault as LND;

if( ! function_exists( 'local_news_main_banner_part' ) ) :
    /**
     * Main Banner element
     * 
     * @since 1.0.0
     */
     function local_news_main_banner_part() {
        $main_banner_option = LND\local_news_get_customizer_option( 'main_banner_option' );
        if( ! $main_banner_option || is_paged() ) return;
        $main_banner_slider_categories = json_decode( LND\local_news_get_customizer_option( 'main_banner_slider_categories' ) );
        $main_banner_args = array(
            'slider_args'  => array(
                'order' => 'desc',
                'orderby' => 'date',
                'posts_per_page'    => absint( LND\local_news_get_customizer_option( 'main_banner_slider_numbers' ) )
            )
        );
        if( $main_banner_slider_categories ) $main_banner_args['slider_args']['category_name'] = local_news_get_categories_for_args($main_banner_slider_categories);
        $banner_section_order = LND\local_news_get_customizer_option( 'banner_section_order' );
        ?>
            <section id="main-banner-section" class="local-news-section banner-layout--one <?php echo esc_attr( implode( '--', array( $banner_section_order[0]['value'], $banner_section_order[1]['value'] ) ) ); ?>">
                <div class="ln-container">
                    <div class="row">
                        <?php get_template_part( 'template-parts/main-banner/template', 'one', $main_banner_args ); ?>
                    </div>
                </div>
            </section>
        <?php
     }
endif;
add_action( 'local_news_main_banner_hook', 'local_news_main_banner_part', 10 );

if( ! function_exists( 'local_news_full_width_blocks_part' ) ) :
    /**
     * Full Width Blocks element
     * 
     * @since 1.0.0
     */
     function local_news_full_width_blocks_part() {
        $full_width_blocks = LND\local_news_get_customizer_option( 'full_width_blocks' );
        if( empty( $full_width_blocks ) || is_paged() ) return;
        $full_width_blocks = json_decode( $full_width_blocks );
        if( ! in_array( true, array_column( $full_width_blocks, 'option' ) ) ) {
            return;
        }
        ?>
            <section id="full-width-section" class="local-news-section full-width-section">
                <div class="ln-container">
                    <div class="row">
                        <?php
                            foreach( $full_width_blocks as $block ) :
                                if( $block->option ) :
                                    $type = $block->type;
                                    switch($type) {
                                        case 'shortcode-block' : local_news_shortcode_block_html( $block, true );
                                                        break;
                                        case 'ad-block' : local_news_advertisement_block_html( $block, true );
                                                        break;
                                        default: $layout = $block->layout;
                                                $order = $block->query->order;
                                                $postCategories = $block->query->categories;
                                                $customexclude_ids = $block->query->ids;
                                                $orderArray = explode( '-', $order );
                                                $block_args = array(
                                                    'post_args' => array(
                                                        'post_type' => 'post',
                                                        'order' => esc_html( $orderArray[1] ),
                                                        'orderby' => esc_html( $orderArray[0] ),
                                                        'posts_per_page' => absint( $block->query->count )
                                                    ),
                                                    'options'    => $block
                                                );
                                                if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = $customexclude_ids;
                                                if( $postCategories ) $block_args['post_args']['category_name'] = local_news_get_categories_for_args($postCategories);
                                                // get template file w.r.t par
                                                get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                    }
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'local_news_full_width_blocks_hook', 'local_news_full_width_blocks_part' );
endif;

if( ! function_exists( 'local_news_leftc_rights_blocks_part' ) ) :
    /**
     * Left Content Right Sidebar Blocks element
     * 
     * @since 1.0.0
     */
     function local_news_leftc_rights_blocks_part() {
        $leftc_rights_blocks = LND\local_news_get_customizer_option( 'leftc_rights_blocks' );
        if( empty( $leftc_rights_blocks ) || is_paged() ) return;
        $leftc_rights_blocks = json_decode( $leftc_rights_blocks );
        if( ! in_array( true, array_column( $leftc_rights_blocks, 'option' ) ) ) {
            return;
        }
        ?>
            <section id="leftc-rights-section" class="local-news-section leftc-rights-section">
                <div class="ln-container">
                    <div class="row">
                        <div class="primary-content">
                            <?php
                                foreach( $leftc_rights_blocks as $block ) :
                                    if( $block->option ) :
                                        $type = $block->type;
                                        switch($type) {
                                            case 'shortcode-block' : local_news_shortcode_block_html( $block, true );
                                                        break;
                                            case 'ad-block' : local_news_advertisement_block_html( $block, true );
                                                            break;
                                            default: $layout = $block->layout;
                                                    $order = $block->query->order;
                                                    $postCategories = $block->query->categories;
                                                    $customexclude_ids = $block->query->ids;
                                                    $orderArray = explode( '-', $order );
                                                    $block_args = array(
                                                        'post_args' => array(
                                                            'post_type' => 'post',
                                                            'order' => esc_html( $orderArray[1] ),
                                                            'orderby' => esc_html( $orderArray[0] ),
                                                            'posts_per_page' => absint( $block->query->count )
                                                        ),
                                                        'options'    => $block
                                                    );
                                                    if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = $customexclude_ids;
                                                    if( $postCategories ) $block_args['post_args']['category_name'] = local_news_get_categories_for_args($postCategories);
                                                    // get template file w.r.t par
                                                    get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                        }
                                    endif;
                                endforeach;
                            ?>
                        </div>
                        <div class="secondary-sidebar">
                            <?php dynamic_sidebar( 'front-right-sidebar' ); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'local_news_leftc_rights_blocks_hook', 'local_news_leftc_rights_blocks_part', 10 );
endif;

if( ! function_exists( 'local_news_lefts_rightc_blocks_part' ) ) :
    /**
     * Left Sidebar Right Content Blocks element
     * 
     * @since 1.0.0
     */
     function local_news_lefts_rightc_blocks_part() {
        $lefts_rightc_blocks = LND\local_news_get_customizer_option( 'lefts_rightc_blocks' );
        if( empty( $lefts_rightc_blocks )|| is_paged() ) return;
        $lefts_rightc_blocks = json_decode( $lefts_rightc_blocks );
        if( ! in_array( true, array_column( $lefts_rightc_blocks, 'option' ) ) ) {
            return;
        }
        ?>
            <section id="lefts-rightc-section" class="local-news-section lefts-rightc-section">
                <div class="ln-container">
                    <div class="row">
                        <div class="secondary-sidebar">
                            <?php dynamic_sidebar( 'front-left-sidebar' ); ?>
                        </div>
                        <div class="primary-content">
                            <?php
                                foreach( $lefts_rightc_blocks as $block ) :
                                    if( $block->option ) :
                                        $type = $block->type;
                                        switch($type) {
                                            case 'shortcode-block' : local_news_shortcode_block_html( $block, true );
                                                        break;
                                            case 'ad-block' : local_news_advertisement_block_html( $block, true );
                                                            break;
                                            default: $layout = $block->layout;
                                                    $order = $block->query->order;
                                                    $postCategories = $block->query->categories;
                                                    $customexclude_ids = $block->query->ids;
                                                    $orderArray = explode( '-', $order );
                                                    $block_args = array(
                                                        'post_args' => array(
                                                            'post_type' => 'post',
                                                            'order' => esc_html( $orderArray[1] ),
                                                            'orderby' => esc_html( $orderArray[0] ),
                                                            'posts_per_page' => absint( $block->query->count )
                                                        ),
                                                        'options'    => $block
                                                    );
                                                    if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = $customexclude_ids;
                                                    if( $postCategories ) $block_args['post_args']['category_name'] = local_news_get_categories_for_args($postCategories);
                                                    // get template file w.r.t par
                                                    get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                        }
                                    endif;
                                endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'local_news_lefts_rightc_blocks_hook', 'local_news_lefts_rightc_blocks_part', 10 );
endif;

if( ! function_exists( 'local_news_bottom_full_width_blocks_part' ) ) :
    /**
     * Bottom Full Width Blocks element
     * 
     * @since 1.0.0
     */
     function local_news_bottom_full_width_blocks_part() {
        $bottom_full_width_blocks = LND\local_news_get_customizer_option( 'bottom_full_width_blocks' );
        if( empty( $bottom_full_width_blocks )|| is_paged() ) return;
        $bottom_full_width_blocks = json_decode( $bottom_full_width_blocks );
        if( ! in_array( true, array_column( $bottom_full_width_blocks, 'option' ) ) ) {
            return;
        }
        ?>
            <section id="bottom-full-width-section" class="local-news-section bottom-full-width-section">
                <div class="ln-container">
                    <div class="row">
                        <?php
                            foreach( $bottom_full_width_blocks as $block ) :
                                if( $block->option ) :
                                    $type = $block->type;
                                    switch($type) {
                                        case 'shortcode-block' : local_news_shortcode_block_html( $block, true );
                                                        break;
                                        case 'ad-block' : local_news_advertisement_block_html( $block, true );
                                                        break;
                                        default: $layout = $block->layout;
                                                $order = $block->query->order;
                                                $postCategories = $block->query->categories;
                                                $customexclude_ids = $block->query->ids;
                                                $orderArray = explode( '-', $order );
                                                $block_args = array(
                                                    'post_args' => array(
                                                        'post_type' => 'post',
                                                        'order' => esc_html( $orderArray[1] ),
                                                        'orderby' => esc_html( $orderArray[0] ),
                                                        'posts_per_page' => absint( $block->query->count )
                                                    ),
                                                    'options'    => $block
                                                );
                                                if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = $customexclude_ids;
                                                if( $postCategories ) $block_args['post_args']['category_name'] = local_news_get_categories_for_args($postCategories);
                                                // get template file w.r.t par
                                                get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                    }
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'local_news_bottom_full_width_blocks_hook', 'local_news_bottom_full_width_blocks_part', 10 );
endif;