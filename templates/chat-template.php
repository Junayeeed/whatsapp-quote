<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wq-chat-popup">
    <div class="wq-chat-header">
        <?php
        $profile_picture = get_option('wq_profile_picture', '');
        $default_picture = plugins_url('../assets/images/profile.png', __FILE__);
        $picture_url = $profile_picture ? $profile_picture : $default_picture;
        ?>
        <img src="<?php echo esc_url($picture_url); ?>" alt="Profile Picture" class="wq-profile-pic">
        <div class="wq-user-info">
            <div class="wq-user-name"><?php echo esc_html(get_option('wq_business_name', 'Business Name')); ?></div>
            <div class="wq-user-status">
                <span class="wq-status-dot"></span>
                <?php echo esc_html(get_option('wq_status_message', 'Typically replies within a few minutes.')); ?>
            </div>
        </div>
        <div class="wq-close-btn">×</div>
    </div>
    <div class="wq-chat-body">
        <div class="wq-time-indicator"><?php echo esc_html(current_time('g:i A')); ?></div>
        <div class="wq-message received">
            <?php echo nl2br(wp_kses_post(get_option('wq_welcome_message', 'Hi there 👋\nHow can we help you?'))); ?>
        </div>
    </div>
    <div class="wq-chat-footer">
        <div class="wq-input-container">
            <button class="wq-attachment-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#54656f">
                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
                    <path d="M13 7h-2v5.414l3.293 3.293 1.414-1.414L13 11.586z"/>
                </svg>
            </button>
            <input type="text" class="wq-message-input" placeholder="<?php echo esc_attr(get_option('wq_input_placeholder', 'Type a message...')); ?>">
            <button class="wq-send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#54656f">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
<div class="wq-floating-btn">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#ffffff">
        <path d="M12 2C6.486 2 2 6.486 2 12c0 1.762.458 3.42 1.264 4.868L2 22l5.132-1.264C8.58 21.542 10.238 22 12 22c5.514 0 10-4.486 10-10S17.514 2 12 2zm0 18c-1.42 0-2.79-.265-4.067-.76l-2.833.708.708-2.833C5.265 14.79 5 13.42 5 12c0-3.86 3.14-7 7-7s7 3.14 7 7-3.14 7-7 7z"/>
    </svg>
    <span class="wq-notification-dot"></span>
</div>