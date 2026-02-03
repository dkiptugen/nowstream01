import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var TwitterEmbedButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fab fa-twitter"></i>',
        tooltip: 'Embed Tweet',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Embed Tweet</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="tweetUrl" class="control-label">Tweet URL:</label>' +
                '<input type="text" id="tweetUrl" class="form-control" placeholder="Enter tweet URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertTweetBtn">Embed Tweet</button>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertTweetBtn').on('click', function () {
                var tweetUrl = $('#tweetUrl').val();
                if (tweetUrl) {
                    // Insert tweet embed code
                    var tweetEmbed = $('<blockquote class="twitter-tweet"><a href="' + tweetUrl + '"></a></blockquote>');
                    context.invoke('editor.insertNode', tweetEmbed[0]);

                    // Insert the Twitter widgets script if not already present
                    if (!$('#twitter-wjs').length) {
                        var twitterScript = document.createElement('script');
                        twitterScript.id = 'twitter-wjs';
                        twitterScript.src = 'https://platform.twitter.com/widgets.js';
                        document.body.appendChild(twitterScript);
                    } else {
                        // If script is already present, reload it to render new tweets
                        twttr.widgets.load();
                    }

                    resetAndHideModal();
                } else {
                    alert('Please enter a valid tweet URL.');
                }
            });

            function resetAndHideModal() {
                $('#tweetUrl').val('');
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
    insertTwitterEmbed: function (context) {
        return TwitterEmbedButton(context);
    }
});

$.extend($.summernote.plugins, {
    'twitterEmbed': function (context) {
        context.memo('button.twitterEmbed', function () {
            return TwitterEmbedButton(context);
        });
    }
});