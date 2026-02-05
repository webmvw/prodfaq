<?php
namespace ProdFaq\Includes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class Plugin {

    private static $instance = null;

    public function version(): string {
        return (string) PRODFAQ_VERSION;
    }

    private function __construct() {
        $this->init_hooks();
    }

    // prevent cloning
    private function __clone() {}

    // prevent unserializing
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialize singleton' );
    }

    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();

            // for future checking that Prodfaq is loaded
            do_action( 'prodfaq_loaded' );
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        if ( is_admin() ) {
            $this->define_admin_part();
        }
        $this->define_frontend_part();

        add_action( 'admin_menu', [ $this, 'prodfaq_register_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'prodfaq_register_settings' ] );
    }

    /**
     * Register plugin settings
     */
    public function prodfaq_register_settings() {
        register_setting( 'prodfaq_settings', 'prodfaq_enabled', [ $this, 'prodfaq_sanitize_enabled' ] );
        register_setting( 'prodfaq_settings', 'prodfaq_position', [ $this, 'prodfaq_sanitize_position' ] );
        register_setting( 'prodfaq_settings', 'prodfaq_design', [ $this, 'prodfaq_sanitize_design' ] );
        register_setting( 'prodfaq_settings', 'prodfaq_hide_out_of_stock', [ $this, 'prodfaq_sanitize_hide_out_of_stock' ] );
    }

    /**
     * Sanitize for enabled setting
     */
    public function prodfaq_sanitize_enabled( $input ) {
        return in_array( $input, array( 'yes', 'no' ), true ) ? $input : 'yes';
    } 

    /**
     * Sanitize for position setting
     */
    public function prodfaq_sanitize_position( $input ) {
        $allowed = array(
            'after_summary',
            'inside_tabs',
        );

        return in_array( $input, $allowed, true ) ? $input : 'after_summary';
    }

    /**
     * Sanitize for hide out of stock setting
     */
    public function prodfaq_sanitize_hide_out_of_stock( $input ) {
        return in_array( $input, array( 'yes', 'no' ), true ) ? $input : 'no';
    }

    /**
     * Sanitize for design setting
     */
    public function prodfaq_sanitize_design( $input ) {
        $allowed = array(
            'accordion',
            'card',
            'list',
        );

        return in_array( $input, $allowed, true ) ? $input : 'accordion';
    }

    /**
     * Register admin menu
     */
    public function prodfaq_register_admin_menu() {
        add_submenu_page(
            'options-general.php',
            __( 'ProdFAQ Settings', 'prodfaq' ),
            __( 'ProdFAQ', 'prodfaq' ),
            'manage_options',
            'prodfaq-settings',
            [ $this, 'prodfaq_settings_page' ]
        );
    }

    /**
     * render settings page
     */
    public function prodfaq_settings_page() {
        $settings_file = __DIR__ . '/settings/settings.php';
        if ( file_exists( $settings_file ) ) {
            include $settings_file;

            if ( class_exists( '\ProdFaq\Includes\Settings\Settings' ) ) {
                $settings = new \ProdFaq\Includes\Settings\Settings( $this->version() );
                $settings->render();
            }
        }
    }

    /**
     * Define admin part of the plugin
     */
    private function define_admin_part() {
        $admin_file = __DIR__ . '/admin/prodfaq-admin.php';

        if ( file_exists( $admin_file ) ) {
            require_once $admin_file;

            if ( class_exists( '\ProdFaq\Includes\Admin\Admin' ) ) {
                new \ProdFaq\Includes\Admin\Admin( $this->version() );
            }
        }
    }

    /**
     * Define frontend part of the plugin
     */
    private function define_frontend_part() {
        $frontend_file = __DIR__ . '/frontend/prodfaq-frontend.php';

        if ( file_exists( $frontend_file ) ) {
            require_once $frontend_file;

            if ( class_exists( '\ProdFaq\Includes\Frontend\Frontend' ) ) {
                new \ProdFaq\Includes\Frontend\Frontend( $this->version() );
            }
        }
    }
}