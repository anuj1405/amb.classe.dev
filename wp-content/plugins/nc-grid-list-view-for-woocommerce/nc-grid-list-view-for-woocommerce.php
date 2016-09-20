<?php

/**
 * @wordpress-plugin
 * Plugin Name:       NC Grid List View For Woocommerce
 * Plugin URI:        http://wordpress.org/nc-grid-list-view-for-woocommerce
 * Description:       This plugin allow you to add list and grid view to your woocommerce shop page.
 * Version:           1.0.1
 * Author:            Nabaraj Chapagain
 * Author URI:        http://dovecreation.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nc-grid-list-view-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_nc_grid_list_view_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nc-grid-list-view-for-woocommerce-activator.php';
	NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_nc_grid_list_view_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nc-grid-list-view-for-woocommerce-deactivator.php';
	NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nc_grid_list_view_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_nc_grid_list_view_for_woocommerce' );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  ) { 
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nc-grid-list-view-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE() {

	$plugin = new NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE();
	$plugin->run();

}
run_NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE();

/**
 * NC Grid List View page link 
 * @since      1.0.1
*/	

 function NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_add_settings_link( $links ) 
		{
   			$settings_link = '<a href="admin.php?page=nc_grid_list_view">' . __( 'Configure','nc-grid-list-view-for-woocommerce' ) . '</a>';
   			array_push( $links, $settings_link );
  			return $links;
		}
				$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_add_settings_link');
}