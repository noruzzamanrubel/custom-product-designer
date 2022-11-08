<?php

/**
 * Fired during plugin activation
 *
 * @link       https://http://boomdevs.com/
 * @since      1.0.0
 *
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Custom_Product_Designer_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        //create request quote table in woocommerce folder
        $directory = get_template_directory() . "/woocommerce/";

        if ( ! file_exists( $directory ) ) {
            mkdir( $directory, 0777, true );
        } else {
            $myfile = fopen( $directory . "/request-quote-table.php", "w" ) or die( "Unable to open file!" );
            $txt    = file_get_contents( plugin_dir_path( __FILE__ ) . 'woocommerce/request-quote-table.php' );
            fwrite( $myfile, $txt );
            fclose( $myfile );
        }

        //create single-fanclubs-design.php in theme folder
        $single_directory = get_template_directory() . "/";

        if ( ! file_exists( $single_directory ) ) {
            mkdir( $single_directory, 0777, true );
        } else {
            $myfile = fopen( $single_directory . "single-fanclubs-design.php", "w" ) or die( "Unable to open file!" );
            $txt    = file_get_contents( plugin_dir_path( __FILE__ ) . 'single-fanclubs-design.php' );
            fwrite( $myfile, $txt );
            fclose( $myfile );
        }

    }

}
