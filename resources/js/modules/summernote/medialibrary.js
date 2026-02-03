import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included

var ImageLibraryButton = function (context) {
    var ui = $.summernote.ui;

    // Retrieve the dynamic URLs from the textarea element
    var $editor = $(context.layoutInfo.editor[0]);

    var uploadUrl = $editor.prev().data('uploadurl') || '/endpoint/media/image/summernote-upload'; // Default to '/upload/image' if not provided
    var imageFetchUrl = $editor.prev().data('imagefetch') || '/endpoint/media/image/fetch'; // Default to '/images' if not provided


    var button = ui.button({
        contents: '<i class="fas fa-images"></i>',
        tooltip: 'Image Library',
        click: function () {
            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog modal-lg" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Image Library</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<ul class="nav nav-tabs" id="imageLibraryTabs" role="tablist">' +
                '<li class="nav-item">' +
                '<a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload" role="tab" aria-controls="upload" aria-selected="true">Upload</a>' +
                '</li>' +
                '<li class="nav-item">' +
                '<a class="nav-link" id="select-tab" data-toggle="tab" href="#select" role="tab" aria-controls="select" aria-selected="false">Select</a>' +
                '</li>' +
                '</ul>' +
                '<div class="tab-content" id="imageLibraryTabsContent">' +
                '<div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">' +
                '<form class="uploadImageForm" method="post" action="'+uploadUrl+'" enctype="multipart/form-data">' +
                '<div class="form-group">' +
                '<label for="imageTitle" class="control-label">Title:</label>' +
                '<input type="text" id="imageTitle" name="title" class="form-control"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="imageCaption" class="control-label">Caption:</label>' +
                '<input type="text" id="imageCaption" name="caption" class="form-control"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="imageByline" class="control-label">Byline:</label>' +
                '<input type="text" id="imageByline" name="byline" class="form-control"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="imageKeywords" class="control-label">Keywords:</label>' +
                '<input type="text" id="imageKeywords" name="keywords" class="form-control tagsinput"/>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="uploadImageInput" class="control-label">Upload Image:</label>' +
                '<input type="file" id="uploadImageInput" name="images" class="form-control-file"/>' +
                '</div>' +
                '<button type="submit" class="btn btn-primary">Upload</button>' +
                '</form>' +
                '</div>' +
                '<div class="tab-pane fade" id="select" role="tabpanel" aria-labelledby="select-tab">' +
                '<input type="text" id="imageSearch" class="form-control" placeholder="Search images..."/>' +
                '<div id="imageList" class="d-flex flex-wrap"></div>' +
                '<hr />'+
                '<div class="w-100 d-flex">' +
                '<button class="btn btn-blue mx-auto media-next" data-page="1">Next</button> ' +
                '</div>'+
                '</div>' +

                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '</div>' +
                '</div>' +
                '</div>');

            // Show the modal
            $modal.appendTo('body').modal('show');

            // Handle image upload
            $('.uploadImageForm').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: uploadUrl,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var range = context.invoke('editor.createRange');  // Capture the current caret position
                        resetAndHideModal();
                        if (range) {
                            range.deleteContents();  // Remove any selected content (optional)
                            var imageUrl = response.imageloc;
                            // Create the figure element (image with caption)
                            var figure = $('<figure>').css('margin', '10px 0');
                            var imgNode = $('<img>').attr('src',imageUrl).css('max-width', '100%'); // Replace 'imageUrl' with the uploaded image URL
                            var captionText = (response.caption && response.caption.trim() !== '') ? response.caption : 'caption';
                            var captionContentEditable = $('<figcaption contenteditable="true" class="caption ">')
                                .text(captionText)
                                .css('text-align', 'center');

                            figure.append(imgNode);
                            figure.append(captionContentEditable);

                            // Insert the figure at the current caret position
                            range.insertNode(figure.get(0));

                            // Ensure the editor remains focused after insertion
                            context.invoke('editor.focus');
                        }

                    },
                    error: function () {
                        alert('Image upload failed. Please try again.');
                    }
                });
            });

            // Fetch and display images from the database
            function fetchImages(page,query = '') {
                $.ajax({
                    url: imageFetchUrl,
                    type: 'GET',
                    data: { search: query,page:page },
                    success: function (response) {
                        var images = response.images.data;

                        if (Array.isArray(images)) {
                            $('#imageList').empty(); // Clear previous images
                            images.forEach(function (image) {
                                var img = $('<img>').attr('src', 'https://cms.eu-central-1.linodeobjects.com/' + image.url).css({
                                    'max-width': '100px',
                                    'margin': '5px',
                                    'cursor': 'pointer'
                                }).on('click', function () {
                                    var range = context.invoke('editor.createRange');  // Capture the current caret position
                                    resetAndHideModal();
                                    if (range) {
                                        range.deleteContents();  // Remove any selected content (optional)

                                        // Create the figure element (image with caption)
                                        var figure = $('<figure>').css('margin', '10px 0');
                                        var imgNode = $('<img>').attr('src', 'https://cms.eu-central-1.linodeobjects.com/' + image.url).css('max-width', '100%'); // Replace 'imageUrl' with the uploaded image URL
                                        var captionText = (image.caption && image.caption.trim() !== '') ? image.caption : 'caption';
                                        var captionContentEditable = $('<figcaption contenteditable="true"  class="caption ">')
                                            .text(captionText)
                                            .css('text-align', 'center');

                                        figure.append(imgNode);
                                        figure.append(captionContentEditable);

                                        // Insert the figure at the current caret position
                                        range.insertNode(figure.get(0));

                                        // Ensure the editor remains focused after insertion
                                        context.invoke('editor.focus');
                                    }

                                });

                                $('#imageList').append(img);
                            });
                        } else {
                            console.error('Expected an array of images but got:', images);
                        }
                    },
                    error: function (e) {
                        console.log(e.responseText);
                        //alert(e.responseText);
                    }
                });
            }

            function resetAndHideModal() {
                $modal.modal('hide').remove();
                $modal.off().remove();
                $('.modal-backdrop').remove(); // Remove the modal backdrop
                $('body').removeClass('modal-open'); // Ensure modal-open class is removed
                resetModal();
            }
            $(document).on('click','.media-next',function (e){
                e.preventDefault();
                var page = $(this).data('page');
                var next_page = page+1;
                var search = $(this).data('search');
                fetchImages(next_page,search);
                $(this).data('page',next_page)
            });

            function resetModal() {
                // Reset the tabs and search
                $('#imageLibraryTabs a[href="#upload"]').tab('show'); // Reset to Upload tab
                $('#imageSearch').val(''); // Clear search field
                $('#select-tab').data('page', 1); // Reset page counter
            }
            // Load images when the "Select" tab is shown
            $('#select-tab').on('shown.bs.tab', function () {

                fetchImages(1);

            });

            // Handle search input
            $('#imageSearch').on('keyup', function () {

                var query = $(this).val();
                fetchImages(1,query);
                $('.media-next').data('search',query);
            });
        }
    });

    return button.render();
};

$.fn.extend({
    insertImageLibrary: function (context) {
        return ImageLibraryButton(context);
    }
});

$.extend($.summernote.plugins, {
    'imageLibrary': function (context) {
        context.memo('button.imageLibrary', function () {
            return ImageLibraryButton(context);
        });
    }
});