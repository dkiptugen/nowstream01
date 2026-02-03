import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var AudioLibraryButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fas fa-music"></i>',
        tooltip: 'Insert Audio',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert Audio</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="audioUrl" class="control-label">Audio URL:</label>' +
                '<input type="text" id="audioUrl" class="form-control" placeholder="Enter audio URL"/>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-primary" id="insertAudioBtn">Insert Audio</button>' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertAudioBtn').on('click', function () {
                var audioUrl = $('#audioUrl').val();
                if (audioUrl) {
                    var audioNode = $('<audio>').attr('src', audioUrl).attr('controls', true).css('width', '100%').addClass('audio-player');
                    context.invoke('editor.insertNode', audioNode[0]);
                    $modal.modal('hide');
                } else {
                    alert('Please enter a valid audio URL.');
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
    insertAudioLibrary: function (context) {
        return AudioLibraryButton(context);
    }
});

$.extend($.summernote.plugins, {
    'audioLibrary': function (context) {
        context.memo('button.audioLibrary', function () {
            return AudioLibraryButton(context);
        });
    }
});