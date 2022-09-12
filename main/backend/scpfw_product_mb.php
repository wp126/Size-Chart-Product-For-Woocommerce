<?php
add_action( 'add_meta_boxes', 'SCPFW_add_metabox_product');
function SCPFW_add_metabox_product() {
    add_meta_box(
        'SizeChart_metabox',
        __( 'All Size Chart', 'size-chart-product-for-woocommerce' ),
        'SCPFW_metabox_size',
        'product',
        'side'
    );
}


function SCPFW_metabox_size( $post ) {
    wp_nonce_field( 'scpfw_productmeta_save', 'scpfw_productmeta_save_nounce' );
    $args = array(
        'post_type' => 'scpfw_size_chart',
        'posts_per_page' => -1
    );
             
    $my_query = get_posts( $args );

    ?>
    <select id="scpfw_selectchart" name="product_sizechart[]" multiple="multiple" style="width:100%;max-width:25em;">
        <?php 
            $selected_chart = get_post_meta( $post->ID, SCPFW_PREFIX.'product_sizechart', true);
            
            if(!empty($selected_chart)) {
                foreach ($selected_chart as $key => $value) {
                    echo '<option value="'.esc_attr($value).'" selected>'.get_the_title( esc_attr($value) ).'</option>';
                }
            }
        ?>
    </select>
    <?php
}


add_action( 'edit_post','SCPFW_save_metabox_product', 10, 2);
function SCPFW_save_metabox_product( $post_id, $post ) {
    if ($post->post_type != 'product') {return;}
 
    if ( !current_user_can( 'edit_post', $post_id )) return;
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['scpfw_productmeta_save_nounce']) && wp_verify_nonce( $_POST['scpfw_productmeta_save_nounce'], 'scpfw_productmeta_save' )? 'true': 'false');

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

    if (isset($_REQUEST['product_sizechart'])) {
        $product_sizechart  =  SCPFW_recursive_sanitize_text_field( $_REQUEST['product_sizechart'] );
    }else{
        $product_sizechart  =  '';
    }
    update_post_meta( $post_id, SCPFW_PREFIX.'product_sizechart',$product_sizechart);
}

add_action( 'wp_ajax_nopriv_scpfw_search_chart','scpfw_search_chart');
add_action( 'wp_ajax_scpfw_search_chart', 'scpfw_search_chart');
function scpfw_search_chart() {
    $return = array();
    $search_results = new WP_Query( array( 
        's'=>sanitize_text_field($_GET['q']), // the search query
        'post_status' => 'publish',
        'post_type' => 'scpfw_size_chart',
        'posts_per_page' => -1,
    ) );

    if( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();   
            $productc = wc_get_product( $search_results->post->ID );
           
            $title = $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title);
                
        endwhile;
    endif;
    echo json_encode( $return );
    die;
}