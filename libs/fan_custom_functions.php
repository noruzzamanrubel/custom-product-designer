<?php

class ProductDesignImg {


    public $product_design_img = '';
    public $fanclubs_design_page_width='';
    public $fanclubs_design_page_top='';
    public $fanclubs_design_page_left='';
    public $design_product_id = '';
    public $design_id='';
    public $variation_id = '';

    public function __construct()
    {
        add_action('single_product_custom_hook', [$this, 'fan_design_single_product_featured']);
        add_action ('template_redirect', [$this, 'fan_design_redirect_archive_page']);
        add_action( 'plugins_loaded', [$this, 'fan_design_product_cat_to_custom_post_type'] );
        add_shortcode('design_cat_sidebar', [$this, 'fan_design_cat_sidebar']);

    }
    //single page
    public function fan_design_single_product_featured(){
        if(isset($_GET['d_id'])) {
            $meta = get_post_meta( $_GET['d_id'], 'fan_clubs', true );
            $product_meta = $meta['fanclubs_select_product'];
            $fanclubs_upload_img = $meta['fanclubs_upload_img'];

            $fanclubs_design_page = $meta['fanclubs_design_page'];

            global $product;
            $id = $product->id;
            $this->design_product_id = $id;
            $this->design_id = $_GET['d_id'];
            $this->variation_id = $_GET['variation_id'];

            for($i=0; $i < count($product_meta); $i++) {
                $fanclubs_custom_design_page= $product_meta[$i]['fanclubs_custom_design_page'];
                $fanclubs_product_custom_checkbox= $fanclubs_custom_design_page['fanclubs_product_custom_checkbox'];

                if(intval($product_meta[$i]['fanclubs_product']) === intval($id)) {
                    if($product_meta[$i]['fanclubs_product_upload_img'] !== '') {
                        $this->product_design_img = $product_meta[$i]['fanclubs_product_upload_img'];
                    } else {
                        $this->product_design_img = $fanclubs_upload_img;
                    }

                    if($fanclubs_product_custom_checkbox) {
                        $this->fanclubs_design_page_width = $fanclubs_custom_design_page['fanclubs_custom_design_page_width'];
                        $this->fanclubs_design_page_top = $fanclubs_custom_design_page['fanclubs_custom_design_page_top'];
                        $this->fanclubs_design_page_left = $fanclubs_custom_design_page['fanclubs_custom_design_page_left'];
                    } else {
                        $this->fanclubs_design_page_width = $fanclubs_design_page['fanclubs_design_page_width'];
                        $this->fanclubs_design_page_top = $fanclubs_design_page['fanclubs_design_page_top'];
                        $this->fanclubs_design_page_left = $fanclubs_design_page['fanclubs_design_page_left'];
                    }
                }
            }
            ?>
            <img class="design_single_image" src="<?php echo $this->product_design_img; ?>" style="position: absolute; z-index: 1000; top: <?php echo $this->fanclubs_design_page_top; ?>%; left: <?php echo $this->fanclubs_design_page_left; ?>%; width: <?php echo $this->fanclubs_design_page_width;?>%;">
            <?php
        }
    }

    //redirect shop to design archive page
    public function fan_design_redirect_archive_page(){
        if( is_shop() ){
            wp_redirect( home_url( '/fanclubs-design/' ) );
            exit();
        }
    }
    //Add WooCommerce Product Categories in Custom Post Type
    public function fan_design_product_cat_to_custom_post_type() {
        register_taxonomy_for_object_type( 'design', 'fanclubs-design' );
    }
    // design cat Sidebar
    public function fan_design_cat_sidebar($atts = [], $content = null, $tag = ''){
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        // override default attributes with user attributes
        $wporg_atts = shortcode_atts(
            array(
                'title' => 'FANCLUB AUSSTATTUNG',
            ), $atts, $tag
        );
        ?>

        <h2><strong><?php echo esc_html__( $wporg_atts['title'] ); ?></strong></h2>

        <?php


        $taxonomy     = 'design';
        $orderby      = 'menu_order';
        $show_count   = 0;
        $pad_counts   = 0;
        $hierarchical = 1;
        $title        = '';
        $empty        = 0;

        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty,
        );
        $all_categories = get_terms( $args );
        foreach ($all_categories as $cat) {
//            $term = get_category_by_slug( $cat);
//            $meta = get_term_meta( $term->term_id, 'my_taxonomy_options', true );
//            var_dump($meta);
//
//            echo $meta['opt-text']; // id of the field
//            echo $meta['opt-textarea']; // id of the field




            if($cat->category_parent == 0) {
                $category_id = $cat->term_id;
                $product_thumb_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true );
                $product_thumbnail    = wp_get_attachment_url( $product_thumb_id );
                ?>
                <div class="product_cat_item">
                    <div id="block-8" class="widget_block clr">
                        <ul>
                            <li>
                                <a class="product_cat_single_item  <?php  if(isset($_GET['cat']) && $_GET['cat'] === $cat->slug) { echo 'active'; } else {echo '';} ?>  " href="<?php echo home_url( '/' ).'fanclubs-design'. '?cat='.$cat->slug; ?>">
                                    <?php
                                        if($product_thumbnail){
                                            ?>
                                            <span class="product_cat_image">
                                                <img src="<?php echo $product_thumbnail; ?>"/>
                                            </span>
                                            <?php
                                        }
                                    ;?>
                                    <span class="product_cat_name">
                                        <?php echo $cat->name; ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <?php
    }
}
new ProductDesignImg();

// Add header
function fan_club_admin_order_item_headers( $order ) {
    // Set the column name
    $column_name = __( 'Design Image', 'woocommerce' );

    // Display the column name
    echo '<th class="line_packing_weight sortable" data-sort="string-ins">' . $column_name . '</th>';
}
add_action( 'woocommerce_admin_order_item_headers', 'fan_club_admin_order_item_headers', 10, 1 );

//Add content
function fan_club_admin_order_item_values( $_product, $item, $item_id = null ) {

    $variation_id = $item->get_variation_id();
    $variation = new WC_Product_Variation($variation_id);
    $variationName = ' ' . implode(" - ", $variation->get_variation_attributes());
   $order_design_meta = get_post_meta($variationName, 'fan_clubs', true);

    $fanclubs_upload_img = $order_design_meta['fanclubs_upload_img'];
    $fanclubs_design_page = $order_design_meta['fanclubs_design_page'];
    $fanclubs_design_page_width = $fanclubs_design_page['fanclubs_design_page_width'];
    $fanclubs_design_page_top = $fanclubs_design_page['fanclubs_design_page_top'];
    $fanclubs_design_page_left = $fanclubs_design_page['fanclubs_design_page_left'];
    $product_meta = $order_design_meta['fanclubs_select_product'];

    for($i=0; $i < count($product_meta); $i++) {
        $fanclubs_product_upload_img = $product_meta[$i]['fanclubs_product_upload_img'];
        $fanclubs_custom_design_page = $product_meta[$i]['fanclubs_custom_design_page'];
        $fanclubs_product_custom_checkbox = $fanclubs_custom_design_page['fanclubs_product_custom_checkbox'];
        $fanclubs_custom_design_page_width = $fanclubs_custom_design_page['fanclubs_custom_design_page_width'];
        $fanclubs_custom_design_page_top = $fanclubs_custom_design_page['fanclubs_custom_design_page_top'];
        $fanclubs_custom_design_page_left = $fanclubs_custom_design_page['fanclubs_custom_design_page_left'];

        $product_id = $_product->get_parent_id();
        $product_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($product_id));

        if(intval($product_meta[$i]['fanclubs_product']) === $_product->get_parent_id()) {
            if($fanclubs_product_custom_checkbox == true){
                if($fanclubs_product_upload_img !== '') {
                    ?>
                    <td>
                        <div style="position: relative; display: inline-block; overflow: hidden;">
                            <img src="<?php  echo $product_thumb[0]; ?>">
                            <img src="<?php echo $fanclubs_product_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                        </div>
                    </td>
                    <?php
                } else {
                    ?>
                    <td>
                        <div style="position: relative; display: inline-block; overflow: hidden;">
                            <img src="<?php  echo $product_thumb[0]; ?>">
                            <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                        </div>
                    </td>
                    <?php
                }
            }else {
                if($fanclubs_product_upload_img !== '') {
                    ?>
                    <td>
                        <div style="position: relative; display: inline-block; overflow: hidden;">
                            <img src="<?php  echo $product_thumb[0]; ?>">
                            <img src="<?php echo $fanclubs_product_upload_img;  ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                        </div>
                    </td>
                    <?php
                } else {
                    ?>
                    <td>
                        <div style="position: relative; display: inline-block; overflow: hidden;">
                            <img src="<?php  echo $product_thumb[0]; ?>">
                            <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                        </div>
                    </td>
                    <?php
                }
            }
        }
    }
}
add_action( 'woocommerce_admin_order_item_values', 'fan_club_admin_order_item_values', 10, 3 );


function fan_design_update_post_meta($post_id){

    if ('fanclubs-design' !== get_post_type($post_id)) {
        return;
    }

    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM `wp_fans_woocommerce_attribute_taxonomies` WHERE `attribute_label` LIKE 'Design'");

    if(count($result) < 1) {
        createAttribute('Design', 'design');
    }

    // Loop over selected products and create variations
    $meta = get_post_meta( $post_id, 'fan_clubs', true );

    if ($meta == '') {
        return;
    }

    $product_meta = $meta['fanclubs_select_product'];

    foreach ($product_meta as $value){
        $product_id = $value['fanclubs_product'];
        wp_remove_object_terms( $product_id, 'simple', 'product_type' );
        wp_set_object_terms( $product_id, 'variable', 'product_type', true );

        // Set product attribute for specific product
        $design_attributes = [
            'pa_design' => [
                'name' => 'pa_design',
                'value' => '',
                'position' => 0,
                'is_visible' => 1,
                'is_variation' => 1,
                'is_taxonomy' => 1
            ]
        ];

        if(metadata_exists('post', $product_id, '_product_attributes')) {
            $meta_arr = get_post_meta($product_id, '_product_attributes', true);
            update_post_meta($product_id, '_product_attributes', array_merge($meta_arr,$design_attributes));
        } else {
            add_post_meta($product_id, '_product_attributes', $design_attributes);
        }

        // Create term under attribute
        $post_title = get_the_title($post_id);
        createTerm($post_title, $post_id, 'design', 0);
        wp_set_object_terms( $product_id, $post_title, 'pa_design' , true);

        $variation_data = array(
            'attributes' => array(
                'design'  => $post_title,
            ),
            'regular_price' => '01.00',
            'sale_price'    => '00.00',
        );
        $product_title = get_the_title($product_id);
        $product_variation_exist = post_exists( $product_title.' - '.$post_title,'','','product_variation');
        if(!$product_variation_exist) {
            create_product_variation( $product_id, $variation_data );
        }

    }
}

add_action('save_post', 'fan_design_update_post_meta', 10, 4);

// Create attribute
function createAttribute(string $attributeName, string $attributeSlug): ?\stdClass {
    delete_transient('wc_attribute_taxonomies');
    \WC_Cache_Helper::incr_cache_prefix('woocommerce-attributes');

    $attributeLabels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
    $attributeWCName = array_search($attributeSlug, $attributeLabels, TRUE);

    if (! $attributeWCName) {
        $attributeWCName = wc_sanitize_taxonomy_name($attributeSlug);
    }

    $attributeId = wc_attribute_taxonomy_id_by_name($attributeWCName);
    if (! $attributeId) {
        $taxonomyName = wc_attribute_taxonomy_name($attributeWCName);
        unregister_taxonomy($taxonomyName);
        $attributeId = wc_create_attribute(array(
            'name' => $attributeName,
            'slug' => $attributeSlug,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => 0,
        ));

        register_taxonomy($taxonomyName, apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array(
            'product'
        )), apply_filters('woocommerce_taxonomy_args_' . $taxonomyName, array(
            'labels' => array(
                'name' => $attributeSlug,
            ),
            'hierarchical' => FALSE,
            'show_ui' => FALSE,
            'query_var' => TRUE,
            'rewrite' => FALSE,
        )));
    }

    return wc_get_attribute($attributeId);
}

// Create attribute term
function createTerm(string $termName, string $termSlug, string $taxonomy, int $order = 0): ?\WP_Term {
    $taxonomy = wc_attribute_taxonomy_name($taxonomy);

    if (! $term = get_term_by('slug', $termSlug, $taxonomy)) {
        $term = wp_insert_term($termName, $taxonomy, array(
            'slug' => $termSlug,
        ));
        $term = get_term_by('id', $term['term_id'], $taxonomy);
        if ($term) {
            update_term_meta($term->term_id, 'order', $order);
        }
    }

    return $term;
}

// Create variation
function create_product_variation( $product_id, $variation_data ){
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title'  => $product->get_name(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );

    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation( $variation_id );

    // Iterating through the variations attributes
    foreach ($variation_data['attributes'] as $attribute => $term_name )
    {
        $taxonomy = 'pa_'.$attribute; // The attribute taxonomy

        // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
                'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst( $attribute ),
                    'query_var' => true,
                    'rewrite' => array( 'slug' => sanitize_title($attribute) ), // The base slug
                )
            );
        }

        // Check if the Term name exist and if not we create it.
        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy ); // Create the term

        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug; // Get the term slug

        // Get the post Terms names from the parent variable product.
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );

        // Check if the post term exist and if not we set it in the parent variable product.
        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );

        // Set/save the attribute data in the product variation
        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
    }


    // Prices
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }

    $variation->set_regular_price( $variation_data['regular_price'] );

    $variation->save();
}

//custom image size
add_image_size( 'design_thumb', 600, 600, true);