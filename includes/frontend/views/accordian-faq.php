<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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