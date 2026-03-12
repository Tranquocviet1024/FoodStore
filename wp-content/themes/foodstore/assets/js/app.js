/**
 * FoodStore WordPress - JavaScript
 */
(function ($) {
    'use strict';

    // Toast notification
    function showToast(message, type) {
        type = type || 'success';
        var container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        var toast = document.createElement('div');
        toast.className = 'toast show align-items-center text-white bg-' + type + ' border-0';
        toast.innerHTML =
            '<div class="d-flex">' +
            '<div class="toast-body">' + message + '</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>';

        container.appendChild(toast);

        setTimeout(function () {
            toast.remove();
        }, 3000);
    }

    // WooCommerce AJAX add to cart - show toast
    $(document.body).on('added_to_cart', function () {
        showToast('Đã thêm vào giỏ hàng!', 'success');
    });

    // Update cart count via WooCommerce fragments
    $(document.body).on('added_to_cart removed_from_cart', function (e, fragments) {
        if (fragments && fragments['.cart-count']) {
            $('.cart-count').replaceWith(fragments['.cart-count']);
        }
    });

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    }

    // Initialize
    $(document).ready(function () {
        // Quantity buttons on single product
        $(document).on('click', '.quantity .minus, .quantity .plus', function () {
            var $qty = $(this).closest('.quantity').find('.qty');
            var currentVal = parseFloat($qty.val());
            var max = parseFloat($qty.attr('max'));
            var min = parseFloat($qty.attr('min'));
            var step = parseFloat($qty.attr('step')) || 1;

            if ($(this).hasClass('plus')) {
                if (max && currentVal >= max) {
                    $qty.val(max);
                } else {
                    $qty.val(currentVal + step);
                }
            } else {
                if (min && currentVal <= min) {
                    $qty.val(min);
                } else if (currentVal > 1) {
                    $qty.val(currentVal - step);
                }
            }

            $qty.trigger('change');
        });
    });

    // Make toast function globally available
    window.foodstoreShowToast = showToast;

})(jQuery);
