import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var FileLibraryButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fas fa-file"></i>',
        tooltip: 'Insert File',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert File</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<ul class="nav nav-tabs" id="fileLibraryTabs" role="tablist">' +
                '<li class="nav-item">' +
                '<a class="nav-link active" id="upload-file-tab" data-toggle="tab" href="#upload-file" role="tab" aria-controls="upload-file" aria-selected="true">Upload File</a>' +
                '</li>' +
                '<li class="nav-item">' +
                '<a class="nav-link" id="link-file-tab" data-toggle="tab" href="#link-file" role="tab" aria-controls="link-file" aria-selected="false">Link File</a>' +
                '</li>' +
                '</ul>' +
                '<div class="tab-content" id="fileLibraryTabsContent">' +
                '<div class="tab-pane fade show active" id="upload-file" role="tabpanel" aria-labelledby="upload-file-tab">' +
                '<form id="uploadFileForm" method="post" enctype="multipart/form-data">' +
                '<div class="form-group">' +
                '<label for="uploadFileName" class="control-label">Name:</label>' +
                '<input type="filename" id="uploadFileName" name="filename" class="form-control"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="uploadFileInput" class="control-label">Upload File:</label>' +
                '<input type="file" id="uploadFileInput" name="file" class="form-control-file"/>' +
                '</div>' +
                '<button type="submit" class="btn btn-primary">Upload</button>' +
                '</form>' +
                '</div>' +
                '<div class="tab-pane fade" id="link-file" role="tabpanel" aria-labelledby="link-file-tab">' +
                '<div class="form-group">' +
                '<label for="fileUrl" class="control-label">File URL:</label>' +
                '<input type="text" id="fileUrl" class="form-control" placeholder="Enter file URL"/>' +
                '</div>' +
                '<button type="button" class="btn btn-primary" id="insertFileLinkBtn">Insert File Link</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            $modal.appendTo('body').modal('show');

            // Handle file upload
            $('#uploadFileForm').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '/', // Replace with your upload URL
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var fileUrl = response.fileUrl;
                        var fileName = response.fileName;
                        var fileNode = $('<a>').attr('href', fileUrl).attr('target', '_blank').text(fileName);
                        context.invoke('editor.insertNode', fileNode[0]);
                        resetAndHideModal();
                    },
                    error: function () {
                        alert('File upload failed. Please try again.');
                    }
                });
            });

            // Handle file link insertion
            $('#insertFileLinkBtn').on('click', function () {
                var fileUrl = $('#fileUrl').val();
                if (fileUrl) {
                    var fileName = fileUrl.split('/').pop(); // Extract file name from URL
                    var fileNode = $('<a>').attr('href', fileUrl).attr('target', '_blank').text(fileName);
                    context.invoke('editor.insertNode', fileNode[0]);
                    resetAndHideModal();
                } else {
                    alert('Please enter a valid file URL.');
                }
            });

            function resetAndHideModal() {
                // Clear input fields
                $('#uploadFileForm')[0].reset();
                $('#fileUrl').val('');

                // Hide the modal
                $modal.modal('hide');
            }

            // Dispose of the modal when it's hidden
            $modal.on('hidden.bs.modal', function (e) {
                $(this).remove();
            });
        }
    });

    return button.render();
};

$.fn.extend({
    insertFileLibrary: function (context) {
        return FileLibraryButton(context);
    }
});

$.extend($.summernote.plugins, {
    'fileLibrary': function (context) {
        context.memo('button.fileLibrary', function () {
            return FileLibraryButton(context);
        });
    }
});