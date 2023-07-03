<?php
/**
 * Adds Local_News_Carousel_Widget widget.
 * 
 * @package LocalNews
 * @since 1.0.0
 */
class Local_News_Carousel_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'local_news_carousel_widget',
            esc_html__( 'LN : Carousel Posts', 'localnews' ),
            array( 'description' => __( 'A collection of posts from specific category for carousel slide.', 'localnews' ) )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        $posts_category = isset( $instance['posts_category'] ) ? $instance['posts_category'] : '';
        
        echo wp_kses_post($before_widget);
            // Slider direction
            $local_news_widget_attr = ' local_horizontal_slider';
            if( empty( $widget_title ) ) $local_news_widget_attr .= ' no_heading_widget';
            $local_news_widget_attr .= ' layout--one';
            ?>
            <div class="local-news-widget-carousel-posts<?php echo esc_attr($local_news_widget_attr); ?>">
                <?php if ($widget_title ): ?>
                    <h2 class="widget-title">
                        <?php echo esc_html($widget_title); ?>
                    </h2>
                <?php endif; ?>
                <div class="carousel-posts-wrap" data-auto="true" data-arrows="true" data-loop="false" data-vertical="horizontal">
                    <?php
                        $carousel_posts_args = array( 
                                    'numberposts' => -1,
                                    'category_name' => esc_html( $posts_category )
                                );
                        if( empty( $posts_category ) ) $carousel_posts_args['numberposts'] = 4;
                        $carousel_posts = get_posts( $carousel_posts_args );
                        if( $carousel_posts ) :
                            $total_posts = sizeof($carousel_posts);
                            foreach( $carousel_posts as $carousel_post_key => $carousel_post ) :
                                $carousel_post_id  = $carousel_post->ID;
                            ?>
                                    <article class="post-item <?php if(!has_post_thumbnail($carousel_post_id)){ echo esc_attr('no-feat-img');} ?>">
                                        <figure class="post-thumb-wrap">
                                            <?php if( has_post_thumbnail($carousel_post_id) ): ?> 
                                                <a href="<?php echo esc_url(get_the_permalink($carousel_post_id)); ?>">
                                                    <img src="<?php echo esc_url( get_the_post_thumbnail_url($carousel_post_id, 'local-news-grid') ); ?>"/>
                                                </a>
                                            <?php endif; ?>
                                            <?php local_news_get_post_categories($carousel_post_id,2); ?>
                                        </figure>
                                        <div class="post-element">
                                            <?php local_news_get_post_categories($carousel_post_id,2); ?>
                                            <h2 class="post-title"><a href="<?php the_permalink($carousel_post_id); ?>"><?php echo wp_kses_post( get_the_title($carousel_post_id) ); ?></a></h2>
                                            <div class="post-meta">
                                                <?php local_news_posted_on(); ?>
                                            </div>
                                        </div>
                                    </article>
                            <?php
                            endforeach;
                        endif;
                    ?>
                </div>
            </div>
    <?php
        echo wp_kses_post($after_widget);
    }

    /**
     * Widgets fields
     * 
     */
    function widget_fields() {
        $categories = get_categories();
        $categories_options[''] = esc_html__( 'Select category', 'localnews' );
        foreach( $categories as $category ) :
            $categories_options[$category->slug] = $category->name. ' (' .$category->count. ') ';
        endforeach;
        return array(
                array(
                    'name'      => 'widget_title',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Widget Title', 'localnews' ),
                    'description'=> esc_html__( 'Add the widget title here', 'localnews' ),
                    'default'   => esc_html__( 'Highlights', 'localnews' )
                ),
                array(
                    'name'      => 'posts_category',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Categories', 'localnews' ),
                    'description'=> esc_html__( 'Choose the category to display for carousel posts', 'localnews' ),
                    'options'   => $categories_options
                )
            );
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();
        foreach( $widget_fields as $widget_field ) :
            if ( isset( $instance[ $widget_field['name'] ] ) ) {
                $field_value = $instance[ $widget_field['name'] ];
            } else if( isset( $widget_field['default'] ) ) {
                $field_value = $widget_field['default'];
            } else {
                $field_value = '';
            }
            local_news_widget_fields( $this, $widget_field, $field_value );
        endforeach;
    }
 
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        if( ! is_array( $widget_fields ) ) {
            return $instance;
        }
        foreach( $widget_fields as $widget_field ) :
            $instance[$widget_field['name']] = local_news_sanitize_widget_fields( $widget_field, $new_instance );
        endforeach;

        return $instance;
    }
 
} // class Local_News_Carousel_Widget