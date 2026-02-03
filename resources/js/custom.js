import './modules/moment'
import './modules/bootstrap'
import './modules/feather'
import './modules/font-awesome'
import './modules/sidebar'
import './modules/toastr'
import './modules/user-agent'
import './modules/chartjs'
import './modules/select2'
import './modules/daterangepicker'
import './modules/datetimepicker'
import './modules/validation'
import './modules/wizard'
import './modules/summernote'
import './modules/tagsinput'
import './modules/datatables'
import './modules/dropzone'




import * as $ from "jquery";
import {document} from "postcss";

$('#multiselect').multiselect();
$(function () {
    const inputs = $('.otp-input');
    const hiddenOtp = $('#otp');

    inputs.on('input', function () {
        this.value = this.value.replace(/\D/g, '');

        if (this.value && $(this).next('.otp-input').length) {
            $(this).next('.otp-input').focus();
        }

        updateOtp();
    });

    inputs.on('keydown', function (e) {
        if (e.key === 'Backspace' && !this.value && $(this).prev('.otp-input').length) {
            $(this).prev('.otp-input').focus();
        }
    });

    inputs.on('paste', function (e) {
        const paste = e.originalEvent.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        if (!paste) return;

        inputs.each(function (i) {
            this.value = paste[i] || '';
        });

        updateOtp();
        inputs.eq(paste.length - 1).focus();
    });

    function updateOtp() {
        let otp = '';
        inputs.each(function () {
            otp += this.value || '';
        });
        hiddenOtp.val(otp);

        // auto submit
        if (otp.length === 6) {
            $('#otp-form').submit();
        }
    }

    // focus first box on load
    inputs.first().focus();
});
$(document).ready(function () {
    $(document).on('submit', '#image-search', function (e) {
        e.preventDefault();
        $('#images_display').empty();
    });
    $(document).on('submit', '.savemedia', function (e) {
        e.preventDefault();
        var frm = $(this);
        $.ajax({
            type: 'POST',
            url: frm.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $(this).serialize(),
            success: function (Mess) {
                if (Mess.error === 0) {
                    console.log(Mess);
                    var prev = $('.preview');
                    $('#image-modal').modal('hide');
                    $('.upload').removeClass('d-none');
                    $('#content-preview').addClass('d-none').removeClass('d-flex');
                    //$('#media_id').val(Mess.media_id);
                    $('#mainImage').val(Mess.media_id);
                    prev.html('<img src="' + Mess.image + '" class="img-fluid" id="thumbnail" />');
                    frm.trigger('reset');
                    $('#images_display').load(window.location + " #images_display")

                }
            },
            error: function (xhr, status, errorThrown) {

                console.log(errorThrown);


            }
        });

    });
    $('#myModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset'); // Reset form fields
        $(this).find('.modal-body').html('');  // Clear any dynamic content
    });
    $(document).on('click', '.selectImage', function (e) {
        e.preventDefault();
        //console.log($(this).attr('src'));
        $('#mainImage').val($(this).data('id'));
        $('#thumbnail').attr('src', $(this).attr('src'));
        $('#content-preview').addClass('d-none').removeClass('d-flex');
        $('#image-modal').modal('hide');

    });
    $(document).on('change', '.file-input', function () {
        var filesCount = $(this)[0].files.length;
        var textbox = $(this).prev();
        var progressBarContainer = $('#progressBarContainer');
        var progressBar = $('#uploadProgressBar');
        progressBar.removeClass('d-none');

        if (filesCount === 1) {
            var fileName = $(this).val().split('\\').pop();
            textbox.text(fileName);
        } else {
            textbox.text(filesCount + ' files selected');
        }

        var data = new FormData();

        $.each($(this)[0].files, function (obj, v) {
            data.append('images', v);
        });

        // Display the progress bar container
        progressBarContainer.show();

        // Perform file upload with progress bar
        $.ajax({
            url: $(this).data('url'),
            type: "POST",
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,
            cache: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                // Listen to the progress event
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        progressBar.width(percentComplete + '%');
                        progressBar.attr('aria-valuenow', percentComplete);
                    }
                }, false);

                return xhr;
            },
            success: function (data) {
                if (data === 'invalid') {
                    // invalid file format.
                    $("#err").html("Invalid File!").fadeIn();
                } else {
                    //console.log(data);
                    // view uploaded file.
                    $(".upload").addClass('d-none').fadeOut();
                    $("#image").attr('src', data.imageloc);
                    $("#imgname").val(data.imgname);
                    $("#size").val(data.size);
                    $("#mime").val(data.mime);
                    $("#content-preview").removeClass('d-none').addClass('d-flex').fadeIn();

                    // Reset progress bar after successful upload
                    progressBar.width('0');
                }
            },
            error: function (e) {
                $("#err").html(e).fadeIn();
            }
        });
    });


    $(document).on('click', '.delete', function (s) {
        s.preventDefault();
        var link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (Mess) {
                if (Mess.status == true) {

                    toastr.success(Mess.msg, Mess.header, {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true,
                        onHidden: function () {
                            window.location = Mess.url;
                        }
                    });


                } else {
                    toastr.error(Mess.msg, Mess.header, {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true,
                        onHidden: function () {

                            //window.location = Mess.url;
                        }
                    });
                }
            },
            error: function (request, msg, error) {

                toastr.error(error, 'error', {
                    timeOut: 1000,
                    closeButton: true,
                    progressBar: true,
                    newestOnTop: true,
                    onHidden: function () {
                        //window.location.reload();
                    }
                });
            }
        });

    });
    $(document).ready(function () {


        var editor = $('.editor');
        var basic_editor = $('.basic-editor');
        var summary = $('#summary');
        var code = $('.code');
        var glancefact = $('.glancefact');
        if (summary.length) {
            summary.summernote({
                placeholder: "Summary",
                tabsize: 2,
                height: 100,
                wordcount: false,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript', 'fontname']],
                    ['para', ['ul', 'ol']]
                ]
            });
        }
        if (glancefact.length) {
            glancefact.summernote({
                placeholder: "Glancefact",
                tabsize: 2,
                height: 100,
                wordcount: false,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']]
                ]
            });
        }
        if (basic_editor.length) {
            basic_editor.summernote({
                dialogsInBody: true,
                dialogsFade: true,
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                fontSizeUnits: ['px', 'pt'],
                placeholder: basic_editor.data('placeholder'),
                tabsize: 2,
                height: 100,
                wordcount: false,
                popover: {
                    image: [
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    table: [
                        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                    ],
                    air: [
                        ['color', ['color']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']]
                    ]
                },


                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'caseConverter']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'style']],
                    ['font', ['strikethrough', 'superscript', 'subscript', 'fontname']],
                    ['fontsize', ['fontsize']]
                ]
            });
        }

        if (editor.length) {
            const autosaveUrl = editor.data('autosave-url');

            editor.summernote({
                dialogsInBody: true,
                dialogsFade: true,
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                fontSizeUnits: ['px', 'pt'],
                placeholder: editor.data('placeholder'),
                airMode: false,
                height: 600,
                tabsize: 2,
                lineHeight: 1.3,
                wordcount: true,
                popover: {
                    image: [
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    table: [
                        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                    ],
                    air: [
                        ['color', ['color']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']]
                    ],
                    insertYoutube: ['delete', ['delete']],
                    videoLibrary: ['remove', ['removeMedia']]

                },
                styleTags: [
                    'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
                ],
                toolbar: [
                    // [groupName, [list of button]]

                    ['style', ['bold', 'italic', 'underline', 'caseConverter']],
                    ['font', ['superscript', 'subscript', 'fontsize']],
                    ['para', ['ul', 'ol', 'paragraph', 'style', 'height', 'color']],
                    ['insert', ['extendedLink', 'table', 'mapEmbed', 'dynamicInsert']],
                    ['custom', ['imageLibrary', 'audioLibrary', 'videoLibrary', 'fileLibrary']],
                    ['social', ['twitterEmbed', "insertYoutube", 'insertFacebook', 'instagramEmbed', 'tiktokEmbed', 'spotifyEmbed']],
                    ['misc', ['undo', 'redo', 'clear']],
                    ['view', ['fullscreen', 'codeview', 'help']]

                ],
                fileFetchUrl: '/api/files', // API to fetch files
                fileUploadUrl: '/api/upload', // API to upload files
                TEXT_NODE: {
                    onImageUpload: function (image) {

                        uploadImage(image[0], $(this));

                    }
                },
                callbacks: {
                    onPaste: function (e) {
                        e.preventDefault();

                        // Get the plain text and HTML from the clipboard
                        var clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
                        var html = clipboardData.getData('text/html') || clipboardData.getData('text/plain');

                        // Debugging: Check if clipboard data is retrieved
                        console.log("Clipboard HTML:", html);

                        // Create a temporary container to parse the HTML
                        var $tempContainer = $('<div>').html(html);

                        // Remove all tags except <p>, <a>, <h1>-<h6>, and remove attributes from these tags
                        //$tempContainer.find('*').not('p, a, h1, h2, h3, h4, h5, h6').remove();
                        $tempContainer.find('p, a, h1, h2, h3, h4, h5, h6, ol, ul, li,strong,span,div').removeAttr('style').removeAttr('class');

                        // Remove empty <p> tags and <p><br/></p> tags
                        $tempContainer.find('p:empty, p:has(> br), div:empty').remove();

                        // Get the cleaned HTML
                        var cleanedHTML = $tempContainer.html();

                        // Debugging: Check the cleaned HTML before inserting it
                        console.log("Cleaned HTML:", cleanedHTML);

                        // Insert the cleaned HTML into the editor
                        $(this).summernote('pasteHTML', cleanedHTML);

                        // Debugging: Confirm the cleaned HTML was inserted
                        setTimeout(() => {
                            console.log("Editor content after paste:", $(this).summernote('code'));
                        }, 500);
                    },
                    onChange: function (contents) {
                        // Debounced autosave for specific Summernote editor
                        clearTimeout(this.autosaveTimer);
                        this.autosaveTimer = setTimeout(() => {
                            autosaveSpecificSummernote(editor, autosaveUrl);
                        }, 5000); // Save after 1 second of inactivity
                        console.log(autosaveUrl);
                    }
                }

            }).on('summernote.change', function (we, contents, $editable) {
                $(this).val(contents);

            });

            function autosaveSpecificSummernote(editor, url) {
                const formData = $('#post-form').serialize(); // Serialize the form data

                $.ajax({
                    url: url,  // Dynamic autosave URL
                    method: 'PUT',
                    data: formData,
                    success: function (response) {
                        $('#statusText').removeClass('text-danger').addClass('text-success').text('Content saved at ' + new Date().toLocaleTimeString());
                        $('#statusIcon').removeClass('failed').addClass('saved');
                    },
                    error: function (e) {
                        console.log(e.responseText);
                        $('#statusText').removeClass('text-success').addClass('text-danger').text('Failed to save content');
                        $('#statusIcon').removeClass('saved').addClass('failed');
                    }
                });
            }
        }
        if (code.length) {
            code.on('summernote.init', function () {
                code.summernote('codeview.activate');
            }).summernote({
                height: 300,
                toolbar: false,
                placeholder: 'Paste content here...',
                codemirror: {
                    theme: 'monokai',
                    mode: 'text/html',
                    htmlMode: true,
                    lineNumbers: true
                }
            });
            $(code.closest("form")).on("submit", function (e) {
                if (code.summernote('codeview.isActivated')) {
                    code.val(code.summernote());
                    //console.log(code.summernote('code'));
                    return true;
                }
                return true;
            });
        }

        function uploadImage(image, $summernote) {
            var dat = new FormData();
            dat.append("images", image);
            var IMAGE_PATH = 'https://www.now.co.ke/';
            $.ajax({
                data: dat,
                type: "POST",
                url: 'https://www.now.co.ke/now/api/image_upload',
                cache: false,
                contentType: false,
                processData: false,
                success: function (url) {
                    var image = $.trim(url.imageloc);
                    $summernote.summernote("insertImage", image, function ($image) {
                        $image.attr('class', 'image-fluid');
                    });
                },
                error: function (e) {
                    toastr.error(e, 'upload', {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true

                    });
                }
            });
        }

        editor.filter("[disabled=disabled]").next().find(".note-editable").attr("contenteditable", false);
    });
    $(document).on('submit', '.create-form', function (e) {
        e.preventDefault();

        const frm = $(this);
        const button = e.originalEvent.submitter;
        const $button = $(button); // 👈 important
        const btnText = $button.html();

        const formData = new FormData(this);

        $button
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            type: 'POST',
            url: frm.attr('action'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,

            success: function (Mess) {
                if (Mess.status === true) {
                    toastr.success(Mess.msg, Mess.header, {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true,
                        onHidden: function () {
                            window.location = Mess.url;
                        }
                    });
                } else {
                    toastr.error(Mess.msg, Mess.header, {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true,
                        onHidden: function () {
                            $button
                                .prop('disabled', false)
                                .html(btnText);
                        }
                    });
                }
            },

            error: function (xhr, status, errorThrown) {
                toastr.error(errorThrown, xhr.responseText, {
                    timeOut: 1000,
                    closeButton: true,
                    progressBar: true,
                    newestOnTop: true,
                    onHidden: function () {
                        $button
                            .prop('disabled', false)
                            .html(btnText);
                    }
                });
            }
        });
    });


});

$(document).ready(function () {
    let debounceTimeout;
    const $tagsInput = $('.tags-input');
    const $suggestions = $('#suggestions');
    $tagsInput.tagsinput({
        tagClass: 'badge badge-blue mr-2 mb-2',

    });

    // Debounce function
    /* function debounce(func, delay) {
         let timeout;
         return function (...args) {
             clearTimeout(timeout);
             timeout = setTimeout(() => func.apply(this, args), delay);
         };
     }

     // Fetch suggestions
     function fetchSuggestions(query) {
         $.ajax({
             url: '/api/suggest-tags',
             method: 'GET',
             cache: true,
             data: {query: query},
             success: function (data) {
                 if (data.suggestions.length > 0) {
                     displaySuggestions(data.suggestions);
                 } else {
                     $suggestions.empty(); // Clear suggestions if no results
                 }
             }
         });
     }

     // Display suggestions
     function displaySuggestions(suggestions) {
         $suggestions.empty();
         const suggestionItems = suggestions.map(suggestion =>
             `<div class="autocomplete-suggestion">${suggestion}</div>`
         ).join(''); // Join suggestions into a single string
         $suggestions.html(suggestionItems); // Update the DOM once
     }

     // Handle input changes
     $tagsInput.on('input', function () {
         const query = $(this).val().trim();
         if (query.length > 0) {
             debounce(fetchSuggestions, 100)(query);
         } else {
             $suggestions.empty();
         }*/
});

// Handle suggestion clicks
/* $suggestions.on('click', '.autocomplete-suggestion', function () {
	 const selectedTag = $(this).text();
	 addTag(selectedTag);
	 $tagsInput.val('').trigger('input'); // Clear input and trigger input event to refresh suggestions
	 $suggestions.empty();
 });*/

// Add tag to the input and check database
/*function addTag(tag) {
	$tagsInput.tagsinput('add', tag);

	$.ajax({
		url: '/api/check-tag', // Your API endpoint to check if the tag exists
		method: 'POST',
		data: {tag: tag},
		success: function (response) {
			if (!response.exists) {
				// Add the tag to the database
				$.ajax({
					url: '/api/add-tag', // Your API endpoint to add a new tag
					method: 'POST',
					data: {tag: tag},
					success: function () {
						console.log('Tag added to the database');
					}
				});
			}
		}
	});
}*/

// Hide suggestions on click outside
/* $(document).on('click', function (event) {
	 if (!$(event.target).closest('#tags-input').length) {
		 $suggestions.empty();
	 }
 });
});*/


$(document).on('click', '#thumbnail', function (e) {
    e.preventDefault();
    $('#image-modal').modal('show');
});
$(document).ready(function (event) {
    // Select2
    $('.select2').each(function () {
        $(this)
            .wrap('<div class="position-relative"></div>')
            .select2({
                dropdownParent: $(this).parent(),
                tags: true

            });
    });
});


$(document).ready(function (event) {

    // Daterangepicker
    $('input[name="daterange"]').daterangepicker({
        opens: 'left'
    });
    $('input[name="datetimes"], .datetimes').daterangepicker({
        timePicker: true,
        opens: 'left',
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'Y/M/DD hh:mm A'
        }
    });
    $('input[name="datesingle"] , .datesingle').daterangepicker({
        timePicker: true,
        opens: 'left',
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment(),
        locale: {
            format: 'Y-MM-DD HH:mm:ss'
        }
    });
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);
});
