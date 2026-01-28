import './modules/moment'
import './modules/bootstrap'
import './modules/feather'
import './modules/font-awesome'
import './modules/sidebar'
import './modules/toastr'
import './modules/user-agent'
import './modules/apexcharts'
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
        const $input = $(this);
        const files = $input[0].files;
        const filesCount = files.length;
        const $textbox = $input.prev();
        const $progressBarContainer = $('#progressBarContainer');
        const $progressBar = $('#uploadProgressBar');
        const formName = $input.attr('name');

        $textbox.text(filesCount === 1 ? files[0].name : `${filesCount} files selected`);
        $("#err").hide().empty();
        $progressBarContainer.show();
        $progressBar.removeClass('d-none').css('width', '0%').attr('aria-valuenow', 0);

        const data = new FormData();
        Array.from(files).forEach(file => data.append(formName, file));

        $.ajax({
            url: $input.data('url'),
            type: 'POST',
            data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,
            cache: false,
            processData: false,
            xhr: function () {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $progressBar.css('width', `${percentComplete}%`);
                        $progressBar.attr('aria-valuenow', percentComplete);
                    }
                }, false);
                return xhr;
            },
            success: function (data) {
                if (data === 'invalid') {
                    $("#err").html("Invalid File!").fadeIn();
                } else {
                    $(".upload").addClass('d-none').fadeOut();
                    $("#image").attr('src', data.imageloc);
                    $("#imgname").val(data.imgname);
                    $("#size").val(data.size);
                    $("#mime").val(data.mime);
                    $("#content-preview").removeClass('d-none').addClass('d-flex').fadeIn();
                }
                $progressBar.css('width', '0%').attr('aria-valuenow', 0);
            },
            error: function (xhr) {
                let errMsg = "Upload failed.";
                if (xhr.responseJSON?.message) {
                    errMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errMsg = xhr.responseText;
                }
                $("#err").html(errMsg).fadeIn();
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
                    ['social', ['magicEmbed']],
                    ['misc', ['undo', 'redo', 'clear']],
                    ['view', ['fullscreen', 'codeview', 'help']],

                ],
                fileFetchUrl: '/api/files', // API to fetch
                                            // files
                fileUploadUrl: '/api/upload', // API to
                                              // upload files
                TEXT_NODE: {
                    onImageUpload: function (image) {

                        uploadImage(image[0], $(this));

                    }
                },
                callbacks: {
                    onPaste: function (e) {
                        e.preventDefault();

                        // Get clipboard data
                        var clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
                        var html = clipboardData.getData('text/html') || clipboardData.getData('text/plain');

                        // Debugging: Check clipboard
                        // content
                        console.log("Clipboard HTML:", html);

                        if (!html) return; // Exit if no
                                           // content

                        // Create a temporary container
                        // to parse the HTML
                        var $tempContainer = $('<div>').html(html);

                        // **1. Prevent Pasted Images**
                        $tempContainer.find('img').remove();

                        // **2. Remove all tags except
                        // allowed ones & remove
                        // attributes**
                        $tempContainer.find('p, a, h1, h2, h3, h4, h5, h6, ol, ul, li, strong, span, div')
                            .removeAttr('style')
                            .removeAttr('class');

                        // **3. Remove empty <p> and
                        // <div> tags**
                        // $tempContainer.find('p:empty, p:has(> br), div:empty').remove();

                        // **4. Get cleaned HTML content**
                        var cleanedHTML = $tempContainer.html();

                        // Debugging: Check cleaned HTML
                        console.log("Cleaned HTML:", cleanedHTML);

                        // **5. Insert cleaned HTML into
                        // the editor**
                        $(this).summernote('pasteHTML', cleanedHTML);

                        // Debugging: Confirm content was
                        // inserted correctly
                        setTimeout(() => {
                            console.log("Editor content after paste:", $(this).summernote('code'));
                        }, 500);
                    },
                    onChange: function (contents) {
                        // Debounced autosave for
                        // specific Summernote editor
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
                const formData = $('#post-form').serialize(); // Serialize
                                                              // the form
                                                              // data

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

        var frm = $(this);
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: frm.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                            // window.location = Mess.url;
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
                        //window.location.reload();
                    }
                });
            }
        });
    });


});

$(document).ready(function () {
    const $tagsInput = $('.tags-input');
    const restrictedTags = ['the star',
        'star news',
        'the star online',
        'thestaronline',
        'the star kenya',
        'thestar',
        'thestardigital',
        'the star digital',
        'star',
        'the star newspaper',
        'star news kenya',
        'the star news',
        'the star',
        'mpasho'

    ]; // Tags to block

    $tagsInput.tagsinput({
        tagClass: 'badge badge-primary mr-2',
    });

    // Listen for 'beforeItemAdd' event to restrict certain tags
    $tagsInput.on('beforeItemAdd', function (event) {
        const tag = event.item.toLowerCase().trim(); // Normalize case and trim whitespace

        if (restrictedTags.includes(tag)) {
            event.cancel = true; // Prevent the tag from being added
            alert(`The tag "${tag}" is not allowed.`);
        }
        if (tag.includes('star')) {
            event.cancel = true; // Prevent the tag from being added
            alert(`Tags containing the word "star" are not allowed.`);
        }
    });


});


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
    $('#schedule_date').daterangepicker({
        timePicker: false,
        opens: 'left',
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'Y-MM-DD'
        }
    });
    $('.starttime').datetimepicker({
        format: 'HH:mm', // 24-hour time format
        stepping: 15,    // optional minute interval
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
        }
    });
    $('.endtime').datetimepicker({
        format: 'HH:mm', // 24-hour time format
        stepping: 15,    // optional minute interval
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
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
$(document).ready(function () {

    const toggleBtn = $("#theme-toggle");

    function applyTheme(theme) {
        $('html').attr("data-theme", theme);
        localStorage.setItem("theme", theme);

        $('body').toggleClass('dark-mode', theme === "dark");
    }

    // Load saved theme or use system preference
    let saved = localStorage.getItem("theme");

    if (!saved) {
        const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        saved = prefersDark ? "dark" : "light";
    }

    applyTheme(saved);

    // Toggle
    toggleBtn.on("click", function (e) {
        e.preventDefault();
        console.log("Theme toggle clicked");

        const newTheme = $('body').hasClass('dark-mode') ? 'light' : 'dark';
        applyTheme(newTheme);
    });

});