import * as $ from "jquery";
import "bootstrap";
import "jquery-ui";

const ImageLibraryButton = function (context)
	{
		const ui = $.summernote.ui;
		const $editor = $(context.layoutInfo.editor[0]);
		
		const uploadUrl = $editor.prev().data('uploadurl') || '/endpoint/media/image/image/summernote-upload';
		const imageFetchUrl = $editor.prev().data('imagefetch') || '/endpoint/media/image/fetch';
		const multiupload   = $editor.prev().data('multiupload') || '/endpoint/media/image/multiupload';
		
		const button = ui.button({
			                         contents: '<i class="fas fa-images"></i>',
			                         tooltip : 'Image Library',
			                         click   : function ()
				                         {
					                         const $modal = $(
						                         `<div class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Image Library</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-tabs" id="imageLibraryTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload" role="tab">Upload</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="select-tab" data-toggle="tab" href="#select" role="tab">Select</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link" id="select-tab" data-toggle="tab" href="#multiupload" role="tab">Multi Upload</a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3">
                                    <div class="tab-pane fade show active" id="upload" role="tabpanel">
                                        <form class="uploadImageForm" method="post" action="${uploadUrl}" enctype="multipart/form-data">
                                            <div class="form-group"><label>Title:</label><input type="text" name="title" class="form-control"/></div>
                                            <div class="form-group"><label>Caption:</label><input type="text" name="caption" class="form-control"/></div>
                                            <div class="form-group"><label>Byline:</label><input type="text" name="byline" class="form-control"/></div>
                                            <div class="form-group"><label>Keywords:</label><input type="text" name="keywords" class="form-control tagsinput"/></div>
                                            <div class="form-group"><label>Upload Image:</label><input type="file" name="image" class="form-control-file"/></div>
                                             <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="ai_content"
                                       value="1" >
                                <span class="custom-control-label">AI Content</span>
                            </label>
                        </div>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="select" role="tabpanel">
                                        <input type="text" id="imageSearch" class="form-control mb-3" placeholder="Search images..."/>
                                        <div id="imageLoader" class="text-center" style="display:none;"><div class="spinner-border" role="status"></div></div>
                                        <div id="imageList" class="d-flex flex-wrap" style="max-height: 400px; overflow-y: auto;"></div>
                                    </div>
                                    <div class="tab-pane fade" id="multiupload" role="tabpanel">
                                         <div class="upload-wrapper bg-transparent h-100">
				                            <div class="container d-flex justify-content-center h-100 ">
				                                <div class=" row align-items-center my-2 h-100">
				                                    <div class="col-md">
				                                        <div class="file-drop-area">
				                                            <span class="choose-file-button">Click here to select file</span>
				                                            <span class="file-message">or drag and drop file here</span>
				                                            <input class="file-input" type="file" name="image" data-url="" accept="image/*" multiple>
				                                        </div>
				                                        <div id="progressBarContainer" class="mt-2">
				                                            <div class="progress d-none">
				                                                <div id="uploadProgressBar" class="progress-bar" role="progressbar"
				                                                     style="width: 0;" aria-valuenow="0" aria-valuemin="0"
				                                                     aria-valuemax="100"></div>
				                                            </div>
				                                        </div>
				                                        <div id="err" class="text-danger"></div>
				                                        
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>`
					                         );
					                         
					                         $modal.appendTo('body').modal('show');
					                         
					                         $modal.one('submit', '.uploadImageForm', function (e)
					                         {
						                         e.preventDefault();
						                         const formData = new FormData(this);
						                         
						                         $.ajax({
							                                url        : uploadUrl,
							                                type       : 'POST',
							                                headers    : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
							                                data       : formData,
							                                processData: false,
							                                contentType: false,
							                                success    : function (response)
								                                {
									                                insertImage(response.imageloc, response.caption);
									                                closeModal();
								                                },
							                                error      : () => alert('Image upload failed. Please try again.')
						                                });
					                         });
					                         
					                         const fetchImages = (page = 1, query = '') =>
						                         {
							                         $('#imageLoader').show();
							                         return $.ajax({
								                                       url    : imageFetchUrl,
								                                       type   : 'GET',
								                                       data   : {search: query, page},
								                                       success: function (response)
									                                       {
										                                       $('#imageLoader').hide();
										                                       const images = response.images?.data || [];
										                                       images.forEach(image =>
										                                                      {
											                                                      const img = $('<img>').attr('src', `https://cms.eu-central-1.linodeobjects.com/${image.url}`).css({
												                                                                                                                                                        maxWidth: '100px',
												                                                                                                                                                        margin  : '5px',
												                                                                                                                                                        cursor  : 'pointer'
											                                                                                                                                                        });
											                                                      
											                                                      img.on('click', () =>
											                                                      {
												                                                      insertImage(img.attr('src'), image.caption);
												                                                      closeModal();
											                                                      });
											                                                      
											                                                      $('#imageList').append(img);
										                                                      });
										                                       $('#select-tab').data('page', page);
									                                       },
								                                       error  : () => $('#imageLoader').hide()
							                                       });
						                         };
					                         
					                         const insertImage = (src, captionText = 'caption') =>
						                         {
							                         const range = context.invoke('editor.createRange');
							                         if(!range) return;
							                         
							                         range.deleteContents();
							                         const figure = $('<figure>').css('margin', '10px 0');
							                         const img = $('<img>').attr('src', src).css({
								                                                                     maxWidth: '100%',
								                                                                     width   : '100%'
							                                                                     });
							                         const caption = $('<figcaption contenteditable="true" class="caption">')
								                         .text(captionText).css('text-align', 'center');
							                         
							                         img.draggable({
								                                       revert: 'invalid',
								                                       cursor: 'move',
								                                       helper: 'clone'
							                                       });
							                         
							                         figure.append(img).append(caption);
							                         const newParagraph = document.createElement('p');
							                         newParagraph.innerHTML = '<br>';
							                         
							                         range.insertNode(figure.get(0));
							                         figure.after(newParagraph);
							                         
							                         const newRange = document.createRange();
							                         newRange.setStart(newParagraph, 0);
							                         newRange.collapse(true);
							                         
							                         const sel = window.getSelection();
							                         sel.removeAllRanges();
							                         sel.addRange(newRange);
							                         
							                         context.invoke('editor.focus');
						                         };
					                         
					                         $('#imageSearch').on('keyup', function ()
					                         {
						                         $('#imageList').empty();
						                         fetchImages(1, $(this).val());
					                         });
					                         
					                         $('#imageList').off('scroll').on('scroll', function ()
					                         {
						                         const $list = $(this);
						                         if($list.scrollTop() + $list.innerHeight() >= $list[0].scrollHeight - 10)
							                         {
								                         const nextPage = ($('#select-tab').data('page') || 1) + 1;
								                         fetchImages(nextPage, $('#imageSearch').val());
							                         }
					                         });
					                         
					                         $('#select-tab').one('shown.bs.tab', function ()
					                         {
						                         $('#imageList').empty();
						                         fetchImages();
					                         });
					                         
					                         function closeModal ()
						                         {
							                         $modal.modal('hide').remove();
							                         $('.modal-backdrop').remove();
							                         $('body').removeClass('modal-open');
						                         }
				                         }
		                         });
		
		return button.render();
	};

$.fn.extend({
	            insertImageLibrary: function (context)
		            {
			            return ImageLibraryButton(context);
		            }
            });

$.extend($.summernote.plugins, {
	'imageLibrary': function (context)
		{
			context.memo('button.imageLibrary', function ()
			{
				return ImageLibraryButton(context);
			});
		}
});
