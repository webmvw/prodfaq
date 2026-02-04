<?php
namespace ProdFaq\Includes\Admin;

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Admin{

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
     * Initialize admin functionalities
     */
    public function init() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_faq_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_faq_meta_box_data' ] );
    }

    /**
     * Save FAQ meta box data
     */
    public function save_faq_meta_box_data( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['prodfaq_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['prodfaq_nonce'], 'prodfaq_save_meta' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'product' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_product', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        // Sanitize and save the FAQ data
        if ( isset( $_POST['prodfaq'] ) && is_array( $_POST['prodfaq'] ) ) {
            $faqs = [];

            foreach ( $_POST['prodfaq'] as $faq ) {
                if ( empty( $faq['question'] ) && empty( $faq['answer'] ) ) {
                    continue;
                }

                $faqs[] = [
                    'question' => sanitize_text_field( $faq['question'] ),
                    'answer'   => wp_kses_post( $faq['answer'] ),
                ];
            }

            update_post_meta( $post_id, '_prodfaq_items', $faqs );
        } else {
            delete_post_meta( $post_id, '_prodfaq_items' );
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts() {
        // Only load scripts on product edit screen
        $screen = get_current_screen();
        if ( $screen->id == 'product' ) {
            wp_enqueue_style( 'prodfaq-admin-style', PRODFAQ_URL . 'assets/admin/css/admin-style.css', [], $this->version );
            wp_enqueue_script( 'prodfaq-admin-script', PRODFAQ_URL . 'assets/admin/js/admin-script.js', [ 'jquery' ], $this->version, true );
            
            // pass faq count to JS
            $faqs = get_post_meta( get_the_ID(), '_prodfaq_items', true );
            $faqs = is_array( $faqs ) ? $faqs : [];

            wp_localize_script( 'prodfaq-admin-script', 'prodfaqData', [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'faqs' => count( $faqs ),
            ] );
        }

        // settings page styles
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'prodfaq-settings' ) {
            wp_enqueue_style( 'prodfaq-settings-style', PRODFAQ_URL . 'assets/admin/css/settings-style.css', [], $this->version );
        }
    }

    /**
     * Add FAQ meta box to product edit screen
     */
    public function add_faq_meta_box() {
        add_meta_box(
            'prodfaq_faq_meta_box',
            __( 'Product FAQs', 'prodfaq' ),
            [ $this, 'prodfaq_render_faq_meta_box' ],
            'product',
            'normal',
            'high'
        );  
    }

    /**
     * Render FAQ meta box content
     */
    public function prodfaq_render_faq_meta_box($post) {
        wp_nonce_field( 'prodfaq_save_meta', 'prodfaq_nonce' );
        $faqs = get_post_meta( $post->ID, '_prodfaq_items', true );
        if ( ! is_array( $faqs ) ) {
            $faqs = [];
        }
        ?>
        <div id="prodfaq-wrapper">
            <?php if ( ! empty( $faqs ) && is_array( $faqs ) ) : ?>
                <?php foreach ( $faqs as $index => $faq ) : ?>
                    <div class="prodfaq-row">
                        <input type="text" name="prodfaq[<?php echo $index; ?>][question]" placeholder="Question"
                            value="<?php echo esc_attr( $faq['question'] ?? '' ); ?>" />
                        
                        <textarea name="prodfaq[<?php echo $index; ?>][answer]" placeholder="Answer"><?php 
                            echo esc_textarea( $faq['answer'] ?? '' ); 
                        ?></textarea>

                        <button class="button prodfaq-remove"><?php esc_html_e( 'Remove', 'prodfaq' ); ?></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="button button-primary" id="prodfaq-add"><?php esc_html_e( '+ Add FAQ', 'prodfaq' ); ?></button>
        <?php
    }
}

