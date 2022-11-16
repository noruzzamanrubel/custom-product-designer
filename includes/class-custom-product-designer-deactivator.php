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
        $single_related_product = get_template_directory() . "/woocommerce/single-product/related.php";
        $single_product_image = get_template_directory() . "/woocommerce/single-product/product-image.php";
        $single_product_meta = get_template_directory() . "/woocommerce/single-product/meta.php";

        if ( file_exists( $woo_request_quote_table ) ) {
            unlink( $woo_request_quote_table );
        }
        if ( file_exists( $single_related_product ) ) {
            unlink( $single_related_product );
        }
        if ( file_exists( $single_product_image ) ) {
            unlink( $single_product_image );
        }
        if ( file_exists( $single_product_meta ) ) {
            unlink( $single_product_meta );
        }

        //delete single-fanclubs-design.php from theme folder
        $single_design_file = get_template_directory(). "/single-fanclubs-design.php";
        $archive_design_file = get_template_directory(). "/archive-fanclubs-design.php";

        if ( file_exists( $single_design_file ) ) {
            unlink( $single_design_file );
        }
        if ( file_exists( $archive_design_file ) ) {
            unlink( $archive_design_file );
        }

    }

}
