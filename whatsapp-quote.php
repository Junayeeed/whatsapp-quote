<?php
/*
Plugin Name: WhatsApp Quote
Plugin URI: 
Description: A WordPress plugin for requesting quotes via WhatsApp
Version: 1.2.1
Author: Muhammad Junayed
Author URI: https://www.junayed.dev
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: whatsapp-quote
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WQ_PLUGIN_FILE', __FILE__);
define('WQ_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WQ_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required classes
require_once WQ_PLUGIN_PATH . 'includes/class-whatsapp-chat.php';
require_once WQ_PLUGIN_PATH . 'includes/class-woocommerce-catalog-mode.php';

// Initialize the plugin
function wq_initialize_plugin() {
    // Check if WooCommerce is active
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        WooCommerce_Catalog_Mode::get_instance();
    }
    WhatsApp_Chat::get_instance();
}
add_action('plugins_loaded', 'wq_initialize_plugin');

// Activation Hook
register_activation_hook(__FILE__, 'wq_activate_plugin');

function wq_activate_plugin() {
    // Set default options
    if (!get_option('wq_whatsapp_number')) {
        update_option('wq_whatsapp_number', '');
        update_option('wq_business_name', 'Business Name');
        update_option('wq_status_message', 'Typically replies within a few minutes.');
        update_option('wq_welcome_message', 'Hi there 👋\nHow can we help you?');
        update_option('wq_input_placeholder', 'Type a message...');
        update_option('wq_profile_picture', '');
    }
}

