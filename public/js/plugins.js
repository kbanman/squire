
// remap jQuery to $
(function($){
	
	$('#modalwindow button.close, #modalwindow button.cancel').live('click',function(e) {
		e.preventDefault();
		hideModalPopup($(this).parents('#modalwindow').attr('id'));
	});
	
	$('#newlead-button').live('click',function(e) {
		showModalPopup('new_lead', false, 'venti');
	});
	$('#newmessage-button, #btn_new_message').live('click',function(e) {
		showModalPopup('new_message');
	});
	
	$('#btn_add_subuser').live('click', function(e) {
		showModalPopup('new_user', false, 'grande');
	});

	$('#minisearch_results').append('<div id="minisearch_loading">Searching Database&hellip;</div><section id="minisearch_resultlist"></section>');
	$('#minisearch_form').submit( function(e){
		minisearch($('#minisearch_query').val());
		return false;
	});
	$('#minisearch_results .clickable').live('click', function(e) {
		var leadID = $(this).data('leadid');
		if (URI_STRING == '/leads') {
			$('.leadrow:data(leadid,'+leadID+')').click();
		} else {
			window.location = SITE_URL + 'leads#' + leadID;
		}
	});
	
	$('.btn_delete_message').live('click', function(e) {
		if (!confirm('Are you sure?')) return false;
		$.post(SITE_URL + 'messages/delete/' + $(this).data('messageid'), function(response) {
			if (typeof(response) != 'object') {
				return alert('Something terrible just happened.');
			}
			if (response.status == 'success') {
				reloadMessages();
			}
		});
	});
	
	// This needs to go
	$('.makedatepicker').datepicker({	dateFormat:'d-M-yy'	});
	
	// This needs to go
	$('.makeuibutton').button();
	
	$('#btn_feedback').click(function(e) {
		showModalPopup('feedback_message');
	});
	
	// Check if we need to refresh cpower appointments
	if ($('#panel_appointments').length && $('#panel_appointments').data('cpuptodate') != 'yes') {
		refreshCPAppointments();
	}
	
	$('#btn_new_appointment').live('click', new_appointment);
	
	// Initialize nav menu
	var baseURI = URI_STRING.split('/')[0];
	$('nav > span > a').each(function(){
		var $item = $(this);
		var $container = $item.parent();
		var $subnav = $('.subnavbar',$container);
		var uri = $item.data('uri');
				
		if (uri == baseURI)
		{
			$item.addClass('active');
			$subnav.show().length && $item.addClass('hassubnav');
		} else if ($subnav.length) {
			$item.addClass('hassubnav');
			$container.hover(function(){
				clearTimeout(Quill.navtimers[uri]);
				$subnav.slideDown();
				$item.addClass('active');
			}, function(){
				Quill.navtimers[uri] = setTimeout(function(){
					$subnav.slideUp(200, function(){
						$item.removeClass('active') 
					});
				}, 500);
			});
		}
	});
		
	$(window).resize(function() {
		$('#mask').css({height:$(document).height()+'px',width:'100%'});
		var width = $(window).width();
		
		$('.nonness2, .nonness1').hide();
		
		if (width > 1004) $('.nonness1').show();
		if (width > 1260) $('.nonness2').show();
		
	}).resize();

	// This seems to make touch events work better
	try {
		cleanEvents();
	} catch(e) {}
})(window.jQuery);




