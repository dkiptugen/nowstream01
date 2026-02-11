import './modules/moment'
import './modules/bootstrap'
import './modules/feather'
import './modules/font-awesome'
import './modules/sidebar'
import './modules/toastr'
import './modules/user-agent'
import './modules/chartjs'
import './modules/datatables'
import './modules/select2'
import './modules/daterangepicker'
import './modules/datetimepicker'
import './modules/validation'
import './modules/wizard'
import './modules/summernote'
import './modules/tagsinput'

import './modules/dropzone'





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


        if (editor.length) {

            editor.summernote({


                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                fontSizeUnits: ['px', 'pt'],
                placeholder: editor.data('placeholder'),
                airMode: false,
                height: 100,
                tabsize: 2,
                lineHeight: 1.3,
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


                },
                styleTags: [
                    'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
                ],
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'caseConverter']],
                    ['font', ['superscript', 'subscript', 'fontsize']],
                    ['para', ['ul', 'ol', 'paragraph', 'style', 'height', 'color']],
                    ['insert', [ 'table']],
                    ['custom', ['imageLibrary', 'audioLibrary', 'videoLibrary', 'fileLibrary']],
                    ['misc', ['undo', 'redo', 'clear']],
                    ['view', ['fullscreen', 'codeview', 'help']]

                ],
                TEXT_NODE: {
                    onImageUpload: function (image) {

                        uploadImage(image[0], $(this));

                    }
                }


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


});



$(document).on('click', '#thumbnail', function (e) {
    e.preventDefault();
    $('#image-modal').modal('show');
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
