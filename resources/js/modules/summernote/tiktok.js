import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var TikTokEmbedButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fab fa-tiktok"></i>',
        tooltip: 'Embed TikTok Post',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Embed TikTok Post</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="tiktokUrl" class="control-label">TikTok Post URL:</label>' +
                '<input type="text" id="tiktokUrl" class="form-control" placeholder="Enter TikTok post URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertTikTokBtn">Embed Post</button>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertTikTokBtn').on('click', function () {
                var tiktokUrl = $('#tiktokUrl').val();
                if (tiktokUrl) {
                    // Construct the TikTok embed HTML
                    var tiktokEmbed = $('<blockquote class="tiktok-embed" cite="' + tiktokUrl + '"><section> </section></blockquote>');
                    context.invoke('editor.insertNode', tiktokEmbed[0]);

                    // Insert the TikTok embed script if not already present
                    if (!$('#tiktok-wjs').length) {
                        var tiktokScript = document.createElement('script');
                        tiktokScript.id = 'tiktok-wjs';
                        tiktokScript.src = 'https://www.tiktok.com/embed.js';
                        document.body.appendChild(tiktokScript);
                    } else {
                        // If script is already present, reload it to render new posts
                        window.tiktok.embed.load();
                    }

                    resetAndHideModal();
                } else {
                    alert('Please enter a valid TikTok post URL.');
                }
            });

            function resetAndHideModal() {
                $('#tiktokUrl').val('');
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
    insertTikTokEmbed: function (context) {
        return TikTokEmbedButton(context);
    }
});

$.extend($.summernote.plugins, {
    'tiktokEmbed': function (context) {
        context.memo('button.tiktokEmbed', function () {
            return TikTokEmbedButton(context);
        });
    }
});