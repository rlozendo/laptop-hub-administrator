<?php 
if ( !defined('ABSPATH')) exit;

/************************************/
// CREATE CPT PROJECTS / START
/************************************/

function my_custom_post_products() {
    $labels = array(
        'name'               => _x( 'Laptops', 'post type general name' ),
        'singular_name'      => _x( 'Laptop', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Laptop' ),
        'add_new_item'       => __( 'Add New Laptop' ),
        'edit_item'          => __( 'Edit Laptop' ),
        'new_item'           => __( 'New Laptop' ),
        'all_items'          => __( 'All Laptops' ),
        'view_item'          => __( 'View Laptops' ),
        'search_items'       => __( 'Search Laptops' ),
        'not_found'          => __( 'No Laptops found' ),
        'not_found_in_trash' => __( 'No Laptops found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Laptops'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'For Laptops only',
        'public'        => true,
        'publicly_queryable' => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'thumbnail', 'excerpt' ),
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-laptop', // You can use a different icon for products
        'rewrite' => array( 'slug' => 'products' ),
        'show_in_rest' => true,
        'hierarchical' => false,
    );
    register_post_type( 'products', $args );        
}
add_action( 'init', 'my_custom_post_products' );

function create_my_taxonomies_for_products() {
    // Product Brands Taxonomy
    register_taxonomy('products_brand', 'products', array(
        'hierarchical' => true,
        'label' => 'Laptop Brands',
        'query_var' => true,
        'rewrite' => array( 'slug' => 'product-brands' ),
        'show_in_rest' => true,
    ));

    // Product Size Taxonomy
    //register_taxonomy('products_size', 'products', array(
        //'hierarchical' => true,
        //'label' => 'Laptop Sizes',
        //'query_var' => true,
        //'rewrite' => array( 'slug' => 'product-size' ),
        //'show_in_rest' => true,
    //));
}
add_action('init', 'create_my_taxonomies_for_products', 0);

function add_author_support_to_products() {
    add_post_type_support( 'products', 'author' ); 
}
add_action( 'init', 'add_author_support_to_products' );

function products_tag() {
    register_taxonomy_for_object_type('post_tag', 'products');
}
add_action('init', 'products_tag');



function add_brand_logo_url_to_taxonomy() {
    register_rest_field('products_brand',
        'brand_logo_url',
        array(
            'get_callback'    => 'get_brand_logo_url',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action('rest_api_init', 'add_brand_logo_url_to_taxonomy');


function get_brand_logo_url($term, $field_name, $request, $object_type) {
    $term_id = $term['id'];
    
    // Use get_field() function from ACF to get the value of the 'brand_logo' field
    $brand_logo_url = get_field('brand_logo', 'products_brand_' . $term_id);

    if ($brand_logo_url) {
        return $brand_logo_url;
    } else {
        return null; // or handle it based on your requirements
    }
}
/************************************/
// CREATE CPT PROJECTS / START
/************************************/