<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LocalNews
 */

 /**
  * hook - local_news_before_footer_section
  * 
  */
  do_action( 'local_news_before_footer_section' );
?>
	<footer id="colophon" class="site-footer dark_bk">
		<?php
			/**
			 * Function - local_news_footer_sections_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			local_news_footer_sections_html();

			/**
			 * Function - local_news_bottom_footer_sections_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			local_news_bottom_footer_sections_html();
		?>
	</footer><!-- #colophon -->
	<?php
		/**
		* hook - local_news_after_footer_hook
		*
		* @hooked - local_news_scroll_to_top
		*
		*/
		if( has_action( 'local_news_after_footer_hook' ) ) {
			do_action( 'local_news_after_footer_hook' );
		}
	?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>