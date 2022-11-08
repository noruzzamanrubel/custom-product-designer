<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://http://boomdevs.com/
 * @since             1.0.0
 * @package           Custom_Product_Designer
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Product Designer
 * Plugin URI:        https://#
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            BoomDevs
 * Author URI:        https://http://boomdevs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-product-designer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_PRODUCT_DESIGNER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-product-designer-activator.php
 */
function activate_custom_product_designer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-product-designer-activator.php';
	Custom_Product_Designer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-product-designer-deactivator.php
 */
function deactivate_custom_product_designer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-product-designer-deactivator.php';
	Custom_Product_Designer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_product_designer' );
register_deactivation_hook( __FILE__, 'deactivate_custom_product_designer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-product-designer.php';


/**
 * 
 */
// require plugin_dir_path( __FILE__ ) . 'woocommerce/request-quote-table.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_product_designer() {

	$plugin = new Custom_Product_Designer();
	$plugin->run();

}
run_custom_product_designer();