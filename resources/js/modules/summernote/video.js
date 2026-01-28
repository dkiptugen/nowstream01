import * as $ from "jquery";
import "bootstrap";
import Hls from "hls.js"; // Import hls.js for HLS support

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
                '<label for="youtubeUrl" class="control-label">YouTube URL:</label>' +
                '<input type="text" id="youtubeUrl" class="form-control" placeholder="Enter YouTube URL (optional)"/>' +
                '</div>' +
                '<hr>' +
                '<div class="form-group">' +
                '<label for="hlsUrl" class="control-label">HLS URL (.m3u8):</label>' +
                '<input type="text" id="hlsUrl" class="form-control" placeholder="Enter HLS video URL (optional)"/>' +
                '</div>' +
                '<hr>' +
                '<div class="form-group">' +
                '<label for="videoUrlMp4" class="control-label">MP4 URL:</label>' +
                '<input type="text" id="videoUrlMp4" class="form-control" placeholder="Enter MP4 video URL"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="videoUrlWebm" class="control-label">WebM URL:</label>' +
                '<input type="text" id="videoUrlWebm" class="form-control" placeholder="Enter WebM video URL (optional)"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="videoUrlOgg" class="control-label">Ogg URL:</label>' +
                '<input type="text" id="videoUrlOgg" class="form-control" placeholder="Enter Ogg video URL (optional)"/>' +
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
                var youtubeUrl = $('#youtubeUrl').val().trim();
                var hlsUrl = $('#hlsUrl').val().trim();
                var mp4Url = $('#videoUrlMp4').val().trim();
                var webmUrl = $('#videoUrlWebm').val().trim();
                var oggUrl = $('#videoUrlOgg').val().trim();

                var videoNode;

                if (youtubeUrl) {
                    // Convert YouTube URL to embeddable format
                    var videoId = youtubeUrl.match(/[?&]v=([^&]+)/) || youtubeUrl.match(/youtu\.be\/([^?]+)/);
                    if (videoId) {
                        videoNode = $('<iframe>', {
                            src: `https://www.youtube.com/embed/${videoId[1]}`,
                            width: "560",
                            height: "315",
                            frameborder: "0",
                            allowfullscreen: true
                        });
                    } else {
                        alert("Invalid YouTube URL.");
                        return;
                    }
                } else if (hlsUrl) {
                    // HLS requires a video tag and script for hls.js
                    var videoId = `hls-video-${Date.now()}`;
                    videoNode = $('<video>', {
                        id: videoId,
                        controls: true,
                        class: "video-player",
                        width: "100%"
                    });

                    // JavaScript snippet to initialize hls.js
                    var script = $('<script>').text(`
                        setTimeout(function() {
                            var video = document.getElementById('${videoId}');
                            if (Hls.isSupported()) {
                                var hls = new Hls();
                                hls.loadSource('${hlsUrl}');
                                hls.attachMedia(video);
                                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                                    video.play();
                                });
                            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                video.src = '${hlsUrl}';
                                video.addEventListener('loadedmetadata', function() {
                                    video.play();
                                });
                            }
                        }, 500);
                    `);
                    videoNode = $('<div>').append(videoNode).append(script);
                } else if (mp4Url || webmUrl || oggUrl) {
                    videoNode = $('<video>').attr('controls', true).addClass('video-player');

                    if (mp4Url) {
                        videoNode.append($('<source>').attr('src', mp4Url).attr('type', 'video/mp4'));
                    }
                    if (webmUrl) {
                        videoNode.append($('<source>').attr('src', webmUrl).attr('type', 'video/webm'));
                    }
                    if (oggUrl) {
                        videoNode.append($('<source>').attr('src', oggUrl).attr('type', 'video/ogg'));
                    }

                    // Fallback message
                    videoNode.append('Your browser does not support the video tag.');
                } else {
                    alert('Please enter at least one valid video URL.');
                    return;
                }

                context.invoke('editor.insertNode', videoNode[0]);
                $modal.modal('hide');
            });

            // Dispose of the modal when it's hidden
            $modal.on('hidden.bs.modal', function () {
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
