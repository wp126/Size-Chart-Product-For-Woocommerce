<?php 
/**
* Plugin Name: Size Chart Product For Woocommerce
* Description: This plugin allows create Size Chart plugin.
* Version: 1.0
* Copyright: 2023
* Text Domain: size-chart-product-for-woocommerce
* Domain Path: /languages 
*/


if (!defined('SCPFW_PREFIX')) {
   define('SCPFW_PREFIX', 'scpfw_');
}
 
// define for plugin dir path
if (!defined('SCPFW_PLUGIN_DIR')) {
   define('SCPFW_PLUGIN_DIR',plugins_url('', __FILE__));
}

 // define for plugin file
if (!defined('SCPFW_PLUGIN_FILE')) {
   define('SCPFW_PLUGIN_FILE', __FILE__);
}

// define for base name
if (!defined('SCPFW_BASE_NAME')) {
   define('SCPFW_BASE_NAME', plugin_basename( SCPFW_PLUGIN_FILE ));
}
 
// Include All Files
include_once('main/backend/scpfw_charts_mb.php');
include_once('main/backend/scpfw_create_post.php');
include_once('main/backend/scpfw_product_mb.php');
include_once('main/frontend/scpfw_front.php');
include_once('main/resource/scpfw-load-js-css.php');
include_once('main/resource/scpfw-load-custom-post.php');
include_once('main/resource/scpfw-load-my-textdomain.php');

function SCPFW_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'size-chart-product-for-woocommerce') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/size-chart-product-for-woocommerce/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'size-chart-product-for-woocommerce') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'SCPFW_support_and_rating_links', 10, 4 );