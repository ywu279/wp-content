<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package LocalNews
 */
use LocalNews\CustomizerDefault as LND;
get_header();
?>
<div id="theme-content">
	<?php
		/**
		 * hook - local_news_before_main_content
		 * 
		 */
		do_action( 'local_news_before_main_content' );
	?>
	<main id="primary" class="site-main">
		<div class="ln-container">
           	<div class="row">
			   <div class="secondary-left-sidebar">
					<?php
						get_sidebar('left');
					?>
				</div>
           		<div class="primary-content">
					<section class="error-404 not-found">
						<?php
							/**
							 * hook - local_news_before_inner_content
							 * 
							 */
							do_action( 'local_news_before_inner_content' );
						?>
						<div class="post-inner-wrapper">
							<header class="page-header">
								<h1 class="page-title ln-block-title"><?php echo local_news_wrap_last_word( esc_html__( '404 Not Found', 'localnews' ) ); ?></h1>
							</header><!-- .page-header -->

							<div class="page-content">
								<p><?php echo esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'localnews' ); ?></p>
							</div><!-- .page-content -->

							<div class="page-footer">
								<a class="button-404" href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html__( 'Go back to home', 'localnews' ); ?></a>
							</div>
						</div><!-- .post-inner-wrapper -->
					</section><!-- .error-404 -->
				</div>
				<div class="secondary-sidebar">
					<?php
						get_sidebar();
					?>
				</div>
			</div>
		</div>
	</main><!-- #main -->
</div><!-- #theme-content -->
<?php
get_footer();
