<?php
if (!defined('ABSPATH')) {
    exit;
}

class Quote_Message_Handler {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Initialize the message handler
    }

    public function parse_quote_message($product_id) {
        $message_template = get_option('wq_quote_button_message', 'Request quote for [product_name]');
        $product = wc_get_product($product_id);

        if (!$product) {
            return 'Request quote';
        }

        $replacements = array(
            '[product_name]' => $product->get_name(),
            '[product_link]' => get_permalink($product_id)
        );

        return str_replace(array_keys($replacements), array_values($replacements), $message_template);
    }
}