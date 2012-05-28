$(document).ready(function() {
	// Create the datepicker and set it to today
	$('#submission_date').datepicker({	dateFormat:'d-M-yy'	}).datepicker('setDate','+0');
	
	
	// Populate time fields
	for (var i=1; i<=12; i++) {
		$("#time_start_hour, #time_end_hour").append("\n"+'<option value="'+i+'">'+i+'</option>');
	}
	
	for (var i=0; i<60; i+=15) {
		$("#time_start_minute, #time_end_minute").append("\n"+'<option value="'+i+'">'+zeroPad(i,2)+'</option>');
	}
	
	$('#btn_submit_leads').click(submitLeadsForm);
	
	// Activate the first form
	attachFormEvents($('.leadform'));
});

function attachFormEvents($form){
	$('input', $form).focus(function(){
		// If this is the last row and it is blank, add another row
		if ($form.is('.leadform:not(.first)') && !isBlankRow($form.prev()) && !isBlankRow($form.next('.leadform'))) {
			addLeadForm();
		}
		if ($form.is('.leadform.first') && !isBlankRow($form.next()) && isBlankRow($form)) {
			addLeadForm();
		}
	});
	$('form',$form).validate({
		rules: {
			lastname: {	required:true	},
			phone: {	required:true	},
			address: {	required:true	}
		},
		messages: {
			lastname: {	required:'Last name is required'	},
			phone: {	required:'Phone number is required'	},
			address: {	required:'Address is required'	}
		}
	});
}

function isBlankRow($form)
{
	if (!$form.is('.leadform')) return false;
	var blank = true;
	$('input',$form).each(function(i, el) {
		if ($(this).val().length) return blank = false;
	});
	return blank;
}

function addLeadForm()
{
	var $firstForm = $('.leadform.first');
	var $newForm = $firstForm.clone().hide().insertAfter('.leadform:last').css({
		height:0,
		marginTop:0,
		marginBottom:0,
		opacity: 0
	}).removeClass('first').show();
	$('input',$newForm).val('').removeClass('invalidField');
	$newForm.animate({
			height: $firstForm.height(),
			marginTop: $firstForm.css('marginTop'),
			marginBottom: $firstForm.css('marginBottom'),
			opacity: 1.0
		}, 1000);
	attachFormEvents($newForm);
}

function removeLeadForm($form)
{
	$form.animate({
			height: 0,
			marginTop: 0,
			marginBottom: 0,
			opacity: 0
		}, 1000, function() {
			$form.remove();
	});
}

function submitLeadsForm() {
	// Validation
	var $errorMessage = $('#leads_error_message');
	var $dateField = $('#submission_date');
	if (!$dateField.val().length) {
		$errorMessage.text('You must enter a submission date').flash(3000);
		return false;
	}
	var postData = {
		date: Math.round($dateField.datepicker('getDate').getTime()/1000), 
		time_start_hour: parseInt($('#time_start_hour').val()),
		time_start_minute: parseInt($('#time_start_minute').val()),
		time_end_hour: parseInt($('#time_end_hour').val()),
		time_end_minute: parseInt($('#time_end_minute').val()),
		leads: []
	};
	// Make sure time is logical
	postData.time_start_hour += ($('#time_start_ampm').val() == 'pm')? 12 : 0;
	postData.time_end_hour += ($('#time_end_ampm').val() == 'pm')? 12 : 0;
	
	var duration = (postData.time_end_hour + postData.time_end_minute / 60) - (postData.time_start_hour + postData.time_start_minute/60);
	if (duration <= 0) {
		$errorMessage.text('The duration must be greater than zero').flash(3000);
		return false;
	}
	
	var error = false;
	$('.leadform').each(function(index){
		var $row = $(this);
		if(!isBlankRow($row)){
			if (!$('form',$row).valid()) {
				error = true;
			}
			postData.leads[postData.leads.length] = {
				firstname: $('input[name=firstname]',$row).val(),
				lastname: $('input[name=lastname]',$row).val(),
				phone: $('input[name=phone]',$row).val(),
				address: $('input[name=address]',$row).val(),
				email: $('input[name=email]',$row).val(),
				notes: $('input[name=notes]',$row).val(),
				phone_other: $('input[name=phone_other]',$row).val(),
				postalcode: $('select[name=postalcode]',$row).val(),
				postalcode2: $('input[name=postalcode2]',$row).val(),
				jobtype: $('select[name=jobtype]',$row).val()
			};
		} else {
			if (!$row.is('.first')) $row.slideUp();
		}
	});
	if (error) return false;
	if (!postData.leads.length && !confirm(duration + ' hours and no leads? Are you sure?')) return false;
	
	postData.leads = JSON.stringify(postData.leads);

	$.post(SITE_URL + 'leads/ajax_submit', postData, function(response){
		if (typeof(response) == 'object' && response.status == 'success') {
			// remove the extra lead rows
			$('.leadform:not(.first)').remove();
			// Clear all fields
			$('#submission_metadata input, .leadform input').val('');
			alert('Leads Submitted Successfully');
		} else if (typeof(response) != 'object') {
			alert('Something unexpected happened. Try again, and send a feedback message about it.');
		} else {
			alert(response.message);
		}
	});
}