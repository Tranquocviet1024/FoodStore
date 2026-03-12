<?php
/**
 * Checkout Form - FoodStore Custom Template
 *
 * @package FoodStore
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<!-- Progress Steps -->
<div class="checkout-progress mb-5">
    <div class="checkout-steps d-flex justify-content-center align-items-center">
        <div class="step completed">
            <div class="step-icon"><i class="bi bi-cart-check"></i></div>
            <div class="step-label">Giỏ hàng</div>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-icon"><i class="bi bi-credit-card"></i></div>
            <div class="step-label">Thanh toán</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-icon"><i class="bi bi-check-circle"></i></div>
            <div class="step-label">Hoàn thành</div>
        </div>
    </div>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="row g-4 checkout-layout">

        <!-- LEFT: Billing / Shipping Info -->
        <div class="col-lg-7 col-12">

            <!-- Billing Details -->
            <?php if ( $checkout->get_checkout_fields() ) : ?>
                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="checkout-card mb-4">
                    <div class="checkout-card-header">
                        <i class="bi bi-person-fill me-2"></i>
                        <?php esc_html_e( 'Thông tin giao hàng', 'woocommerce' ); ?>
                    </div>
                    <div class="checkout-card-body">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
            <?php endif; ?>

            <!-- Additional Info / Order Notes -->
            <div class="checkout-card mb-4">
                <div class="checkout-card-header">
                    <i class="bi bi-chat-left-text me-2"></i>
                    Ghi chú đơn hàng
                </div>
                <div class="checkout-card-body">
                    <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                        <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- RIGHT: Order Summary + Payment -->
        <div class="col-lg-5 col-12">
            <div class="checkout-sticky">

                <!-- Order Summary -->
                <div class="checkout-card mb-4">
                    <div class="checkout-card-header">
                        <i class="bi bi-bag-check me-2"></i>
                        Đơn hàng của bạn
                    </div>
                    <div class="checkout-card-body p-0">
                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                </div>

                <!-- Payment -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <i class="bi bi-shield-lock me-2"></i>
                        Phương thức thanh toán
                    </div>
                    <div class="checkout-card-body">
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                        <div id="payment" class="woocommerce-checkout-payment">
                            <?php if ( WC()->cart->needs_payment() ) : ?>
                                <ul class="wc_payment_methods payment_methods methods">
                                    <?php
                                    if ( ! empty( $checkout->get_checkout_fields() ) ) {
                                        foreach ( WC()->payment_gateways()->get_available_payment_gateways() as $gateway ) {
                                            wc_get_template(
                                                'checkout/payment-method.php',
                                                array( 'gateway' => $gateway )
                                            );
                                        }
                                    }
                                    ?>
                                </ul>
                            <?php endif; ?>
                            <div class="form-row place-order">
                                <?php woocommerce_checkout_privacy_policy_text(); ?>
                                <?php woocommerce_checkout_terms_and_conditions(); ?>
                                <?php do_action( 'woocommerce_checkout_before_submit' ); ?>
                                <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button checkout-submit-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '"><i class="bi bi-lock-fill me-2"></i>' . esc_html( $order_button_text ) . '</button>' ); ?>
                                <?php do_action( 'woocommerce_checkout_after_submit' ); ?>
                            </div>
                        </div>
                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>

                <!-- Security badge -->
                <div class="checkout-security-badge mt-3">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    <small class="text-muted">Thanh toán bảo mật SSL 256-bit</small>
                    <span class="mx-2 text-muted">|</span>
                    <i class="bi bi-arrow-counterclockwise text-success me-1"></i>
                    <small class="text-muted">Đổi trả trong 7 ngày</small>
                </div>

            </div>
        </div>

    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
