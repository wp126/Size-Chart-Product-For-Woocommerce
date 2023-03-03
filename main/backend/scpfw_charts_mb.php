<?php
add_action( 'admin_menu','SCPFW_asprods_asprodcats_metabox_for_select2');
function SCPFW_asprods_asprodcats_metabox_for_select2() {
    add_meta_box( 'asprods_select2', __( 'Assign Products', 'size-chart-product-for-woocommerce' ),'SCPFW_asprods_display_select2_metabox', 'scpfw_size_chart', 'side');

    add_meta_box( 'asprodcats_select2', __( 'Assign Categories', 'size-chart-product-for-woocommerce' ),'SCPFW_asprodcats_display_select2_metabox', 'scpfw_size_chart', 'side');

    add_meta_box( 'asprodattrs_select2', __( 'Assign Attributes', 'size-chart-product-for-woocommerce' ),'SCPFW_asprodattrs_display_select2_metabox', 'scpfw_size_chart', 'side');
}

function SCPFW_asprods_display_select2_metabox( $post_object ) {

    // $html = '';
 
    $appended_posts = get_post_meta( $post_object->ID, SCPFW_PREFIX.'asprods_select2_posts',true );?>
 
    <p><select id="asprods_select2_posts" name="asprods_select2_posts[]" multiple="multiple" style="width:99%;max-width:25em;">
    <?php 
        if( !empty($appended_posts) ) {
            foreach( $appended_posts as $post_id ) {
                $title = get_the_title( $post_id );
                $title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
                ?><option value="<?php echo esc_attr($post_id); ?>" selected="selected"><?php echo esc_attr($title); ?></option><?php 
            }
        }
        ?>
   </select></p>

    <?php 
}

add_action( 'save_post', 'SCPFW_asprods_save_metaboxdata', 10, 2 );
function SCPFW_asprods_save_metaboxdata( $post_id, $post ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
 
    if ( $post->post_type == 'scpfw_size_chart' ) {
        if( isset( $_POST['asprods_select2_posts'] ) ) {
            update_post_meta( $post_id, SCPFW_PREFIX.'asprods_select2_posts', $_POST['asprods_select2_posts'] );
        }
        else {
            delete_post_meta( $post_id, SCPFW_PREFIX.'asprods_select2_posts' );
        }
    }
    return $post_id;
}

add_action( 'wp_ajax_nopriv_SCPFW_asprods_get_posts','SCPFW_asprods_get_posts_ajax_callback');
add_action( 'wp_ajax_SCPFW_asprods_get_posts', 'SCPFW_asprods_get_posts_ajax_callback');
function SCPFW_asprods_get_posts_ajax_callback() {

    $return = array();

    $search_results = new WP_Query( array( 
        'post_type' => 'product',
        's'=> sanitize_text_field($_GET['q']),
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 50
    ) );
    if( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();   
            $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title );
        endwhile;
    endif;
    echo json_encode( $return );
    die;
}

function SCPFW_asprodcats_display_select2_metabox( $post_object ) {

    $appended_terms = get_post_meta( $post_object->ID, SCPFW_PREFIX.'asprodcats_select2_posts',true ); ?>
    <p>
        <select id="asprodcats_select2_posts" name="asprodcats_select2_posts[]" multiple="multiple" style="width:99%;max-width:25em;">
            <?php

            if( !empty($appended_terms) ) {
                foreach( $appended_terms as $term_id ) {
                    $term_name = get_term( $term_id )->name;
                    $term_name = ( mb_strlen( $term_name ) > 50 ) ? mb_substr( $term_name, 0, 49 ) . '...' : $term_name;
                    ?>
                     <option value="<?php echo esc_attr($term_id); ?>" selected="selected"><?php echo esc_attr($term_name); ?></option>
                     <?php 
                }
            } ?>
        </select>
    </p>
    <?php 

}

add_action( 'save_post', 'SCPFW_asprodcats_save_metaboxdata', 10, 2 );
function SCPFW_asprodcats_save_metaboxdata( $post_id, $post ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
 
    if ( $post->post_type == 'scpfw_size_chart' ) {
        if( isset( $_POST['asprodcats_select2_posts'] ) ) {
            update_post_meta( $post_id, SCPFW_PREFIX.'asprodcats_select2_posts', $_POST['asprodcats_select2_posts'] );
        }
        else {
            delete_post_meta( $post_id, SCPFW_PREFIX.'asprodcats_select2_posts' );
        }
    }
    return $post_id;
}


add_action( 'wp_ajax_nopriv_SCPFW_asprodcats_get_posts','SCPFW_asprodcats_get_posts_ajax_callback');
add_action( 'wp_ajax_SCPFW_asprodcats_get_posts', 'SCPFW_asprodcats_get_posts_ajax_callback');
function SCPFW_asprodcats_get_posts_ajax_callback() {

    $return = array();

    $product_categories = get_terms( 'product_cat', false );

    if( !empty($product_categories) ) {
        foreach ($product_categories as $key => $category) {
            $category->term_id;
            $title = ( mb_strlen( $category->name ) > 50 ) ? mb_substr( $category->name, 0, 49 ) . '...' : $category->name;
            $return[] = array( $category->term_id, $title );
        }
    }

    echo json_encode( $return );
    die;
}


function SCPFW_asprodattrs_display_select2_metabox( $post_object ) {

    $html = '';
 
    $appended_terms = get_post_meta( $post_object->ID, SCPFW_PREFIX.'asprodattrs_select2_posts',true );

    ?>


    <p><select id="asprodattrs_select2_posts" name="asprodattrs_select2_posts[]" multiple="multiple" style="width:99%;max-width:25em;">
        <?php
        if( !empty($appended_terms) ) {
            foreach( $appended_terms as $term_id ) {
                $term_name = get_term( $term_id )->name;
                $term_name = ( mb_strlen( $term_name ) > 50 ) ? mb_substr( $term_name, 0, 49 ) . '...' : $term_name;
                ?>
                 <option value="<?php echo esc_attr($term_id); ?>" selected="selected"><?php echo esc_attr($term_name); ?></option>
                 <?php 
            }
        } ?>
    </select></p>
 
   <?php 
}


add_action( 'save_post', 'SCPFW_asprodattrs_save_metaboxdata', 10, 2 );
function SCPFW_asprodattrs_save_metaboxdata( $post_id, $post ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
 
    if ( $post->post_type == 'scpfw_size_chart' ) {
        if( isset( $_POST['asprodattrs_select2_posts'] ) ) {
            update_post_meta( $post_id, SCPFW_PREFIX.'asprodattrs_select2_posts',$_POST['asprodattrs_select2_posts'] );
        }
        else {
            delete_post_meta( $post_id, SCPFW_PREFIX.'asprodattrs_select2_posts' );
        }
    }
    return $post_id;
}


add_action( 'wp_ajax_nopriv_SCPFW_asprodattrs_get_posts', 'SCPFW_asprodattrs_get_posts_ajax_callback');
add_action( 'wp_ajax_SCPFW_asprodattrs_get_posts', 'SCPFW_asprodattrs_get_posts_ajax_callback');
function SCPFW_asprodattrs_get_posts_ajax_callback() {

    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( $attribute_taxonomies ) {
        foreach ($attribute_taxonomies as $tax) {
            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                $taxonomy_terms[$tax->attribute_id] = get_terms( wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name' );
            }
        }
    }

    $product_attributes = array();
    foreach ( $taxonomy_terms as $key => $attribute ) {
        foreach($attribute as $dfg){
            $title = ( mb_strlen( $dfg->name ) > 50 ) ? mb_substr( $dfg->name, 0, 49 ) . '...' : $dfg->name;
            $product_attributes[] = array($dfg->term_id , $title);
        }
    }

    echo json_encode( $product_attributes );
    die;
}