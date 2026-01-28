(function ($) {
    $.extend(true, $.summernote.lang, {
        'en-US': {
            dynamicInsert: {
                fileManager: 'File Manager',
                upload: 'Upload',
                select: 'Select',
                searchPlaceholder: 'Search...',
                noFilesFound: 'No files found.',
                uploadSuccess: 'File uploaded successfully!',
                uploadError: 'Failed to upload file.',
            },
        },
    });

    $.extend($.summernote.plugins, {
        dynamicInsert: function (context) {
            var self = this;
            var ui = $.summernote.ui;

            // URLs for file operations
            var fileFetchUrl = context.options.fileFetchUrl || '/files';
            var fileUploadUrl = context.options.fileUploadUrl || '/upload';

            // Initialize button
            context.memo('button.dynamicInsert', function () {
                return ui.buttonGroup({
                    className: 'note-btn-group',
                    children: [
                        ui.button({
                            contents: '<i class="fas fa-folder-open"/> ty' + $.summernote.lang['en-US'].dynamicInsert.fileManager,
                            tooltip: $.summernote.lang['en-US'].dynamicInsert.fileManager,
                            className: 'dropdown-toggle',
                            data: {
                                toggle: 'dropdown',
                            },
                        }).node,
                        ui.dropdown({
                            className: 'dropdown-menu',
                            children: [
                                ui.button({
                                    contents: $.summernote.lang['en-US'].dynamicInsert.upload,
                                    className: 'dropdown-item',
                                    click: openUploadModal,
                                }).node,
                                ui.button({
                                    contents: $.summernote.lang['en-US'].dynamicInsert.select,
                                    className: 'dropdown-item',
                                    click: openSelectModal,
                                }).node,
                            ],
                        }).node,
                    ],
                }).node;
            });

            // Modal HTML for file manager and upload
            function buildModals() {
                var modals = `
                <div class="modal fade" id="fileManagerModal" tabindex="-1" role="dialog" aria-labelledby="fileManagerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="fileManagerModalLabel">File Manager</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="text" id="fileSearch" class="form-control mb-3" placeholder="${$.summernote.lang['en-US'].dynamicInsert.searchPlaceholder}">
                                <div id="fileList" class="d-flex flex-wrap"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="fileUploadModalLabel">Upload File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="file" id="fileUploadInput" class="form-control mb-3">
                                <button id="fileUploadButton" class="btn btn-primary btn-block">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>`;
                $('body').append(modals);
            }

            // Open Select File Modal
            function openSelectModal() {
                resetAndHideModals();
                $('#fileManagerModal').modal('show');
                fetchFiles(1);
            }

            // Open Upload File Modal
            function openUploadModal() {
                resetAndHideModals();
                $('#fileUploadModal').modal('show');
            }

            // Reset and hide modals
            function resetAndHideModals() {
                $('#fileList').empty();
                $('#fileSearch').val('');
                $('#fileManagerModal').modal('hide');
                $('#fileUploadModal').modal('hide');
            }

            // Fetch Files from server
            function fetchFiles(page, query = '') {
                $.ajax({
                    url: fileFetchUrl,
                    type: 'GET',
                    data: { search: query, page: page },
                    success: function (response) {
                        $('#fileList').empty();
                        var files = response.files.data;

                        if (files.length === 0) {
                            $('#fileList').html(`<div class="text-center">${$.summernote.lang['en-US'].dynamicInsert.noFilesFound}</div>`);
                            return;
                        }

                        files.forEach(function (file) {
                            var fileNode = $('<div class="file-item p-2">').css({
                                'max-width': '120px',
                                'margin': '5px',
                                'text-align': 'center',
                                'cursor': 'pointer',
                                'border': '1px solid #ddd',
                                'border-radius': '4px',
                            });

                            var icon = getFileIcon(file);
                            var fileName = $('<div>').text(file.name).css({
                                'font-size': '12px',
                                'white-space': 'nowrap',
                                overflow: 'hidden',
                                'text-overflow': 'ellipsis',
                            });

                            fileNode.append(icon, fileName);

                            fileNode.on('click', function () {
                                var range = context.invoke('editor.createRange');
                                resetAndHideModals();
                                if (range) insertFileIntoEditor(file, range);
                            });

                            $('#fileList').append(fileNode);
                        });
                    },
                    error: function () {
                        alert('Failed to fetch files.');
                    },
                });
            }

            // Get appropriate icon based on file type
            function getFileIcon(file) {
                var icon;
                if (file.type.startsWith('image/')) {
                    icon = $('<img>').attr('src', file.url).css({ width: '100%', 'border-radius': '4px' });
                } else if (file.type.startsWith('video/')) {
                    icon = $('<i class="fas fa-video fa-2x mb-2"></i>');
                } else if (file.type.startsWith('audio/')) {
                    icon = $('<i class="fas fa-music fa-2x mb-2"></i>');
                } else {
                    icon = $('<i class="fas fa-file-alt fa-2x mb-2"></i>');
                }
                return icon;
            }

            // Insert file into editor
            function insertFileIntoEditor(file, range) {
                var element;
                if (file.type.startsWith('image/')) {
                    element = $('<img>').attr('src', file.url).attr('alt', file.name).css({ 'max-width': '100%' });
                } else if (file.type.startsWith('video/')) {
                    element = $('<video controls>').attr('src', file.url).css({ 'max-width': '100%' });
                } else if (file.type.startsWith('audio/')) {
                    element = $('<audio controls>').attr('src', file.url).css({ 'max-width': '100%' });
                } else {
                    element = $('<a>').attr('href', file.url).text(file.name).css({ 'text-decoration': 'underline', color: 'blue' });
                }
                range.insertNode(element.get(0));
                context.invoke('editor.focus');
            }

            // Handle file upload
            $(document).on('click', '#fileUploadButton', function () {
                var file = $('#fileUploadInput')[0].files[0];
                if (!file) {
                    alert('Please select a file to upload.');
                    return;
                }

                var formData = new FormData();
                formData.append('file', file);

                $.ajax({
                    url: fileUploadUrl,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function () {
                        alert($.summernote.lang['en-US'].dynamicInsert.uploadSuccess);
                        $('#fileUploadModal').modal('hide');
                    },
                    error: function () {
                        alert($.summernote.lang['en-US'].dynamicInsert.uploadError);
                    },
                });
            });

            // Search handler
            $(document).on('input', '#fileSearch', function () {
                fetchFiles(1, $(this).val());
            });

            // Initialize modals
            this.initialize = function () {
                buildModals();
            };

            // Destroy modals
            this.destroy = function () {
                $('#fileManagerModal').remove();
                $('#fileUploadModal').remove();
            };
        },
    });
})(jQuery);
