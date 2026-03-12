<?php
/**
 * FoodStore - Default Index Template
 *
 * @package FoodStore
 */

get_header();
?>

<div class="container py-5">
    <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                <?php the_title(); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted"><?php echo get_the_date(); ?></small>
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
                'class'     => 'pagination justify-content-center',
            ]);
            ?>
        </nav>
    <?php else : ?>
        <div class="text-center py-5">
            <h4>Không tìm thấy bài viết nào.</h4>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
