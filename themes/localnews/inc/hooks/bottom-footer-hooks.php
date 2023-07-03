<?php
/**
 * Bottom Footer hooks and functions
 * 
 * @package LocalNews
 * @since 1.0.0
 */
use LocalNews\CustomizerDefault as LND;

if( ! function_exists( 'local_news_botttom_footer_social_part' ) ) :
   /**
    * Bottom Footer social element
    * 
    * @since 1.0.0
    */
   function local_news_botttom_footer_social_part() {
     if( ! LND\local_news_get_customizer_option( 'bottom_footer_social_option' ) ) return;
     ?>
        <div class="social-icons-wrap">
           <?php local_news_customizer_social_icons(); ?>
        </div>
     <?php
   }
   add_action( 'local_news_botttom_footer_hook', 'local_news_botttom_footer_social_part', 10 );
endif;

 if( ! function_exists( 'local_news_bottom_footer_menu_part' ) ) :
      /**
       * Bottom Footer menu element
       * 
       * @since 1.0.0
       */
      function local_news_bottom_footer_menu_part() {
         if( ! LND\local_news_get_customizer_option( 'bottom_footer_menu_option' ) ) return;
         ?>
            <div class="bottom-menu">
               <?php
               if( has_nav_menu( 'menu-3' ) ) :
                  wp_nav_menu(
                     array(
                        'theme_location' => 'menu-3',
                        'menu_id'        => 'bottom-footer-menu',
                        'depth' => 1
                     )
                  );
                  else :
                     if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
                        ?>
                           <a href="<?php echo esc_url( admin_url( '/nav-menus.php?action=locations' ) ); ?>"><?php esc_html_e( 'Setup Bottom Footer Menu', 'localnews' ); ?></a>
                        <?php
                     }
                  endif;
               ?>
            </div>
         <?php
      }
      add_action( 'local_news_botttom_footer_hook', 'local_news_bottom_footer_menu_part', 20 );
 endif;

 if( ! function_exists( 'local_news_bottom_footer_copyright_part' ) ) :
   /**
    * Bottom Footer copyright element
    * 
    * @since 1.0.0
    */
   function local_news_bottom_footer_copyright_part() {
      $bottom_footer_site_info = LND\local_news_get_customizer_option( 'bottom_footer_site_info' );
      if( ! $bottom_footer_site_info ) return;
     ?>
        <div class="site-info">
            <?php echo wp_kses_post( str_replace( '%year%', date('Y'), $bottom_footer_site_info ) ); ?>
				<?php echo sprintf( esc_html( 'Free Theme By %s.', 'localnews' ), '<a href="https://blazethemes.com/">' .esc_html( 'BlazeThemes' ). '</a>'  ); ?>
        </div>
     <?php
   }
   add_action( 'local_news_botttom_footer_hook', 'local_news_bottom_footer_copyright_part', 30 );
endif;