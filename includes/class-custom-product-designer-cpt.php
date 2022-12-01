<?php

class Custom_Product_Designer_CPT {
    public function __construct() {
        add_action( 'init', [$this, 'custom_product_designer_cpt_post'], 0 );
    }

    // Register Custom Post Type
    public function custom_product_designer_cpt_post() {

        $labels = array(
            'name'                  => _x( 'Fans Designs', 'Post Type General Name', 'oceanwp' ),
            'singular_name'         => _x( 'Fan Design', 'Post Type Singular Name', 'oceanwp' ),
            'menu_name'             => __( 'Fans Designs', 'oceanwp' ),
            'name_admin_bar'        => __( 'Fans Designs', 'oceanwp' ),
            'archives'              => __( 'Item Archives', 'oceanwp' ),
            'attributes'            => __( 'Item Attributes', 'oceanwp' ),
            'parent_item_colon'     => __( 'Parent Item:', 'oceanwp' ),
            'all_items'             => __( 'All Items', 'oceanwp' ),
            'add_new_item'          => __( 'Add New Item', 'oceanwp' ),
            'add_new'               => __( 'Add New', 'oceanwp' ),
            'new_item'              => __( 'New Item', 'oceanwp' ),
            'edit_item'             => __( 'Edit Item', 'oceanwp' ),
            'update_item'           => __( 'Update Item', 'oceanwp' ),
            'view_item'             => __( 'View Item', 'oceanwp' ),
            'view_items'            => __( 'View Items', 'oceanwp' ),
            'search_items'          => __( 'Search Item', 'oceanwp' ),
            'not_found'             => __( 'Not found', 'oceanwp' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'oceanwp' ),
            'featured_image'        => __( 'Featured Image', 'oceanwp' ),
            'set_featured_image'    => __( 'Set featured image', 'oceanwp' ),
            'remove_featured_image' => __( 'Remove featured image', 'oceanwp' ),
            'use_featured_image'    => __( 'Use as featured image', 'oceanwp' ),
            'insert_into_item'      => __( 'Insert into item', 'oceanwp' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'oceanwp' ),
            'items_list'            => __( 'Items list', 'oceanwp' ),
            'items_list_navigation' => __( 'Items list navigation', 'oceanwp' ),
            'filter_items_list'     => __( 'Filter items list', 'oceanwp' ),
        );
        $args = array(
            'label'                 => __( 'Fan Design', 'oceanwp' ),
            'description'           => __( 'Post Type Description', 'oceanwp' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail' ),
//            'taxonomies'            => array( 'category'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type( 'fanclubs-design', $args );

        register_taxonomy( 'design', 'fanclubs-design', array(
            'label'        => __( 'Design Categories', 'oceanwp' ),
            'rewrite'      => array( 'slug' => 'design' ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'supports'          => array( 'thumbnail' ),
        ) );

    }


}

new Custom_Product_Designer_CPT();