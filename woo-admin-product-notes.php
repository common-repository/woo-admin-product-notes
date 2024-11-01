<?php 
/**
 * Plugin Name: Woo Admin Product Notes
 * Plugin URI: https://kankoz.com/woo-admin-product-notes
 * Description: This woocommerce plugin add custom text area on all products within the admin section.  It's sweet & simple.
 * Version: 1.0.0
 * Author: Jamiu Oloyede
 * Author URI: http://kankoz.com
 * Developer: Jamiu Oloyede
 * Developer URI: https://kankoz.com/
 * Text Domain: woo-admin-product-notes
 * Domain Path: /languages
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 3.4.3
 * WC tested up to: 3.4.3
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Create a helper function for easy SDK access.
function wapn_fs() {
    global $wapn_fs;

    if ( ! isset( $wapn_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $wapn_fs = fs_dynamic_init( array(
            'id'                  => '2364',
            'slug'                => 'woo-admin-product-notes',
            'type'                => 'plugin',
            'public_key'          => 'pk_7ca8ac193d5909d861da036927fbe',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'first-path'     => 'plugins.php',
                'account'        => false,
                'contact'        => false,
                'support'        => false,
            ),
        ) );
    }

    return $wapn_fs;
}

// Init Freemius.
wapn_fs();
// Signal that SDK was initiated.
do_action( 'wapn_fs_loaded' );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce is active.
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {    
    // General fields
    // Let create the product note field
    add_action('woocommerce_product_options_general_product_data','kankoz_woo_admpnotes_add_custom_fields' );
    // Let save the data
    add_action('woocommerce_process_product_meta','kankoz_woo_admpnotes_save_custom_fields' );

    function kankoz_woo_admpnotes_add_custom_fields(){
        global $woocommerce, $post;

        echo '<div class="kankoz_woo_admpnotes_options_group">';

        // Create textarea custom fields
        woocommerce_wp_textarea_input( 
            array( 
                'id'          => '_kankoz_woo_admpnotes', 
                'label'       => __( 'Product Note', 'woocommerce' ), 
                'placeholder' => 'Please enter product note', 
                'desc_tip'    => 'true',
                'description' => __( 'This is only visible to admins.', 'woocommerce' ) 
            )
        );
        
        echo '</div>';
    }
        
    function kankoz_woo_admpnotes_save_custom_fields( $post_id ){
        
    // Textarea.
    $woocommerce_textarea = sanitize_text_field($_POST['_kankoz_woo_admpnotes']);
    if(isset($woocommerce_textarea) && !empty( $woocommerce_textarea ) )
        update_post_meta( $post_id, '_kankoz_woo_admpnotes', esc_url( $woocommerce_textarea ));
    }
}