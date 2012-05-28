$(document).ready(function() {
	
	$('.leadrow').click(function(e) {
		var $row = $(this);
		showLeadDetails($row.data('leadid'));
		$('.leadrow').removeClass('active');
		$row.addClass('active');
		// Position the detail box
		var fromBottom = $row.offsetParent().outerHeight() - $row.position().top;
		$detailPanel = $('#panel_leads_view_details').css('top',$row.position().top-27);
		if (fromBottom < 500) {
			$detailPanel.css('margin-bottom', $row.offsetParent().outerHeight()-fromBottom);
		}
		scrollToLead($row);
	});
	
	$('#btn_leads_detail_edit').live('click', editLead);
	
	$('#btn_leads_detail_delete').live('click', function(e) {
		if (confirm('Are you sure you want to delete this lead? This will NOT delete it from CPOWER, and cannot be undone')) {
			deleteLead($('#panel_leads_view_details').data('leadid'));
		}
	});
	
	$('#btn_leads_detail_save').live('click', function(e) {
		var leadID = $('#panel_leads_view_details').data('leadid');
		var $form = $('#leaddetails_editing');
		$.post(SITE_URL + 'leads/editLead/' + leadID, $form.serialize(), function(response) {
			if (typeof(response) != 'object') {
				return alert('Unknown error saving lead details');
			}
			if (response.status != 'success') {
				return alert(response.message);
			}
			// Reflect the changes in the list
			var newDetails = new Array();
			newDetails[0] = $('#name_first',$form).val() + ' ' + $('#name_last',$form).val();
			newDetails[1] = $('#address_street',$form).val();
			newDetails[2] = $('#phone_home',$form).val();
			newDetails[3] = $('#type option:selected',$form).text();
			newDetails[4] = $('#status option:selected',$form).text();
			newDetails[5] = $('#grade option:selected',$form).text();
			$('.leadrow:data(leadid,'+leadID+')').children().each(function(i){
				$(this).text(newDetails[i]);
			});
		
			
			// Reload the details panel
			showLeadDetails($('#panel_leads_view_details').data('leadid'));
			
		});
	});
	
	$('#btn_wp_lookup').live('click', function(e){
		showModalPopup('whitepages_result');
	});
	$('#btn_log_call').live('click', logCall);
	$('#btn_savetocpower').live('click', saveToCpower);
	
	/*
	// Semi-fixed detailview
	var $detailView = $('#panel_leads_view_details');
	var margin_top = parseInt($detailView.css('margin-top'));	var scrollerTopMargin = $detailView.offset().top + margin_top;
	
	$(window).scroll(function(){
		var margin_left = $detailView.position().left;
		var c = $(window).scrollTop()+margin_top;
		var d = $('#panel_leads_view_details');
		if (c > scrollerTopMargin-margin_top-8) {
			d.css({ position: 'fixed', top: margin_top+'px', left: margin_left   });
		} else if (c <= scrollerTopMargin) {
			d.css({ position: 'relative', top: '', left: ''   });
		}
	}).scroll();
	*/
	
	// Get the baseline offset().top for 
	
	// Test to see if we should be looking at a lead's details
	var hash = document.location.hash.substring(1);
	if (hash.length) {
		var $row = $('.leadrow:data(leadid,'+hash+')').click();
		//log($row);
		//showLeadDetails(hash);
	}
	
	$('.submissionrow .button.delete').click(deleteSubmission);
});

function showLeadDetails(leadID)
{
	// Show loading message
	var $detailPanel = $('#panel_leads_view_details').empty().append('<h3>Loading</h3>').data('leadid', leadID);
	
	// Send off the request
	$.post(SITE_URL + 'leads/getDetails/' + leadID, function(response) {
		if (typeof(response) == 'object') {
			$detailPanel.append('<p>'+response.message+'</p>');
			$('h3',$detailPanel).text('Ajax Error');
			return false;
		}
		if ($('body').hasClass('ie')) {
			$detailPanel.replaceWith(response);
		} else {
			$detailPanel.empty().append($(response).contents());
		}
		codeAddress($('#address_street').text() + ' ' + $('#franchise_location').val());
		Quill.currentClient = {
			'id': leadID,
			'name': $('#client_name',$detailPanel).text()
		}
	});
}

function createStreetView(latLng) {
  // Note: constructed panorama objects have visible: true
  // set by default.
  var panoOptions = {
    position: latLng,
    addressControl: false,
    linksControl: false,
    panControl: false,
    zoomControl: false,
    enableCloseButton: false
  };
 
  var panorama = new google.maps.StreetViewPanorama(document.getElementById('detailview_map'), panoOptions);
}

function codeAddress(address)
{
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address, 'region':'CA' }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			createStreetView(results[0].geometry.location);
		} else {
			$('#detailview_map').text('Address is no good');
		}
	});
}

function editLead(callback_load)
{
	var leadID = $('#panel_leads_view_details').data('leadid');
	$.get(SITE_URL + 'leads/getEditForm/' + leadID, function(response) {
		// Hide static details, show edit form
		var $staticTable = $('.leaddetails').hide().after(response);
		// Hide edit button, show Cancel
		var $editButton = $('#btn_leads_detail_edit').hide().after('<button id="btn_leads_detail_save">Save</button><button id="btn_leads_detail_cancel">Cancel</button>');
		$('#btn_leads_detail_delete').hide();
		$('#btn_leads_detail_cancel').click(function(e) {
			$('#leaddetails_editing').remove();
			$staticTable.show();
			$(this).remove();
			$('#btn_leads_detail_save').remove();
			$editButton.show();
			$('#btn_leads_detail_delete').show();
		});
		// Fire callback
		if (typeof(callback_load) == 'function') callback_load();
	});
}

function deleteLead(leadID)
{
	$.post(SITE_URL + 'leads/deleteLead', {	'leadID':leadID	}, function(response) {
		if (typeof(response) == 'object' && response.status == 'success') {
			$('.leadrow:data(leadid,'+leadID+')').remove();
			$('#panel_leads_view_details').html('<h3 style="margin-top:.7em;">Select a lead for more details</h3>');
		} else {
			alert('Error deleting lead. Send a feedback message.');
		}
	});
}

function reverseAddress()
{
	var address = $('#leaddetails_editing').length? $('input#address_street').val() : $('span#address_street').text();
	$('#wp_result_container').load(SITE_URL + 'leads/reverseAddress/' + escape(address.replace('/',' ')),function(){
		$(this).slideDown();
	});
}

function reversePhone()
{
	var phone = $('#leaddetails_editing').length? $('input#phone_home').val() : $('span#phone_home').text();
	$('#wp_result_container').load(SITE_URL + 'leads/reversePhone/' + phone.replace(/\D/g,''),function(){
		$(this).slideDown();
	});
}

function activateWhitepagesResult()
{
	var updateLeadForm = function() {
		$('#modalwindow input:checked').each(function(index) {
			var field = $(this).data('field'); 
			var value = $('#modalwindow td:data(field,'+field+')').text();
			// Update the form fields
			$('#panel_leads_view_details input#'+field).val(value);
		});
	}
	$('#btn_fix_whitepages_result').click(function(){
		if ($('#leaddetails_editing').length) {
			updateLeadForm();
		} else {
			editLead(updateLeadForm);
		}
		hideModalPopup();
	});
	var $radioButtons = $('#modalwindow .radio.button');
	$radioButtons.click(function(e){
		if ($(this).hasClass('active')) return false;
		$radioButtons.removeClass('active');
		$(this).addClass('active');
		$('#wp_result_container').slideUp();
		if ($(this).hasClass('address')) {
			// Lookup by address
			reverseAddress();
		} else {
			// Lookup by phone
			reversePhone();
		}
	});
	// Start with phone
	reversePhone();
}

function saveToCpower()
{
	var leadID = $('#panel_leads_view_details').data('leadid');
	if (!leadID || $('#btn_savetocpower').hasClass('disabled')) return false;
	if (!confirm('Are you sure? This action cannot be undone')) return false;
	$.post(SITE_URL + 'opencpro/addLead/' + leadID, function(response) {
		if (typeof(response) != 'object') {
			return alert('An unknown error occurred. Please report this.');
		}
		if (response.status != 'success') {
			return alert(response.message);
		}
		// Success; reload lead detail panel
		showLeadDetails(leadID);
	});
}

function logCall()
{
	var leadID = $('#panel_leads_view_details').data('leadid');
	showModalPopup('log_call', leadID, 'grande');
}

function deleteSubmission(e)
{
	var $button = $(this);
	if ( ! confirm('Are you sure? This will affect payroll calculation, and cannot be undone')) return false;
	var submissionID = $button.data('submissionid');
	$.post(SITE_URL + 'leads/deleteSubmission', { submission_id: submissionID }, function(response){
		if (typeof response != 'object') {
			return alert(response);
		}
		if (response.status != 'success') {
			return alert(response.message);
		}
		$button.parents('tr.submissionrow').slideUp(function(){ $(this).remove() }).next('tr.norecords').slideUp(function(){ $(this).remove() });
	});
}

function saveToCpower()
{
	var leadID = $('#panel_leads_view_details').data('leadid');
	if (!leadID || $('#btn_savetocpower').hasClass('disabled')) return false;
	if (!confirm('Are you sure? This action cannot be undone')) return false;
	$.post(SITE_URL + 'opencpro/addLead/' + leadID, function(response) {
		if (typeof(response) != 'object') {
			return alert('An unknown error occurred. Please report this.');
		}
		if (response.status != 'success') {
			return alert(response.message);
		}
		// Success; reload lead detail panel
		showLeadDetails(leadID);
	});
}