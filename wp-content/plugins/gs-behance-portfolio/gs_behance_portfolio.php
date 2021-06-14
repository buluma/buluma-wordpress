<?php 
/**
 *
 * @package   GS_Behance_Portfolio
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2016 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:			GS Projects for Behance Lite
 * Plugin URI:			https://www.gsplugins.com/wordpress-plugins
 * Description:       	Best Responsive Behance plugin for Wordpress to showcase Behance projects. Display anywhere at your site using shortcode like [gs_behance] & widgets. Check more shortcode examples and documentation at <a href="http://behance.gsplugins.com">GS Behance Porfolio PRO Demos & Docs</a>
 * Version:           	1.0.6
 * Author:       		GS Plugins
 * Author URI:       	https://www.gsplugins.com
 * Text Domain:       	gs-behance
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
*/

if( ! defined( 'GSBEH_HACK_MSG' ) ) define( 'GSBEH_HACK_MSG', __( 'Sorry cowboy! This is not your place', 'gs-behance' ) );

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GSBEH_HACK_MSG );

/**
 * Defining constants
 */
if( ! defined( 'GSBEH_VERSION' ) ) define( 'GSBEH_VERSION', '1.0.6' );
if( ! defined( 'GSBEH_MENU_POSITION' ) ) define( 'GSBEH_MENU_POSITION', 31 );
if( ! defined( 'GSBEH_PLUGIN_DIR' ) ) define( 'GSBEH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'GSBEH_FILES_DIR' ) ) define( 'GSBEH_FILES_DIR', GSBEH_PLUGIN_DIR . 'gs-behance-assets' );
if( ! defined( 'GSBEH_PLUGIN_URI' ) ) define( 'GSBEH_PLUGIN_URI', plugins_url( '', __FILE__ ) );
if( ! defined( 'GSBEH_FILES_URI' ) ) define( 'GSBEH_FILES_URI', GSBEH_PLUGIN_URI . '/gs-behance-assets' );

require_once GSBEH_FILES_DIR . '/gs-plugins/gs-plugins.php';
require_once GSBEH_FILES_DIR . '/includes/gs-behance-shortcode.php';
require_once GSBEH_FILES_DIR . '/admin/class.settings-api.php';
require_once GSBEH_FILES_DIR . '/admin/gs_behance_options_config.php';
require_once GSBEH_FILES_DIR . '/gs-behance-scripts.php';

if ( ! function_exists('gs_behance_pro_link') ) {
	function gs_behance_pro_link( $gsBehan_links ) {
		$gsBehan_links[] = '<a class="gs-pro-link" href="https://www.gsplugins.com/product/gs-behance-portfolio" target="_blank">Go Pro!</a>';
		$gsBehan_links[] = '<a href="https://www.gsplugins.com/wordpress-plugins" target="_blank">GS Plugins</a>';
		return $gsBehan_links;
	}
	add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'gs_behance_pro_link' );
}
/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_gs_behance_portfolio() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once GSBEH_FILES_DIR . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( 'faaa4259-d73c-4c90-b6c3-f96a676d343e', 'GS Projects for Behance', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_gs_behance_portfolio();

/**
 * @gs_behance_review_dismiss()
 * @gs_behance_review_pending()
 * @gs_behance_review_notice_message()
 * Make all the above functions working.
 */
function gs_behance_review_notice(){

    gs_behance_review_dismiss();
    gs_behance_review_pending();

    $activation_time    = get_site_option( 'gs_behance_active_time' );
    $review_dismissal   = get_site_option( 'gs_behance_review_dismiss' );
    $maybe_later        = get_site_option( 'gs_behance_maybe_later' );

    if ( 'yes' == $review_dismissal ) {
        return;
    }

    if ( ! $activation_time ) {
        add_site_option( 'gs_behance_active_time', time() );
    }
    
    $daysinseconds = 259200; // 3 Days in seconds.
   
    if( 'yes' == $maybe_later ) {
        $daysinseconds = 604800 ; // 7 Days in seconds.
    }

    if ( time() - $activation_time > $daysinseconds ) {
        add_action( 'admin_notices' , 'gs_behance_review_notice_message' );
    }
}
add_action( 'admin_init', 'gs_behance_review_notice' );

/**
 * For the notice preview.
 */
function gs_behance_review_notice_message(){
    $scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
    $url         = $_SERVER['REQUEST_URI'] . $scheme . 'gs_behance_review_dismiss=yes';
    $dismiss_url = wp_nonce_url( $url, 'gs_behance-review-nonce' );
    $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'gs_behance_review_later=yes';
    $later_url   = wp_nonce_url( $_later_link, 'gs_behance-review-nonce' );
    ?>
    
    <div class="gslogo-review-notice">
        <div class="gslogo-review-thumbnail">
            <img src="<?php echo GSBEH_FILES_URI . '/assets/img/icon-128x128.png'; ?>" alt="">
        </div>
        <div class="gslogo-review-text">
            <h3><?php _e( 'Leave A Review?', 'gs-behance' ) ?></h3>
            <p><?php _e( 'We hope you\'ve enjoyed using <b>GS Projects for Behance Lite</b>! Would you consider leaving us a review on WordPress.org?', 'gs-behance' ) ?></p>
            <ul class="gslogo-review-ul">
                <li>
                    <a href="https://wordpress.org/support/plugin/gs-behance-portfolio/reviews/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php _e( 'Sure! I\'d love to!', 'gs-behance' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php _e( 'I\'ve already left a review', 'gs-behance' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $later_url ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php _e( 'Maybe Later', 'gs-behance' ) ?>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsplugins.com/support/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php _e( 'I need help!', 'gs-behance' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php _e( 'Never show again', 'gs-behance' ) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <?php
}

/**
 * For Dismiss! 
 */
function gs_behance_review_dismiss(){

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gs_behance-review-nonce' ) ||
        ! isset( $_GET['gs_behance_review_dismiss'] ) ) {

        return;
    }

    add_site_option( 'gs_behance_review_dismiss', 'yes' );   
}

/**
 * For Maybe Later Update.
 */
function gs_behance_review_pending() {

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gs_behance-review-nonce' ) ||
        ! isset( $_GET['gs_behance_review_later'] ) ) {

        return;
    }
    // Reset Time to current time.
    update_site_option( 'gs_behance_active_time', time() );
    update_site_option( 'gs_behance_maybe_later', 'yes' );

}

/**
 * Remove Reviews Metadata on plugin Deactivation.
 */
function gs_behance_deactivate() {
    delete_option('gs_behance_active_time');
    delete_option('gs_behance_maybe_later');
}
register_deactivation_hook(__FILE__, 'gs_behance_deactivate');

if ( ! function_exists('gs_behance_row_meta') ) {
    function gs_behance_row_meta( $meta_fields, $file ) {
  
      if ( $file != 'gs-behance-portfolio-lite/gs_behance_portfolio.php' ) {
          return $meta_fields;
      }
    
        echo "<style>.gs-behance-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.gs-behance-rate-stars svg{ fill:#ffb900; } .gs-behance-rate-stars svg:hover{ fill:#ffb900 } .gs-behance-rate-stars svg:hover ~ svg{ fill:none; } </style>";
  
        $plugin_rate   = "https://wordpress.org/support/plugin/gs-behance-portfolio/reviews/?rate=5#new-post";
        $plugin_filter = "https://wordpress.org/support/plugin/gs-behance-portfolio/reviews/?filter=5";
        $svg_xmlns     = "https://www.w3.org/2000/svg";
        $svg_icon      = '';
  
        for ( $i = 0; $i < 5; $i++ ) {
          $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
        }
  
        // Set icon for thumbsup.
        $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'gscs' ) . '</a>';
  
        // Set icon for 5-star reviews. v1.1.22
        $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'gscs' ) . "'><i class='gs-behance-rate-stars'>" . $svg_icon . "</i></a>";
  
        return $meta_fields;
    }
    add_filter( 'plugin_row_meta','gs_behance_row_meta', 10, 2 );
  }

