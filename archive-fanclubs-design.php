
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
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $_GET['cat'],
        )
    );
}
$wp_query = get_posts( $args );
?>

<div id="content-wrap" class="container clr">
    <div class="archive_design_wrapper">
        <div id="primary" class="content-area clr">
            <div id="content" class="clr site-content">
                <article class="entry-content entry clr">
                    <div class="archive_design_items">
                        <?php
                        foreach( $wp_query as $post ) : setup_postdata($post); 
                        ?>
                            <div class="archive_design_item">
                                <a href="<?php echo get_permalink(); ?>" target="_blank" >
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail('thumbnail');
                                    }
                                    ?>
                                </a>
                            </div>
                        <?php endforeach; wp_reset_query(); ?>
                    </div
                </article>
            </div>
        </div>
        <?php echo do_shortcode('[design_cat_sidebar]'); ?>
<!--        --><?php //dynamic_sidebar('custom_design_sidebar'); ?>
    </div>
</div>

<?php get_footer();?>
