<?php 
/**
 * Plugin Name: Bulk Post URL to Post ID
 * Description: Get the Post ID from a URL's list.
 * Plugin URI: https://diez10.mx/
 * Author: @OGdiez10
 * Version: 0.0.10
 * Author URI: https://diez10.mx
 */


function bulk_url_to_postid_plugin_admin_page() { 
    add_menu_page( 'Bulk Post URL to Post ID', 'URL to Post ID', 'administrator', 'bulk-url-id', 'bulk_url_id_main', 'dashicons-search', '20');
}

add_action('admin_menu', 'bulk_url_to_postid_plugin_admin_page');



function bulk_url_id_main(){

    $plugin_dir_path = plugin_dir_url( __FILE__ );
    
    wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js' );
    wp_register_style( 'ogstyles', $plugin_dir_path.'/styles.css' );
    wp_enqueue_style('ogstyles');

    echo '<div id="bulkUrlIdApp"><vue-plugin></vue-plugin></div>';

    wp_register_script('ogapp', WP_PLUGIN_URL.'/bulk-url-to-postid/app.js',array(),NULL,true);
    wp_enqueue_script( 'ogapp',  plugin_dir_url( __FILE__ ) . 'app.js' );

    $wnm_custom = array( 'blog_url' => get_bloginfo('url') );
    wp_localize_script( 'ogapp', 'wnm_custom', $wnm_custom ); 



}


function get_post_page_id( $data ) {
    $postId    = url_to_postid($data['url']);
    $postType  = get_post_type($postId);
    $controller = new WP_REST_Posts_Controller($postType);
    $request    = new WP_REST_Request('GET', "/wp/v2/{$postType}s/{$postId}");
    $request->set_url_params(array('id' => $postId));
    return $controller->get_item($request);
  }

  add_action( 'rest_api_init', function () {
    register_rest_route( 'bulk-url-to-postid/v1', '/url/(?P<url>.*?)', array(
      'methods' => 'GET',
      'callback' => 'get_post_page_id',
    ) );
  } );

?>