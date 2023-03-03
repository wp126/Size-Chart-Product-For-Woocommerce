<?php  
add_action( 'init','SCPFW_create_menu');
function SCPFW_create_menu() {
    $post_type = 'scpfw_size_chart';
    $singular_name = 'Size Chart';
    $plural_name = 'Size Charts';
    $slug = 'scpfw_size_chart';
    $labels = array(
        'name'               => _x( $plural_name, 'post type general name', 'size-chart-product-for-woocommerce' ),
        'singular_name'      => _x( $singular_name, 'post type singular name', 'size-chart-product-for-woocommerce' ),
        'menu_name'          => _x( $singular_name, 'admin menu name', 'size-chart-product-for-woocommerce' ),
        'name_admin_bar'     => _x( $singular_name, 'add new name on admin bar', 'size-chart-product-for-woocommerce' ),
        'add_new'            => __( 'Add New', 'size-chart-product-for-woocommerce' ),
        'add_new_item'       => __( 'Add New '.$singular_name, 'size-chart-product-for-woocommerce' ),
        'new_item'           => __( 'New '.$singular_name, 'size-chart-product-for-woocommerce' ),
        'edit_item'          => __( 'Edit '.$singular_name, 'size-chart-product-for-woocommerce' ),
        'view_item'          => __( 'View '.$singular_name, 'size-chart-product-for-woocommerce' ),
        'all_items'          => __( 'All '.$plural_name, 'size-chart-product-for-woocommerce' ),
        'search_items'       => __( 'Search '.$plural_name, 'size-chart-product-for-woocommerce' ),
        'parent_item_colon'  => __( 'Parent '.$plural_name.':', 'size-chart-product-for-woocommerce' ),
        'not_found'          => __( 'No Size Chart found.', 'size-chart-product-for-woocommerce' ),
        'not_found_in_trash' => __( 'No Size Chart found in Trash.', 'size-chart-product-for-woocommerce' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description', 'size-chart-product-for-woocommerce' ),
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => $slug ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'          => 'dashicons-chart-pie'
    );
    register_post_type( $post_type, $args );
}

add_action( 'add_meta_boxes','SCPFW_add_meta_box');
function SCPFW_add_meta_box() {
    add_meta_box(
        'SCPFW_metabox',
        __( 'All Size Chart Settings', 'size-chart-product-for-woocommerce' ),
        'SCPFW_metabox_cb',
        'scpfw_size_chart',
        'normal'


    );
}


function SCPFW_metabox_cb( $post ) {
    wp_nonce_field( 'scpfw_meta_save', 'scpfw_meta_save_nounce' );
    ?> 
    <div class="scpfw-container">
        <ul class="nav-tab-wrapper woo-nav-tab-wrapper">
            <li class="nav-tab" data-tab="tab-default">
                <?php echo __( 'Chart', 'size-chart-product-for-woocommerce' );?>
            </li>
            <li class="nav-tab" data-tab="tab-general">
                <?php echo __( 'Chart Show Settings', 'size-chart-product-for-woocommerce' );?>
            </li>
            <li class="nav-tab" data-tab="tab-table">
                <?php echo __( 'Table Settings', 'size-chart-product-for-woocommerce' );?>
            </li>
            <li class="nav-tab" data-tab="tab-tab">
                <?php echo __( 'Tab Settings', 'size-chart-product-for-woocommerce' );?>
            </li>
           
        </ul>
        <div id="tab-default" class="tab-content current">
            <h2><?php echo __( "Create Chart", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <p class="scpfw_shortcode"><?php echo __( 'Use this shortcode <strong>[scpfw_buttons product_id=id]</strong> (Ex. id = 1,2,3,4,5.... product ids) for any product.', 'size-chart-product-for-woocommerce' );?></p>
                <?php
                    $table = get_post_meta( $post->ID, SCPFW_PREFIX.'size_chartdata', true); 
                    $table_array = $table;

                    if(!empty($table_array[0])) {

                        $totalrow = get_post_meta( $post->ID, SCPFW_PREFIX.'totalrow', true);
                        $totalcol = get_post_meta( $post->ID, SCPFW_PREFIX.'totalcol', true);
                        
                            echo '<table class="scpfw_chart_tbl">';
                            echo '<input type="hidden" name="totalrow" value="'.$totalrow.'">';
                            echo '<input type="hidden" name="totalcol" value="'.$totalcol.'">';

                            $count = 0;
                            ?>
                            <tr>
                                <td>
                                    <a class="addcolumn"><img src= "<?php echo SCPFW_PLUGIN_DIR; ?>/assets/images/plus.png"></a>
                                </td>
                                <?php for($j=0; $j<$totalcol-1; $j++) { ?>
                                       <td><a class="addcolumn"><img src= "<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/plus.png"></a><a class="deletecolumn"><img src= "<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/delete.png"></a></td> 
                                <?php } ?>
                                <td></td>
                            </tr>
                            <?php for($i=0; $i<$totalrow; $i++) { ?>
                                <tr>
                                    <?php  for($j=0; $j<$totalcol; $j++) { ?>
                                        <td>
                                            <input type="text" name="size_chartdata[]" value="<?php echo htmlspecialchars($table_array[$count]); ?>">
                                        </td>
                                    <?php $count++; }

                                    if($count == $totalcol) { ?>
                                       <td><a class="addrow"><img src= "<?php echo SCPFW_PLUGIN_DIR; ?>/assets/images/plus.png"></a></td>
                                    <?php }else{ ?>
                                        <td><a class="addrow"><img src= "<?php echo SCPFW_PLUGIN_DIR; ?>/assets/images/plus.png"></a><a class="deleterow"><img src= "<?php echo SCPFW_PLUGIN_DIR; ?>/assets/images/delete.png"></a></td>
                                   <?php } ?>
                               </tr>
                            <?php } ?>
                        </table>
                        <?php
                    } else {
                        ?>
                        <table class="scpfw_chart_tbl">
                            <input type="hidden" name="totalrow">
                            <input type="hidden" name="totalcol">
                            <tr>
                                <td>
                                    <a class="addcolumn">
                                        <img src= " <?php echo SCPFW_PLUGIN_DIR . '/assets/images/plus.png' ?>">
                                    </a>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="size_chartdata[]"></td>
                                <td>
                                    <a class="addrow">
                                        <img src= " <?php echo SCPFW_PLUGIN_DIR . '/assets/images/plus.png' ?>">
                                    </a>   
                                </td>
                            </tr>
                        </table> 
                        <?php
                    }
                ?>
            </div>
        </div>
        <div id="tab-general" class="tab-content">
            <h2><?php echo __( "Popup Loader Setting", 'size-chart-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Popup Loader', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <div class="loader_div">
                                <?php $popup_loader = get_post_meta( $post->ID, SCPFW_PREFIX.'popup_loader', true); 
                                 if( $popup_loader == '') {
                                    $popup_loader = 'loader_1';
                                }?>
                                <input type="radio" name="popup_loader" value="loader_1" <?php if($popup_loader == 'loader_1'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-1.gif" class="loader_admin">
                                <input type="radio" name="popup_loader" value="loader_2" <?php if($popup_loader == 'loader_2'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-2.gif" class="loader_admin">
                                <input type="radio" name="popup_loader" value="loader_3" <?php if($popup_loader == 'loader_3'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-3.gif" class="loader_admin">
                                <input type="radio" name="popup_loader" value="loader_4" <?php if($popup_loader == 'loader_4'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-4.gif" class="loader_admin">
                                <input type="radio" name="popup_loader" value="loader_5" <?php if($popup_loader == 'loader_5'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-5.gif" class="loader_admin">
                                <input type="radio" name="popup_loader" value="loader_6" <?php if($popup_loader == 'loader_6'){echo "checked";}?>>
                                <img src="<?php echo SCPFW_PLUGIN_DIR;?>/assets/images/loader-6.gif" class="loader_admin">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <h2><?php echo __( "Single Product Page Setting", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Apply Chart to All Products', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $szchartpp_aply_all = get_post_meta( $post->ID, SCPFW_PREFIX.'szchartpp_aply_all', true); ?>
                        <td>
                            <input type="checkbox" name="szchartpp_aply_all" value="enable" <?php if($szchartpp_aply_all == "enable") { echo "checked"; } ?>>
                            <label><?php echo __( 'Apply to All', 'size-chart-product-for-woocommerce' );?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Show Chart', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $btn_show = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_show', true);
                            if( $btn_show == '') {
                                $btn_show = 'popup';
                            }  
                        ?>
                        <td>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="btn_show" value="tab" <?php if($btn_show == "tab"){ echo "checked"; } ?>><?php echo __( 'In Product Tab', 'size-chart-product-for-woocommerce' );?>    
                            </div>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="btn_show" value="popup" <?php if($btn_show == "popup"){ echo "checked"; } ?>><?php echo __( 'Popup', 'size-chart-product-for-woocommerce' );?>
                            </div>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="btn_show" value="sidepopup" disabled><?php echo __( 'Sidebar Popup', 'size-chart-product-for-woocommerce' );?>
                                <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Chart Heading Text', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $sub_title = get_post_meta( $post->ID, SCPFW_PREFIX.'sub_title', true); 
                            if( $sub_title == '') {
                                $sub_title = 'Size Charts';
                            }
                        ?>
                        <td>
                            <input type="text" name="sub_title" value="<?php echo esc_attr($sub_title); ?>">
                        </td>
                    </tr> 
                </table>
            </div>
            <div class="scpfw_tab_div" style="display: none;">
                <h2><?php echo __( "Product Tab Setting", 'size-chart-product-for-woocommerce' );?></h2>
                <div class="scpfw_child_div">
                    <table>
                        <tr>
                            <th><?php echo __( 'Tab Label', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $tab_lbl = get_post_meta( $post->ID, SCPFW_PREFIX.'tab_lbl', true); 
                                if( $tab_lbl == '') {
                                    $tab_lbl = 'Charts';
                                }
                            ?>
                            <td>
                                <input type="text" name="tab_lbl" value="<?php echo esc_attr($tab_lbl); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Tab Priority', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $tab_pririty = get_post_meta( $post->ID, SCPFW_PREFIX.'tab_pririty', true); ?>
                            <td>
                                <input type="text" name="tab_pririty" value="<?php echo esc_attr($tab_pririty); ?>">
                                <span class="scpfw_desc"><?php echo __( 'Lower number means higher position in the order.', 'size-chart-product-for-woocommerce' );?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="scpfw_popup_div" style="display: none;">
                <h2><?php echo __( "Popup Button Setting", 'size-chart-product-for-woocommerce' );?></h2>
                <div class="scpfw_child_div">
                    <table>
                        <tr>
                            <th><?php echo __( 'Button Label', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $btn_lbl = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_lbl', true); 
                                if( $btn_lbl == '') {
                                    $btn_lbl = 'Charts';
                                }
                            ?>
                            <td>
                                <input type="text" name="btn_lbl" value="<?php echo esc_attr($btn_lbl); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Button Text Color', 'size-chart-product-for-woocommerce' );?></th>
                            <?php
                            $btn_ft_clr = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_ft_clr', true);
                            
                            if($btn_ft_clr == '') {
                                $btn_ft_clr = '#ffffff';
                            }
                            ?>
                            <td>
                                <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($btn_ft_clr); ?>" name="btn_ft_clr" value="<?php echo esc_attr($btn_ft_clr); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Button Border Radius', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $btn_brd_rd = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_brd_rd', true); ?>
                            <td>
                                <input type="number" name="btn_brd_rd" value="<?php echo esc_attr($btn_brd_rd); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Button Background Color', 'size-chart-product-for-woocommerce' );?></th>
                            <?php
                            $btn_bg_clr = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_bg_clr', true);

                            if($btn_bg_clr == '') {
                                $btn_bg_clr = '#000000';
                            }
                            ?>
                            <td>
                                <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($btn_bg_clr); ?>" name="btn_bg_clr" value="<?php echo esc_attr($btn_bg_clr); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Button Position', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $btn_pos = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_pos', true); ?>
                            <td>
                                <select name="btn_pos">
                                    <option value="before_add_cart" <?php if($btn_pos == "before_add_cart") { echo "selected"; } ?>><?php echo __( 'Before Add To Cart', 'size-chart-product-for-woocommerce' );?></option>
                                    <option value="after_add_cart" <?php if($btn_pos == "after_add_cart") { echo "selected"; } ?>><?php echo __( 'After Add To Cart', 'size-chart-product-for-woocommerce' );?></option>
                                    <option value="before_summry_text" <?php if($btn_pos == "before_summry_text") { echo "selected"; } ?>><?php echo __( 'Before Summary Text', 'size-chart-product-for-woocommerce' );?></option>
                                    <option value="aftr_prod_meta" <?php if($btn_pos == "aftr_prod_meta") { echo "selected"; } ?>><?php echo __( 'After Product Meta', 'size-chart-product-for-woocommerce' );?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo __( 'Button Padding', 'size-chart-product-for-woocommerce' );?></th>
                            <?php $btn_padding = get_post_meta( $post->ID, SCPFW_PREFIX.'btn_padding', true); ?>
                            <td>
                                <input type="text" name="btn_padding" value="<?php echo esc_attr($btn_padding); ?>"/>
                                <span class="scpfw_desc"><?php echo __( 'add padding like ex. <strong>10px 15px</strong>', 'size-chart-product-for-woocommerce' );?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>



            <h2><?php echo __( "Shop Page Setting", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Show Size Chart Popup Button on Shop Page', 'size-chart-product-for-woocommerce' );?></th>
                        <?php
                        $szchartpp_shop_enbl = get_post_meta( $post->ID, SCPFW_PREFIX.'szchartpp_shop_enbl', true);
                            if( $szchartpp_shop_enbl == '') {
                                $szchartpp_shop_enbl = 'enable';
                            }
                        ?>
                        <td>
                            <input type="checkbox" name="szchartpp_shop_enbl" value="enable" <?php if($szchartpp_shop_enbl == "enable") { echo "checked"; } ?>>
                            <label><?php echo __( 'Enable', 'size-chart-product-for-woocommerce' );?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Show Chart', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $shop_btn_show = get_post_meta( $post->ID, SCPFW_PREFIX.'shop_btn_show', true); 
                            if( $shop_btn_show == '') {
                                $shop_btn_show = 'popup';
                            }
                        ?>
                        <td>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="shop_btn_show" value="popup" checked><?php echo __( 'Popup', 'size-chart-product-for-woocommerce' );?>
                            </div>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="shop_btn_show" value="sidepopup" disabled><?php echo __( 'Sidebar Popup', 'size-chart-product-for-woocommerce' );?>
                                <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Button Position', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $shop_btn_pos = get_post_meta( $post->ID, SCPFW_PREFIX.'shop_btn_pos', true); 
                            if( $shop_btn_pos == '') {
                                $shop_btn_pos = 'before_add_cart';
                            }
                        ?>
                        <td>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="shop_btn_pos" value="before_add_cart" checked><?php echo __( 'Before Add To Cart', 'size-chart-product-for-woocommerce' );?>
                            </div>
                            <div class="scpfw_swcrt_select">
                                <input type="radio" name="shop_btn_pos" disabled><?php echo __( 'After Add To Cart', 'size-chart-product-for-woocommerce' );?>
                                <label class="scpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>



            <h2><?php echo __( "User Setting", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Show Chart only to Logged in Users', 'size-chart-product-for-woocommerce' );?></th>
                        
                        <td>
                            <input type="checkbox" name="alw_gust_usr" disabled>
                            <strong>Enable</strong>
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Show Chart in Mobile', 'size-chart-product-for-woocommerce' );?></th>
                        <?php
                        $alw_mobile = get_post_meta($post->ID, SCPFW_PREFIX.'alw_mobile', true);
                        if( $alw_mobile == '') {
                            $alw_mobile = 'on';
                        }
                        ?>
                        <td>
                            <input type="checkbox" name="alw_mobile" <?php if($alw_mobile == "on") { echo "checked"; } ?>>
                            <strong><?php echo __( 'Enable', 'size-chart-product-for-woocommerce' );?></strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="tab-table" class="tab-content">
            <h2><?php echo __( "table Setting", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Table Head Background Color', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="#e9ebed" name="tbl_head_bg_clr" value="#e9ebed"  disabled />
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Table Head Font Color', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="#000000" name="tbl_head_ft_clr" value="#000000" disabled/>
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Table Even Row Color', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="#d6d8db" name="tbl_even_row_clr" value="#d6d8db" disabled/>
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Table Odd Raw Color', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="#e9ebed" name="tbl_odd_row_clr" value="#e9ebed" disabled/>
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Table Data Row Font Color', 'size-chart-product-for-woocommerce' );?></th>
                        <td>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="#000000" name="tbl_dtrow_font_clr" value="#000000" disabled />
                            <label class="scpfw_comman_link"><?php echo __('This Option Available in ','size-chart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/size-chart-product-for-woocommerce/" target="_blank"><?php echo __( 'Pro Version', 'size-chart-product-for-woocommerce' );?></a></label>

                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Table Border', 'size-chart-product-for-woocommerce' );?></th>
                        <?php
                        $tbl_border = get_post_meta( $post->ID, SCPFW_PREFIX.'tbl_border', true);
                        ?>
                        <td>
                            <input type="text" name="tbl_border" value="<?php echo esc_attr($tbl_border); ?>" placeholder="2px solid #ff0000"/>
                            <span class="scpfw_desc"><?php echo __( 'add border like ex. <strong>2px solid #ff0000</strong>', 'size-chart-product-for-woocommerce' );?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="tab-tab" class="tab-content">
            <h2><?php echo __( "Tab Setting", 'size-chart-product-for-woocommerce' );?></h2>
            <div class="scpfw_child_div">
                <table>
                    <tr>
                        <th><?php echo __( 'Show Tab Wise Content', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $show_tab = get_post_meta( $post->ID, SCPFW_PREFIX.'show_tab', true); 
                            if($show_tab == '') {
                                $show_tab = 'on';
                            }
                        ?>
                        <td>
                            <input type="checkbox" name="show_tab" value="on" <?php if($show_tab == "on"){ echo "checked"; } ?>>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo __( 'Chart Tab Name', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $chart_tab_name = get_post_meta( $post->ID, SCPFW_PREFIX.'chart_tab_name', true); 
                            if($chart_tab_name == '') {
                                $chart_tab_name = 'Size Chart';
                            }
                        ?>
                        <td>
                            <input type="text" name="chart_tab_name" value="<?php echo esc_attr($chart_tab_name); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Description Tab Name', 'size-chart-product-for-woocommerce' );?></th>
                        <?php $dis_tab_name = get_post_meta( $post->ID, SCPFW_PREFIX.'dis_tab_name', true); 
                            if($dis_tab_name == '') {
                                $dis_tab_name = 'How to Measure';
                            }    
                        ?>
                        <td>
                            <input type="text" name="dis_tab_name" value="<?php echo esc_attr($dis_tab_name); ?>">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}


function SCPFW_recursive_sanitize_text_field($array) {
    if(!empty($array)) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = SCPFW_recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field( $value );
            }
        }
    }
    return $array;
}


add_action( 'edit_post','SCPFW_meta_save', 10, 2);
function SCPFW_meta_save( $post_id, $post ) {
 
    if ($post->post_type != 'scpfw_size_chart') { return; }
 
    if ( !current_user_can( 'edit_post', $post_id )) return;
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['scpfw_meta_save_nounce']) && wp_verify_nonce( $_POST['scpfw_meta_save_nounce'], 'scpfw_meta_save' )? 'true': 'false');

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;



    if (isset($_REQUEST['show_tab'])) {
        $show_tab           = sanitize_text_field( $_REQUEST['show_tab'] );
    }else{
        $show_tab           = 'off';
    }

    $size_chartdata = "";
    if(!empty($_REQUEST['size_chartdata'] )){
        $size_chartdata = SCPFW_recursive_sanitize_text_field( $_REQUEST['size_chartdata']);
    }
    $totalrow = "";
    if(!empty($_REQUEST['totalrow'] )){
        $totalrow = sanitize_text_field( $_REQUEST['totalrow']);
    }
    $totalcol = "";
    if(!empty($_REQUEST['totalcol'] )){
        $totalcol = sanitize_text_field( $_REQUEST['totalcol']);
    }
    $btn_show = "";
    if(!empty($_REQUEST['btn_show'] )){
        $btn_show = sanitize_text_field( $_REQUEST['btn_show']);
    }
    $shop_btn_show = "";
    if(!empty($_REQUEST['shop_btn_show'] )){
        $shop_btn_show = sanitize_text_field( $_REQUEST['shop_btn_show']);
    }
    $sub_title = "";
    if(!empty($_REQUEST['sub_title'] )){
        $sub_title = sanitize_text_field( $_REQUEST['sub_title']);
    }
    $tab_lbl = "";
    if(!empty($_REQUEST['tab_lbl'] )){
        $tab_lbl = sanitize_text_field( $_REQUEST['tab_lbl']);
    }
    $tab_pririty = "";
    if(!empty($_REQUEST['tab_pririty'] )){
        $tab_pririty = sanitize_text_field( $_REQUEST['tab_pririty']);
    }
    $btn_lbl = "";
    if(!empty($_REQUEST['btn_lbl'] )){
        $btn_lbl = sanitize_text_field( $_REQUEST['btn_lbl']);
    }
    $btn_ft_clr = "";
    if(!empty($_REQUEST['btn_ft_clr'] )){
        $btn_ft_clr = sanitize_text_field( $_REQUEST['btn_ft_clr']);
    }
    $btn_bg_clr = "";
    if(!empty($_REQUEST['btn_bg_clr'] )){
        $btn_bg_clr = sanitize_text_field( $_REQUEST['btn_bg_clr']);
    }
    $btn_pos = "";
    if(!empty($_REQUEST['btn_pos'] )){
        $btn_pos = sanitize_text_field( $_REQUEST['btn_pos']);
    }
    $shop_btn_pos = "";
    if(!empty($_REQUEST['shop_btn_pos'] )){
        $shop_btn_pos = sanitize_text_field( $_REQUEST['shop_btn_pos']);
    }
    $btn_padding = "";
    if(!empty($_REQUEST['btn_padding'] )){
        $btn_padding = sanitize_text_field( $_REQUEST['btn_padding']);
    }

    $tbl_border = "";
    if(!empty($_REQUEST['tbl_border'] )){
        $tbl_border = sanitize_text_field( $_REQUEST['tbl_border']);
    }
    $chart_tab_name = "";
    if(!empty($_REQUEST['chart_tab_name'] )){
        $chart_tab_name = sanitize_text_field( $_REQUEST['chart_tab_name']);
    }
    $dis_tab_name = "";
    if(!empty($_REQUEST['dis_tab_name'] )){
        $dis_tab_name = sanitize_text_field( $_REQUEST['dis_tab_name']);
    }
    $btn_brd_rd = "";
    if(!empty($_REQUEST['btn_brd_rd'] )){
        $btn_brd_rd = sanitize_text_field( $_REQUEST['btn_brd_rd']);
    }
    $popup_loader = "";
    if(!empty($_REQUEST['popup_loader'] )){
        $popup_loader = sanitize_text_field( $_REQUEST['popup_loader']);
    }



    if (isset($_REQUEST['alw_mobile'])) {
        $alw_mobile       = sanitize_text_field( $_REQUEST['alw_mobile'] );
    }else{
        $alw_mobile       = 'off';
    }
    
    if(isset($_REQUEST['szchartpp_shop_enbl']) && $_REQUEST['szchartpp_shop_enbl'] == 'enable') {
        $szchartpp_shop_enbl = sanitize_text_field($_REQUEST['szchartpp_shop_enbl']); 
    } else {
        $szchartpp_shop_enbl = 'disable';
    }

    if(isset($_REQUEST['szchartpp_aply_all']) && $_REQUEST['szchartpp_aply_all'] == 'enable') {
        $szchartpp_aply_all = sanitize_text_field($_REQUEST['szchartpp_aply_all']); 
    } else {
        $szchartpp_aply_all = 'disable';
    }
    
    update_post_meta( $post_id, SCPFW_PREFIX.'szchartpp_aply_all', $szchartpp_aply_all);
    update_post_meta( $post_id, SCPFW_PREFIX.'size_chartdata', $size_chartdata);
    update_post_meta( $post_id, SCPFW_PREFIX.'totalrow', $totalrow );
    update_post_meta( $post_id, SCPFW_PREFIX.'totalcol', $totalcol );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_show', $btn_show );
    update_post_meta( $post_id, SCPFW_PREFIX.'shop_btn_show', $shop_btn_show );
    update_post_meta( $post_id, SCPFW_PREFIX.'sub_title', $sub_title );
    update_post_meta( $post_id, SCPFW_PREFIX.'tab_lbl', $tab_lbl );
    update_post_meta( $post_id, SCPFW_PREFIX.'tab_pririty', $tab_pririty );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_lbl', $btn_lbl );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_ft_clr', $btn_ft_clr );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_bg_clr', $btn_bg_clr );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_pos', $btn_pos );
    update_post_meta( $post_id, SCPFW_PREFIX.'shop_btn_pos', $shop_btn_pos );
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_padding', $btn_padding );
    update_post_meta( $post_id, SCPFW_PREFIX.'alw_mobile', $alw_mobile );
    update_post_meta( $post_id, SCPFW_PREFIX.'tbl_border', $tbl_border);
    update_post_meta( $post_id, SCPFW_PREFIX.'show_tab', $show_tab);
    update_post_meta( $post_id, SCPFW_PREFIX.'chart_tab_name', $chart_tab_name);
    update_post_meta( $post_id, SCPFW_PREFIX.'dis_tab_name', $dis_tab_name);
    update_post_meta( $post_id, SCPFW_PREFIX.'btn_brd_rd', $btn_brd_rd);
    update_post_meta( $post_id, SCPFW_PREFIX.'szchartpp_shop_enbl', $szchartpp_shop_enbl);
    update_post_meta( $post_id, SCPFW_PREFIX.'popup_loader', $popup_loader );
}


add_action( 'admin_menu','SCPFW_add_pages');
function SCPFW_add_pages() {
    add_submenu_page(
        'edit.php?post_type=scpfw_size_chart',
        __( 'Import Sample Size Charts', 'size-chart-product-for-woocommerce' ),
        __( 'Import Sample Size Charts', 'size-chart-product-for-woocommerce' ),
        'manage_options',
        'scpfw-import-sample-size-charts',
        'SCPFW_pages_callback',
        100
    );
}


function SCPFW_pages_callback() {
    $url = admin_url()."edit.php?post_type=scpfw_size_chart&action=scpfwimport_chart";
    ?>
    <div class="wrap">
        <div class="scpfw_import_main">
            <h2><?php echo __( 'Import Sample Size Charts', 'size-chart-product-for-woocommerce' );?></h2>
            <?php
            if(isset($_REQUEST['import']) && $_REQUEST['import'] == 'success') {
                echo "<div class='scpfw_notice_success'><p>Sample size charts imported successfully.</p></div>";
            }
            ?>
            <form method="post" enctype="multipart/form-data" class="scpfw_import">
                <?php wp_nonce_field( 'scpfw_import_nonce_action', 'scpfw_import_nonce_field' ); ?>                      
                <div class="scpfw_importbox">
                    <h3><?php echo __( 'Import sample size charts', 'size-chart-product-for-woocommerce' );?></h3>
                    <input type="hidden" name="scpfw_import_action" value="scpfw_import_size_charts">
                    <input type="submit" value="One Click Import">
                </div>
                <p class="description"><?php echo __( 'Import sample size charts (premade sizecharts created by us) to better understand size charts options and you can simply edit them with your size charts.', 'size-chart-product-for-woocommerce' );?></p>
            </form>
        </div>
    </div>
    <?php
}

add_action( 'init','SCPFW_create_chart');
function SCPFW_create_chart() {
    if( current_user_can('administrator') ) { 
        if(isset($_REQUEST['scpfw_import_action']) && $_REQUEST['scpfw_import_action'] == 'scpfw_import_size_charts') {
            if(!isset( $_POST['scpfw_import_nonce_field'] ) || !wp_verify_nonce( $_POST['scpfw_import_nonce_field'], 'scpfw_import_nonce_action' )) {
                
                echo 'Sorry, your nonce did not verify.';
                exit;

            } else {
                $post_array = array(
                    "Women’s Shoes Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>',
                        'chart'  => Array(
                                    'US'  ,'Euro'  ,'UK'  ,'Inches'  ,'CM',
                                    '4'   ,'35'    ,'2'   ,'8.1875'  ,'20.8',
                                    '4.5' ,'35'    ,'2.5' ,'8.375'   ,'21.3',
                                    '5'   ,'35-36' ,'3'   ,'8.5'     ,'21.6',
                                    '5.5' ,'36'    ,'3.5' ,'8.75'    ,'22.2',
                                    '6'   ,'36-37' ,'4'   ,'8.875'   ,'22.5',
                                    '6.5' ,'37'    ,'4.5' ,'9.0625'  ,'23',
                                    '7'   ,'37-38' ,'5'   ,'9.25'    ,'23.5',
                                    '7.5' ,'38'    ,'5.5' ,'9.375'   ,'23.8',
                                    '8'   ,'38-39' ,'6'   ,'9.5'     ,'24.1',
                                    '8.5' ,'39'    ,'6.5' ,'9.6875'  ,'24.6',
                                    '9'   ,'39-40' ,'7'   ,'9.875'   ,'25.1',
                                    '9.5' ,'40'    ,'7.5' ,'10'      ,'25.4',
                                    '10'  ,'40-41' ,'8'   ,'10.1875' ,'25.9',
                                    '10.5','41'    ,'8.5' ,'10.3125' ,'26.2',
                                    '11'  ,'41-42' ,'9'   ,'10.5'    ,'26.7',
                                    '11.5','42'    ,'9.5' ,'10.6875' ,'27.1',
                                    '12'  ,'42-43' ,'10'  ,'10.875'  ,'27.6',

                                 ) ,
                        'totalrow'  => '18',
                        'totalcol'  => '5', 
                        'btn_show'=> 'popup',
                        'sub_title'=> "Women's Shoes Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Women's Shoes Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "before_add_cart",
                        'alw_mobile' =>"off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'women-shoes-size-image.jpg',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Men’s Shoes Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>',
                        'chart'  => Array(
                                    'US'   ,'Euro'  ,'UK'   ,'Inches'  ,'CM',
                                    '6'    ,'39'    ,'5.5'  ,'9.25'    ,'23.5',
                                    '6.5'  ,'39'    ,'6'    ,'9.5'     ,'24.1',
                                    '7'    ,'40'    ,'6.5'  ,'9.625'   ,'24.4',
                                    '7.5'  ,'40-41' ,'7'    ,'9.75'    ,'24.8',
                                    '8'    ,'41'    ,'7.5'  ,'9.9375'  ,'25.4',
                                    '8.5'  ,'41-42' ,'8'    ,'10.125'  ,'25.7',
                                    '9'    ,'42'    ,'8.5'  ,'10.25'   ,'26',
                                    '9.5'  ,'42-43' ,'9'    ,'10.4375' ,'26.7',
                                    '10'   ,'43'    ,'9.5'  ,'10.5625' ,'27',
                                    '10.5' ,'43-44' ,'10'   ,'10.75'   ,'27.3',
                                    '11'   ,'44'    ,'10.5' ,'10.9375' ,'27.9',
                                    '11.5' ,'44-45' ,'11'   ,'11.125'  ,'28.3',
                                    '12'   ,'45'    ,'11.5' ,'11.25'   ,'28.6',
                                    '13'   ,'46'    ,'12.5' ,'11.5625' ,'29.4',
                                    '14'   ,'47'    ,'13.5' ,'11.875'  ,'30.2',
                                    '15'   ,'48'    ,'14.5' ,'12.1875' ,'31',
                                    '16'   ,'49'    ,'15.5' ,'12.5'    ,'31.8',

                                 ) ,
                        'totalrow'  => '18',
                        'totalcol'  => '5', 
                        'btn_show'=> 'sidepopup',
                        'sub_title'=> "Men's Shoes Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Men's Shoes Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'mens-shoes-size-chart.png',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Women’s Cloth Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <ul>
                                      <li>
                                        <strong>Chest : </strong><br>
                                        Measure around the fullest part of the bust, keeping the tape parallel to the floor.
                                      </li>
                                      <li>
                                        <strong>Waist : </strong><br>
                                        Measure around the narrowest point, keeping the tape parallel to the floor.
                                      </li>
                                      <li>
                                        <strong>Hip : </strong><br>
                                        Stand with feet together and measure around the fullest point of the hip, keep the tape parallel to the floor.
                                      </li>
                                      <li>
                                        <strong>Inseam : </strong><br>
                                        Measure inside length of leg from your crotch to the bottom of ankle.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'UK SIZE'  , 'BUST'   ,'BUST'   ,'WAIST'  ,'WAIST', 'HIPS'   , 'HIPS',
                                    'N/A'      , 'INCHES' ,'CM'     ,'INCHES' ,'CM'   , 'INCHES' , 'CM',
                                    '4'        , '31'     ,'78'     ,'24'     ,'60'   , '33'     , '83.5',
                                    '6'        , '32'     ,'80.5'   ,'25'     ,'62.5' , '34'     , '86',
                                    '8'        , '33'     ,'83'     ,'26'     ,'65'   , '35'     , '88.5',
                                    '10'       , '35'     ,'88'     ,'28'     ,'70'   , '37'     , '93.5',
                                    '12'       , '37'     ,'93'     ,'30'     ,'75'   , '39'     , '98.5',
                                    '14'       , '39'     ,'98'     ,'31'     ,'80'   , '41'     , '103.5',
                                    '16'       , '41'     ,'103'    ,'33'     ,'85'   , '43'     , '108.5',
                                    '18'       , '44'     ,'110.5'  ,'36'     ,'92.5' , '46'     , '116',
                                 ) ,
                        'totalrow'  => '10',
                        'totalcol'  => '7', 
                        'btn_show'=> 'popup',
                        'sub_title'=> "Women’s Cloth Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Women’s Cloth Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'cloth_size_chart.png',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Men’s Waistcoats Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <strong>Chest :</strong>
                                    Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.',
                        'chart'  => Array(
                                    'CHEST MEASUREMENT'   , 'INCHES' ,  'CM',
                                    '32'                  , '32'     ,  '81',
                                    '34'                  , '34'     ,  '86',
                                    '36'                  , '36'     ,  '91',
                                    '38'                  , '38'     ,  '96',
                                    '40'                  , '40'     ,  '101',
                                    '42'                  , '42'     ,  '106',
                                    '44'                  , '44'     ,  '111',
                                    '46'                  , '46'     ,  '116',
                                 ) ,
                        'totalrow'  => '9',
                        'totalcol'  => '3', 
                        'btn_show'=> 'tab',
                        'sub_title'=> "Men’s Waistcoats Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Men’s Waistcoats Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'mens-waistcoats.jpg',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Women’s Jeans And Jeggings Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <ul>
                                      <li>
                                        <strong>Waist : </strong><br>
                                        Measure around your natural waistline,keeping the tape bit loose.
                                      </li>
                                      <li>
                                        <strong>Hips : </strong><br>
                                        Measure around the fullest part of your body at the top of your leg.
                                      </li>
                                      <li>
                                        <strong>Inseam : </strong><br>
                                        Wearing pants that fit well, measure from the crotch seam to the bottom of the leg.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'Size'    , 'Waist'  ,  'Hip',
                                    '24'      , '24'     ,  '35',
                                    '25'      , '25'     ,  '36',
                                    '26'      , '26'     ,  '37',
                                    '27'      , '27'     ,  '38',
                                    '28'      , '28'     ,  '39',
                                    '29'      , '29'     ,  '40',
                                    '30'      , '30'     ,  '41',
                                    '31'      , '31'     ,  '42',
                                    '32'      , '32'     ,  '43',
                                    '33'      , '33'     ,  '44',
                                    '34'      , '34'     ,  '45',
                                 ) ,
                        'totalrow'  => '12',
                        'totalcol'  => '3', 
                        'btn_show'=> 'tab',
                        'sub_title'=> "Women’s Jeans Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Women’s Jeans Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'women-jeans-size-chart.png',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Men’s Jeans & Trousers Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <p>To choose the correct size for you, measure your body as follows:</p>
                                    <ul>
                                      <li>
                                        <strong>Waist : </strong><br>
                                        Measure around natural waistline.
                                      </li>
                                      <li>
                                        <strong>Inside leg : </strong><br>
                                        Measure from top of inside leg at crotch to ankle bone.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'Size'    , 'Waist'  ,  'Hip',  'Inseam'  ,  'Outseam',
                                    '28'      , '28'     ,  '32' ,   '30'     ,  '41',
                                    '30'      , '28.5'   ,  '34' ,   '30'     ,  '42',
                                    '32'      , '30.5'   ,  '36' ,   '30'     ,  '43',
                                    '34'      , '32.5'   ,  '38' ,   '32'     ,  '44',
                                    '36'      , '34.5'   ,  '40' ,   '34'     ,  '45',
                                 ) ,
                        'totalrow'  => '6',
                        'totalcol'  => '5', 
                        'btn_show'=> 'tab',
                        'sub_title'=> "Men’s Jeans Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Men’s Jeans Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'mens-jeans-and-trousers.jpg',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Women’s Dress Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <ul>
                                      <li>
                                        <strong>Chest : </strong><br>
                                        Measure under your arms, around the fullest part of the your chest.
                                      </li>
                                      <li>
                                        <strong>Waist : </strong><br>
                                        Measure around your natural waistline, keeping the tape a bit loose.
                                      </li>
                                      <li>
                                        <strong>Hips : </strong><br>
                                        Measure around the fullest part of your body at the top of your leg.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'SIZE'  , 'NUMERIC SIZE'   , 'BUST'   , 'WAIST'   , 'HIP',
                                    'XXXS'  , '000'            , '30'     , '23'      , '33',
                                    'XXS'   , '00'             , '31.5'   , '24'      , '34',
                                    'XS'    , '0'              , '32.5'   , '25'      , '35',
                                    'XS'    , '2'              , '33.5'   , '26'      , '36',
                                    'S'     , '4'              , '34.5'   , '27'      , '37',
                                    'S'     , '6'              , '35.5'   , '28'      , '38',
                                    'M'     , '8'              , '36.5'   , '29'      , '39',
                                    'M'     , '10'             , '37.5'   , '30'      , '40',
                                    'L'     , '12'             , '39'     , '31.5'    , '41.5',
                                    'L'     , '14'             , '40.5'   , '33'      , '43',
                                    'XL'    , '16'             , '42'     , '34.5'    , '44.5',
                                    'XL'    , '18'             , '44'     , '36'      , '46.5',
                                    'XXL'   , '20'             , '46'     , '37.5'    , '48.5',
                                 ) ,
                        'totalrow'  => '14',
                        'totalcol'  => '5', 
                        'btn_show'=> 'popup',
                        'sub_title'=> "Women’s Dress Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Women’s Dress Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'women-dress-size-chart.png',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Men’s Shirts Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <p>To choose the correct size for you, measure your body as follows:</p>
                                    <ul>
                                      <li>
                                        <strong>Chest : </strong><br>
                                        Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'TO FIT CHEST SIZE' , 'INCHES'  , 'CM'     , 'TO FIT NECK SIZE','INCHES','CM',
                                    'XXXS'              , '30-32'   , '76-81'  , 'XXXS'            ,'14'    ,'36',
                                    'XXS'               , '32-34'   , '81-86'  , 'XXS'             ,'14.5'  ,'37.5',
                                    'XS'                , '34-36'   , '86-91'  , 'XS'              ,'15'    ,'38.5',
                                    'S'                 , '36-38'   , '91-96'  , 'S'               ,'15.5'  ,'39.5',
                                    'M'                 , '38-40'   , '96-101' , 'M'               ,'16'    ,'41.5',
                                    'L'                 , '40-42'   , '101-106', 'L'               ,'17'    ,'43.5',
                                    'XL'                , '42-44'   , '106-111', 'XL'              ,'17.5'  ,'45.5',
                                    'XXL'               , '44-46'   , '111-116', 'XXL'             ,'18.5'  ,'47.5',
                                    'XXXL'              , '46-48 '  , '116-121', 'XXXL'            ,'19.5'  ,'49.5',
                                 ) ,
                        'totalrow'  => '10',
                        'totalcol'  => '6', 
                        'btn_show'=> 'popup',
                        'sub_title'=> "Men’s Shirts Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Men’s Shirts Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'mens-shirts.jpg',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Women’s T-shirt / Tops Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <ul>
                                      <li>
                                        <strong>Chest : </strong><br>
                                        Measure under your arms, around the fullest part of the your chest.
                                      </li>
                                      <li>
                                        <strong>Waist : </strong><br>
                                        Measure around your natural waistline, keeping the tape a bit loose.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'UK SIZE'  , 'BUST'   ,'BUST'   ,'WAIST'  ,'WAIST', 'HIPS'   , 'HIPS',
                                    'N/A'      , 'INCHES' ,'CM'     ,'INCHES' ,'CM'   , 'INCHES' , 'CM',
                                    '4'        , '31'     ,'78'     ,'24'     ,'60'   , '33'     , '83.5',
                                    '6'        , '32'     ,'80.5'   ,'25'     ,'62.5' , '34'     , '86',
                                    '8'        , '33'     ,'83'     ,'26'     ,'65'   , '35'     , '88.5',
                                    '10'       , '35'     ,'88'     ,'28'     ,'70'   , '37'     , '93.5',
                                    '12'       , '37'     ,'93'     ,'30'     ,'75'   , '39'     , '98.5',
                                    '14'       , '39'     ,'98'     ,'31'     ,'80'   , '41'     , '103.5',
                                    '16'       , '41'     ,'103'    ,'33'     ,'85'   , '43'     , '108.5',
                                    '18'       , '44'     ,'110.5'  ,'36'     ,'92.5' , '46'     , '116',
                                 ) ,
                        'totalrow'  => '10',
                        'totalcol'  => '7', 
                        'btn_show'=> 'sidepopup',
                        'sub_title'=> "Women’s T-shirt Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Women’s T-shirt Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'women-t-shirt-top.png',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                    "Men’s T-Shirts & Polo Shirts Size Chart" => array(
                        'content' => '<p><strong>How to measure</strong></p>
                                    <p>To choose the correct size for you, measure your body as follows:</p>
                                    <ul>
                                      <li>
                                        <strong>Chest : </strong><br>
                                        Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.
                                      </li>
                                    </ul>',
                        'chart'  => Array(
                                    'TO FIT CHEST SIZE' , 'INCHES'  , 'CM'     ,
                                    'XXXS'              , '30-32'   , '76-81'  ,
                                    'XXS'               , '32-34'   , '81-86'  ,
                                    'XS'                , '34-36'   , '86-91'  ,
                                    'S'                 , '36-38'   , '91-96'  ,
                                    'M'                 , '38-40'   , '96-101' ,
                                    'L'                 , '40-42'   , '101-106',
                                    'XL'                , '42-44'   , '106-111',
                                    'XXL'               , '44-46'   , '111-116',
                                    'XXXL'              , '46-48 '  , '116-121',
                                 ) ,
                        'totalrow'  => '10',
                        'totalcol'  => '3', 
                        'btn_show'=> 'popup',
                        'sub_title'=> "Men’s T-Shirts Chart",
                        'tab_lbl'=> 'Size Chart',
                        'btn_lbl'=> "Men’s T-Shirts Chart",
                        'btn_ft_clr'=> "#ffffff",
                        'btn_bg_clr'=> "#000000",
                        'btn_pos'=> "after_add_cart",
                        'alw_mobile'=> "off",
                        'show_tab'=> "on",
                        'chart_tab_name'=>  "Size Chart",
                        'dis_tab_name'=>  "How to Measure",
                        'btn_brd_rd'=>  "5",
                        'image'=>'mens-tshirts-and-polo-shirts.jpg',
                        'szchartpp_shop_enbl'=> 'disable',
                        'shop_btn_show'=> 'popup',
                        'shop_btn_pos'=> 'before_add_cart',
                        'szchartpp_aply_all'=> 'disable',
                        'tab_pririty'=> '50',
                        'border'=> 'none',
                        'btn_padding'=> '10px 15px'
                    ),
                );

                if(!empty($post_array)) {
                    foreach ($post_array as $key => $value) {

                        $customPost = get_page_by_title($key, OBJECT, 'scpfw_size_chart');

                        if(is_null($customPost)) {

                            $new_post = array(
                                     'post_title'   => $key,
                                     'post_status'  => 'publish',
                                     'post_type'    => 'scpfw_size_chart',
                                     'post_content' => $value['content']
                                  );
                            $post_id = wp_insert_post($new_post);

                            update_post_meta( $post_id, SCPFW_PREFIX.'size_chartdata', $value['chart'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'totalrow', $value['totalrow'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'totalcol', $value['totalcol'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_show', $value['btn_show'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'sub_title', $value['sub_title'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'tab_lbl', $value['tab_lbl'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_lbl', $value['btn_lbl'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_ft_clr', $value['btn_ft_clr'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_bg_clr', $value['btn_bg_clr'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_pos', $value['btn_pos'] );
                           
                            update_post_meta( $post_id, SCPFW_PREFIX.'alw_mobile', $value['alw_mobile'] );
                            update_post_meta( $post_id, SCPFW_PREFIX.'show_tab',$value['show_tab']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'chart_tab_name', $value['chart_tab_name']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'dis_tab_name', $value['dis_tab_name']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_brd_rd', $value['btn_brd_rd']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'szchartpp_shop_enbl', $value['szchartpp_shop_enbl']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'shop_btn_show', $value['shop_btn_show']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'shop_btn_pos', $value['shop_btn_pos']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'szchartpp_aply_all', $value['szchartpp_aply_all']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'tab_pririty', $value['tab_pririty']);
                            update_post_meta( $post_id, SCPFW_PREFIX.'btn_padding', $value['btn_padding']);

                            $IMGFileName = $value['image'];
                            $dirPath = SCPFW_PLUGIN_DIR."/assets/images/";
                            $IMGFilePath = $dirPath.$IMGFileName;
                            $upload = wp_upload_bits($IMGFileName , null, file_get_contents($IMGFilePath, FILE_USE_INCLUDE_PATH));
                            $imageFile = $upload['file'];
                            $wpFileType = wp_check_filetype($imageFile, null);
                            $attachment = array(
                                'post_mime_type' => $wpFileType['type'],  // file type
                                'post_title' => sanitize_file_name($IMGFileName), // sanitize and use image name as file name
                                'post_content' => '',  // could use the image description here as the content
                                'post_status' => 'inherit'
                            );

                            //insert and return attachment id
                            $attachmentId = wp_insert_attachment( $attachment, $imageFile, $post_id );
                            $success = set_post_thumbnail( $post_id, $attachmentId );
                        }
                    }
                }

                $url = admin_url().'edit.php?post_type=scpfw_size_chart&page=scpfw-import-sample-size-charts&import=success';

                wp_redirect($url);
                exit;
            }
        }
    }
}

add_filter( 'post_row_actions','SCPFW_clone_post_link', 10, 2 );
function SCPFW_clone_post_link( $actions, $post ) {
    if ($post->post_type=='scpfw_size_chart' && current_user_can('edit_posts')) {
        $actions['clone'] = '<a href="' . wp_nonce_url('admin.php?action=scpfw_clone_post_as_draft&post=' . $post->ID, basename(__FILE__), 'clone_nonce' ) . '" title="Clone this Size Chart" rel="permalink">Clone</a>';
    }
    return $actions;
}


add_action( 'admin_action_scpfw_clone_post_as_draft','scpfw_clone_post_as_draft');
function scpfw_clone_post_as_draft() {
    global $wpdb;
    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'scpfw_clone_post_as_draft' == sanitize_text_field($_REQUEST['action']) ) ) ) {
        wp_die('No post to duplicate has been supplied!');
    }
 
    if ( !isset( $_GET['clone_nonce'] ) || !wp_verify_nonce( $_GET['clone_nonce'], basename( __FILE__ ) ) )
        return;
 
    $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
    $post = get_post( $post_id );
 
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;
 
    if (isset( $post ) && $post != null) {
 
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title.' - Copy',
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );
 
        $new_post_id = wp_insert_post( $args );
 
        $taxonomies = get_object_taxonomies($post->post_type);
        
        if(!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
        }
 
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            if(!empty($post_meta_infos)) {
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    if( $meta_key == '_wp_old_slug' ) continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }

        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die('Post creation failed,could not find original post: ' . $post_id);
    }
}