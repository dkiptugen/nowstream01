$(document).on('submit', '.create-article-form', function (e)
{
	e.preventDefault();
	
	var frm = $(this);
	var formData = new FormData(this);
	
	// Get the publication status
	var publicationStatusField = frm.find('select[name="pub_status"]');
	var publicationStatus = publicationStatusField.val();
	
	if (publicationStatus === '1')
		{ // Assuming '1' is the value for "Published"
			if (!confirm('Are you sure you want to publish this article?'))
				{
					publicationStatusField.val('0'); // Set to 'Not Published' if cancelled
				}
		}
	
	// If the status is '3' (Returning), show the modal
	if (publicationStatus === '2')
		{ // Assuming '3' is the value for "Returning"
			$('#returnReasonModal').modal('show'); // Show the modal for entering the return reason
			return; // Stop form submission until reason is provided
		}
	
	// Proceed with form submission via AJAX
	submitForm(frm);
});

// Handle the submission of the return reason
$('#submitReturnReason').on('click', function ()
{
	var reason = $('#returnReason').val();
	
	if (reason.trim() === '')
		{
			alert('Please provide a reason for returning the article.');
			return;
		}
	
	// Append return reason to the formData and close the modal
	var form = $('.create-article-form')[0];
	var formData = new FormData(form);
	formData.append('return_reason', reason);
	
	$('#returnReasonModal').modal('hide'); // Hide the modal
	
	// Proceed with form submission via AJAX
	submitForm($(form), formData);
});

// Function to handle form submission via AJAX
function submitForm(frm, formData = null)
	{
		if (!formData)
			{
				formData = new FormData(frm[0]); // Rebuild formData if not provided
			}
		
		$.ajax({
			       type: 'POST',
			       url: frm.attr('action'),
			       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			       data: formData,
			       processData: false,
			       contentType: false,
			       success: function (Mess)
				       {
					       if (Mess.status === true)
						       {
							       toastr.success(Mess.msg, Mess.header, {
								       timeOut: 1000,
								       closeButton: true,
								       progressBar: true,
								       newestOnTop: true,
								       onHidden: function ()
									       {
										       window.location = Mess.url;
									       }
							       });
						       } else
						       {
							       toastr.error(Mess.msg, Mess.header, {
								       timeOut: 1000,
								       closeButton: true,
								       progressBar: true,
								       newestOnTop: true,
								       onHidden: function ()
									       {
										       window.location = Mess.url;
									       }
							       });
						       }
				       },
			       error: function (xhr, status, errorThrown)
				       {
					       toastr.error(errorThrown, xhr.responseText, {
						       timeOut: 1000,
						       closeButton: true,
						       progressBar: true,
						       newestOnTop: true,
						       onHidden: function ()
							       {
								       // window.location.reload();
							       }
					       });
				       }
		       });
	}