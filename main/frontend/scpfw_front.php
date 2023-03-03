<?php

function scpfw_get_chart_ids($product_id) {
    $chart_ids = get_post_meta( $product_id, SCPFW_PREFIX.'product_sizechart', true );
        
    if(!empty($chart_ids)) {
        $chart_ids = $chart_ids;
    } else {
        $chart_ids = array();
    }

    $product_terms = wp_get_object_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );

    if(!empty($product_terms)) {
        $product_terms = $product_terms;
    } else {
        $product_terms = array();
    }

    $product = wc_get_product( $product_id );
    $product_attrs = array();
    foreach ($product->get_attributes() as $key => $value) {
        $product_attributes = wc_get_product_terms( $product_id, $key , array( 'fields' => 'ids' ));
        $product_attrs[] = $product_attributes;
    }

    $assign_prod_ids   = array();

    $args = array(
        'post_type'        =>   'scpfw_size_chart',
        'posts_per_page'   =>   -1,
        'post_status'      =>   'publish',
    );

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $assign_products = get_post_meta( get_the_ID(), SCPFW_PREFIX.'asprods_select2_posts',true );

            $assign_terms = get_post_meta( get_the_ID(), SCPFW_PREFIX.'asprodcats_select2_posts',true );

            $assign_attr = get_post_meta( get_the_ID(), SCPFW_PREFIX.'asprodattrs_select2_posts',true );

            $szchartpp_aply_all = get_post_meta( get_the_ID(), SCPFW_PREFIX.'szchartpp_aply_all',true );

            if($szchartpp_aply_all == 'enable') {

                $assign_prod_ids[] = get_the_ID();
            } else {
                if(!empty($assign_products)) {
                    foreach ($assign_products as $prod_key => $prod_value) {
                        if($prod_value == $product_id) {
                            $assign_prod_ids[] = get_the_ID();
                        }
                    }
                }

                if(!empty($assign_terms)) {
                    foreach ($assign_terms as $term_key => $term_value) {
                        if (in_array($term_value, $product_terms)) {
                            $assign_prod_ids[] = get_the_ID();
                        }
                    }
                }

                if(!empty($assign_attr)) {
                    foreach ($assign_attr as $attr_key => $attr_value) {
                        foreach ($product_attrs as $product_attrskey => $product_attrsvalue) {
                            if (in_array($attr_value, $product_attrsvalue)) {
                                $assign_prod_ids[] = get_the_ID();
                            }
                        }
                    }
                }
            }
        }
    }
    wp_reset_query();
    
    $chart_ids_merge = array_merge($chart_ids, $assign_prod_ids);
    $chart_unique = array_unique($chart_ids_merge);

    return $chart_unique;
}

add_action( 'wp', 'scpfw_header_action' );
function scpfw_header_action() {
    if(is_product()) {

        $SCPFW_object = get_queried_object();
        $product_id   = $SCPFW_object->ID;
        $product      = wc_get_product( $product_id );

        $chart_ids = scpfw_get_chart_ids($product_id);



        if(!empty($chart_ids)) {

            foreach ($chart_ids as $key => $chart_id) {
                $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_show', true );
                $btn_pos      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_pos', true );
                $alw_mobile   = get_post_meta( $chart_id, SCPFW_PREFIX.'alw_mobile', true );
                
              
                $showCouponField = 'true';

                if(wp_is_mobile()) {
                    if($alw_mobile != "on") {
                        $showCouponField = 'false';
                    }
                }
                if( $showCouponField == 'true'){
                    
                    if($btn_tab == "tab") {
                        add_filter( 'woocommerce_product_tabs', 'scpfw_add_tab'); 
                    } elseif ($btn_tab == "popup") {
                        if($btn_pos == "after_add_cart" ) {
                        
                            if ( ! $product->is_in_stock()) {
                                add_action('woocommerce_product_meta_start', 'scpfw_button_after',0);
                            } else {
                                add_action('woocommerce_after_add_to_cart_form', 'scpfw_button_after');
                            }

                        } elseif ($btn_pos == "before_add_cart") {

                            if( ! $product->is_in_stock()) {
                                add_action('woocommerce_single_product_summary', 'scpfw_button_before');
                            } else {
                                if ( $product->is_type( 'variable' ) ) {
                                    add_action('woocommerce_single_variation','scpfw_button_before' );
                                } else {

                                    add_action('woocommerce_before_add_to_cart_form', 'scpfw_button_before');
                                }
                            }
                        } elseif ($btn_pos == "before_summry_text") {
                            add_action('woocommerce_single_product_summary','scpfw_button_before_summry_text');
                        } elseif($btn_pos == "aftr_prod_meta") {
                            add_action('woocommerce_product_meta_end','scpfw_button_aftr_prod_meta');
                        }
                    }
                

                }   
                    
            }
        }
    }

    if(is_shop() || is_product_category() || is_product_tag()) {
        add_action('woocommerce_after_shop_loop_item', 'scpfw_shop_page_popup_button_before', 9);
    }
}




function scpfw_shop_page_popup_button_before() {
    global $post;
    
    $chart_ids = scpfw_get_chart_ids($post->ID);

    if(!empty($chart_ids)) {

        foreach ($chart_ids as $key => $chart_id) {
            $showCouponField ='true';
            $szchartpp_shop_enbl      = get_post_meta( $chart_id, SCPFW_PREFIX.'szchartpp_shop_enbl', true );
            $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'shop_btn_show', true );
            $btn_pos      = get_post_meta( $chart_id, SCPFW_PREFIX.'shop_btn_pos', true );
            $alw_mobile = get_post_meta( $chart_id, SCPFW_PREFIX.'alw_mobile', true );

             if(wp_is_mobile()) {
                if($alw_mobile != "on") {
                    $showCouponField = 'false';
                }
            }
            if( $showCouponField == 'true'){

                if($szchartpp_shop_enbl == 'enable') {
                    $btn_lbl    = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_lbl', true );
                    $btn_ft     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_ft_clr', true );
                    $btn_bg     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_bg_clr', true );
                    $btn_brd_rd = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_brd_rd', true );
                    $btn_padding = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_padding', true );
                    $popup_loader = get_post_meta( $chart_id, SCPFW_PREFIX.'popup_loader', true );
                    if ($popup_loader == "loader_1" || empty($popup_loader)) {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-1.gif';
                    }elseif ($popup_loader == "loader_2") {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-2.gif';
                    }elseif ($popup_loader == "loader_3") {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-3.gif';
                    }elseif ($popup_loader == "loader_4") {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-4.gif';
                    }elseif ($popup_loader == "loader_5") {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-5.gif';
                    }elseif ($popup_loader == "loader_6") {
                        $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-6.gif';
                    }
                    $style      = "color:".esc_attr($btn_ft).";background-color:".esc_attr($btn_bg).";border-radius:".esc_attr($btn_brd_rd)."px;padding:".esc_attr($btn_padding).";";
                    
                    if ($btn_tab == "popup") {
                        if ($btn_pos == "before_add_cart") {
                            ?>
                            <div class="scpfw_btn">
                                <button class="scpfw_open" data-id="<?php echo esc_attr($post->ID); ?>" data-image="<?php echo esc_attr($loader);?>" data-cid="<?php echo esc_attr($chart_id); ?>" style="<?php echo esc_attr($style); ?>">
                                    <?php echo esc_attr($btn_lbl); ?>
                                </button>
                            </div>
                            <?php
                        }
                    } 
                    
                }
            }
        }
    }
}


function scpfw_add_tab( $tabs ) {
    $product_id = get_the_ID();
    $product    = wc_get_product( $product_id );

    $chart_ids = scpfw_get_chart_ids($product_id);

    if(!empty($chart_ids)) {
        foreach ($chart_ids as $key => $chart_id) {
            $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_show', true );
            if($btn_tab == "tab") {
                $tab_lbl        = get_post_meta( $chart_id, SCPFW_PREFIX.'tab_lbl', true );
                $tab_pririty    = get_post_meta( $chart_id, SCPFW_PREFIX.'tab_pririty', true );

                $tabs['desc_tab'] = array(
                    'title'     => __( $tab_lbl, 'woocommerce' ),
                    'priority'  => $tab_pririty,
                    'callback'  => 'scpfw_tab_content'
                );
            }
        }
    }
    return $tabs;
}


function scpfw_tab_content() {
    $product_id         = get_the_ID();
    $product            = wc_get_product( $product_id );
    $chart_ids          = scpfw_get_chart_ids($product_id);
    
    if(!empty($chart_ids)) {
        foreach ($chart_ids as $key => $chart_id) {
            $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_show', true );
            if($btn_tab == "tab") {
                $chart_title            = get_post_meta( $chart_id, SCPFW_PREFIX.'sub_title', true );
                $size_chartdata         = get_post_meta( $chart_id, SCPFW_PREFIX.'size_chartdata', true );
                $totalrow               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalrow', true );
                $totalcol               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalcol', true );
                $show_tab               = get_post_meta( $chart_id, SCPFW_PREFIX.'show_tab', true );
                $chart_tab_name         = get_post_meta( $chart_id, SCPFW_PREFIX.'chart_tab_name', true);
                $dis_tab_name           = get_post_meta( $chart_id, SCPFW_PREFIX.'dis_tab_name', true);
                $tbl_border             = get_post_meta( $chart_id, SCPFW_PREFIX.'tbl_border', true);
                $table_array            = $size_chartdata;
                echo '<div class="scpfw_tableclass">';
                    echo '<div class="scpfw_sizechart_tab_content">';
                        echo '<div class="scpfw_tab_header">';
                            echo '<h1>'.$chart_title.'</h1>';
                        echo '</div>';
                        echo '<div class="scpfw_tab_body">';
                            echo '<div class="scpfw_tab_data">';
                                echo '<div class="scpfw_tab_padding_div">';
                                    if($show_tab == "on"){ ?>
                                            <ul class="scpfw_front_tabs">
                                                <li class="tab-link current" data-tab="tab-default">
                                                    <?php echo __( $chart_tab_name, 'size-chart-product-for-woocommerce' );?>
                                                </li>
                                                <li class="tab-link" data-tab="tab-general">
                                                    <?php echo __( $dis_tab_name , 'size-chart-product-for-woocommerce' );?>
                                                </li>
                                            </ul>
                                            <div id="tab-default" class="scpfw_front_tab_content current">
                                                <div class="scpfw_child_div">
                                                    <?php
                                                        echo '<table>';
                                                            $count = 0;
                                                            for($i=0;$i<$totalrow;$i++){
                                                                echo "<tr>";
                                                                    
                                                                    for($j=0;$j<$totalcol;$j++){
                                                                        echo "<td>".esc_attr($table_array[$count])."</td>";
                                                                        $count++;
                                                                    }

                                                                echo "</tr>";
                                                            }
                                                        echo '</table>'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div id="tab-general" class="scpfw_front_tab_content">
                                                <div class="scpfw_child_div">
                                                    <?php echo get_post_field('post_content', $chart_id);
                                                     
                                                     ?> 
                                                    <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                                </div>
                                            </div>
                                        <?php
                                    }else{
                                        echo get_post_field('post_content', $chart_id);
                                        ?>
                                            <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                        <?php
                                        echo '<table>';
                                            $count = 0;
                                            for($i=0;$i<$totalrow;$i++){
                                                echo "<tr>";

                                        
                                                    for($j=0;$j<$totalcol;$j++){
                                                        echo "<td>".esc_attr($table_array[$count])."</td>";
                                                        $count++;
                                                    }

                                                echo "</tr>";
                                            }
                                        echo '</table>';
                                    } 
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                ?>
                <style type="text/css">
                    .scpfw_tableclass table {
                        border: <?php echo esc_attr($tbl_border); ?>;
                    }
                    .scpfw_tableclass tr {
                        color: #000000;
                    }
                    .scpfw_tableclass tr:nth-child(even) {
                        background: #d6d8db;
                    }
                    .scpfw_tableclass tr:nth-child(odd) {
                        background: #e9ebed;
                    }
                    .scpfw_tableclass tr:nth-child(1) {
                        background: #e9ebed;
                        color: #000000;
                        font-weight: 700;
                        text-transform: capitalize;
                    }
                </style>
                <?php
            }
        }
    }
}


function scpfw_button_after() {
    $product_id = get_the_ID();
    $product    = wc_get_product( $product_id );
    $chart_ids = scpfw_get_chart_ids($product_id);

    if(!empty($chart_ids)) {
        scpfw_popupSizeChartOpenButton($chart_ids);
    }
}


function scpfw_button_before() {

    $product_id = get_the_ID();
    $product    = wc_get_product( $product_id );
    $chart_ids = scpfw_get_chart_ids($product_id);

    if(!empty($chart_ids)) {
        scpfw_popupSizeChartOpenButton($chart_ids);
    }
}


function scpfw_button_before_summry_text() {
    $product_id = get_the_ID();
    $product    = wc_get_product( $product_id );
    $chart_ids = scpfw_get_chart_ids($product_id);

    if(!empty($chart_ids)) {
        scpfw_popupSizeChartOpenButton($chart_ids);
    }
}


function scpfw_button_aftr_prod_meta() {
    $product_id = get_the_ID();
    $product    = wc_get_product( $product_id );
    $chart_ids = scpfw_get_chart_ids($product_id);

    if(!empty($chart_ids)) {
        scpfw_popupSizeChartOpenButton($chart_ids);
    }
}


function scpfw_popupSizeChartOpenButton($chart_ids){
    if(!empty($chart_ids)) {
        foreach ($chart_ids as $key => $chart_id) {
            $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_show', true );
            $btn_pos      = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_pos', true );
            if($btn_tab == "popup" || $btn_pos == "aftr_prod_meta") {
                $btn_lbl    = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_lbl', true );
                $btn_ft     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_ft_clr', true );
                $btn_bg     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_bg_clr', true );
                $btn_brd_rd = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_brd_rd', true );
                $btn_padding = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_padding', true );
                $popup_loader = get_post_meta( $chart_id, SCPFW_PREFIX.'popup_loader', true );
                if ($popup_loader == "loader_1" || empty($popup_loader)) {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-1.gif';
                }elseif ($popup_loader == "loader_2") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-2.gif';
                }elseif ($popup_loader == "loader_3") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-3.gif';
                }elseif ($popup_loader == "loader_4") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-4.gif';
                }elseif ($popup_loader == "loader_5") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-5.gif';
                }elseif ($popup_loader == "loader_6") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-6.gif';
                }
                $style      = "color:".esc_attr($btn_ft).";background-color:".esc_attr($btn_bg).";border-radius:".esc_attr($btn_brd_rd)."px;padding:".esc_attr($btn_padding).";";

                $product_id = get_the_ID();
                ?>
                <div class="scpfw_btn">
                    <button class="scpfw_open" data-id="<?php echo esc_attr($product_id); ?>" data-image="<?php echo esc_attr($loader);?>" data-cid="<?php echo esc_attr($chart_id); ?>" style="<?php echo esc_attr($style); ?>">
                        <?php echo esc_attr($btn_lbl); ?>
                    </button>
                </div>
                <?php
            }
        }
    }
}




add_action( 'wp_footer','scpfw_popup_div_footer' );
function scpfw_popup_div_footer() {
    ?>
    <div id="scpfw_sizechart_popup" class="scpfw_sizechart_main">
    </div>
    <div id="scpfw_schart_sidepopup" class="scpfw_schart_sdpopup_main">
    </div>
    <div class="scpfw_schart_sidpp_overlay"></div>
    <?php
}


add_action( 'wp_ajax_scpfw_sizechart','scpfw_sizechart' );
add_action( 'wp_ajax_nopriv_scpfw_sizechart','scpfw_sizechart' );
function scpfw_sizechart() {
    $product_id             = sanitize_text_field($_REQUEST['product_id']);
    $product                = wc_get_product( $product_id );
    $chart_id               = sanitize_text_field($_REQUEST['chart_id']);
    $chart_title            = get_post_meta( $chart_id, SCPFW_PREFIX.'sub_title', true );
    $size_chartdata         = get_post_meta( $chart_id, SCPFW_PREFIX.'size_chartdata', true );
    $totalrow               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalrow', true );
    $totalcol               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalcol', true );
    $show_tab               = get_post_meta( $chart_id, SCPFW_PREFIX.'show_tab', true );
    $chart_tab_name         = get_post_meta( $chart_id, SCPFW_PREFIX.'chart_tab_name', true);
    $dis_tab_name           = get_post_meta( $chart_id, SCPFW_PREFIX.'dis_tab_name', true);
    $tbl_border             = get_post_meta( $chart_id, SCPFW_PREFIX.'tbl_border', true);
    $table_array            = $size_chartdata;

    echo '<div id="scpfw_schart_popup_cls" class="scpfw_schart_popup_cls"></div>';
    echo '<div class="scpfw_tableclass">';
        echo '<div class="scpfw_sizechart_content">';
            echo '<div class="scpfw_popup_header">';
                echo '<h1>'.esc_attr($chart_title).'</h1>';
                echo '<span class="scpfw_popup_close">';
                echo '<svg height="365.696pt" viewBox="0 0 365.696 365.696" width="365.696pt" xmlns="http://www.w3.org/2000/svg">
                        <path d="m243.1875 182.859375 113.132812-113.132813c12.5-12.5 12.5-32.765624 0-45.246093l-15.082031-15.082031c-12.503906-12.503907-32.769531-12.503907-45.25 0l-113.128906 113.128906-113.132813-113.152344c-12.5-12.5-32.765624-12.5-45.246093 0l-15.105469 15.082031c-12.5 12.503907-12.5 32.769531 0 45.25l113.152344 113.152344-113.128906 113.128906c-12.503907 12.503907-12.503907 32.769531 0 45.25l15.082031 15.082031c12.5 12.5 32.765625 12.5 45.246093 0l113.132813-113.132812 113.128906 113.132812c12.503907 12.5 32.769531 12.5 45.25 0l15.082031-15.082031c12.5-12.503906 12.5-32.769531 0-45.25zm0 0"/>
                    </svg>';
                echo '</span>';
            echo '</div>';
            echo '<div class="scpfw_popup_body">';
                echo '<div class="scpfw_popup_data">';
                    echo '<div class="scpfw_popup_padding_div">';
                        if($show_tab == "on"){
                            ?>
                                <ul class="scpfw_front_tabs">
                                    <li class="tab-link current" data-tab="tab-default">
                                        <?php echo __( $chart_tab_name, 'size-chart-product-for-woocommerce' );?>
                                    </li>
                                    <li class="tab-link" data-tab="tab-general">
                                        <?php echo __( $dis_tab_name , 'size-chart-product-for-woocommerce' );?>
                                    </li>
                                </ul>
                                <div id="tab-default" class="scpfw_front_tab_content current">
                                    <div class="scpfw_child_div">
                                        <?php
                                            echo '<table>';
                                                $count = 0;
                                                for($i=0;$i<$totalrow;$i++){
                                                    echo "<tr>";
                                                        
                                                        for($j=0;$j<$totalcol;$j++){
                                                            echo "<td>".esc_attr($table_array[$count])."</td>";
                                                            $count++;
                                                        }

                                                    echo "</tr>";
                                                }
                                            echo '</table>'; 
                                        ?>
                                    </div>
                                </div>
                                <div id="tab-general" class="scpfw_front_tab_content">
                                    <div class="scpfw_child_div">
                                        <?php echo get_post_field('post_content', $chart_id); ?> 
                                        <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                    </div>
                                </div>
                            <?php
                        }else{
                            echo get_post_field('post_content', $chart_id);
                            ?>
                                <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                            <?php
                            echo '<table>';
                                $count = 0;
                                for($i=0;$i<$totalrow;$i++){
                                    echo "<tr>";
                                        for($j=0;$j<$totalcol;$j++){
                                            echo "<td>".esc_attr($table_array[$count])."</td>";
                                            $count++;
                                        }
                                    echo "</tr>";
                                }
                            echo '</table>';
                        } 
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    ?>
    <style type="text/css">
        #scpfw_sizechart_popup .scpfw_tableclass table {
            border: <?php echo esc_attr($tbl_border); ?>;
        }
        #scpfw_sizechart_popup .scpfw_tableclass tr {
            color: #000000;
        }
        #scpfw_sizechart_popup .scpfw_tableclass table tr:nth-child(even) {
            background: #d6d8db;
        }
        #scpfw_sizechart_popup .scpfw_tableclass table tr:nth-child(odd) {
            background:#e9ebed;
        }
        #scpfw_sizechart_popup .scpfw_tableclass table tr:nth-child(1) {
            background: #e9ebed;
            color:#000000;
            font-weight: 700;
            text-transform: capitalize;
        }
    </style>
    <?php
    
    exit();
}

//SCpfw ajax
add_action( 'wp_ajax_scpfw_sdpp_sizechart','scpfw_sdpp_sizechart' );
add_action( 'wp_ajax_nopriv_scpfw_sdpp_sizechart', 'scpfw_sdpp_sizechart' );
function scpfw_sdpp_sizechart() {
    $product_id             = sanitize_text_field($_REQUEST['product_id']);
    $product                = wc_get_product( $product_id );
    $chart_id               = sanitize_text_field($_REQUEST['chart_id']);
    $chart_title            = get_post_meta( $chart_id, SCPFW_PREFIX.'sub_title', true );
    $size_chartdata         = get_post_meta( $chart_id, SCPFW_PREFIX.'size_chartdata', true );
    $totalrow               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalrow', true );
    $totalcol               = get_post_meta( $chart_id, SCPFW_PREFIX.'totalcol', true );
    $show_tab               = get_post_meta( $chart_id, SCPFW_PREFIX.'show_tab', true );
    $chart_tab_name         = get_post_meta( $chart_id, SCPFW_PREFIX.'chart_tab_name', true);
    $dis_tab_name           = get_post_meta( $chart_id, SCPFW_PREFIX.'dis_tab_name', true);
    $tbl_border             = get_post_meta( $chart_id, SCPFW_PREFIX.'tbl_border', true);
    $table_array            = $size_chartdata;

    echo '<div class="scpfw_schart_sdpopup_close">';
    echo '<svg height="365.696pt" viewBox="0 0 365.696 365.696" width="365.696pt" xmlns="http://www.w3.org/2000/svg">
            <path d="m243.1875 182.859375 113.132812-113.132813c12.5-12.5 12.5-32.765624 0-45.246093l-15.082031-15.082031c-12.503906-12.503907-32.769531-12.503907-45.25 0l-113.128906 113.128906-113.132813-113.152344c-12.5-12.5-32.765624-12.5-45.246093 0l-15.105469 15.082031c-12.5 12.503907-12.5 32.769531 0 45.25l113.152344 113.152344-113.128906 113.128906c-12.503907 12.503907-12.503907 32.769531 0 45.25l15.082031 15.082031c12.5 12.5 32.765625 12.5 45.246093 0l113.132813-113.132812 113.128906 113.132812c12.503907 12.5 32.769531 12.5 45.25 0l15.082031-15.082031c12.5-12.503906 12.5-32.769531 0-45.25zm0 0"/>
        </svg>';
    echo '</div>';
    echo '<div class="scpfw_sdpp_table">';
        echo '<div class="scpfw_sdpp_szchart_content">';
            echo '<div class="scpfw_sdpp_popup_header">';
                echo '<h1>'.esc_attr($chart_title).'</h1>';
            echo '</div>';
            echo '<div class="scpfw_sdpp_popup_body">';
                echo '<div class="scpfw_popup_data">';
                    echo '<div class="scpfw_sdpp_padding_div">';
                        if($show_tab == "on") {
                            ?>
                                <ul class="scpfw_sdpp_front_tabs">
                                    <li class="tab-link current" data-tab="tab-default">
                                        <?php echo __( $chart_tab_name, 'size-chart-product-for-woocommerce' );?>
                                    </li>
                                    <li class="tab-link" data-tab="tab-general">
                                        <?php echo __( $dis_tab_name , 'size-chart-product-for-woocommerce' );?>
                                    </li>
                                </ul>
                                <div id="tab-default" class="scpfw_sdpp_frtab_content current">
                                    <div class="scpfw_sdpp_child_div">
                                        <?php
                                            echo '<table>';
                                                $count = 0;
                                                for($i=0;$i<$totalrow;$i++){
                                                    echo "<tr>";
                                                        
                                                        for($j=0;$j<$totalcol;$j++){
                                                            echo "<td>".esc_attr($table_array[$count])."</td>";
                                                            $count++;
                                                        }

                                                    echo "</tr>";
                                                }
                                            echo '</table>'; 
                                        ?>
                                    </div>
                                </div>
                                <div id="tab-general" class="scpfw_sdpp_frtab_content">
                                    <div class="scpfw_child_div">
                                        <?php echo get_post_field('post_content', $chart_id); ?> 
                                        <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                    </div>
                                </div>
                            <?php
                        } else {
                            echo get_post_field('post_content', $chart_id);
                            ?>
                                <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                            <?php
                            echo '<table>';
                                $count = 0;
                                for($i=0;$i<$totalrow;$i++) {
                                    echo "<tr>";
                                        for($j=0;$j<$totalcol;$j++){
                                            echo "<td>".esc_attr($table_array[$count])."</td>";
                                            $count++;
                                        }

                                    echo "</tr>";
                                }
                            echo '</table>';
                        } 
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    ?>
    <style type="text/css">
        .scpfw_sdpp_child_div table {
            border: <?php echo esc_attr($tbl_border); ?>;
        }
        .scpfw_sdpp_table tr {
            color: #000000;
        }
        .scpfw_sdpp_table tr:nth-child(even) {
            background:#d6d8db;
        }
        .scpfw_sdpp_table tr:nth-child(odd) {
            background: #e9ebed;
        }
        .scpfw_sdpp_table tr:nth-child(1) {
            background: #e9ebed;
            color: #000000;
            font-weight: 700;
            text-transform: capitalize;
        }
    </style>
    <?php

    exit;
}


//Shortcode Button
add_shortcode('scpfw_buttons', 'scpfw_custom_shortcode_button');
function scpfw_custom_shortcode_button( $atts = '' ) {  

    $value = shortcode_atts( array(
        'product_id' => ''
    ), $atts );
    if (!empty($value['product_id'])) {
        $pro_id = $value['product_id'];
    }else{
        $pro_id = get_the_ID();
    }

    $chart_ids = scpfw_get_chart_ids($pro_id);
    ob_start();

    if(!empty($chart_ids)) {

        foreach ($chart_ids as $key => $chart_id) {
            $szchartpp_shop_enbl      = get_post_meta( $chart_id, SCPFW_PREFIX.'szchartpp_shop_enbl', true );
            $btn_tab      = get_post_meta( $chart_id, SCPFW_PREFIX.'shop_btn_show', true );
            $btn_pos      = get_post_meta( $chart_id, SCPFW_PREFIX.'shop_btn_pos', true );
            $alw_mobile      = get_post_meta( $chart_id, SCPFW_PREFIX.'alw_mobile', true );
            $showCouponField ='true';
            if(wp_is_mobile()) {
                if($alw_mobile != "on") {
                    $showCouponField = 'false';
                }
            }
            if( $showCouponField == 'true'){
                $btn_lbl    = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_lbl', true );
                $btn_ft     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_ft_clr', true );
                $btn_bg     = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_bg_clr', true );
                $btn_brd_rd = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_brd_rd', true );
                $btn_padding = get_post_meta( $chart_id, SCPFW_PREFIX.'btn_padding', true );
                $popup_loader = get_post_meta( $chart_id, SCPFW_PREFIX.'popup_loader', true );
                if ($popup_loader == "loader_1" || empty($popup_loader)) {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-1.gif';
                }elseif ($popup_loader == "loader_2") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-2.gif';
                }elseif ($popup_loader == "loader_3") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-3.gif';
                }elseif ($popup_loader == "loader_4") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-4.gif';
                }elseif ($popup_loader == "loader_5") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-5.gif';
                }elseif ($popup_loader == "loader_6") {
                    $loader = SCPFW_PLUGIN_DIR.'/assets/images/loader-6.gif';
                }
                $style      = "color:".esc_attr($btn_ft).";background-color:".esc_attr($btn_bg).";border-radius:".esc_attr($btn_brd_rd)."px;padding:".esc_attr($btn_padding).";";
                
                if ($btn_tab == "popup") {
                        ?>
                        <div class="scpfw_btn">
                            <button class="scpfw_open" data-id="<?php echo esc_attr($pro_id); ?>" data-image="<?php echo esc_attr($loader);?>" data-cid="<?php echo esc_attr($chart_id); ?>" style="<?php echo esc_attr($style); ?>">
                                <?php echo esc_attr($btn_lbl); ?>
                            </button>
                        </div>
                        <?php
                } 
                
            }
        }
    }
    $content = ob_get_clean();

    return $content;
}