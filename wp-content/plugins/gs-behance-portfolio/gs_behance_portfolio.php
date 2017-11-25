<?php 
/**
 *
 * @package   GS_Behance_Portfolio
 * @author    Golam Samdani <samdani1997@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.gsamdani.com
 * @copyright 2016 Golam Samdani
 *
 * @wordpress-plugin
 * Plugin Name:			GS Behance Portfolio Lite
 * Plugin URI:			http://www.gsamdani.com/wordpress-plugins
 * Description:       	Best Responsive Behance plugin for Wordpress to showcase Behance projects. Display anywhere at your site using shortcode like [gs_behance] & widgets. Check more shortcode examples and documentation at <a href="http://www.behance.gsamdani.com">GS Behance Porfolio PRO Demos & Docs</a>
 * Version:           	1.0.0
 * Author:       		Golam Samdani
 * Author URI:       	http://www.gsamdani.com
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
if( ! defined( 'GSBEH_VERSION' ) ) define( 'GSBEH_VERSION', '1.0.0' );
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
		$gsBehan_links[] = '<a class="gs-pro-link" href="https://www.gsamdani.com/product/gs-behance-portfolio" target="_blank">Go Pro!</a>';
		$gsBehan_links[] = '<a href="https://www.gsamdani.com/wordpress-plugins" target="_blank">GS Plugins</a>';
		return $gsBehan_links;
	}
	add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'gs_behance_pro_link' );
}