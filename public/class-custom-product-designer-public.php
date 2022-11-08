<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://http://boomdevs.com/
 * @since      1.0.0
 *
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Product_Designer
 * @subpackage Custom_Product_Designer/public
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Custom_Product_Designer_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Product_Designer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Product_Designer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name.'carousel', plugin_dir_url( __FILE__ ) . 'css/owl.carousel.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'carousel-default', plugin_dir_url( __FILE__ ) . 'css/owl.theme.default.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'custom-product-design', plugin_dir_url( __FILE__ ) . 'css/custom-product-designer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Product_Designer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Product_Designer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . 'custom-product-design-carousel', plugin_dir_url( __FILE__ ) . 'js/owl.carousel.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'custom-product-design', plugin_dir_url( __FILE__ ) . 'js/custom-product-designer-public.js', array( 'jquery' ), $this->version, false );

		if( isset($_GET['d_id']) ) {
            wp_localize_script( $this->plugin_name . 'custom-product-design', 'fan_design', array(
                'variation_id' => $_GET['d_id'],
            ));
        }

	}

}
