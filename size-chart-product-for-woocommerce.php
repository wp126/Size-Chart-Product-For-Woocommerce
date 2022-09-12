<?php 
/**
* Plugin Name: Size Chart Product For Woocommerce
* Description: This plugin allows create Size Chart plugin.
* Version: 1.0
* Copyright: 2022
* Text Domain: size-chart-product-for-woocommerce
* Domain Path: /languages 
*/


if (!defined('SCPFW_PLUGIN_DIR')) {
   define('SCPFW_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('SCPFW_PREFIX')) {
   define('SCPFW_PREFIX', 'scpfw_');
}
 
// Include All Files
include_once('main/backend/scpfw_charts_mb.php');
include_once('main/backend/scpfw_create_post.php');
include_once('main/backend/scpfw_product_mb.php');
include_once('main/frontend/scpfw_front.php');
include_once('main/resource/scpfw-load-js-css.php');
include_once('main/resource/scpfw-load-custom-post.php');
include_once('main/resource/scpfw-load-my-textdomain.php');