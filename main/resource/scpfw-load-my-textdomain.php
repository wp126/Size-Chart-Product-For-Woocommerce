<?php 

add_action( 'plugins_loaded', 'SCPFW_load_textdomain_pro' );
    function SCPFW_load_textdomain_pro() {
        load_plugin_textdomain( 'size-chart-product-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }

add_filter( 'load_textdomain_mofile', 'SCPFW_load_my_own_textdomain_pro', 10, 2 );
    function SCPFW_load_my_own_textdomain_pro( $mofile, $domain ) {
        if ( 'size-chart-woocommerce-pro' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
            $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
            $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
        }
        return $mofile;
    }
