<?php
/**
 * FoodStore - Sidebar Template
 *
 * @package FoodStore
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside class="col-lg-3 mb-4">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>
