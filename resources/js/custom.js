$(document).ready(function(){
    $(document).on('submit','.savemedia',function(e){
        e.preventDefault();
        var frm = $(this);
        $.ajax({
            type:'POST',
            url:frm.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:$(this).serialize(),
            success:function(Mess){
               if(Mess.error === 0)
                   {
                       console.log(Mess);
                       var prev =   $('.preview');
                       $('#image-modal').modal('hide');
                        $('.upload').removeClass('d-none');
                        $('#preview').addClass('d-none');
                       $('#media_id').val(Mess.media_id);
                       prev.html('<img src="'+prev.data('url')+'/'+Mess.image+'" class="img-fluid" id="thumbnail" />');
                       frm.trigger('reset');



                   }
            },
            error:function (xhr, status, errorThrown) {

                console.log(errorThrown);



            }
        });

    });
    $(document).on('click','.delete',function(s){
        s.preventDefault();
        var link    =   $(this).attr('href');
        $.ajax({
            url: link,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(Mess) {
                if (Mess.status == true) {

                    toastr.success(Mess.msg, Mess.header, {
                        timeOut: 1000,
                        closeButton: true,
                        progressBar: true,
                        newestOnTop: true,
                        onHidden: function () {
                            //window.location = Mess.url;
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
            error: function(request,msg,error) {

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
    var editor  =   $('.editor');
    var summary =   $('#summary');
    var code    =   $('.code');
    if(summary.length)
        {
            summary.summernote({
                placeholder: "Summary",
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']]
                ]
            });
        }

    if(editor.length)
        {
            editor.summernote({
                height:150,
                tabsize: 2,
                lineHeight:1.5,
                dialogsInBody: true,
                dialogsFade: false,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript','fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph','style']],
                    ['height', ['height']],
                    ['insert',['picture','link','video','table','hr']],
                    ['misc',['codeview','undo','redo']]
                ],
                callbacks : {
                    onImageUpload: function(image) {

                        uploadImage(image[0],$(this));

                    }
                }

            }).on('summernote.change', function (we, contents, $editable) {
                $(this).val(contents);
            });
        }
    if(code.length) {
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
        $(code.closest("form")).on("submit",function(e){
            if (code.summernote('codeview.isActivated')) {
                code.val(code.summernote());
                console.log(code.summernote('code'));
                return true;
            }
            return true;
        });
    }
    function uploadImage(image,$summernote)
        {
            var dat = new FormData();
            dat.append("images",image);
            var IMAGE_PATH = 'https://www.now.co.ke/uploads/';
            $.ajax ({
                data: dat,
                type: "POST",
                url:  'https://www.now.co.ke/now/api/image_upload',
                headers: {"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr('content')},
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    var image = IMAGE_PATH+$.trim(url.imgname);
                    $summernote.summernote("insertImage", image,function ($image) {
                        $image.attr('class', 'image-fluid w-100');
                    });
                },
                error: function(e) {
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
    $(document).on('submit','.create-form',function(e){
        e.preventDefault();
        var frm = $(this);
        var formData = new FormData(this);  // Use FormData to handle file uploads
        $.ajax({
            type: 'POST',
            url: frm.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: formData,
            contentType: false,  // Prevent jQuery from setting the Content-Type
            processData: false,
            success:function(Mess){
                if(Mess.status === true)
                    {

                        toastr.success( Mess.msg,Mess.header, {timeOut: 1000, closeButton:true, progressBar:true, newestOnTop:true, onHidden: function () {

                                window.location= Mess.url;
                            }});


                    }
                else
                    {
                        toastr.error( Mess.msg,Mess.header, {timeOut: 1000, closeButton:true, progressBar:true, newestOnTop:true, onHidden: function () {

                                window.location= Mess.url;
                            }});
                    }
            },
            error:function (xhr, status, errorThrown) {

                toastr.error(errorThrown, xhr.responseText, {timeOut: 1000, closeButton:true, progressBar:true, newestOnTop:true,onHidden: function () {
                        window.location.reload();
                    }});



            }
        });

    });
    $('.tags-input').tagsinput({
        tagClass: function(item) {
            return (item.length > 10 ? 'badge-info mr-1' : 'mr-1 badge-info');
        },
        confirmKeys: [13, 44],
        trimValue: true,
        onTagExists: function(item, $tag) {
            $tag.hide().fadeIn();
        }
    });
});
document.addEventListener("DOMContentLoaded", function(event) {
    // Select2
    $('.select2').each(function() {
        $(this)
            .wrap('<div class="position-relative"></div>')
            .select2({
                placeholder: 'Select value',
                dropdownParent: $(this).parent()
            });
    })
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
    $('input[name="datetimesingle"] , .datetimesingle').daterangepicker({
        timePicker: true,
        opens: 'left',
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().startOf('hour'),
        locale: {
            format: 'Y-MM-DD HH:mm:ss'
        }
    });
    $('input[name="datesingle"] , .datesingle').daterangepicker({
        timePicker: true,
        opens: 'left',
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().startOf('hour'),
        locale: {
            format: 'Y-MM-DD'
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

$(document).on('click','#thumbnail',function (e){
    e.preventDefault();
    $('#image-modal').modal('show');
});
$(document).on('change', '.file-input', function() {


    var filesCount = $(this)[0].files.length;

    var textbox = $(this).prev();

    if (filesCount === 1) {
        var fileName = $(this).val().split('\\').pop();
        textbox.text(fileName);




    }
    else {
        textbox.text(filesCount + ' files selected');
    }
    var data    =   new FormData();

    $.each($(this)[0].files, function (obj, v) {

            data.append('images', v);

    });
    //console.log(data);
    $.ajax({
        url: $(this).data('url'),
        type: "POST",
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,
        processData: false,

        success: function (data) {
            if (data === 'invalid') {
                // invalid file format.
                $("#err").html("Invalid File !").fadeIn();
            } else {
                console.log(data);
                // view uploaded file.
                $(".upload").addClass('d-none').fadeOut();
                $("#image").attr('src',data.imageloc);
                $("#imgname").val(data.imgname);
                $("#preview").removeClass('d-none').fadeIn();

            }
        },
        error: function (e) {
            $("#err").html(e).fadeIn();
        }
    });
});
