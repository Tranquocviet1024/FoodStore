<?php
/**
 * FoodStore - Single Product Template
 *
 * @package FoodStore
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php while (have_posts()) : the_post();
    global $product;

    $price       = (float) $product->get_regular_price();
    $sale_price  = $product->get_sale_price() ? (float) $product->get_sale_price() : null;
    $image_url   = wp_get_attachment_url($product->get_image_id()) ?: wc_placeholder_img_src();
    $stock       = $product->get_stock_quantity();
    $in_stock    = $product->is_in_stock();
    $cats        = wp_get_post_terms($product->get_id(), 'product_cat');
    $category    = !empty($cats) ? $cats[0] : null;
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Sản phẩm</a></li>
            <?php if ($category) : ?>
            <li class="breadcrumb-item">
                <a href="<?php echo esc_url(get_term_link($category)); ?>">
                    <?php echo esc_html($category->name); ?>
                </a>
            </li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?php the_title(); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <?php
                $gallery_ids = $product->get_gallery_image_ids();
                if (!empty($gallery_ids)) :
                ?>
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo esc_url($image_url); ?>" class="d-block w-100"
                                 alt="<?php the_title_attribute(); ?>"
                                 style="max-height: 400px; object-fit: cover;">
                        </div>
                        <?php foreach ($gallery_ids as $gid) : ?>
                        <div class="carousel-item">
                            <img src="<?php echo esc_url(wp_get_attachment_url($gid)); ?>" class="d-block w-100"
                                 alt="<?php the_title_attribute(); ?>"
                                 style="max-height: 400px; object-fit: cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <?php else : ?>
                <img src="<?php echo esc_url($image_url); ?>" class="card-img-top"
                     alt="<?php the_title_attribute(); ?>"
                     style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <h2><?php the_title(); ?></h2>
            <?php if ($category) : ?>
            <p class="text-muted">
                Danh mục: <a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
            </p>
            <?php endif; ?>

            <div class="mb-3">
                <?php if ($sale_price) : ?>
                    <span class="fs-3 text-success fw-bold"><?php echo foodstore_format_currency($sale_price); ?></span>
                    <span class="fs-5 text-decoration-line-through text-muted ms-2"><?php echo foodstore_format_currency($price); ?></span>
                    <span class="badge bg-danger ms-2">
                        Giảm <?php echo round((1 - $sale_price / $price) * 100); ?>%
                    </span>
                <?php else : ?>
                    <span class="fs-3 text-success fw-bold"><?php echo foodstore_format_currency($price); ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <span class="<?php echo $in_stock ? 'text-success' : 'text-danger'; ?>">
                    <i class="bi bi-<?php echo $in_stock ? 'check-circle' : 'x-circle'; ?>"></i>
                    <?php if ($in_stock) : ?>
                        Còn hàng<?php echo $stock ? ' (' . $stock . ')' : ''; ?>
                    <?php else : ?>
                        Hết hàng
                    <?php endif; ?>
                </span>
            </div>

            <hr>

            <div class="mb-4">
                <h5>Mô tả</h5>
                <?php if ($product->get_short_description()) : ?>
                    <p><?php echo wp_kses_post($product->get_short_description()); ?></p>
                <?php else : ?>
                    <p><?php echo wp_kses_post($product->get_description()) ?: 'Chưa có mô tả'; ?></p>
                <?php endif; ?>
            </div>

            <hr>

            <?php if ($in_stock) : ?>
                <?php woocommerce_template_single_add_to_cart(); ?>
            <?php else : ?>
            <button class="btn btn-secondary btn-lg w-100" disabled>
                <i class="bi bi-x-circle"></i> Hết hàng
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Tabs (Description, Reviews) -->
    <div class="mt-5">
        <?php woocommerce_output_product_data_tabs(); ?>
    </div>

    <!-- Related Products -->
    <?php
    $related_ids = wc_get_related_products($product->get_id(), 4);
    if (!empty($related_ids)) :
        $related_products = array_map('wc_get_product', $related_ids);
    ?>
    <hr class="my-5">
    <h4 class="mb-4">Sản phẩm liên quan</h4>
    <div class="row g-4">
        <?php foreach ($related_products as $related) :
            if (!$related) continue;
            $r_image = wp_get_attachment_url($related->get_image_id()) ?: wc_placeholder_img_src();
            $r_price = $related->get_sale_price() ?: $related->get_regular_price();
        ?>
        <div class="col-6 col-md-3">
            <div class="card h-100 product-card">
                <img src="<?php echo esc_url($r_image); ?>"
                     class="card-img-top product-image"
                     alt="<?php echo esc_attr($related->get_name()); ?>">
                <div class="card-body">
                    <h6 class="card-title text-truncate">
                        <a href="<?php echo esc_url(get_permalink($related->get_id())); ?>" class="text-decoration-none text-dark">
                            <?php echo esc_html($related->get_name()); ?>
                        </a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <?php echo foodstore_format_currency((float) $r_price); ?>
                        </span>
                        <a href="<?php echo esc_url($related->add_to_cart_url()); ?>"
                           data-quantity="1"
                           class="btn btn-success btn-sm add_to_cart_button ajax_add_to_cart"
                           data-product_id="<?php echo esc_attr($related->get_id()); ?>">
                            <i class="bi bi-cart-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
