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

    private function init_hooks() {
        if ( is_admin() ) {
            $this->define_admin_part();
        }
        $this->define_frontend_part();
    }

    private function define_admin_part() {
        $admin_file = __DIR__ . '/admin/prodfaq-admin.php';

        if ( file_exists( $admin_file ) ) {
            require_once $admin_file;

            if ( class_exists( '\ProdFaq\Includes\Admin\Admin' ) ) {
                new \ProdFaq\Includes\Admin\Admin( $this->version() );
            }
        }
    }

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