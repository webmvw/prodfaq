<?php
/**
 * Plugin Name: ProdFAQ - Product FAQs for WooCommerce
 * Description: Add product-specific FAQ accordion to WooCommerce single product pages.
 * Author: Masud Rana
 * Author URI: https://devsmasudrana.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: prodfaq
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Define plugin constants
 */
if ( ! defined( 'PRODFAQ_FILE' ) ) {
    define( 'PRODFAQ_FILE', __FILE__ );
}

if ( ! defined( 'PRODFAQ_PATH' ) ) {
    define( 'PRODFAQ_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PRODFAQ_URL' ) ) {
    define( 'PRODFAQ_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PRODFAQ_VERSION' ) ) {
    define( 'PRODFAQ_VERSION', '1.0.0' );
}


/**
 * Check if WooCommerce is active and initialize plugin
 */
function prodfaq_init() {

    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function () {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong><?php esc_html_e( 'ProdFAQ', 'prodfaq' ); ?></strong>
                    <?php esc_html_e( 'requires WooCommerce to be installed and active.', 'prodfaq' ); ?>
                </p>
            </div>
            <?php
        });
        return;
    }

    require_once PRODFAQ_PATH . 'includes/plugin.php';

    if ( class_exists( 'ProdFaq\\Includes\\Plugin' ) ) {
        ProdFaq\Includes\Plugin::get_instance();
    }
}
add_action( 'plugins_loaded', 'prodfaq_init' );


