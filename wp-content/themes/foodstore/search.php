<?php
/**
 * FoodStore - Search Results Template
 *
 * @package FoodStore
 */

get_header();
?>

<div class="container py-5">
    <h2 class="mb-4">
        Kết quả tìm kiếm: "<?php echo esc_html(get_search_query()); ?>"
    </h2>

    <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('product-card'); ?>"
                             class="card-img-top product-image"
                             alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                <?php the_title(); ?>
                            </a>
                        </h6>
                        <?php if (get_post_type() === 'product') :
                            $product = wc_get_product(get_the_ID());
                            if ($product) :
                        ?>
                            <span class="text-success fw-bold"><?php echo $product->get_price_html(); ?></span>
                        <?php endif; endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <nav class="mt-5">
            <?php
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => 'Trước',
                'next_text' => 'Sau',
            ]);
            ?>
        </nav>
    <?php else : ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Không tìm thấy sản phẩm nào phù hợp.
        </div>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-success">
            Xem tất cả sản phẩm
        </a>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
