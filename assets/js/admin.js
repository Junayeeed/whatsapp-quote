(function($) {
    'use strict';

    $(document).ready(function() {
    var mediaUploader = null;
    var profilePicturePreview = $('#profile-picture-preview');
    var profilePictureInput = $('#wq_profile_picture');
    var removeButton = $('#remove_profile_picture');

    // Check if we're on the correct admin page
    if (!$('#upload_profile_picture').length) {
        return;
    }

    $('#upload_profile_picture').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Choose Profile Picture',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            profilePictureInput.val(attachment.url);
            profilePicturePreview.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;">');
            removeButton.show();
        });

        mediaUploader.open();
    });

    removeButton.on('click', function(e) {
        e.preventDefault();
        profilePictureInput.val('');
        profilePicturePreview.empty();
        removeButton.hide();
    });
    });
})(jQuery);