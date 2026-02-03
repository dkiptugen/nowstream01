import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var SpotifyEmbedButton = function (context) {
    var ui = $.summernote.ui;

    // Create the button UI for embedding Spotify
    var button = ui.button({
        contents: '<i class="fab fa-spotify"></i>',
        tooltip: 'Embed Spotify Track',
        click: function () {
            // Create a modal for inputting the Spotify URL
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Embed Spotify Track</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="spotifyUrl" class="control-label">Spotify Track URL:</label>' +
                '<input type="text" id="spotifyUrl" class="form-control" placeholder="Enter Spotify track URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertSpotifyBtn">Embed Track</button>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            // Append the modal to the body and show it
            $modal.appendTo('body').modal('show');

            // Handle the embed button click inside the modal
            $('#insertSpotifyBtn').on('click', function () {
                var spotifyUrl = $('#spotifyUrl').val();
                if (spotifyUrl) {
                    // Extract the track ID from the Spotify URL
                    var trackId = spotifyUrl.split('track/')[1].split('?')[0];

                    // Construct the Spotify embed iframe
                    var spotifyEmbed = $('<iframe src="https://open.spotify.com/embed/track/' + trackId + '" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>');

                    // Insert the iframe into the editor
                    context.invoke('editor.insertNode', spotifyEmbed[0]);

                    // Close and reset the modal
                    resetAndHideModal();
                } else {
                    alert('Please enter a valid Spotify track URL.');
                }
            });

            // Function to reset the modal input and hide it
            function resetAndHideModal() {
                $('#spotifyUrl').val('');
                $modal.modal('hide');
            }

            // Remove the modal from the DOM after it is hidden
            $modal.on('hidden.bs.modal', function (e) {
                $(this).remove();
            });
        }
    });

    return button.render(); // Return the button element
};

// Extend Summernote to add the Spotify embed button
$.fn.extend({
    insertSpotifyEmbed: function (context) {
        return SpotifyEmbedButton(context);
    }
});

// Register the plugin in Summernote
$.extend($.summernote.plugins, {
    'spotifyEmbed': function (context) {
        context.memo('button.spotifyEmbed', function () {
            return SpotifyEmbedButton(context);
        });
    }
});