<?php
/**
 * FoodStore Theme Functions
 *
 * @package FoodStore
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// Theme Setup
// ============================================================
function foodstore_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Custom image sizes
    add_image_size('product-card', 400, 300, true);
    add_image_size('product-large', 800, 600, true);
    add_image_size('category-thumb', 160, 160, true);

    // Register navigation menus
    register_nav_menus([
        'primary'   => __('Menu chính', 'foodstore'),
        'footer'    => __('Menu footer', 'foodstore'),
    ]);

    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // HTML5 support
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Custom logo
    add_theme_support('custom-logo', [
        'height'      => 50,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
}
add_action('after_setup_theme', 'foodstore_setup');

// ============================================================
// Enqueue Scripts & Styles
// ============================================================
function foodstore_scripts() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        [],
        '5.3.2'
    );

    // Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
        [],
        '1.11.1'
    );

    // Theme stylesheet (chứa WP theme header + base reset)
    wp_enqueue_style(
        'foodstore-style',
        get_stylesheet_uri(),
        ['bootstrap', 'bootstrap-icons'],
        wp_get_theme()->get('Version')
    );

    // Components: product cards, hero, buttons, navbar, footer, animations...
    wp_enqueue_style(
        'foodstore-components',
        get_template_directory_uri() . '/assets/css/components.css',
        ['foodstore-style'],
        wp_get_theme()->get('Version')
    );

    // WooCommerce overrides: shop grid, prices, sale badge, cart...
    wp_enqueue_style(
        'foodstore-woocommerce',
        get_template_directory_uri() . '/assets/css/woocommerce.css',
        ['foodstore-style'],
        wp_get_theme()->get('Version')
    );

    // Checkout page: load trên trang có body class woocommerce-checkout
    if (is_checkout() || is_page('checkout') || has_shortcode(get_the_content(), 'woocommerce_checkout')) {
        wp_enqueue_style(
            'foodstore-checkout',
            get_template_directory_uri() . '/assets/css/checkout.css',
            ['foodstore-woocommerce'],
            wp_get_theme()->get('Version')
        );
    }

    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.2',
        true
    );

    // Theme JS
    wp_enqueue_script(
        'foodstore-app',
        get_template_directory_uri() . '/assets/js/app.js',
        ['bootstrap', 'jquery'],
        wp_get_theme()->get('Version'),
        true
    );

    // Pass data to JS
    wp_localize_script('foodstore-app', 'foodstore', [
        'ajax_url'  => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('foodstore_nonce'),
        'cart_url'  => function_exists('wc_get_cart_url') ? wc_get_cart_url() : '',
        'shop_url'  => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '',
    ]);
}
add_action('wp_enqueue_scripts', 'foodstore_scripts');

// ============================================================
// Widgets
// ============================================================
function foodstore_widgets_init() {
    register_sidebar([
        'name'          => __('Sidebar', 'foodstore'),
        'id'            => 'sidebar-1',
        'description'   => __('Thêm widget ở đây.', 'foodstore'),
        'before_widget' => '<div id="%1$s" class="widget card mb-4 %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="card-header bg-success text-white"><h6 class="mb-0">',
        'after_title'   => '</h6></div><div class="card-body">',
    ]);

    register_sidebar([
        'name'          => __('Footer 1', 'foodstore'),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6>',
        'after_title'   => '</h6>',
    ]);
}
add_action('widgets_init', 'foodstore_widgets_init');

// ============================================================
// WooCommerce Customizations
// ============================================================

// Change number of products displayed per page
add_filter('loop_shop_per_page', function () {
    return 12;
});

// Change number of columns
add_filter('loop_shop_columns', function () {
    return 4;
});

// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', function ($styles) {
    // Keep only the general styles
    unset($styles['woocommerce-layout']);
    return $styles;
});

// Custom product card wrapper
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

add_action('woocommerce_before_shop_loop_item', function () {
    echo '<div class="card h-100 product-card">';
    echo '<div class="position-relative">';
}, 5);

add_action('woocommerce_before_shop_loop_item_title', function () {
    echo '</div>'; // close position-relative
    echo '<div class="card-body">';
}, 15);

add_action('woocommerce_after_shop_loop_item', function () {
    echo '</div>'; // close card-body
    echo '</div>'; // close card
}, 20);

// Custom sale badge
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('woocommerce_before_shop_loop_item_title', function () {
    global $product;
    if ($product->is_on_sale()) {
        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();
        if ($regular > 0) {
            $percent = round((1 - $sale / $regular) * 100);
            echo '<span class="badge bg-danger position-absolute top-0 end-0 m-2">Giảm ' . $percent . '%</span>';
        }
    }
}, 9);

// Product title as link
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', function () {
    echo '<h6 class="card-title"><a href="' . esc_url(get_the_permalink()) . '" class="text-decoration-none text-dark">' . esc_html(get_the_title()) . '</a></h6>';
}, 10);

// Customize add to cart button
add_filter('woocommerce_loop_add_to_cart_args', function ($args) {
    $args['class'] = str_replace('button', 'button btn btn-success btn-sm', $args['class']);
    return $args;
});

// Format Vietnamese currency
add_filter('woocommerce_currency_symbol', function ($symbol, $currency) {
    if ($currency === 'VND') {
        return 'đ';
    }
    return $symbol;
}, 10, 2);

// ============================================================
// AJAX Add to Cart with Fragment Update
// ============================================================
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = WC()->cart->get_cart_contents_count();
    $fragments['.cart-count'] = '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">' . $count . '</span>';
    return $fragments;
});

// ============================================================
// Helper: Format VND
// ============================================================
function foodstore_format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' đ';
}

// ============================================================
// Custom Breadcrumb Arguments
// ============================================================
add_filter('woocommerce_breadcrumb_defaults', function () {
    return [
        'delimiter'   => ' <span class="mx-1">/</span> ',
        'wrap_before' => '<nav aria-label="breadcrumb" class="mb-4"><ol class="breadcrumb">',
        'wrap_after'  => '</ol></nav>',
        'before'      => '<li class="breadcrumb-item">',
        'after'       => '</li>',
        'home'        => __('Trang chủ', 'foodstore'),
    ];
});

// ============================================================
// Remove WordPress default styles/scripts we don't need
// ============================================================
function foodstore_dequeue_unnecessary() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'foodstore_dequeue_unnecessary', 100);
