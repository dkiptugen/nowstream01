import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var MapEmbedButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fas fa-map-marked-alt"></i>',
        tooltip: 'Embed Map',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Embed Map</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="mapUrl" class="control-label">Map URL:</label>' +
                '<input type="text" id="mapUrl" class="form-control" placeholder="Enter map URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertMapBtn">Embed Map</button>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            $('#insertMapBtn').on('click', function () {
                var mapUrl = $('#mapUrl').val();
                if (mapUrl) {
                    var iframe = $('<iframe>')
                        .attr('src', mapUrl)
                        .attr('width', '100%')
                        .attr('height', '450')
                        .attr('frameborder', '0')
                        .attr('style', 'border:0')
                        .attr('allowfullscreen', '')
                        .css('width', '100%')
                        .css('height', '450px');

                    context.invoke('editor.insertNode', iframe[0]);
                    resetAndHideModal();
                } else {
                    alert('Please enter a valid map URL.');
                }
            });

            function resetAndHideModal() {
                $('#mapUrl').val('');
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
    insertMapEmbed: function (context) {
        return MapEmbedButton(context);
    }
});

$.extend($.summernote.plugins, {
    'mapEmbed': function (context) {
        context.memo('button.mapEmbed', function () {
            return MapEmbedButton(context);
        });
    }
});