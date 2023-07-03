<?php
/**
 * Ticker news template one
 * 
 * @package LocalNews
 * @since 1.0.0
 */
$ticker_query = new WP_Query( $args );
if( $ticker_query->have_posts() ) :
    while( $ticker_query->have_posts() ) : $ticker_query->the_post();
    ?>
        <li class="ticker-item">
              <figure class="feature_image">
                <?php
                if( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php
                            the_post_thumbnail('local-news-thumb', array(
                                        'title' => the_title_attribute(array(
                                            'echo'  => false
                                        ))
                                    ));
                                ?>
                    </a>
                <?php endif;
                ?>
                </figure>
            
            <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( local_news_limit_string( get_the_title(), 60 ) ); ?></a></h2>
        </li>
    <?php
    endwhile;
endif;