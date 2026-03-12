<?php
/**
 * FoodStore - WooCommerce Archive (Shop / Category)
 *
 * @package FoodStore
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Get categories for sidebar
$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'exclude'    => get_option('default_product_cat'),
]);

$current_cat = get_queried_object();
$is_category = is_product_category();
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-list"></i> Danh mục
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
                       class="list-group-item list-group-item-action <?php echo !$is_category ? 'active' : ''; ?>">
                        Tất cả sản phẩm
                    </a>
                    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                        <?php foreach ($categories as $cat) : ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                           class="list-group-item list-group-item-action <?php echo ($is_category && $current_cat->term_id === $cat->term_id) ? 'active' : ''; ?>">
                            <?php echo esc_html($cat->name); ?>
                            <span class="badge bg-secondary float-end"><?php echo $cat->count; ?></span>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-9">
            <!-- Breadcrumb -->
            <?php woocommerce_breadcrumb(); ?>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">
                        <?php if (is_search()) : ?>
                            Kết quả tìm kiếm: "<?php echo esc_html(get_search_query()); ?>"
                        <?php elseif ($is_category) : ?>
                            <?php echo esc_html($current_cat->name); ?>
                        <?php else : ?>
                            Tất cả sản phẩm
                        <?php endif; ?>
                    </h4>
                    <small class="text-muted">
                        <?php woocommerce_result_count(); ?>
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (woocommerce_product_loop()) : ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php while (have_posts()) : the_post();
                    wc_get_template_part('content', 'product');
                endwhile; ?>

                <?php woocommerce_product_loop_end(); ?>

                <!-- Pagination -->
                <nav class="mt-5">
                    <?php woocommerce_pagination(); ?>
                </nav>

            <?php else : ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Không tìm thấy sản phẩm nào.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
