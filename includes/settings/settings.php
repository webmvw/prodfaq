<?php
namespace ProdFaq\Includes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Settings {

    /**
     * Plugin version
     *
     * @var string
     */
    protected string $version;

    public function __construct( string $version ) {
        $this->version = $version;
    }

    /**
     * Render settings page
     */
    public function render() {
        ?>
        <div class="wrap prodfaq-settings-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'ProdFAQ Settings', 'prodfaq' ); ?></h1>
            <p class="description"><?php esc_html_e( 'Configure how FAQs appear on your product pages.', 'prodfaq' ); ?></p>

            <!-- success message when settings are saved -->
            <?php if ( isset( $_GET['settings-updated'] ) && sanitize_text_field( wp_unslash( $_GET['settings-updated'] ) ) === 'true' ): ?>
                <div id="message" class="updated notice is-dismissible">
                    <p><?php esc_html_e( 'Settings saved successfully.', 'prodfaq' ); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="options.php">
                <?php settings_fields( 'prodfaq_settings' ); ?>

                <div class="prodfaq-settings-flex">
                    <!-- left: Settings Card -->
                    <div class="prodfaq-card">

                        <!-- Enable FAQ -->
                        <div class="prodfaq-field">
                            <label for="prodfaq_enabled">
                                <strong><?php esc_html_e( 'Enable FAQ', 'prodfaq' ); ?></strong>
                                <span class="field-desc"><?php esc_html_e( 'Turn FAQ section on or off for products.', 'prodfaq' ); ?></span>
                            </label>
                            <?php $enabled = get_option( 'prodfaq_enabled', 'yes' ); ?>
                            <select id="prodfaq_enabled" name="prodfaq_enabled">
                                <option value="yes" <?php selected( $enabled, 'yes' ); ?>><?php esc_html_e( 'Yes', 'prodfaq' ); ?></option>
                                <option value="no" <?php selected( $enabled, 'no' ); ?>><?php esc_html_e( 'No', 'prodfaq' ); ?></option>
                            </select>
                        </div>

                        <!-- FAQ Position -->
                        <div class="prodfaq-field">
                            <label for="prodfaq_position">
                                <strong><?php esc_html_e( 'FAQ Position', 'prodfaq' ); ?></strong>
                                <span class="field-desc"><?php esc_html_e( 'Choose where the FAQ will be displayed.', 'prodfaq' ); ?></span>
                            </label>
                            <?php $position = get_option( 'prodfaq_position', 'after_summary' ); ?>
                            <select id="prodfaq_position" name="prodfaq_position">
                                <option value="after_summary" <?php selected( $position, 'after_summary' ); ?>>
                                    <?php esc_html_e( 'After Product Summary', 'prodfaq' ); ?>
                                </option>
                                <option value="inside_tabs" <?php selected( $position, 'inside_tabs' ); ?>>
                                    <?php esc_html_e( 'Inside Product Tabs', 'prodfaq' ); ?>
                                </option>
                            </select>
                        </div>

                        <!-- FAQ Design -->
                        <div class="prodfaq-field">
                            <label for="prodfaq_design">
                                <strong><?php esc_html_e( 'FAQ Design', 'prodfaq' ); ?></strong>
                                <span class="field-desc"><?php esc_html_e( 'Select a visual style for FAQs.', 'prodfaq' ); ?></span>
                            </label>
                            <?php $design = get_option( 'prodfaq_design', 'accordion' ); ?>
                            <select id="prodfaq_design" name="prodfaq_design">
                                <option value="accordion" <?php selected( $design, 'accordion' ); ?>>
                                    <?php esc_html_e( 'Accordion', 'prodfaq' ); ?>
                                </option>
                                <option value="card" <?php selected( $design, 'card' ); ?>>
                                    <?php esc_html_e( 'Card', 'prodfaq' ); ?>
                                </option>
                                <option value="list" <?php selected( $design, 'list' ); ?>>
                                    <?php esc_html_e( 'List', 'prodfaq' ); ?>
                                </option>
                            </select>
                        </div>

                        <!-- Hide FAQ when Out of Stock -->
                        <div class="prodfaq-field">
                            <label for="prodfaq_hide_out_of_stock">
                                <strong><?php esc_html_e( 'Hide FAQs for Out of Stock Products', 'prodfaq' ); ?></strong>
                                <span class="field-desc">
                                    <?php esc_html_e( 'Automatically hide FAQs when the product is out of stock.', 'prodfaq' ); ?>
                                </span>
                            </label>

                            <?php $hide_oos = get_option( 'prodfaq_hide_out_of_stock', 'no' ); ?>

                            <select id="prodfaq_hide_out_of_stock" name="prodfaq_hide_out_of_stock">
                                <option value="yes" <?php selected( $hide_oos, 'yes' ); ?>><?php esc_html_e( 'Yes', 'prodfaq' ); ?></option>
                                <option value="no" <?php selected( $hide_oos, 'no' ); ?>><?php esc_html_e( 'No', 'prodfaq' ); ?></option>
                            </select>
                        </div>

                    </div>

                    <!-- right: Author Box -->
                     <div class="prodfaq-author-box">
                        <!-- Profile Image -->
                        <div class="author-profile">
                            <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../assets/images/masud_rana.webp' ); ?>" alt="Author" />
                        </div>

                        <!-- Info -->
                        <h2><?php esc_html_e( 'Masud Rana', 'prodfaq' ); ?></h2>
                        <p><?php esc_html_e( 'WordPress Developer', 'prodfaq' ); ?></p>
                        <p><strong><?php esc_html_e( 'Plugin:', 'prodfaq' ); ?></strong> <?php esc_html_e( 'ProdFAQ', 'prodfaq' ); ?></p>
                        <p><strong><?php esc_html_e( 'Version:', 'prodfaq' ); ?></strong> <?php echo esc_html( $this->version ); ?></p>
                        
                        <!-- social links -->
                        <div class="author-social">
                            <a href="#" target="_blank" title="GitHub" class="github-link">
                                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../assets/images/github.svg' ); ?>" alt="Github" />
                            </a>

                            <a href="#" target="_blank" aria-label="LinkedIn">
                                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../assets/images/linkedin.svg' ); ?>" alt="Linkedin" />
                            </a>
                            
                            <a href="#" target="_blank" title="Facebook" class="facebook-link">
                                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../assets/images/facebook.svg' ); ?>" alt="Facebook" />
                            </a>

                            <a href="#" target="_blank" aria-label="WordPress">
                                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../assets/images/wordpress.svg' ); ?>" alt="WordPress" />
                            </a>
                        </div>

                        <!-- Buttons -->
                        <a href="#" target="_blank" class="button button-primary"><?php esc_html_e( 'Support', 'prodfaq' ); ?></a>
                        <a href="#" target="_blank" class="button"><?php esc_html_e( 'Visit Website', 'prodfaq' ); ?></a>
                    </div>

                </div>
                <?php submit_button( 'Save Settings' ); ?>
            </form>
        </div>
        <?php
    }

}