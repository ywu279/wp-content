<?php
/**
 * Main Banner template one
 * 
 * @package LocalNews
 * @since 1.0.0
 */
use LocalNews\CustomizerDefault as LND;
$slider_args = $args['slider_args'];
?>
<div class="main-banner-wrap">
    <div class="main-banner-slider" data-auto="true" data-arrows="true" data-dots="true">
        <?php
            $slider_query = new WP_Query( $slider_args );
            if( $slider_query -> have_posts() ) :
                while( $slider_query -> have_posts() ) : $slider_query -> the_post();
                ?>
                <article class="slide-item <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                    <div class="post_slider_template_one">
                        <figure class="post-thumb-wrap">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                <?php 
                                    if( has_post_thumbnail()) {
                                        the_post_thumbnail('local-news-featured', array(
                                            'title' => the_title_attribute(array(
                                                'echo'  => false
                                            ))
                                        ));
                                    }
                                ?>
                            </a>
                        </figure>
                        <div class="post-element">
                            <div class="post-meta">
                                <?php if( LND\local_news_get_customizer_option( 'main_banner_slider_categories_option' ) ) local_news_get_post_categories( get_the_ID(), 2 ); ?>
                                <?php if( LND\local_news_get_customizer_option( 'main_banner_slider_date_option' ) ) local_news_posted_on(); ?>
                            </div>
                            <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                            <?php if( LND\local_news_get_customizer_option( 'main_banner_slider_excerpt_option' ) ) :
                                 ?>
                                <div class="post-excerpt"><?php the_excerpt(); ?></div>
                            <?php 
                            endif; ?>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
        endif;
        ?>
    </div>
</div>
<div class="main-banner-tabs">
    <ul class="banner-tabs">
        <?php $main_banner_latest_tab_title = LND\local_news_get_customizer_option( 'main_banner_latest_tab_title' ); ?> 
            <li class="banner-tab latest-tab active" tab-item="latest">
                <?php if( $main_banner_latest_tab_title['icon'] != 'fas fa-ban' ) { ?><i class="<?php echo esc_attr( $main_banner_latest_tab_title['icon'] ); ?>"></i><?php } ?>
                <?php echo esc_html( $main_banner_latest_tab_title['text'] ); ?>
            </li>
        <?php $main_banner_popular_tab_title = LND\local_news_get_customizer_option( 'main_banner_popular_tab_title' ); ?>
            <li class="banner-tab popular-tab" tab-item="popular">
                <?php if( $main_banner_popular_tab_title['icon'] != 'fas fa-ban' ) { ?><i class="<?php echo esc_attr( $main_banner_popular_tab_title['icon'] ); ?>"></i><?php } ?><?php echo esc_html( $main_banner_popular_tab_title['text'] ); ?>
            </li>
        <?php $main_banner_comments_tab_title = LND\local_news_get_customizer_option( 'main_banner_comments_tab_title' ); ?>
            <li class="banner-tab comments-tab" tab-item="comments">
                <?php if( $main_banner_comments_tab_title['icon'] != 'fas fa-ban' ) { ?><i class="<?php echo esc_attr( $main_banner_comments_tab_title['icon'] ); ?>"></i><?php } ?><?php echo esc_html( $main_banner_comments_tab_title['text'] ); ?>
            </li>
    </ul>
    <div class="banner-tabs-content">
        <div class="tab-item active" tab-content="latest">
            <?php
                $latest_tab_posts = get_posts( array( 'numberposts' => 4 ) );
                if( $latest_tab_posts ) :
                    foreach( $latest_tab_posts as $latest_tab_post ) :
                        $latest_tab_id  = $latest_tab_post->ID;
                    ?>
                        <article class="post-item <?php if(!has_post_thumbnail($latest_tab_id)){ echo esc_attr('no-feat-img');} ?>">
                            <figure class="post-thumb">
                                <?php if( has_post_thumbnail($latest_tab_id) ): ?>
                                    <a href="<?php echo esc_url(get_the_permalink( $latest_tab_id )); ?>" title="<?php the_title_attribute(array( 'post' => $latest_tab_id )); ?>"><img src="<?php echo esc_url( get_the_post_thumbnail_url($latest_tab_id, 'local-news-thumb') ); ?>"/></a>
                                <?php endif; ?>
                            </figure>
                            <div class="post-element">
                                <div class="post-meta">
                                    <?php local_news_get_post_categories($latest_tab_id,2); ?>
                                    <?php local_news_posted_on($latest_tab_id); ?>
                                </div>
                                <h2 class="post-title"><a href="<?php the_permalink($latest_tab_id); ?>" title="<?php the_title_attribute(array( 'post' => $latest_tab_id )); ?>"><?php echo esc_html( local_news_limit_string( get_the_title($latest_tab_id) ) ); ?></a></h2>
                            </div>
                        </article>
                    <?php
                    endforeach;
                endif;
            ?>
        </div>
        <div class="tab-item" tab-content="popular">
            <?php
                $main_banner_popular_tab_categories = json_decode( LND\local_news_get_customizer_option( 'main_banner_popular_tab_categories' ) );
                $popular_tab_posts = get_posts( array( 'numberposts' => 4, 'category_name' => local_news_get_categories_for_args($main_banner_popular_tab_categories) ) );
                if( $popular_tab_posts ) :
                    foreach( $popular_tab_posts as $popular_tab_post ) :
                        $popular_tab_id  = $popular_tab_post->ID;
                    ?>
                        <article class="post-item <?php if(!has_post_thumbnail($popular_tab_id)){ echo esc_attr('no-feat-img');} ?>">
                            <figure class="post-thumb">
                                <?php if( has_post_thumbnail($popular_tab_id) ): ?>
                                    <a href="<?php echo esc_url(get_the_permalink($popular_tab_id)); ?>" title="<?php the_title_attribute(array( 'post' => $popular_tab_id )); ?>"><img src="<?php echo esc_url( get_the_post_thumbnail_url($popular_tab_id, 'local-news-thumb') ); ?>"/></a>
                                <?php endif; ?>
                            </figure>
                            <div class="post-element">
                                <div class="post-meta">
                                    <?php local_news_get_post_categories( $popular_tab_id, 2 ); ?>
                                    <?php local_news_posted_on($popular_tab_id); ?>
                                </div>
                                <h2 class="post-title"><a href="<?php the_permalink($popular_tab_id); ?>" title="<?php the_title_attribute(array( 'post' => $popular_tab_id )); ?>">
                                    <?php echo esc_html( local_news_limit_string( get_the_title($popular_tab_id) ) ); ?>
                                </a></h2>
                            </div>
                        </article>
                    <?php
                    endforeach;
                endif;
            ?>
        </div>
        <div class="tab-item" tab-content="comments">
            <?php
                $banner_comments = get_comments(array( 'number'   => 4 ));
                if( $banner_comments ) :
                    foreach( $banner_comments as $banner_comment ) :
                ?>
                        <div class="comment-item">
                            <figure class="ln_avatar">
                                    <a href="<?php echo esc_url( get_comment_link( $banner_comment->comment_ID ) ); ?>">
                                        <?php echo get_avatar( $banner_comment->comment_author_email, 50 ); ?>     
                                    </a>                               
                            </figure> 
                            <div class="ln-comm-content">
                                <a href="<?php echo esc_url( get_comment_link( $banner_comment->comment_ID ) ); ?>">
                                    <span class="ln-comment-author"><?php echo esc_html( get_comment_author( $banner_comment->comment_ID ) ); ?> </span> - <span class="ln_comment_post"><?php 
                                        echo esc_html( local_news_limit_string( get_the_title($banner_comment->comment_post_ID)) );
                                    ?></span>
                                </a>
                                <p class="ln-comment">
                                    <?php echo wp_kses_post( local_news_limit_string(  $banner_comment->comment_content, 60 ) ); ?>
                                </p>
                            </div>

                        </div>
                <?php
                    endforeach;
                endif;
            ?>
        </div>
    </div>
</div>