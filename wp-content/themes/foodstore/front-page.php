<?php
/**
 * FoodStore - Front Page Template
 *
 * @package FoodStore
 */

get_header();

// Get featured products
$featured_products = wc_get_products([
    'limit'    => 8,
    'status'   => 'publish',
    'orderby'  => 'date',
    'order'    => 'DESC',
    'stock_status' => 'instock',
]);

// Get product categories
$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'exclude'    => get_option('default_product_cat'),
]);
?>

<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Thực phẩm tươi ngon</h1>
                <p class="lead">Giao hàng nhanh chóng - Chất lượng đảm bảo</p>
                <p>Khám phá hàng trăm món ăn ngon từ các nhà hàng uy tín. Đặt hàng ngay hôm nay!</p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-bag"></i> Mua ngay
                </a>
                <a href="#categories" class="btn btn-outline-light btn-lg">
                    Xem danh mục
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <?php
                $hero_image = get_template_directory_uri() . '/assets/images/hero-food.png';
                ?>
                <img src="<?php echo esc_url($hero_image); ?>" alt="Food" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Danh mục sản phẩm</h2>
        <div class="row g-4">
            <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                <?php foreach ($categories as $category) :
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="text-decoration-none">
                        <div class="card h-100 text-center border-0 shadow-sm category-card">
                            <div class="card-body">
                                <div class="category-icon mb-3">
                                    <img src="<?php echo esc_url($image_url); ?>"
                                         alt="<?php echo esc_attr($category->name); ?>"
                                         class="img-fluid rounded-circle"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <h6 class="card-title text-dark"><?php echo esc_html($category->name); ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Sản phẩm nổi bật</h2>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-success">Xem tất cả</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured_products as $product) :
                $product_id  = $product->get_id();
                $image_url   = wp_get_attachment_url($product->get_image_id()) ?: wc_placeholder_img_src();
                $price       = (float) $product->get_regular_price();
                $sale_price  = $product->get_sale_price() ? (float) $product->get_sale_price() : null;
                $permalink   = get_permalink($product_id);
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card">
                    <div class="position-relative">
                        <img src="<?php echo esc_url($image_url); ?>"
                             class="card-img-top product-image"
                             alt="<?php echo esc_attr($product->get_name()); ?>">
                        <?php if ($sale_price && $price > 0) : ?>
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                            Giảm <?php echo round((1 - $sale_price / $price) * 100); ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="<?php echo esc_url($permalink); ?>" class="text-decoration-none text-dark">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h6>
                        <?php
                        $cats = wp_get_post_terms($product_id, 'product_cat');
                        $cat_name = !empty($cats) ? $cats[0]->name : '';
                        ?>
                        <p class="card-text text-muted small"><?php echo esc_html($cat_name); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($sale_price) : ?>
                                    <span class="text-success fw-bold"><?php echo foodstore_format_currency($sale_price); ?></span>
                                    <br>
                                    <small class="text-decoration-line-through text-muted"><?php echo foodstore_format_currency($price); ?></small>
                                <?php else : ?>
                                    <span class="text-success fw-bold"><?php echo foodstore_format_currency($price); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                               data-quantity="1"
                               class="btn btn-success btn-sm add_to_cart_button ajax_add_to_cart"
                               data-product_id="<?php echo esc_attr($product_id); ?>">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="feature-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                    <i class="bi bi-truck text-success fs-3"></i>
                </div>
                <h6>Giao hàng nhanh</h6>
                <small class="text-muted">Miễn phí giao hàng cho đơn từ 200.000đ</small>
            </div>
            <div class="col-md-3">
                <div class="feature-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                    <i class="bi bi-shield-check text-success fs-3"></i>
                </div>
                <h6>Chất lượng đảm bảo</h6>
                <small class="text-muted">100% thực phẩm tươi sạch</small>
            </div>
            <div class="col-md-3">
                <div class="feature-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                    <i class="bi bi-headset text-success fs-3"></i>
                </div>
                <h6>Hỗ trợ 24/7</h6>
                <small class="text-muted">Luôn sẵn sàng hỗ trợ bạn</small>
            </div>
            <div class="col-md-3">
                <div class="feature-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                    <i class="bi bi-arrow-repeat text-success fs-3"></i>
                </div>
                <h6>Đổi trả dễ dàng</h6>
                <small class="text-muted">Hoàn tiền trong 24 giờ</small>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
