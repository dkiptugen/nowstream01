import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var InstagramEmbedButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fab fa-instagram"></i>',
        tooltip: 'Embed Instagram Post',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Embed Instagram Post</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="instagramUrl" class="control-label">Instagram Post URL:</label>' +
                '<input type="text" id="instagramUrl" class="form-control" placeholder="Enter Instagram post URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertInstagramBtn">Embed Post</button>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertInstagramBtn').on('click', function () {
                var instagramUrl = $('#instagramUrl').val();
                if (instagramUrl) {
                    // Insert Instagram embed code
                    var instagramEmbed = $('<blockquote class="instagram-media"><a href="' + instagramUrl + '"></a></blockquote>');
                    context.invoke('editor.insertNode', instagramEmbed[0]);

                    if (!$('#instagram-wjs').length) {
                        // Insert Instagram embed script if not present
                        var instagramScript = document.createElement('script');
                        instagramScript.id = 'instagram-wjs';
                        instagramScript.src = 'https://www.instagram.com/embed.js';
                        document.body.appendChild(instagramScript);
                    } else if (window.instgrm) {
                        // Reprocess embeds if the script is already loaded
                        window.instgrm.Embeds.process();
                    } else {
                        // Reload the script if something went wrong with the first load
                        var scriptTag = $('#instagram-wjs');
                        scriptTag.remove();
                        var newInstagramScript = document.createElement('script');
                        newInstagramScript.id = 'instagram-wjs';
                        newInstagramScript.src = 'https://www.instagram.com/embed.js';
                        document.body.appendChild(newInstagramScript);
                    }
                    resetAndHideModal();
                } else {
                    alert('Please enter a valid Instagram post URL.');
                }
            });

            function resetAndHideModal() {
                $('#instagramUrl').val('');
                $modal.modal('hide');
            }

            $modal.on('hidden.bs.modal', function (e) {
                $(this).remove();
            });
        }
    });

    return button.render();
};

$.fn.extend({
    insertInstagramEmbed: function (context) {
        return InstagramEmbedButton(context);
    }
});

$.extend($.summernote.plugins, {
    'instagramEmbed': function (context) {
        context.memo('button.instagramEmbed', function () {
            return InstagramEmbedButton(context);
        });
    }
});