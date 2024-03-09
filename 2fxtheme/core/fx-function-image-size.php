<?php 
if ( !defined('ABSPATH')) exit;
/************************************/
// IMAGE SIZES / START
/************************************/
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'ImageSize', 600, 400, true ); //(cropped)
	add_image_size( 'sliderImage', 969, 401, true ); //(cropped)
	add_image_size( 'contactImage', 641, 401, true ); //(cropped)
	add_image_size( 'cptThumb', 310, 200, true ); //(cropped)
	add_image_size( 'latop_image_size', 266, 187, true ); //(cropped)
	add_image_size( 'latop_brands', 160, 80, true ); //(cropped)


	//add_image_size( 'thumb1', 360, 9999999, false ); //(cropped)

}
/************************************/
// IMAGE SIZES / END
/************************************/


// Add instructions below featured image meta box for custom post type
add_action('do_meta_boxes', 'custom_post_type_featured_image_instructions');

function custom_post_type_featured_image_instructions() {
    add_filter('admin_post_thumbnail_html', 'custom_post_type_featured_image_html');
}

function custom_post_type_featured_image_html($content) {
    global $post_type;

    if ('products' === $post_type) {
        $content .= '<p>Make sure to upload a good quality of image with size of 587px by 437px</p>';
    }

    return $content;
}
