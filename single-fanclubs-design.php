<?php
/*
 * Template Name: Fan Design
 * Template Post Type: fanclubs-design
 */

get_header(); ?>
    <div id="content-wrap" class="container clr">
        <div id="primary" class="clr">
            <div id="content" class="site-content clr">
                    <div class="fan_product_wrapper">
                        <div class="fan_product_top_wrapper">
                            <div class="fan_product_thumb">
                                   <?php echo get_the_post_thumbnail(get_the_ID()); ?>
                            </div>
                            <div class="fan_product_details">
                                <div class="fan_product_title">
                                    <h2><?php echo get_the_title(get_the_ID()); ?></h2>
                                </div>
                                <div class="fan_product_description">
                                    <?php echo get_the_content(get_the_ID()); ?>
                                </div>
                            </div>
                        </div>
                        <div class="fan_product_bottom_wrapper">
                            <h2>This design is available on</h2>
                            <ul class="fan_product_carousel owl-carousel owl-theme">
                                
                                <?php 
                                $meta = get_post_meta( get_the_ID(), 'fan_clubs', true );
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
                                    <a href="<?php echo $product->get_permalink().'?d_id='.$custom_post_id; ?>" target="_blank" rel="noopener noreferrer">
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
                                
                                ; ?>
                            </ul>
                        </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>