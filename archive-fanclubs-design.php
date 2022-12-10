
<?php
    /* Template Name: Archive Page Custom */
    get_header();
    global $post;
    $args = array(
        'posts_per_page' => -1,
        'post_type'=> 'fanclubs-design',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    if(isset($_GET['cat'])) {
        $args['tax_query'] = array(
            array (
                'taxonomy' => 'design',
                'field' => 'slug',
                'terms' => $_GET['cat'],
            )
        );
    }
    $wp_query = get_posts( $args );

?>

<div id="content-wrap" class="container clr">
    <div class="archive_design_wrapper">
        <aside id="left-sidebar" class="sidebar-container widget-area sidebar-primary">
            <div id="left-sidebar-inner" class="clr">
                <div id="block-10" class="sidebar-box widget_block clr">
                    <?php echo do_shortcode('[design_cat_sidebar]'); ?>
                </div>
            </div>
        </aside>
        <div id="primary" class="content-area clr">
            <div id="content" class="clr site-content">
                <article class="entry-content entry clr">
                    <div class="archive_design_items">
                        <?php
                        foreach( $wp_query as $post ) : setup_postdata($post);
                        ?>
                            <div class="archive_design_item">

                                <?php

                                $custom_post_id = get_the_ID();
                                $meta = get_post_meta( $custom_post_id, 'fan_clubs', true );
                                $single_product_id = $meta['fanclubs_single_product'];

                                $product_link = '';
                                if($single_product_id !== null) {
                                    $product = wc_get_product( $single_product_id );
                                    $product_link = $product->get_permalink().'?d_id='.$custom_post_id;
                                }

                                ?>
                                <a href="<?php echo $product_link; ?>">

                                    <?php
                                        $meta = get_post_meta( get_the_ID(), 'fan_clubs', true );
                                        $single_product_id = $meta['fanclubs_single_product'];
                                        $product_meta = $meta['fanclubs_select_product'];
                                        $fanclubs_upload_img = $meta['fanclubs_upload_img'];

                                        $fanclubs_design_page = $meta['fanclubs_design_page'];
                                        $fanclubs_design_page_width = $fanclubs_design_page['fanclubs_design_page_width'];
                                        $fanclubs_design_page_top = $fanclubs_design_page['fanclubs_design_page_top'];
                                        $fanclubs_design_page_left = $fanclubs_design_page['fanclubs_design_page_left'];

                                        $product = wc_get_product( $single_product_id );
                                        $product_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($single_product_id));
                                        $custom_post_id = get_the_ID();

                                        foreach($product_meta as $value) {
                                            $product_id = $value['fanclubs_product'];
                                            $fanclubs_product_upload_img= $value['fanclubs_product_upload_img'];

                                            $fanclubs_custom_design_page= $value['fanclubs_custom_design_page'];
                                            $product_term = $fanclubs_custom_design_page['product_term_id'];

                                            $fanclubs_product_custom_checkbox = $fanclubs_custom_design_page['fanclubs_product_custom_checkbox'];
                                            $fanclubs_custom_design_page_width= $fanclubs_custom_design_page['fanclubs_custom_design_page_width'];
                                            $fanclubs_custom_design_page_top= $fanclubs_custom_design_page['fanclubs_custom_design_page_top'];
                                            $fanclubs_custom_design_page_left= $fanclubs_custom_design_page['fanclubs_custom_design_page_left'];
                                            if($single_product_id == $product_id){
                                                ?>
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
                                                <?php
                                            }
                                        }
                                    ?>

                                </a>
                            </div>
                        <?php endforeach; wp_reset_query(); ?>
                    </div
                </article>
            </div>
        </div>
    </div>
</div>

<?php get_footer();?>
