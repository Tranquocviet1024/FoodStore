<?php
/**
 * FoodStore - 404 Template
 *
 * @package FoodStore
 */

get_header();
?>

<div class="container py-5 text-center">
    <div class="py-5">
        <i class="bi bi-emoji-frown display-1 text-muted"></i>
        <h1 class="display-4 mt-3">404</h1>
        <h4 class="mb-3">Trang không tìm thấy</h4>
        <p class="text-muted mb-4">Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-success btn-lg">
            <i class="bi bi-house"></i> Về trang chủ
        </a>
    </div>
</div>

<?php get_footer(); ?>
