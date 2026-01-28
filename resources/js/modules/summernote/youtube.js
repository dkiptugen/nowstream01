import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap JS is loaded

var InsertYoutubeButton = function (context) {
    var ui = $.summernote.ui;
    var $editor = context.layoutInfo.editor;  // Get the editor instance
    var range;

    // Create the button for inserting YouTube videos
    var button = ui.button({
        contents: '<i class="fab fa-youtube"></i>',
        tooltip: 'Insert YouTube Video',
        click: function () {
            // Save the current range (cursor position)
            range = context.invoke('editor.createRange');

            // Clear any existing modals before creating a new one
            $('.modal').remove();

            // Create a modal to get the YouTube video URL
            var $modal = $('<div class="modal" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert YouTube Video</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="youtubeUrl" class="control-label">YouTube URL:</label>' +
                '<input type="text" id="youtubeUrl" class="form-control"/>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-blue" id="insertYoutubeBtn">Insert</button>' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            // Append the modal to the body and show it
            $modal.appendTo('body').modal('show');

            // Attach a click event to the "Insert" button in the modal
            $modal.find('#insertYoutubeBtn').on('click', function () {
                // Get the YouTube URL
                var youtubeUrl = $('#youtubeUrl').val();

                // Regex to extract the video ID from various YouTube URL formats
                var videoId = youtubeUrl.match(/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e|embed)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);

                // Check if video ID is found
                if (videoId && videoId[1]) {
                    // Construct the embed URL
                    var embedUrl = 'https://www.youtube.com/embed/' + videoId[1];

                    // Construct the iframe code with a remove button
                    var iframeCode = '<div class="embed-responsive embed-responsive-16by9 position-relative youtube-video">' +
                        '<iframe class="embed-responsive-item" src="' + embedUrl + '" allowfullscreen></iframe>' +
                        '<button type="button" class="btn btn-danger btn-sm remove-video-btn" style="position:absolute;top:5px;right:5px;"><i class="fas fa-close"></i></button>' +
                        '</div>';

                    // Restore the range to ensure the video is inserted at the cursor position
                    context.invoke('editor.restoreRange', range);

                    // Insert the iframe at the current cursor position using Summernote's insertNode function
                    context.invoke('editor.focus'); // Ensure the editor is focused
                    context.invoke('editor.insertNode', $(iframeCode)[0]);

                    // Hide the modal after insertion
                    $modal.modal('hide');
                } else {
                    alert('Invalid YouTube URL');
                }
            });
        }
    });

    return button.render(); // Return the button as a rendered HTML element
};

// Function to handle video removal with confirmation dialog
var handleVideoSelectionAndRemoval = function () {
    $(document).on('click', '.remove-video-btn', function () {
        var $videoElement = $(this).closest('.youtube-video');

        // Create a confirmation modal
        var $removeModal = $('<div class="modal" tabindex="-1" role="dialog">' +
            '<div class="modal-dialog" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h5 class="modal-title">Remove Video</h5>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p>Are you sure you want to remove this video?</p>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" class="btn btn-danger" id="confirmRemoveBtn">Remove</button>' +
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');

        // Append the remove modal to the body and show it
        $removeModal.appendTo('body').modal('show');

        // On confirmation, remove the video element and hide modal
        $removeModal.find('#confirmRemoveBtn').off('click').on('click', function () {
            $videoElement.remove();
            $removeModal.modal('hide');
        });

        // Ensure modal is removed after it's hidden to prevent lingering issues
        $removeModal.on('hidden.bs.modal', function () {
            $removeModal.remove();
        });
    });
};

// Extend Summernote with the plugin
$.extend($.summernote.plugins, {
    'youtube': function (context) {
        context.memo('button.insertYoutube', function () {
            return InsertYoutubeButton(context);
        });

        // Handle video selection and removal
        handleVideoSelectionAndRemoval();
    }
});