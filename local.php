<?php

require( dirname(__FILE__) . '/../../../wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );

if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

function wp_modify_uploaded_file_names($file) {

    $wordpress_upload_dir = wp_upload_dir();

    if (!file_exists($wordpress_upload_dir['path'] . '/original')) {
        mkdir($wordpress_upload_dir['path'] . '/original', 0777, true);
    }

    $profilepicture = $file;
    $new_file_path = $wordpress_upload_dir['path'] . '/original';
    $new_file_mime = mime_content_type( $profilepicture['tmp_name'] );

    if( empty( $profilepicture ) )
        die( 'File is not selected test.' );

    if( $profilepicture['error'] )
        die( $profilepicture['error'] );

    if( $profilepicture['size'] > wp_max_upload_size() )
        die( 'It is too large than expected.' );

    if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
        die( 'WordPress doesn\'t allow this type of uploads.' );

    $new_file_path_patch = $new_file_path.'/'.$profilepicture['name'];

    copy( $profilepicture['tmp_name'], $new_file_path.'/'.$profilepicture['name'] );
    return $file;
}

add_filter('wp_handle_upload_prefilter', 'wp_modify_uploaded_file_names', 1, 1);
