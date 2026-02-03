import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

$.extend($.summernote.plugins, {
    'extendedLink': function (context) {
        var self = this;
        var ui = $.summernote.ui;
        var $editor = context.layoutInfo.editor;

        // Override the default behavior for the link button
        context.memo('button.extendedLink', function () {
            var button = ui.button({
                contents: '<i class="note-icon-link"></i>',
                tooltip: 'Insert Link',
                click: function () {
                    self.showLinkDialog();
                }
            });
            return button.render();
        });

        // Extend the link dialog
        this.showLinkDialog = function () {
            var $linkDialog = $(
                '<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert Link</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="linkText">Display Text:</label>' +
                '<input type="text" id="linkText" class="form-control" placeholder="Enter display text">' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="linkUrl">URL:</label>' +
                '<input type="text" id="linkUrl" class="form-control" placeholder="Enter URL">' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="linkRel">Rel Attribute:</label>' +
                '<input type="text" id="linkRel" class="form-control" placeholder="Enter rel value (e.g., nofollow, noopener)">' +
                '</div>' +
                '<div class="form-group">' +
                '<label><input type="checkbox" id="linkNewTab"> Open in new tab</label>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-primary" id="insertLinkBtn">Insert Link</button>' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>'
            );

            $linkDialog.appendTo('body').modal('show');

            // Prepopulate the "Display Text" field with selected text
            var selectedText = context.invoke('editor.getSelectedText');
            $('#linkText').val(selectedText);

            // Insert the link on button click
            $('#insertLinkBtn').on('click', function () {
                var linkText = $('#linkText').val();
                var linkUrl = $('#linkUrl').val();
                var linkRel = $('#linkRel').val();
                var linkTarget = $('#linkNewTab').is(':checked') ? '_blank' : '_self';

                if (linkUrl) {
                    // Restore the range before inserting to avoid creating new lines
                    context.invoke('editor.restoreRange');

                    // Create and insert the link inline, replacing the selected text
                    var $link = $('<a></a>')
                        .attr('href', linkUrl)
                        .attr('target', linkTarget)
                        .attr('rel', linkRel)
                        .text(linkText || selectedText);

                    context.invoke('editor.insertNode', $link[0]);

                    resetAndHideModal();
                } else {
                    alert('Please enter a valid URL.');
                }
            });

            function resetAndHideModal() {
                $('#linkText').val('');
                $('#linkUrl').val('');
                $('#linkRel').val('');
                $linkDialog.modal('hide');
            }

            $linkDialog.on('hidden.bs.modal', function () {
                $(this).remove();
            });
        };
    }
});

