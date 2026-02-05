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

    <div class="prodfaq-card-wrapper">

        <?php foreach ( $prodfaqs as $prodfaq ) : ?>
            <?php if ( empty( $prodfaq['question'] ) || empty( $prodfaq['answer'] ) ) continue; ?>
            <div class="prodfaq-card">
                <button class="prodfaq-question">
                    <span class="prodfaq-card-title"><?php echo esc_html( $prodfaq['question'] ); ?></span>
                    <span class="prodfaq-toggle">+</span>
                </button>
                <div class="prodfaq-answer"><p><?php echo wp_kses_post( wpautop( $prodfaq['answer'] ) ); ?></p></div>
            </div>
        <?php endforeach; ?>

    </div>
</div>
