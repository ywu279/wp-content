<?php
/**
 * Adds Local_News_Posts_List_Widget widget.
 * 
 * @package LocalNews
 * @since 1.0.0
 */
class Local_News_Posts_List_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'local_news_posts_list_widget',
            esc_html__( 'LN : Posts List', 'localnews' ),
            array( 'description' => __( 'A collection of posts from specific category displayed in list layout.', 'localnews' ) )
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
        $posts_count = isset( $instance['posts_count'] ) ? $instance['posts_count'] : 3;
        echo wp_kses_post($before_widget);
            if ( ! empty( $widget_title ) ) {
                echo $before_title . $widget_title . $after_title;
            }
    ?>
            <div class="posts-wrap posts-list-wrap feature-post-block layout-one">
                <?php
                    $post = new WP_Query( 
                        array( 
                            'category_name'    => esc_html( $posts_category ),
                            'posts_per_page' => absint( $posts_count )
                        )
                    );
                    if( $post->have_posts() ) :
                        while( $post->have_posts() ) : $post->the_post();
                            $thumbnail_url = get_the_post_thumbnail_url();
                            $categories = get_the_category();
                    ?>
                            <div class="post-item format-standard">
                                <div class="post_thumb_image bmm-post-thumb card__cover <?php if( !$thumbnail_url ) echo esc_attr('no-feat-img'); ?>">
                                    <figure class="post-thumb">

                                        <?php if( $thumbnail_url ) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo esc_url( $thumbnail_url ); ?>" loading="<?php local_news_lazy_load_value(); ?>">
                                            </a>
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <div class="post-content-wrap card__content">
                                    <?php
                                        echo '<div class="bmm-post-cats-wrap bmm-post-meta-item">';
                                            $count = 0;
                                            foreach( $categories as $cat ) {
                                                echo '<h5 class="card__content-category post-cat-' .esc_attr( $cat->cat_ID ). '"><a href="' .esc_url( ( $cat->cat_ID ) ). '">' .esc_html( $cat->name ). '</a></h5>';
                                                if( $count > 0 ) break;
                                                $count++;
                                            }
                                        echo '</div>';
                                        echo '<div class="bmm-post-title card__content-title post-title"><a href="' .esc_url( get_the_permalink() ). '">' .esc_html( get_the_title() ).'</a></div>';
                                        echo '<div class="post-content card__content-info">' .esc_html( get_the_excerpt() ). '</div>';
                                    ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    endif;
                ?>
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
                    'default'   => esc_html__( 'Trending News', 'localnews' )
                ),
                array(
                    'name'      => 'posts_category',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Categories', 'localnews' ),
                    'description'=> esc_html__( 'Choose the category to display list of posts', 'localnews' ),
                    'options'   => $categories_options
                ),
                array(
                    'name'      => 'posts_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'Number of posts to show', 'localnews' ),
                    'default'   => 3
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
 
} // class Local_News_Posts_List_Widget