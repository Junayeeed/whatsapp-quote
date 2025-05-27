<?php
if (!defined('ABSPATH')) {
    exit;
}

class Request_Quote_Button {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once WQ_PLUGIN_PATH . 'includes/class-quote-message-handler.php';
        add_action('woocommerce_single_product_summary', array($this, 'render_request_quote_button'), 30);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_shortcode('whatsapp_quote', array($this, 'shortcode_request_quote_button'));
    }

    public function enqueue_styles() {
        wp_enqueue_style('request-quote', plugins_url('assets/css/request-quote.css', WQ_PLUGIN_FILE));
    }

    public function shortcode_request_quote_button($atts) {
        global $product;
        
        // If product ID is provided in shortcode
        if (isset($atts['product_id'])) {
            $product = wc_get_product($atts['product_id']);
        }
        
        // Get the button HTML
        ob_start();
        $this->render_request_quote_button($product);
        return ob_get_clean();
    }

    public function render_request_quote_button($specific_product = null) {
        global $product;
        
        // Use specific product if provided, otherwise use global product
        $product = $specific_product ? $specific_product : $product;
        
        if (!$product || !apply_filters('whatsapp_quote_show_button', true, $product)) return;

        $product_name = $product->get_name();
        $product_url = get_permalink($product->get_id());
        $whatsapp_number = get_option('wq_whatsapp_number', '');
        
        if (empty($whatsapp_number)) return;

        $message_handler = Quote_Message_Handler::get_instance();
        $message = $message_handler->parse_quote_message($product->get_id());

        $whatsapp_url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp_number) . '?text=' . urlencode($message);
        
        $icon = file_get_contents(WQ_PLUGIN_PATH . 'assets/images/whatsapp-icon.svg');

        echo sprintf(
            '<a href="%s" class="button request-quote-button" target="_blank" rel="noopener noreferrer">%s%s</a>',
            esc_url($whatsapp_url),
            $icon,
            esc_html__('Request Quote', 'whatsapp-quote')
        );
    }
}