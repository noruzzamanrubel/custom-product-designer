<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://http://boomdevs.com/
 * @since      1.0.0
 *
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Custom_Product_Designer_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {

        //delete request quote table from woocommerce folder
        $woo_request_quote_table = get_template_directory() . "/woocommerce/request-quote-table.php";

        if ( file_exists( $woo_request_quote_table ) ) {
            unlink( $woo_request_quote_table );
        }

        //delete single-fanclubs-design.php from theme folder
        $single_directory   = get_template_directory() . "/";
        $single_design_file = $single_directory . "single-fanclubs-design.php";

        if ( file_exists( $single_design_file ) ) {
            unlink( $single_design_file );
        }

    }

}
