
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

//
// You should use my_taxonomy_options as this is the id for your key declared into config


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
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail('design_thumb');
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
