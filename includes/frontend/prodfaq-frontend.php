<?php
namespace ProdFaq\Includes\Frontend;

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Frontend{

    /**
     * Plugin version
     *
     * @var string
     */
    protected string $version;

    public function __construct( string $version ) {
        $this->version = $version;
        $this->init();
    }

    /**
     * Initialize frontend functionalities
     */
    public function init() {
        // Initialize frontend functionalities here
        $position = get_option( 'prodfaq_position', 'after_summary' );
        switch ( $position ) {
            case 'after_summary':
                add_action( 'woocommerce_after_single_product_summary', [ $this, 'prodfaq_display_faqs_on_product_page' ], 15 );
                break;
            case 'inside_tabs':
                add_filter( 'woocommerce_product_tabs', [ $this, 'prodfaq_display_faqs_in_tabs' ] );
                break;
            default:
                add_action('woocommerce_after_single_product_summary', [ $this, 'prodfaq_display_faqs_on_product_page' ], 15);
                break;
        }

        // Enqueue frontend scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'prodfaq_enqueue_frontend_scripts' ] );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function prodfaq_enqueue_frontend_scripts() {
        // frontend enqueue only for single product pages
        if ( ! is_product() ) {
            return;
        }
        // check if design is card and enqueue corresponding styles and scripts
        $design = get_option( 'prodfaq_design', 'accordion' );
        if ( $design === 'card' ) {
            wp_enqueue_style( 'prodfaq-card-style', PRODFAQ_URL . 'assets/frontend/css/prodfaq-card-style.css', [], $this->version );
        } else {
            wp_enqueue_style( 'prodfaq-frontend-style', PRODFAQ_URL . 'assets/frontend/css/prodfaq-styles.css', [], $this->version );
            wp_enqueue_script( 'prodfaq-frontend-script', PRODFAQ_URL . 'assets/frontend/js/prodfaq-scripts.js', [ 'jquery' ], $this->version, true );
        }
        
    }

    /**
     * Display FAQs in product tabs
     */
    public function prodfaq_display_faqs_in_tabs( $tabs ) {
        // check if FAQ is enabled
        $enabled = get_option( 'prodfaq_enabled', 'yes' );
        if ( $enabled !== 'yes' ) {
            return $tabs;
        }

        // check if product is out of stock and hide option is enabled
        $hide_out_of_stock = get_option( 'prodfaq_hide_out_of_stock', 'no' );
        global $product;

        if ( $hide_out_of_stock === 'yes' && ! $product->is_in_stock() ) {
            return $tabs;
        }

        // add FAQ tab
        $tabs['prodfaq'] = array(
            'title'    => __( 'FAQs', 'prodfaq' ),
            'priority' => 20,
            'callback' => array( $this, 'prodfaq_display_faqs_on_product_page' )
        );

        return $tabs;
    }

    /**
     * Display FAQs on the product page
     */
    public function prodfaq_display_faqs_on_product_page() {
        // check if FAQ is enabled
        $enabled = get_option( 'prodfaq_enabled', 'yes' );
        if ( $enabled !== 'yes' ) {
            return;
        }else {

            // check if product is out of stock and hide option is enabled
            $hide_out_of_stock = get_option( 'prodfaq_hide_out_of_stock', 'no' );
            global $product;


            // If product is out of stock and hide option is enabled, do not display FAQs
            if ( $hide_out_of_stock === 'yes' && ! $product->is_in_stock() ) {
                return;
            }else{
                // check design option and load corresponding template
                $design = get_option( 'prodfaq_design', 'accordion' );

                if ( $design === 'card' ) {
                    require_once plugin_dir_path( __FILE__ ) . 'views/card-faq.php';
                } else {
                    require_once plugin_dir_path( __FILE__ ) . 'views/accordian-faq.php';
                } 
            }
        }
        
    }
}