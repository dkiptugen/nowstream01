import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var VideoLibraryButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fas fa-video"></i>',
        tooltip: 'Insert Video',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert Video</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="videoUrl" class="control-label">Video URL:</label>' +
                '<input type="text" id="videoUrl" class="form-control" placeholder="Enter video URL"/>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-primary" id="insertVideoBtn">Insert Video</button>' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertVideoBtn').on('click', function () {
                var videoUrl = $('#videoUrl').val();
                if (videoUrl) {
                    var videoNode = $('<video>').attr('src', videoUrl).attr('controls', true).addClass('video-player');
                    context.invoke('editor.insertNode', videoNode[0]);
                    $modal.modal('hide');
                } else {
                    alert('Please enter a valid video URL.');
                }
            });

            // Dispose of the modal when it's hidden
            $modal.on('hidden.bs.modal', function (e) {
                $(this).remove();
            });
        }
    });

    return button.render();
};

$.fn.extend({
    insertVideoLibrary: function (context) {
        return VideoLibraryButton(context);
    }
});

$.extend($.summernote.plugins, {
    'videoLibrary': function (context) {
        context.memo('button.videoLibrary', function () {
            return VideoLibraryButton(context);
        });
    }
});
