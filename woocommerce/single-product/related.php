<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( $related_products ) : ?>

    <section class="related products">

        <?php
        $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );

        if ( $heading ) :
            ?>
            <h2><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>

        <?php woocommerce_product_loop_start();?>

        <ul class="fan_product_carousel owl-carousel owl-theme">
            <?php
            if(isset($_GET['d_id'])) {
                $meta = get_post_meta( $_GET['d_id'], 'fan_clubs', true );
                $product_meta = $meta['fanclubs_select_product'];
                $fanclubs_upload_img = $meta['fanclubs_upload_img'];

                $fanclubs_design_page = $meta['fanclubs_design_page'];
                $fanclubs_design_page_width = $fanclubs_design_page['fanclubs_design_page_width'];
                $fanclubs_design_page_top = $fanclubs_design_page['fanclubs_design_page_top'];
                $fanclubs_design_page_left = $fanclubs_design_page['fanclubs_design_page_left'];

                foreach($product_meta as $value) {

                    $product_id = $value['fanclubs_product'];
                    $fanclubs_product_upload_img= $value['fanclubs_product_upload_img'];

                    $fanclubs_custom_design_page= $value['fanclubs_custom_design_page'];
                    $product_term = $fanclubs_custom_design_page['product_term_id'];

                    $fanclubs_product_custom_checkbox = $fanclubs_custom_design_page['fanclubs_product_custom_checkbox'];
                    $fanclubs_custom_design_page_width= $fanclubs_custom_design_page['fanclubs_custom_design_page_width'];
                    $fanclubs_custom_design_page_top= $fanclubs_custom_design_page['fanclubs_custom_design_page_top'];
                    $fanclubs_custom_design_page_left= $fanclubs_custom_design_page['fanclubs_custom_design_page_left'];

                    $product = wc_get_product( $product_id );
                    $product_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($product_id));
                    $productname= $product->get_title();
                    $custom_post_id = get_the_ID();

                    ?>
                    <a href="<?php echo $product->get_permalink().'?d_id='.$_GET['d_id']; ?>" target="_blank" rel="noopener noreferrer">
                        <li>
                            <div style="position: relative; overflow: hidden;">
                                <img src="<?php  echo $product_thumb[0]; ?>">
                                <?php
                                if($fanclubs_product_custom_checkbox == true){
                                    if($fanclubs_product_upload_img !== ""){
                                        ?>
                                        <img src="<?php echo $fanclubs_product_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                                        <?php
                                    }else {
                                        ?>
                                        <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                                        <?php
                                    }
                                }else {
                                    if($fanclubs_product_upload_img !== ""){
                                        ?>
                                        <img src="<?php echo $fanclubs_product_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                                        <?php
                                    }else {
                                        ?>
                                        <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php echo $productname; ?>
                        </li>
                    </a>
                    <?php
                }
            }
            ?>
        </ul>

        <?php woocommerce_product_loop_end(); ?>

    </section>
<?php
endif;

wp_reset_postdata();
