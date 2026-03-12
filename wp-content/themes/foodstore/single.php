<?php
/**
 * FoodStore - Single Post Template
 *
 * @package FoodStore
 */

get_header();
?>

<div class="container py-5">
    <?php while (have_posts()) : the_post(); ?>
        <article>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><?php the_title(); ?></li>
                </ol>
            </nav>

            <h1 class="mb-3"><?php the_title(); ?></h1>
            <p class="text-muted mb-4">
                <i class="bi bi-calendar"></i> <?php echo get_the_date(); ?>
                | <i class="bi bi-person"></i> <?php the_author(); ?>
            </p>

            <?php if (has_post_thumbnail()) : ?>
                <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded mb-4" alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>

            <div class="content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
