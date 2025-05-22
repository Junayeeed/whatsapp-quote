<?php
if (!defined('ABSPATH')) {
    exit;
}

class WhatsApp_Chat {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_chat_template'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('whatsapp-chat', plugins_url('assets/css/whatsapp-chat.css', WQ_PLUGIN_FILE));
        wp_enqueue_script('whatsapp-chat', plugins_url('assets/js/whatsapp-chat.js', WQ_PLUGIN_FILE), array('jquery'), '1.0.0', true);

        wp_localize_script('whatsapp-chat', 'whatsappChatSettings', array(
            'whatsappNumber' => get_option('wq_whatsapp_number', ''),
        ));
    }

    public function render_chat_template() {
        include plugin_dir_path(WQ_PLUGIN_FILE) . 'templates/chat-template.php';
    }

    public function add_admin_menu() {
        add_menu_page(
            'WhatsApp Chat Settings',
            'WhatsApp Chat',
            'manage_options',
            'whatsapp-chat-settings',
            array($this, 'render_settings_page'),
            'dashicons-whatsapp',
            2 // Position after Dashboard
        );
    }

    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_whatsapp-chat-settings' !== $hook) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_style('whatsapp-chat-admin', plugins_url('assets/css/admin.css', WQ_PLUGIN_FILE));
        wp_enqueue_script('whatsapp-chat-admin', plugins_url('assets/js/admin.js', WQ_PLUGIN_FILE), array('jquery'), '1.0.0', true);
    }

    public function register_settings() {
        register_setting('whatsapp_chat_settings', 'wq_whatsapp_number');
        register_setting('whatsapp_chat_settings', 'wq_business_name');
        register_setting('whatsapp_chat_settings', 'wq_status_message');
        register_setting('whatsapp_chat_settings', 'wq_welcome_message');
        register_setting('whatsapp_chat_settings', 'wq_input_placeholder');
        register_setting('whatsapp_chat_settings', 'wq_profile_picture');
        register_setting('whatsapp_chat_settings', 'wq_catalog_mode_enabled');
        register_setting('whatsapp_chat_settings', 'wq_quote_button_message');
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <div class="whatsapp-chat-settings">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <form action="options.php" method="post">
                <?php
                settings_fields('whatsapp_chat_settings');
                do_settings_sections('whatsapp_chat_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="wq_whatsapp_number">WhatsApp Number</label>
                        </th>
                        <td>
                            <input type="text" id="wq_whatsapp_number" name="wq_whatsapp_number" 
                                value="<?php echo esc_attr(get_option('wq_whatsapp_number')); ?>" 
                                class="regular-text">
                            <p class="description">Enter your WhatsApp number with country code without "+" (e.g., 8801682024427)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wq_business_name">Business Name</label>
                        </th>
                        <td>
                            <input type="text" id="wq_business_name" name="wq_business_name" 
                                value="<?php echo esc_attr(get_option('wq_business_name', 'Business Name')); ?>" 
                                class="regular-text">
                            <p class="description">Enter your business name</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wq_quote_button_message">Quote Button Message Template</label>
                        </th>
                        <td>
                            <textarea id="wq_quote_button_message" name="wq_quote_button_message" 
                                class="large-text" rows="3"><?php echo esc_textarea(get_option('wq_quote_button_message', 'Request quote for [product_name]')); ?></textarea>
                            <p class="description">Customize the quote button message. Available shortcodes:<br>
                            <code>[product_name]</code> - Displays the product name<br>
                            <code>[product_link]</code> - Displays the product URL</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wq_profile_picture">Profile Picture</label>
                        </th>
                        <td>
                            <input type="hidden" id="wq_profile_picture" name="wq_profile_picture" 
                                value="<?php echo esc_attr(get_option('wq_profile_picture', '')); ?>">
                            <div id="profile-picture-preview" style="margin-bottom: 10px;">
                                <?php
                                $profile_picture = get_option('wq_profile_picture', '');
                                if ($profile_picture) {
                                    echo '<img src="' . esc_url($profile_picture) . '" style="max-width: 100px; height: auto;">';
                                }
                                ?>
                            </div>
                            <input type="button" id="upload_profile_picture" class="button" value="Upload Profile Picture">
                            <input type="button" id="remove_profile_picture" class="button" value="Remove" style="display: <?php echo $profile_picture ? 'inline-block' : 'none'; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wq_status_message">Status Message</label>
                        </th>
                        <td>
                            <input type="text" id="wq_status_message" name="wq_status_message" 
                                value="<?php echo esc_attr(get_option('wq_status_message', 'Typically replies within a few minutes.')); ?>" 
                                class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wq_welcome_message">Welcome Message</label>
                        </th>
                        <td>
                            <textarea id="wq_welcome_message" name="wq_welcome_message" 
                                class="large-text" rows="3"><?php echo esc_textarea(get_option('wq_welcome_message', 'Hi there 👋\nHow can we help you?')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wq_input_placeholder">Input Placeholder</label>
                        </th>
                        <td>
                            <input type="text" id="wq_input_placeholder" name="wq_input_placeholder" 
                                value="<?php echo esc_attr(get_option('wq_input_placeholder', 'Type a message...')); ?>" 
                                class="regular-text">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
                </form>
            </div>
        </div>
        <?php
    }
}