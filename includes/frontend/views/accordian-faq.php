<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

$prodfaqs = get_post_meta( $product->get_id(), '_prodfaq_items', true );

if ( ! is_array( $prodfaqs ) || empty( $prodfaqs ) ) {
    return;
}

?>

<div class="prodfaq-frontend">
    <h3 class="prodfaq-title">
        <?php esc_html_e( 'Frequently Asked Questions', 'prodfaq' ); ?>
    </h3>

    <div class="prodfaq-list">
        <?php foreach ( $prodfaqs as $prodfaq_index => $prodfaq ) : ?>
            <?php if ( empty( $prodfaq['question'] ) || empty( $prodfaq['answer'] ) ) continue; ?>
            
            <div class="prodfaq-item <?php echo $prodfaq_index === 0 ? 'active' : ''; ?>">
                <button class="prodfaq-question" aria-expanded="<?php echo $prodfaq_index === 0 ? 'true' : 'false'; ?>">
                    <span><?php echo esc_html( $prodfaq['question'] ); ?></span>
                    <span class="prodfaq-icon">+</span>
                </button>

                <div class="prodfaq-answer">
                    <?php echo wp_kses_post( wpautop( $prodfaq['answer'] ) ); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>