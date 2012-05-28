/*	 author: Kelly Banman
	updated: 27-Sep-2010
	purpose: Global scripts for ProjectSanta App
	  usage: Included in all the root-level views
*/
// Constants

function hideModalPopup(sel)
{
	$('#modalwindow').hide().removeClass().addClass('popup');
	$('#mask').fadeOut(200);
}

function showModalPopup(sel, data, size)
{
	
	var $mask = $('#mask');
	// Re-Size the mask
	$mask.fadeIn(100, function() {
		// IE transparency fix
		$mask.css({
			'filter': 'alpha(opacity=60)',
			'height': $(document).height()+'px'
		});
	});
	// Attach the data
	if (data) {
		$('#modalwindow').data('popup', data);
	}
	if (sel) {
		$('#modalwindow').load(SITE_URL + 'modal/popup/' + sel, function() {
			$(this).fadeIn(100);
		}).addClass(sel).addClass(size);
	}
}

function minisearch(query)
{
	if (!query.length) return false;
	query = query.replace( /[!@#$%^&*()-+]/g, '');
	$.get(SITE_URL + 'search/mini/' + query, function(response) {
		if (typeof(response) != 'object' || !response.data.length) {
			$('#minisearch_resultlist').text('No results found');
		}
		$('#minisearchResultsTemplate').tmpl(response.data).appendTo('#minisearch_resultlist');
		showSearchResults();
	}, 'json');
	showSearchLoading();
}

function showSearchLoading()
{
	$('#minisearch_results').show();
	$('#minisearch_loading').slideDown(300);
	$('#minisearch_resultlist').empty();
}

function showSearchResults()
{
	$('#minisearch_results').show();
	$('#minisearch_loading').css('visibility','hidden').slideUp(500);
	$('#minisearch_results').animate({ width:'340px' }, 300, function() {
		$('#minisearch_resultlist').slideDown(300);
	});
	$('.maincontent').bind('click', hideSearchResults);
}

function hideSearchResults()
{
	$('#minisearch_resultlist').slideUp(150);
	$('#minisearch_results').fadeOut(150);
	$('.maincontent').unbind('click', hideSearchResults);
}

function activateNewLeadForm()
{
	var $form = $('#newlead-form');
	$('#altnamecheck').bind('change',function(e) {
		if (this.checked) {
			$('#altnamerow').show();
		} else {
			$('#altnamerow').hide();
		}
	});
	$('#otherphonecheck').bind('change',function(e) {
		if (this.checked) {
			$('#otherphonerow').show();
		} else {
			$('#otherphonerow').hide();
		}
	});
	$('#phone_home, #phone_mobile, #phone_other').mask('(999) 999-9999',{placeholder:'  '});
	$('#address_postalcode').mask('a9a 9a9',{placeholder:' ', completed:function(){
		log('test',this.val().toUpperCase());
		this.val(this.val().toUpperCase());
	}});
	
	$form.bind('submit', function(e) {
		if (!$('#newlead-form').valid()) return false;
		// Submit via ajax
		$.post(SITE_URL + 'clientAction/create', 
			$('#newlead-form').serialize(), 
			function(response) {
				if (!response || !response.status) {
					alert(response);
					return;
				}
				if (response.status != 'success') {
					log(response);
					if (response.message == 'validation') {
						// Add an error label to each bad field
						$.each(response.data, function(i,error){
							var $field = $('#'+error.field);
							var message = $field.attr('title')+' needs fixing';
							var $existing = $('#'+error.field+' + label.error');
							if ($existing.length > 0) $existing.text(message);
							else $('<label />').attr('for',error.field).addClass('error').text(message).insertAfter($field);
						});
					}
					return;
				}
				// Success
				$('.popup.newlead-form').load(SITE_URL + 'modal/popup/newlead-form_success').data('popup', response.data);
			}, 
			'json'
		);//post
		
		return false;
	});
	$form.validate({
		rules: {
			name_first: {	required:true	},
			name_last: {	required:true	},
			address_street: {	required:true	},
			address_city: {	required:true	},
			phone_home: {	required:true, phone:true	},
			phone_mobile: { phone:true	},
			phone_other: { phone:true	},
			email: {	email:true	}
		},
		messages: {
			name_first: {	required: 'First Name is required'	},
			name_last: {	required:'Last Name is required'	},
			address_street: {	required:'Street Address is required'	},
			address_city: {	required:'City is required'	},
			phone_home: {	required:'Home phone is required', phone:'Must be a 7-digit phone number'	},
			phone_mobile: { phone:'Must be a 7-digit phone number'	},
			phone_other: { phone:'Must be a 7-digit phone number'	},
			email: {	email:'Please enter a valid email'	}
		}
	});
}

function activateNewLeadSuccess()
{
	// Get the clientID from popup data
	var popupData = $('#modalwindow').data('popup');
	
	$('.newlead-form.popup .done').click(function(e) {
		// reload the recent leads list
		$.get(SITE_URL + 'overview/panel_leads', function(data) {
			$('#panel_recent_leads').replaceWith($(data));
		});
		hideModalPopup('newlead-form');
	});
	$('.newlead-form.popup .addanother').click(function(e) {
		$('#modalwindow').load(SITE_URL + 'modal/popup/newlead-form');
	});
	
	$('.popup #newlead_enterjob').click(function(e) {
		showModalPopup('new_job', popupData);
	});
}

function activateNewUserForm()
{
	var $form = $('#newuser-form');
	$('#altnamecheck').bind('change',function(e) {
		if (this.checked) {
			$('#altnamerow').show();
		} else {
			$('#altnamerow').hide();
		}
	});
	$('#specify_password').bind('change',function(e) {
		if ($(this).is(':checked')) {
			$('ul.passwordrow',$form).slideDown();
		} else {
			$('ul.passwordrow',$form).slideUp();
		}
	});
	
	$form.bind('submit', function(e) {
		e.preventDefault();
		if (!$form.valid()) return false;
		// Submit via ajax
		$.post(SITE_URL + 'administration/newUser', 
			$form.serialize(), 
			function(response) {
				if (typeof(response) != 'object') {
					alert(response);
					return;
				}
				if (response.status != 'success') {
					alert(response.message);
					return;
				}
				// Success
				//$form.load(SITE_URL + 'modal/popup/newuser-form_success').data('popup', response.data);
				hideModalPopup('new_user');
				reloadUsersList();
			}, 
			'json'
		);//post
		
		return false;
	});
	$form.validate({
		rules: {
			name_first: {	required:true	},
			name_last: {	required:true	},
			email: {	required:true, email:true	},
			password: {	required: function(el) { return $('#specify_password').is(':checked'); } },
			password2: { equalTo: '#password' }
		},
		messages: {
			name_first: {	required: 'First Name is required'	},
			name_last: {	required: 'Last Name is required'	},
			email: {	required: 'Email is required', email: 'Please enter a valid email'	},
			password: {	required: 'You must enter a password' },
			password2: { equalTo: 'Passwords don\'t match' }
		}
	});
}

function activateNewMessageForm() 
{
	var $form = $('#newmessage-form');
	$form.bind('submit', function(e) {
		e.preventDefault();
		if (!$form.valid()) return false;
		// Submit via ajax
		$.post(SITE_URL + 'messages/post', 
			$form.serialize(), 
			function(response) {
				if (typeof(response) != 'object') {
					alert(response);
					return;
				}
				if (response.status != 'success') {
					alert(response.message);
					return;
				}
				// Success
				//alert('posted');
				hideModalPopup('new_message');
				reloadMessages();
				//$form.load(SITE_URL + 'modal/popup/newuser-form_success').data('popup', response.data);
			}, 
			'json'
		);//post
		
		return false;
	});
	$form.validate({
		rules: {
			message: {	required:true	}
		},
		messages: {
			message: {	required: 'Message is required'	}
		}
	});
}

// Called in context of dashboard
function reloadMessages()
{
	$.get(SITE_URL + 'dashboard/panel_messages', function(data) {
		$('#panel_messages').replaceWith($(data));
	});
}

// Called in context of dashboard
function refreshCPAppointments()
{
	$.post(SITE_URL + 'administration/refresh_cp_apps', function(response){
		if (typeof(response) != 'object') {
			return;
		}
		if (response.status != 'success') {
			if (response.message == 'Login failed.') return;
			alert(response.message);
			return;
		}
		reloadAppointmentsPanel();
	});
}

function reloadAppointmentsPanel()
{
	$.get(SITE_URL + 'dashboard/panel_appointments', function(data) {
		$('#panel_appointments').replaceWith($(data));
	});
}

function activateFeedbackForm()
{
	var $form = $('#feedback-form');
	$form.bind('submit', function(e) {
		e.preventDefault();
		// Submit via ajax
		$.post(SITE_URL + 'help/sendFeedback', 
			$form.serialize(), 
			function(response) {
				if (typeof(response) != 'object') {
					alert('Failed sending feedback. That\'s just embarrassing :(');
					return;
				}
				if (response.status != 'success') {
					alert(response.message);
					return;
				}
				// Success
				//alert('posted');
				$form.html('<p style="margin:1em; color:#ccc;">Thanks, your input is appreciated. <br />If your query requires a response, you will be contacted via the email on file.<br /></p>');
				//$form.load(SITE_URL + 'modal/popup/newuser-form_success').data('popup', response.data);
		});//post
		
		return false;
	});
}

function new_appointment(e)
{
	// Open the new appointment dialog
	showModalPopup('new_appointment', false, 'tall');
	
}

// Keep track of which element was last touched
var lastTouchee = document;
function touchHandler(event)
{
	return;
	debug('event.type:'+event.type);
	var touches = event.changedTouches;
	var first = touches[0];

	if (event.type == 'touchend' && lastTouchee == first.target) {
		debug('clicked', true);
	}
	debug('setting touchee', true);
	lastTouchee = first.target;
}

function debug(str, append)
{
	return;
	if (append) str = $('#debug').html() + '<br>' + str;
	$('#debug').html(str)
}
Quill.suggester = { 
	container: null,
	list: null,
	el: null,
	altField: null,
	valid: false,
	uri: '',
	tried: false,
	init: function(options) {
		var that=this;
		that.el = $(options.field).addClass('quillSuggest_field');
		if (options.altField) that.altField = $(options.altField);
		that.uri = options.uri;
		that.valid = false;
		that.tried = false;
		that.container = $('<div class="quillSuggest_container" />').insertAfter(that.el);
		//.append('<img src="/autosuggest/arrow.png" />')
		that.list = $('<ul></ul>').addClass('quillSuggest_list clearfix').appendTo(this.container);
		that.el.attr('autocomplete','off').live('keyup', function(e) {
			if (e.which == $.ui.keyCode.SPACE || e.which == $.ui.keyCode.LEFT || e.which == $.ui.keyCode.RIGHT) {
				return true;
			} else if (e.which == $.ui.keyCode.DOWN) {
				if ($('li.hover', that.list).next().length>0)
					$('li.hover', that.list).removeClass('hover').next().addClass('hover');
				return false;
			} else if (e.which == $.ui.keyCode.UP) {
				if ($('li.hover', that.list).prev().length>0)
					$('li.hover', that.list).removeClass('hover').prev().addClass('hover');
				return false;
			} else if (e.which == $.ui.keyCode.ENTER) {
				that.fill();
				e.preventDefault();
				e.stopPropagation();
				return false;
			} else if (e.which == $.ui.keyCode.SHIFT) {
				return false;
			}
			that.el.removeClass('valid');
			if (that.tried) that.el.addClass('invalid');
			that.suggest();
		}).live('keypress',function(e) {
			// don't submit the form while showing suggestions (due to enter pressed)
			if (e.which == $.ui.keyCode.ENTER && that.container.is(':visible')) {
				e.preventDefault();
				e.stopPropagation();
			}
		}).live('blur', function(e) { 
			that.hideSuggestions(); 
			if ( ! that.el.hasClass('valid'))
			{
				that.el.addClass('invalid').val('');
			}
		});
	},
	suggest: function(){
		var that=this;
		that.tried = true;
		var query = that.el.val();
		if(query.length < 2) {
			that.hideSuggestions();
			return;
		}
		that.el.addClass('quillSuggest_loading');
		$.getJSON(that.uri + '/' + query, function(response){
			that.el.removeClass('quillSuggest_loading');
			that.list.empty();
			if(response.data && response.data.length > 0) {
				for (var i=0; i<response.data.length; i++)
				{
					$('<li />').text(response.data[i].name).data('value',response.data[i].id).appendTo(that.list);
				}
				that.container.fadeIn();
				$('li:first',that.list).addClass('hover');
				$('li', that.list).mouseover(function(e){
					$('li',that.list).removeClass('hover');
					$(this).addClass('hover');
				}).click(function(e) {
					that.fill();
				});
			}
		});
	},
	fill: function($itemEl){
		if (!$itemEl) $itemEl = $('li.hover',this.list);
		if (!$itemEl.jQuery) $itemEl = $($itemEl);
		this.el.val($itemEl.text());
		//log ($itemEl.data('value'));
		if (this.altField) this.altField.val($itemEl.data('value'));
		this.hideSuggestions();
		this.el.removeClass('invalid').addClass('valid');
	},
	hideSuggestions: function() {
		this.container.fadeOut(200);
	}
};

// usage: log('inside coolFunc',this,arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
	log.history = log.history || [];   // store logs to an array for reference
	log.history.push(arguments);
	if(this.console){
		console.log( Array.prototype.slice.call(arguments) );
	}
};


$.fn.flash = function(length, speed) {
	speed = speed || 300;
	length = length || 1000;
	var $el = this;
	$el.fadeIn(speed);
	setTimeout(function(){ $el.fadeOut(speed*2); }, length);
};
 
// .trim() for strings
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
function zeroPad (num, count) {
	var numZeropad = num + '';
	while(numZeropad.length < count) {
		numZeropad = '0' + numZeropad;
	}
	return numZeropad;
}

//// Catch any ajax session errors
//$(document).ajaxError(function(e, request, options, exception) {
//	// Send kelly an email about it
//	var debug = {
//		url: options.url,
//		type: options.type,
//		status: request.status,
//		responseText: request.responseText,
//		data: options.data,
//		dataType: options.dataType
//	};
//	if (debug.url != SITE_URL + 'map/getLeads' 
//		 && debug.url != SITE_URL + 'map/getPolylines'
//		 && debug.url != SITE_URL + 'administration/refresh_cp_apps'
//		 && debug.url != SITE_URL + 'dashboard/panel_appointments'
//		 && debug.url != SITE_URL + 'popup/ajax_login') {
//		$.post(SITE_URL + 'help/sendFeedback', { 'error':true, 'debug':JSON.stringify(debug) });
//	} else {
//		return false;
//	}
//	
//	// options.url
//	if (request.status == 401) {
//		showModalPopup('ajax_login', options);
//	} else {
//		alert('Ajax error. Try refreshing the page, then try again. The site admin have been notified of the error.');
//	}
//});

$.ajaxSetup({
	beforeSend: function(request, settings) {
		settings.cache = false;
		if (settings.type == 'POST') {
			// Attach csrf token
			var $csrf_field = $('#csrf_token');
			settings.data += '&' + $csrf_field.attr('name') + '=' + $csrf_field.val();
		}
	}
});

jQuery.expr[':'].data = function(el, index, m) {
	m = m[0].replace(/:data\(|\)$/g, '').split(',');
	return (m.length > 1) ? jQuery(el).data(m[0]) == m[1] : !!jQuery(el).data(m[0]);
};

function scrollToLead(leadID) {
	$row = (leadID.jquery)? leadID: $('.leadrow:data(leadid,'+leadID+')');
	$.scrollTo($row, { duration:1000, offset: {left:0,top:-40}});
}