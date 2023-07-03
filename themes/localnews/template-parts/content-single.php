<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package LocalNews
 */
use LocalNews\CustomizerDefault as LND;
$single_post_element_order = LND\local_news_get_customizer_option( 'single_post_element_order' );
$single_post_meta_order = LND\local_news_get_customizer_option( 'single_post_meta_order' );
?>
<article <?php local_news_schema_article_attributes(); ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-inner">
		<header class="entry-header">
			<?php
				foreach( $single_post_element_order as $element_order ) :
					if( $element_order['option'] ) {
						switch( $element_order['value'] ) {
							case 'categories': the_category();
												break;
							case 'title': the_title( '<h1 class="entry-title"' .local_news_schema_article_name_attributes(). '>', '</h1>' );
												break;
							case 'meta':	if ( 'post' === get_post_type() ) :
												?>
												<div class="entry-meta">
													<?php
														foreach( $single_post_meta_order as $meta_order ) :
															if( $meta_order['option'] ) {
																switch( $meta_order['value'] ) {
																	case 'author': local_news_posted_by();
																				break;
																	case 'date': local_news_posted_on();
																				break;
																	case 'comments': local_news_comments_number();
																				break;
																	case 'read-time': echo '<span class="read-time">' .absint(local_news_post_read_time( get_the_content() )). ' ' .esc_html__( 'mins', 'localnews' ). '</span>';
																				break;
																	default: '';
																}
															}
														endforeach;
													?>
												</div><!-- .entry-meta -->
											<?php endif;
											break;
							case 'thumbnail':	local_news_post_thumbnail();
														break;
							default: '';
						}
					}
				endforeach;
			?>
		</header><!-- .entry-header -->

		<div <?php local_news_schema_article_body_attributes(); ?> class="entry-content">
			<?php
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'localnews' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'localnews' ),
						'after'  => '</div>',
					)
				);
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php local_news_tags_list(); ?>
			<?php local_news_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle"><i class="fas fa-angle-double-left"></i>' . esc_html__( 'Previous:', 'localnews' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'localnews' ) . '<i class="fas fa-angle-double-right"></i></span> <span class="nav-title">%title</span>',
				)
			);
		?>
	</div>
	<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php
	/**
	 * hook - local_news_single_post_append_hook
	 * 
	 */
	do_action( 'local_news_single_post_append_hook' );
?>