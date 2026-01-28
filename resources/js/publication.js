// Store form and FormData globally for reuse
let cachedForm = null;
let cachedFormData = null;

$(document).on('submit', '.create-article-form', function (e)
{
	e.preventDefault();
	
	cachedForm = $(this);
	cachedFormData = new FormData(this); // Store original FormData
	
	let pubStatus = cachedForm.find('select[name="pub_status"]').val();
	
	if (pubStatus === '1') {
		if (!confirm('Are you sure you want to publish this article?')) {
			cachedForm.find('select[name="pub_status"]').val('0');
		}
	}
	
	if (pubStatus === '2') {
		$('#returnReasonModal').modal('show');
		return;
	}
	
	submitForm(cachedForm, cachedFormData);
});

$('#submitReturnReason').on('click', function () {
	let reason = $('#returnReason').val().trim();
	
	if (reason === '') {
		alert('Please provide a reason for returning the article.');
		return;
	}
	
	// Append reason to previously cached FormData
	cachedFormData.append('return_reason', reason);
	
	$('#returnReasonModal').modal('hide');
	
	// Now submit with the originally captured form + data
	submitForm(cachedForm, cachedFormData);
});

// Function to handle form submission via AJAX
function submitForm (frm, formData = null)
	{
		if(!formData)
			{
				formData = new FormData(frm[0]); // Rebuild formData if not provided
			}
		
		$.ajax({
			       type       : 'POST',
			       url        : frm.attr('action'),
			       headers    : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			       data       : formData,
			       processData: false,
			       contentType: false,
			       success    : function (Mess)
				       {
					       if(Mess.status === true)
						       {
							       toastr.success(Mess.msg, Mess.header, {
								       timeOut    : 1000,
								       closeButton: true,
								       progressBar: true,
								       newestOnTop: true,
								       onHidden   : function ()
									       {
										       window.location = Mess.url;
									       }
							       });
						       }
					       else
						       {
							       toastr.error(Mess.msg, Mess.header, {
								       timeOut    : 1000,
								       closeButton: true,
								       progressBar: true,
								       newestOnTop: true,
								       onHidden   : function ()
									       {
										       window.location = Mess.url;
									       }
							       });
						       }
				       },
			       error      : function (xhr, status, errorThrown)
				       {
					       toastr.error(errorThrown, xhr.responseText, {
						       timeOut    : 1000,
						       closeButton: true,
						       progressBar: true,
						       newestOnTop: true,
						       onHidden   : function ()
							       {
								       // window.location.reload();
							       }
					       });
				       }
		       });
	}