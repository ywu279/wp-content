<?php
/**
 * Top Header hooks and functions
 * 
 * @package LocalNews
 * @since 1.0.0
 */
use LocalNews\CustomizerDefault as LND;
 if( ! function_exists( 'local_news_top_header_menu_part' ) ) :
    /**
     * Top header menu element
     * 
     * @since 1.0.0
     */
    function local_news_top_header_menu_part() {
      if( ! LND\local_news_get_customizer_option( 'top_header_menu_option' ) ) return;
      ?>
         <div class="top-menu">
				<?php
               if( has_nav_menu( 'menu-1' ) ) :
                  wp_nav_menu(
                     array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'top-header-menu',
                        'depth' => 1
                     )
                  );
               else :
                  if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
                     ?>
                        <a href="<?php echo esc_url( admin_url( '/nav-menus.php?action=locations' ) ); ?>"><?php esc_html_e( 'Setup Top Header Menu', 'localnews' ); ?></a>
                     <?php
                  }
               endif;
				?>
			</div>
      <?php
    }
    add_action( 'local_news_top_header_hook', 'local_news_top_header_menu_part', 10 );
 endif;

 if( ! function_exists( 'local_news_top_header_social_part' ) ) :
   /**
    * Top header social element
    * 
    * @since 1.0.0
    */
   function local_news_top_header_social_part() {
     if( ! LND\local_news_get_customizer_option( 'top_header_social_option' ) ) return;
     ?>
        <div class="social-icons-wrap">
           <?php local_news_customizer_social_icons(); ?>
        </div>
     <?php
   }
   add_action( 'local_news_top_header_hook', 'local_news_top_header_social_part', 20 );
endif;