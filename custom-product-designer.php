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


//Add our custom template to the admin's templates dropdown
// add_filter( 'theme_fanclubs-design_templates', 'pluginname_template_as_option', 10, 3 );

// function pluginname_template_as_option( $page_templates, $theme, $post ){
//     $page_templates['single-fanclubs-design.php'] = 'Fan Design';
//     return $page_templates;
// }

// add_filter( 'template_include', 'custom_template_include', 99 );
// function custom_template_include( $template ){
//     // For ID 93, load in file by using it's PATH (not URL)
// 	$file = require plugin_dir_path( __FILE__ ) . 'single-fanclubs-design.php';
//     if( file_exists( $file ) )
// 		$template = $file;

//     // ALWAYS return the $template, or *everything* will be blank.
//     return $template;
// }


add_filter('template_include','single_page_template');

function single_page_template($single_template) {
    global $post;

    if ($post->post_type == 'fanclubs-design') {
        $single_template = require plugin_dir_path( __FILE__ ) . 'single-fanclubs-design.php';
        
	}
    return $single_template;
}

// add_filter( 'woocommerce_locate_template', 'woo_adon_plugin_template', 1, 3 );
// function woo_adon_plugin_template( $template, $template_name, $template_path ) {
// 	global $woocommerce;
// 	$_template = $template;
// 	if ( ! $template_path ) 
// 	   $template_path = $woocommerce->template_url;

// 	$plugin_path  = require plugin_dir_path( __FILE__ ) .'woocommerce/';

// //    // Look within passed path within the theme - this is priority
//    $template = locate_template(
//    array(
// 	 $template_path . $template_name,
// 	 $template_name
//    )
//   );

//   if( ! $template && file_exists( $plugin_path . $template_name ) )
//    $template = $plugin_path . $template_name;

//   if ( ! $template )
//    $template = $_template;

//   return $template;
// }


/**
 * Filter the cart template path to use our cart.php template instead of the theme's
 */
// function csp_locate_template( $template, $template_name, $template_path ) {
// 	$basename = basename( $template );
// 	if( $basename == 'custom-product-designer.php' ) {
// 	$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'woocommerce/custom-product-designer.php';
// 	var_dump($template);
// 	}
// 	return $template;
//    }
//    add_filter( 'woocommerce_locate_template', 'csp_locate_template', 10, 3 );


// $directory = get_template_directory()."/woocommerce/";
// if (!file_exists($directory)) {
// 	mkdir($directory, 0777, true);
// } else {
// 	$myfile = fopen($directory."/request-quote-table.php", "w") or die("Unable to open file!");
// 	$txt = file_get_contents(plugin_dir_path( __FILE__ ) . 'woocommerce/request-quote-table.php');
// 	fwrite($myfile, $txt);
// 	fclose($myfile);
// }