<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME']))
{
die ('No access!');
}

require_once( dirname(__FILE__) . '/core/fx-function-default.php'); // options theme







function custom_rest_api_init() {
    register_rest_field('products', 'gallery', array(
        'get_callback' => 'get_gallery_images',
        'update_callback' => null,
        'schema' => null,
    ));
}

function get_gallery_images($object, $field_name, $request) {
    $images = get_field('gallery', $object['id']);

    $image_data = array();

    if ($images) {
        foreach ($images as $image) {
            $image_id = $image['image'];

            // Get the image sizes
            $image_sizes = wp_get_attachment_image_sizes($image_id);

            // Build the URLs for each size
            $url_data = array();
            foreach ($image_sizes as $size => $size_data) {
                $url_data[$size] = wp_get_attachment_image_src($image_id, $size)[0];
            }

            $image_data[] = array(
                'id' => $image_id,
                'urls' => $url_data,
            );
        }
    }

    return $image_data;
}

add_action('rest_api_init', 'custom_rest_api_init');



















// Add custom fields to REST API response for custom post type
function custom_rest_api_fields() {
    // Add fields to 'products' post type
    register_rest_field('products', 'products_brand', array(
        'get_callback' => 'get_taxonomy_info_callback',
        'schema'       => null,
    ));

    register_rest_field('products', 'products_size', array(
        'get_callback' => 'get_taxonomy_info_callback',
        'schema'       => null,
    ));
}

// Callback function to get taxonomy info
function get_taxonomy_info_callback($object, $field_name, $request) {
    $post_id = $object['id'];
    $taxonomy = str_replace('_info', '', $field_name); // Extract the taxonomy name from the field name

    $terms = get_the_terms($post_id, $taxonomy);

    if (is_wp_error($terms) || !is_array($terms)) {
        return null;
    }

    $info = array();

    foreach ($terms as $term) {
        $info[] = array(
            'id'   => $term->term_id,
            'slug' => $term->slug,
            'name' => $term->name,
        );
    }

    return $info;
}

// Hook to add custom fields to REST API response
add_action('rest_api_init', 'custom_rest_api_fields');





















function custom_add_featured_image_to_api() {
    // Replace 'products' with the slug of your custom post type
    register_rest_field('products', 'featured_image', array(
        'get_callback'    => 'custom_get_featured_image_data',
        'update_callback' => null,
        'schema'          => null,
    ));
}

function custom_get_featured_image_data($object, $field_name, $request) {
    $featured_image_id = get_post_thumbnail_id($object['id']);
    
    if ($featured_image_id) {
        $image_sizes = get_intermediate_image_sizes();

        $image_data = array();

        foreach ($image_sizes as $size) {
            $image_data[$size] = wp_get_attachment_image_src($featured_image_id, $size);
        }

        return $image_data;
    }

    return null;
}

add_action('rest_api_init', 'custom_add_featured_image_to_api');


