/**
 * Global helpers
 */
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,'');
}

function empty(v)
{
	var k;
	if (v === '' || v === 0 || v === '0' || v === null || v === false || typeof v === 'undefined') {
		return true;
	}
	if (typeof v == 'object') {
		for (k in v) { return false; }
		return true;
	}
	return false;
}

/**
 * When Squi_Form attaches array data to a field, it encodes it
 * as faux json, with double-quotes converted to single so as to
 * to put it in one html data attribute.
 *
 * This decodes that string back into an object
 */
Sq.parse_fson = function(fson) {
	try {
		return $.parseJSON(fson.trim().replace(/'/g, '"'));
	} catch (e) {
		return null;
	}
}

/**
 * Activate form widgets
 * Given a context (usually an ajax-loaded form), this method
 * activates widgets based on fields' data attributes and classes
 */
Sq.activate_widgets = function($context) {
	if (typeof $context === 'undefined') {
		$context = document.body;
	}

	$('input[data-datetime]', $context).each(function(i, el)
	{
		var $field = $(el);

		var options = {
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:mm tt',
			ampm: true,
			initialValue: new Date()
		};
		
		var opt = Sq.parse_fson($field.data('datetime'));

		if (typeof opt === 'object') {
			$.extend(options, opt);
		}

		$field.datetimepicker(options).datetimepicker('setDate', options.initialValue);
	});

	$('input[data-autocomplete]', $context).autocomplete({
		source: $(this).data('autocomplete-uri')
	});

	$('.datepicker', $context).each(function(i, el)
	{
		var $field = $(el);
		var date = $field.val() ? new Date($field.val()) : new Date();

		var options = {
			dateFormat: 'd-M-yy',
			initialValue: date
		};

		var opt = Sq.parse_fson($field.data('datepicker'));

		if (typeof opt === 'object') {
			$.extend(options, opt);
		}

		$field.datepicker(options);

		if (options.initialValue) {
			$field.datepicker('setDate', options.initialValue);
		}
	});

	// Activate addon widgets
	Sq.widgets.each(function(selector, callback)
	{
		$(selector, $context).each(function(i, el)
		{
			callback($(el));
		});
	})
};


/**
 * Alert box
 * Adds a styled alert box to the passed $context (or the main container by default)
 * Types include 'message' (yellow), 'error' (red), 'success' (green), 'info' (blue)
 * By default hides preexisting alerts. Set 'append' to true to keep existing alerts.
 */
Sq.alert = function(type, message, $context, append) {
	// Default to main container
	if (typeof $context === 'undefined') {
		if ($('body > .container').length) {
			$context = $('body > .container');
		} else {
			$context = $('body > .fluid-container');
		}
	}

	// Needs to be in a function due to the multiple scenarios below
	var add_alerts = function()
	{
		var $alert = $('<div class="alert alert-'+type+'"><a class="close" data-dismiss="alert" href="#">&times;</a>'+message+'</div>');
		console.log('Appending '+type+' alert to ', $context);
		$context.prepend($alert.hide().slideDown());
	};

	// Kill any existing alerts unless 'append' was specified
	var $existing = $('> .alert', $context);
	if ($existing.length && ! append) {
		$existing.slideUp(function(){
			$existing.remove();
			add_alerts();
		});
		return;
	}
	
	add_alerts();
};

/**
 * Reload Panel
 * Fetches new content for a panel based on the panel's URI,
 * specified in the data-uri attribute.
 */
Sq.reload_panel = function(panel_id, data, callback) {
	var $panel = $('#'+panel_id);
	if ( ! $panel.data('uri')) {
		return false;
	}
	$.ajax({
		type: 'get',
		headers: {
			Accept: 'application/vnd.squire+partial,text/html;q=0.9,*/*;q=0.8'
		},
		dataType: 'html',
		data: data,
		url: Sq.site_url($panel.data('uri')),
		success: function(data){
			$panel.replaceWith(data);
			if (typeof callback === 'function') callback($('#'+panel_id));
		}
	});
};

/**
 * Ajax Submit
 * Validates, then submits a form ajaxically.
 * This method responds to JSON post-submit instructions from the server,
 * such as displaying an alert message ("message":"goes here"),
 * and reloading a panel ("reload_panel":"panel_id")
 *
 * @todo: React more intelligently to server-side validation errors
 * @todo: Move the $context parameter into options
 */
Sq.ajax_submit = function($form, $context, options) {
	if ( ! $form.valid()) {
		return false;
	}

	var $_context = $form;
	if ($context) {
		$_context = $context;
	} else if ($form.parent('.modal').length) {
		$_context = $form.parent('.modal:first');
	}
	
	var opt = {
		context: $_context,
		load: function(e) {},
		success: function(data) {},
		error: function(jqXHR, textStatus) {}
	};
	$.extend(opt, options);

	$.ajax({
		url: $form.attr('action'),
		type: $form.attr('method'),
		data: $form.serialize(),
		success: function(data){
			// Call the user-defined event
			opt.success(data);

			// Close the modal window
			if (typeof opt.context !== 'undefined' && opt.context.is('.modal')) {
				opt.context.modal('hide');
			}

			// User-defined callback function
			if (typeof data.callback !== 'undefined' && data.callback.length) {
				if (typeof window[data.callback] !== 'undefined') {
					window[data.callback](data);
				} else {
					console.log('Tried to call undefined callback', data.callback);
				}
			}

			// Show a message if we got one
			if (typeof data.message !== 'undefined' && data.message.length) {
				Sq.alert('success', data.message);
			}

			// Reload a panel, maybe?
			if (typeof data.reload_panel !== 'undefined' && data.reload_panel.length) {
				Sq.reload_panel(data.reload_panel);
			}
			
			// Redirect to another page?
			if (typeof data.redirect !== 'undefined' && data.redirect.length) {
				window.location = Sq.site_url(data.redirect);
			}
		},
		error: function(xhr, status) {
			var response;

			// Try to parse the response as JSON
			try {
				response = $.parseJSON(xhr.responseText);
			} catch(error) {
				console.log('Non-JSONized error: ', error);
				opt.error(xhr, status);
				return;
			}

			// Call the user-defined event
			opt.error(response);

			// Show a message if we got one
			if (typeof response.message !== 'undefined')
			{
				Sq.alert('error', '<p>'+response.message+'</p>', $form);
			}

			// Show validation errors
			if (typeof response.errors !== 'undefined') {
				var errors = [];
				for (var field in response.errors)
				{
					$('[name='+field+']', $form).closest('.control-group').addClass('error');
					errors.push(response.errors[field]);
				}
				Sq.alert('error', '<p>'+errors.join('</p><p>')+'</p>', $form);
			}
		}
	});
};

/**
 * Prepare Modal Dialog
 * Prepares markup for form dialogs by activating widgets and validation
 */
Sq.prepare_modal = function($modal, options) {
	// Options to be passed to Sq.ajax_submit()
	var opt = {
		success: function(data) {},
		error: function(response) {}
	};
	$.extend(opt, options);

	$($modal).on('shown', function()
	{
		$('input[type!=hidden]:first', $modal).focus();
	});

	// Run addon hooks
	for (var i in Sq.dialog_callbacks)
	{
		Sq.dialog_callbacks[i]($modal);
	}
	
	// The rest only applies to form dialogs
	if ( ! $modal.is('.form-modal')) { return; }

	Sq.activate_widgets($modal);

	var $form = $modal.find('form:first');
	var val = {};
	$form.find('input, textarea, select').each(function(){
		var rules = $(this).data('validation');
		var field = $(this).attr('name');
		if ( ! rules) { return; }
		try {
			rules = $.parseJSON(rules.trim().replace(/'/g, '"'));
		} catch (e) {
			console.log('Could not parse validation rules for '+field+': '+rules.trim().replace(/'/g, '"'));
			return;
		}
		val[field] = {};
		var translate = {
			'regex': function(pattern){
				// Take the control characters out
				//pattern = pattern.substr(1, pattern.length-2).replace(/\\/g, '\\\\');
				pattern = pattern.substr(1, pattern.length-2);
				return {
					name: 'pattern',
					arg: new RegExp(pattern)
				};
			}
		};
		for (var name in rules) {
			var rule = rules[name];

			// Translate from server-side terms
			if (typeof translate[name] !== 'undefined')
			{
				var newRule = translate[name](rule);
				rule = newRule.arg;
				name = newRule.name;
			}

			if (typeof rule === 'string' && ! rule.arg.length)
			{
				rule = true;
			}

			// Join the rule array just in case there were colons in the arguments
			val[field][name] = rule;
		}
	});

	val = {
		ignore: ':hidden',
		errorClass: 'error',
		validClass: 'success',
		errorElement: 'span',
		highlight: function(element, errorClass, validClass) {
			if (element.type === 'radio') {
				this.findByName(element.name).parent('div').parent('div').removeClass(validClass).addClass(errorClass);
			} else {
				$(element).parent('div').parent('div').removeClass(validClass).addClass(errorClass);
			}
		},
		unhighlight: function(element, errorClass, validClass) {
			if (element.type === 'radio') {
				this.findByName(element.name).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
			} else {
				$(element).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
			}
		},
		rules: val
	};

	if (val.rules.length)
	{
		$form.validate(val);
	}
	//console.log(val.rules);

	$form.submit(function(e)
	{
		e.preventDefault();
		Sq.ajax_submit($(this), $(this).parents('.form-modal'), opt);

		return false;
	});
};

Sq.modal = function(options)
{
	var opt = {
		url: '',
		success: function($modal) {},        // dialog loaded successfully
		error: function(response) {},           // dialog load error. response will be either responseText or json-parsed object
		submit_success: function(data) {},   // called when form inside dialog submits successfully
		submit_error: function(response) {}, // called when form inside dialog has ajax error
	};
	$.extend(opt, options);

	$.ajax({
		url: options.url,
		type: 'GET',
		headers: {
			Accept: 'application/vnd.squire+dialog,text/html;q=0.9,*/*;q=0.8'
		},
		dataType: 'html',
		success: function(data)
		{
			var $modal = $(data);
			if ($modal.length > 1 || ! $modal.is('.modal')) {
				console.log(data, $(data).find('div.modal:first'), $(data));
				// Create a modal with whatever was returned
				$modal = $('<div class="modal"></div>')
					.append($('<div class="modal-header"></div>')
						.append('<a href="#" class="close" data-dismiss="modal">x</a>')
						.append('<h3>Error Loading Dialog</h3>'))
					.append($('<div class="modal-body"></div')
						.append(data))
					.append($('<div class="modal-footer"></div>')
						.append('<a href="#" class="btn" data-dismiss="modal">Close</a>'))
					.modal();
				opt.success($modal);
				return;
			}
			$modal.addClass('fade').modal();

			$modal.on('shown', function() {
				Sq.prepare_modal($modal, {
					success: opt.submit_success,
					error: opt.submit_error
				});
			});
			opt.success($modal);
		},
		error: function(xhr, status)
		{
			opt.error(xhr.responseText);
		}
	});
};

/**
 * On page ready, make all the magic happen
 */
$(function() {

	// Make all ajax requests accept JSON responses
	$.ajaxSetup({
		accept: 'application/json',
		dataType: 'json'
	});

	// Clickable table rows
	$('tr[data-uri].clickable').live('click', function(e){
		e.preventDefault();
		var uri = $(this).data('uri');
		if ($(this).is('.ajax'))
		{
			return Sq.modal({
				url: Sq.site_url(uri)
			});
		}
		window.location = Sq.site_url(uri);
	});

	// Modal links
	$('.ajax').live('click', function(e){
		var url = (typeof this.href === 'undefined')
			? Sq.site_url($(this).data('uri'))
			: this.href;

		Sq.modal({
			url: url
		});
		return false;
	});

	// Trigger form submission when user clicks primary dialog button
	$('.form-modal .btn-primary').live('click', function(e)
	{
		console.log('modal save clicked');
		// Submit the form contained in the modal
		$(this).parents('.form-modal').find('form:first').submit();
		return false;
	});
	
	// Ajax comment submission
	// @todo: this is actually part of the CRM bundle, and shouldn't be in the core
	$('#entity_comment_form').submit(function(e)
	{
		var $form = $(this);
		Sq.ajax_submit($form);
		return false;
	});

	// Activate any widgets on this page
	Sq.activate_widgets();

	/**
	 * Mini Search
	 * In lieu of a good jQuery plugin for this, I'm manually handling keyboard navigation
	 * for the mini search box. This involves some delicate heirarchical traversal and event
	 * management, and as such scares the crap out of me.
	 * @todo: Should be replaced by a tested solution.
	 */
	var $searchForm = $('#mini-search'),
		$resultBox = $('#search-results');

	$searchForm.submit(function(e)
	{
		if ( ! $('#mini-search-field').val().length)
		{
			return false;
		}
		
		$.getJSON($searchForm.attr('action'), $searchForm.serialize(), function(data) {
			$resultBox.empty();
			
			$.each(data, function(key, set) {
				var $list = $('<ul class="result-set"></ul>').appendTo($resultBox);
				$('<li class="heading"></li>')
					.append('<a href="'+Sq.site_url(set.index_uri)+'">'+set.label+'</a></li>')
					.appendTo($list);
				$.each(set.results, function(key, result) {
					var $item = $('<li></li>')
						.append('<p class="title">'+result.label+'</p>')
						.appendTo($list);
					if (result.context) {
						$item.append('<p class="context">'+result.context+'</p>');
					}
					$item.wrapInner('<a href="'+Sq.site_url(result.uri)+'"></a>');
				});
			});

			if ( ! $resultBox.children().length) {
				$resultBox.append('<p class="noresult">No results found</p>');
			}

			$resultBox.slideDown();
		});
		return false;
	});

	$('#mini-search-field').keydown(function(e)
	{
		var code = (e.keyCode ? e.keyCode : e.which);

		// Down arrow
		if (code === 40) {
			e.stopPropagation();
			if ($resultBox.children().length) {
				$resultBox.find('li:not(.heading):first a').focus();
			}
			return false;
		}
	}).blur(function(e)
	{
		setTimeout(function()
		{
			if ( ! $searchForm.find('a:focus').length) {
				$resultBox.slideUp();
			}
		}, 300);
	});

	$('li a', $resultBox).live('keydown', function(e)
	{
		var code = (e.keyCode ? e.keyCode : e.which);
		var $a = $(e.target);

		// Up arrow
		if (code === 38) {
			e.stopPropagation();
			var $prev = $a.parent().prevAll(':not(.heading):first').find('a:first');
			if ( ! $prev.length) {
				$prev = $a.parent().parent().prevAll(':not(.heading):first').find('a:first');
				if ( ! $prev.length) { return false; }
			}

			$prev.focus();
			return false;
		}

		// Down arrow
		if (code === 40) {
			e.stopPropagation();
			var $next = $a.parent().nextAll(':not(.heading):first').find('a:first');
			if ( ! $next.length) {
				$next = $a.parent().parent().nextAll(':not(.heading):first').find('a:first');
				if ( ! $next.length) { return false; }
			}

			$next.focus();
			return false;
		}

	}).live('blur', function(e)
	{
		setTimeout(function()
		{
			if ( ! $searchForm.find('a:focus').length) {
				$resultBox.slideUp();
			}
		}, 300);
	});
});