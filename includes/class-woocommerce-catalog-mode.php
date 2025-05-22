<?php
if (!defined('ABSPATH')) {
    exit;
}

class WooCommerce_Catalog_Mode {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('init', array($this, 'init_catalog_mode'));
    }

    public function register_settings() {
        register_setting('whatsapp_chat_settings', 'wq_catalog_mode_enabled', array(
            'type' => 'string',
            'default' => 'no',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        register_setting('whatsapp_chat_settings', 'wq_request_quote_enabled', array(
            'type' => 'string',
            'default' => 'no',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        add_settings_section(
            'wq_catalog_mode_section',
            'Catalog Mode Settings',
            array($this, 'render_catalog_mode_section'),
            'whatsapp_chat_settings'
        );

        add_settings_field(
            'wq_catalog_mode_enabled',
            'Enable Catalog Mode',
            array($this, 'render_catalog_mode_field'),
            'whatsapp_chat_settings',
            'wq_catalog_mode_section'
        );

        add_settings_field(
            'wq_request_quote_enabled',
            'Enable Request Quote Button',
            array($this, 'render_request_quote_field'),
            'whatsapp_chat_settings',
            'wq_catalog_mode_section'
        );
    }

    public function init_catalog_mode() {
        $catalog_mode_enabled = get_option('wq_catalog_mode_enabled', 'no');
        $request_quote_enabled = get_option('wq_request_quote_enabled', 'no');
        
        if ($catalog_mode_enabled === 'yes') {
            // Use WooCommerce core filters to disable purchasing
            add_filter('woocommerce_is_purchasable', '__return_false', 999);
            add_filter('woocommerce_get_price_html', '__return_empty_string', 999);
            add_filter('woocommerce_variation_price_html', '__return_empty_string', 999);
            add_filter('woocommerce_variation_is_purchasable', '__return_false', 999);
            add_filter('woocommerce_product_is_visible', '__return_true', 999);
            
            // Remove prices and add to cart using template hooks
            add_action('init', function() {
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            }, 999);
            
            // Disable cart and checkout pages
            add_action('wp', array($this, 'disable_cart_checkout'));
            add_filter('woocommerce_add_to_cart_validation', '__return_false');
            add_filter('woocommerce_update_cart_validation', '__return_false');
            
            // Hide stock status and other product meta
            add_filter('woocommerce_get_stock_html', '__return_empty_string');
            add_filter('woocommerce_product_tabs', array($this, 'remove_product_tabs'), 98);
        }

        if ($request_quote_enabled === 'yes') {
            // Initialize the Request Quote Button
            require_once WQ_PLUGIN_PATH . 'includes/class-request-quote-button.php';
            Request_Quote_Button::get_instance();
        }
    }

    public function disable_cart_checkout() {
        if (is_cart() || is_checkout()) {
            wp_safe_redirect(home_url());
            exit;
        }
    }

    public function render_catalog_mode_section() {
        echo '<p>Configure catalog mode settings for your WooCommerce store.</p>';
    }

    public function render_catalog_mode_field() {
        $value = get_option('wq_catalog_mode_enabled', 'no');
        echo '<select name="wq_catalog_mode_enabled" id="wq_catalog_mode_enabled">';
        echo '<option value="no"' . selected($value, 'no', false) . '>No</option>';
        echo '<option value="yes"' . selected($value, 'yes', false) . '>Yes</option>';
        echo '</select>';
        echo '<p class="description">Enable to hide prices and disable purchasing functionality.</p>';
    }

    public function remove_product_tabs($tabs) {
        unset($tabs['additional_information']);
        return $tabs;
    }

    public function render_request_quote_field() {
        $value = get_option('wq_request_quote_enabled', 'no');
        echo '<select name="wq_request_quote_enabled" id="wq_request_quote_enabled">';
        echo '<option value="no"' . selected($value, 'no', false) . '>No</option>';
        echo '<option value="yes"' . selected($value, 'yes', false) . '>Yes</option>';
        echo '</select>';
        echo '<p class="description">Enable to show Request Quote button on product pages.</p>';
    }
}