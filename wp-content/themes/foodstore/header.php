<?php
/**
 * FoodStore Header Template
 *
 * @package FoodStore
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo esc_url(home_url('/')); ?>">
                <i class="bi bi-shop"></i> <?php bloginfo('name'); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Sản phẩm</a>
                    </li>
                    <?php
                    $product_categories = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                        'exclude'    => get_option('default_product_cat'),
                    ]);
                    if (!empty($product_categories) && !is_wp_error($product_categories)) :
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Danh mục
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($product_categories as $cat) : ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo esc_url(get_term_link($cat)); ?>">
                                    <?php echo esc_html($cat->name); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Search -->
                <form class="d-flex me-3" action="<?php echo esc_url(home_url('/')); ?>" method="GET">
                    <div class="input-group">
                        <input type="hidden" name="post_type" value="product">
                        <input type="text" class="form-control" name="s" placeholder="Tìm kiếm..."
                               value="<?php echo esc_attr(get_search_query()); ?>">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- User menu -->
                <ul class="navbar-nav">
                    <?php if (function_exists('WC')) : ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?php echo esc_url(wc_get_cart_url()); ?>">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (is_user_logged_in()) : 
                        $current_user = wp_get_current_user();
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <?php echo esc_html($current_user->display_name); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (current_user_can('manage_options') || current_user_can('manage_woocommerce')) : ?>
                                    <li><a class="dropdown-item" href="<?php echo esc_url(admin_url()); ?>">
                                        <i class="bi bi-speedometer2"></i> Quản trị
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">
                                    <i class="bi bi-person"></i> Tài khoản
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                                    <i class="bi bi-bag"></i> Đơn hàng
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">
                                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                </a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Flash messages via WooCommerce notices
    if (function_exists('wc_print_notices')) {
        echo '<div class="container mt-3">';
        wc_print_notices();
        echo '</div>';
    }
    ?>
