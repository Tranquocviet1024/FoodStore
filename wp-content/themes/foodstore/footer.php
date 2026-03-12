<?php
/**
 * FoodStore Footer Template
 *
 * @package FoodStore
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-shop"></i> <?php bloginfo('name'); ?></h5>
                    <p class="text-white-50">
                        <?php echo esc_html(get_bloginfo('description')); ?>
                    </p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Liên kết</h6>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'list-unstyled',
                        'fallback_cb'    => function () {
                            echo '<ul class="list-unstyled">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '" class="text-white-50">Trang chủ</a></li>';
                            if (function_exists('wc_get_page_permalink')) {
                                echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="text-white-50">Sản phẩm</a></li>';
                            }
                            echo '</ul>';
                        },
                        'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                    ]);
                    ?>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Liên hệ</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><i class="bi bi-geo-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
                        <li><i class="bi bi-telephone"></i> 0123 456 789</li>
                        <li><i class="bi bi-envelope"></i> info@foodstore.com</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Theo dõi chúng tôi</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-white-50">
                <small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
