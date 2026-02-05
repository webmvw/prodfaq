
<?php 
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

    <div class="prodfaq-card-wrapper">

        <?php foreach ( $faqs as $faq ) : ?>
            <?php if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) continue; ?>
            <div class="prodfaq-card">
                <button class="prodfaq-question">
                    <span class="prodfaq-card-title"><?php echo esc_html( $faq['question'] ); ?></span>
                    <span class="prodfaq-toggle">+</span>
                </button>
                <div class="prodfaq-answer"><p><?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?></p></div>
            </div>
        <?php endforeach; ?>

    </div>
</div>
