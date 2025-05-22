(function($) {
    'use strict';

    // Initialize WhatsApp Chat
    function initWhatsAppChat() {
        const chatPopup = $('.wq-chat-popup');
        const floatingBtn = $('.wq-floating-btn');
        const closeBtn = $('.wq-close-btn');
        const messageInput = $('.wq-message-input');
        const sendBtn = $('.wq-send-btn');

        // Toggle chat popup
        floatingBtn.on('click', function() {
            chatPopup.fadeIn();
            // Remove notification dot when opening chat
            $('.wq-notification-dot').remove();
        });

        // Close chat popup
        closeBtn.on('click', function() {
            chatPopup.fadeOut();
        });

        // Send message
        function sendMessage() {
            const message = messageInput.val().trim();
            if (message) {
                // Get WhatsApp settings from localized variable
                const whatsappNumber = whatsappChatSettings.whatsappNumber;
                const whatsappMessage = encodeURIComponent(message);
                const whatsappURL = `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`;
                
                // Open WhatsApp in new window
                window.open(whatsappURL, '_blank');
                
                // Clear input
                messageInput.val('');
            }
        }

        // Send message on button click
        sendBtn.on('click', sendMessage);

        // Send message on Enter key
        messageInput.on('keypress', function(e) {
            if (e.which === 13) {
                sendMessage();
                e.preventDefault();
            }
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initWhatsAppChat();
    });

})(jQuery);