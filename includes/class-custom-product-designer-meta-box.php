<?php

class Custom_Product_Designer_Meta_Box {
    public function __construct() {
        add_action( 'init', [$this, 'custom_product_designer_meta_box'], 0 );
        add_action( 'init', [$this, 'custom_product_taxonomies_meta_box'], 0 );
    }

    // Register Custom Post Type
    public function custom_product_designer_meta_box() {

        if ( class_exists( 'CSF' ) ) {

            $prefix = 'fan_clubs';

            CSF::createMetabox( $prefix, array(
                'title'     => esc_html__( 'Design Meta Box', 'oceanwp' ),
                'post_type' => 'fanclubs-design',
            ) );

            CSF::createSection( $prefix, array(
                'fields' => array(
                    array(
                        'id'           => 'fanclubs_upload_img',
                        'type'         => 'upload',
                        'title'        => esc_html__( 'Upload Image', 'oceanwp' ),
                        'library'      => 'image',
                        'placeholder'  => 'http://',
                        'button_title' => 'Add Image',
                        'remove_title' => 'Remove Image',
                        'preview'      => true,
                    ),

                    array(
                        'id'     => 'fanclubs_design_page',
                        'type'   => 'fieldset',
                        'title'  => 'Design Page',
                        'fields' => array(
                            array(
                                'id'      => 'fanclubs_design_page_width',
                                'type'    => 'slider',
                                'title'   => 'Width',
                                'min'     => 0,
                                'max'     => 100,
                                'step'    => 1,
                                'unit'    => '%',
                                'default' => 50,
                            ),
                            array(
                                'id'      => 'fanclubs_design_page_top',
                                'type'    => 'slider',
                                'title'   => 'Top',
                                'min'     => 0,
                                'max'     => 100,
                                'step'    => 1,
                                'unit'    => '%',
                                'default' => 5,
                            ),
                            array(
                                'id'      => 'fanclubs_design_page_left',
                                'type'    => 'slider',
                                'title'   => 'Left',
                                'min'     => 0,
                                'max'     => 100,
                                'step'    => 1,
                                'unit'    => '%',
                                'default' => 5,
                            ),
                        ),
                    ),
                    array(
                        'id'     => 'fanclubs_select_product',
                        'type'   => 'repeater',
                        'title'  => esc_html__( 'Select Product', 'oceanwp' ),
                        'fields' => array(
                            array(
                                'id'         => 'fanclubs_product',
                                'type'       => 'select',
                                'title'      => esc_html__( 'Select Product', 'oceanwp' ),
                                'options'    => 'posts',
                                'query_args' => array(
                                    'post_type'   => 'product',
                                    'post_status' => 'any',
                                ),
                            ),
                            array(
                                'id'           => 'fanclubs_product_upload_img',
                                'type'         => 'upload',
                                'title'        => esc_html__( 'Upload Image', 'oceanwp' ),
                                'library'      => 'image',
                                'placeholder'  => 'http://',
                                'button_title' => 'Add Image',
                                'remove_title' => 'Remove Image',
                                'preview'      => true,
                            ),

                            array(
                                'id'     => 'fanclubs_custom_design_page',
                                'type'   => 'fieldset',
                                'title'  => 'Design Page',
                                'fields' => array(
                                    array(
                                        'id'      => 'fanclubs_product_custom_checkbox',
                                        'type'    => 'checkbox',
                                        'title'   => esc_html__( 'Custom Style', 'oceanwp' ),
                                        'label'   => 'Please checked the box and set custom style.',
                                        'default' => false,
                                    ),
                                    array(
                                        'id'         => 'fanclubs_custom_design_page_width',
                                        'type'       => 'slider',
                                        'title'      => 'Width',
                                        'min'        => 0,
                                        'max'        => 100,
                                        'step'       => 1,
                                        'unit'       => '%',
                                        'default'    => 50,
                                        'dependency' => array( 'fanclubs_product_custom_checkbox', '==', 'true' ),
                                    ),
                                    array(
                                        'id'         => 'fanclubs_custom_design_page_top',
                                        'type'       => 'slider',
                                        'title'      => 'Top',
                                        'min'        => 0,
                                        'max'        => 100,
                                        'step'       => 1,
                                        'unit'       => '%',
                                        'default'    => 5,
                                        'dependency' => array( 'fanclubs_product_custom_checkbox', '==', 'true' ),
                                    ),
                                    array(
                                        'id'         => 'fanclubs_custom_design_page_left',
                                        'type'       => 'slider',
                                        'title'      => 'Left',
                                        'min'        => 0,
                                        'max'        => 100,
                                        'step'       => 1,
                                        'unit'       => '%',
                                        'default'    => 5,
                                        'dependency' => array( 'fanclubs_product_custom_checkbox', '==', 'true' ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'id'         => 'fanclubs_single_product',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Select Single Product', 'oceanwp' ),
                        'options'    => 'posts',
                        'query_args' => array(
                            'post_type'   => 'product',
                            'post_status' => 'any',
                        ),
                    ),
                ),

            ) );
        }

    }

    public function custom_product_taxonomies_meta_box(){

// Control core classes for avoid errors
        if( class_exists( 'CSF' ) ) {

            //
            // Set a unique slug-like ID
            $prefix = 'design_taxonomies';

            //
            // Create taxonomy options
            CSF::createTaxonomyOptions( $prefix, array(
                'taxonomy'  => 'design',
                'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
            ) );

            //
            // Create a section
            CSF::createSection( $prefix, array(
                'fields' => array(
                    array(
                        'id'           => 'design_taxonomies_icon',
                        'type'         => 'upload',
                        'title'        => 'Upload Icon',
                        'library'      => 'image',
                        'placeholder'  => 'http://',
                        'button_title' => 'Add Image',
                        'remove_title' => 'Remove Image',
                    ),
                )
            ) );

        }

    }

}


new Custom_Product_Designer_Meta_Box();