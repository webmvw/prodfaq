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
        add_action('woocommerce_after_single_product_summary', [ $this, 'prodfaq_display_faqs_on_product_page' ], 15);

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
        wp_enqueue_style( 'prodfaq-frontend-style', PRODFAQ_URL . 'assets/frontend/css/prodfaq-styles.css', [], $this->version );
        wp_enqueue_script( 'prodfaq-frontend-script', PRODFAQ_URL . 'assets/frontend/js/prodfaq-scripts.js', [ 'jquery' ], $this->version, true );
    }

    /**
     * Display FAQs on the product page
     */
    public function prodfaq_display_faqs_on_product_page() {
        global $product;

        $faqs = get_post_meta( $product->get_id(), '_prodfaq_items', true );

        if ( ! is_array( $faqs ) || empty( $faqs ) ) {
            return;
        }

        ?>

        <div class="prodfaq-frontend">
            <h3 class="prodfaq-title">
                <?php esc_html_e( 'Frequently Asked Questions', 'prodfaq' ); ?>
            </h3>

            <div class="prodfaq-list">
                <?php foreach ( $faqs as $index => $faq ) : ?>
                    <?php if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) continue; ?>
                    
                    <div class="prodfaq-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <button class="prodfaq-question" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                            <span><?php echo esc_html( $faq['question'] ); ?></span>
                            <span class="prodfaq-icon">+</span>
                        </button>

                        <div class="prodfaq-answer">
                            <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
    }
}