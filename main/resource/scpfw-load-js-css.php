<?php

   add_action('admin_enqueue_scripts','SCPFW_load_admin_script_style');
   add_action('wp_enqueue_scripts','SCPFW_load_script_style');

 	function SCPFW_load_script_style() {
         wp_enqueue_style( 'SCPFW_front_style', SCPFW_PLUGIN_DIR . '/assets/css/scpfw_front_style.css', false, '1.0.0' );
         wp_enqueue_script( 'SCPFW_front_script', SCPFW_PLUGIN_DIR . '/assets/js/scpfw_front_script.js', false, '1.0.0', true );

         $scpfw_img_array = SCPFW_PLUGIN_DIR;
         wp_localize_script( 'SCPFW_front_script', 'scpfw_object', array(
         															'scpfw_ajax_url' => admin_url('admin-ajax.php'),
         															'scpfw_object_name' => $scpfw_img_array	
         ) );
      }

 	function SCPFW_load_admin_script_style() {
        	wp_enqueue_style( 'SCPFW_admin_style', SCPFW_PLUGIN_DIR . '/assets/css/scpfw_back_style.css', false, '1.0.0' );
        	wp_enqueue_script( 'SCPFW_admin_script', SCPFW_PLUGIN_DIR . '/assets/js/scpfw_back_script.js',array('jquery','select2'), false, '1.0.0' , true );
    		wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
        	$scpfw_img_array = SCPFW_PLUGIN_DIR;
        	wp_localize_script( 'SCPFW_admin_script', 'scpfw_object', array(
         															'scpfw_object_name' => $scpfw_img_array	
         ) );
        	wp_enqueue_style( 'wp-color-picker' );
        	wp_enqueue_script( 'wp-color-picker-alpha', SCPFW_PLUGIN_DIR . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0.0', true );
      }


?>