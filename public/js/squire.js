// The Sq global should be defined prior to including this file
if (typeof Sq === 'undefined') { var Sq = {}; }

/**
 * Global helpers
 */
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,'');
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
		var options = {
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:mm tt',
			ampm: true,
			initialValue: new Date()
		};
		try {
			// Since the data attribute will be stored in double quotes, the json
			// will need to use single quotes, so we remedy that here.
			var json = $(this).data('datetime').trim().replace(/'/g, '"');
			var opt = $.parseJSON(json);
			if (typeof opt === 'object') {
				$.extend(options, opt);
			}
		} catch (e) {
			console.log(e, e.message);
		}

		$(this).datetimepicker(options).datetimepicker('setDate', options.initialValue);
	})

	$('input[data-autocomplete]', $context).autocomplete({
		source: $(this).data('autocomplete-uri')
	});
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
		$context = $('body > .container');
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
Sq.reload_panel = function(panel_id) {
	var $panel = $('#'+panel_id);
	if ( ! $panel.data('uri')) {
		return false;
	}
	$.ajax({
		type: 'get',
		accept: 'text/html',
		dataType: 'html',
		url: Sq.site_url($panel.data('uri')),
		success: function(data){
			$panel.replaceWith(data);
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
 */
Sq.ajax_submit = function($form, $context) {
	if ( ! $form.valid()) {
		return false;
	}
	$.ajax({
		url: $form.attr('action'),
		type: $form.attr('method'),
		data: $form.serialize(),
		success: function(data){
			// Close the modal window
			if (typeof $context !== 'undefined' && $context.is('.modal')) {
				$context.modal('hide');
			}

			// Show a message if we got one
			if (typeof data.message !== 'undefined' && data.reload_panel.length) {
				Sq.alert('success', data.message);
			}

			// Reload a panel, maybe?
			if (typeof data.reload_panel !== 'undefined' && data.reload_panel.length) {
				Sq.reload_panel(data.reload_panel);
			}
		},
		error: function(xhr, status){
			var response;
			// Try to parse the response as JSON
			try {
				response = $.parseJSON(xhr.responseText);
			} catch(error) {
				console.log('Non-JSONized error: ', error);
				return;
			}
			// Show a message if we got one
			if (typeof response.errors !== 'undefined') {
				var $container = $form;
				if (typeof $context !== 'undefined' && $context.is('.modal')) {
					$container = $context.find('.modal-body');
				}
				Sq.alert('error', '<p>'+response.errors.join('</p><p>')+'</p>', $container);
			}
		}
	});
};

/**
 * Prepare Modal Dialog
 * Prepares markup for form dialogs by activating widgets and validation
 */
Sq.prepare_modal = function($modal) {
	// This only applies to form dialogs
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
	console.log(val.rules);

	$form.validate(val);
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
	$('tr.clickable').live('click', function(e){
		var uri = $(this).data('uri');
		if ( ! uri) { return false; }
		window.location = Sq.site_url(uri);
	});

	// Modal links
	$('a.ajax').live('click', function(e){
		$.ajax({
			url: this.href,
			type: 'GET',
			accept: 'text/html',
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
					return;
				}
				$modal.addClass('fade').modal();
				Sq.prepare_modal($modal);
			},
			error: function(xhr, status)
			{
				console.log(xhr, status);
			}
		});
		return false;
	});

	// Trigger form submission when user clicks primary dialog button
	$('.form-modal .btn-primary').live('click', function(e)
	{
		var $modal = $(this).parents('.form-modal');
		// Submit the form contained in the modal
		var $form = $modal.find('form:first');
		Sq.ajax_submit($form, $modal);
		//$form.submit();
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