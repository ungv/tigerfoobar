/*
	Patchwork - General Front End Functionality
*/
var colors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

$(document).ready(function() {
	/*--------FORM VALIDATION (DOCUMENTATION http://docs.jquery.com/Plugins/Validation)--------*/

	// Call jQuery validate plugin that injects messages for required fields on form submit
	$('#signupForm').validate({
		rules: {
			username: {
				required: true
			},
			password: {
				required: true
			},
			email: {
				email: true
			}
		},
		messages: {
			username: {
				required: "^We can't just call you nothing..."
			},
			password: {
				required: "^Hmm, no password seems kinda fishy"
			}
		}
	});

	/*-----------TagIt Plugin (DOCUMENTATION: http://webspirited.com/tagit/docs.html)-------------------*/

	var companyList = []; // populated with all companies pulled from database as a source for autocompletion
	var tagsList = []; // populated with all tags pulled from database as a source for autocompletion

	$.ajax({
		type: 'POST',
		url: 'http://127.0.0.1/action/getAll',
		dataType: 'json',
		success: function(json) {
			for (var i = 0; i < json.length; i++) {
				companyList.push(i);
			}
		},
		error: function() {
			alert('Fail!');
		}
	});
	console.log(companyList);
	
	// Input field for entering an associated company from "Add new claim" form
	$('#assocCo').tagit({
		tagSource: companyList,
		maxTags: 1,
		select: true,
		triggerKeys: ['enter', 'comma'],
		highlightOnExistColor: '#000'
	});

	// Input field for entering tags from "Add new claim" form
	$('#tagsSearch').tagit({
		tagSource: tagsList,
		select: true,
		triggerKeys: ['enter', 'comma'],
		highlightOnExistColor: '#000'
	});
	
 	/*------Logging In------*/

	//Sends the passed login parameters to server onclick
	function sendLogin(username, password) {
		$.ajax({
			type: 'POST',
			url: 'http://127.0.0.1/action/login',
			data: {
				username: username,
				password: password
			},
			dataType: 'json',
			success: function(json) {
				$('#loginPopup').hide(200);
				$('#login_username').val('');
				$('#login_password').val('');
				$('#login_buttons').hide(200);
				$('#login_status').html('Logged in as ' + json.username);
				$('#login_status').show(200);
				$('#logout').show(200);
			},
			error: function() {
				alert('Fail!');
				$('#login_fail').show(200);
				$('#login_password').val('');
			}
		});
	}
	
	//Show login popup onclick
	$('#login').click(function() {
		$('#loginPopup').show(200);
		$('#signupPopup').hide(200);
	});

	//Ask server to login on click
	$('#login_submit').click(function() {
		$('#login_fail').hide(200);
		sendLogin( $('#login_username').val() , $('#login_password').val() );
	});

	//Ask server to login on click
	$('#login_cancel').click(function() {
		$('#loginPopup').hide(200);
		$('#login_fail').hide(200);
		$('#login_username').val('');
		$('#login_password').val('');
	});

	/*------Sigining Up------*/

	//Show signin box onclick
	$('#signup').click(function() {
		$('#signupPopup').show(200);
		$('#loginPopup').hide(200);
	});

	//Hide Signinpopup
	$('.cancelButton').click(function() {
		$('#signupPopup').hide(200);
	});

	//Set focus behavior for input boxes
	$('input, textarea').each(function() {
			$(this).addClass('outfocus');
		});
	$('input, textarea').focus(function() {
		$(this).removeClass('outfocus').addClass('infocus');
	}).blur(function() {
		$(this).removeClass('infocus').addClass('outfocus');
	}).mouseenter(function() {
		$(this).addClass('hover');
	}).mouseleave(function() {
		$(this).removeClass('hover');
	});

	/*-----Auto Complete Info----*/

	var projects = [
			{
				value: "jquery",
				label: "jQuery",
				desc: "the write less, do more, JavaScript library",
				icon: "jquery_32x32.png"
			},
			{
				value: "jquery-ui",
				label: "jQuery UI",
				desc: "the official user interface library for jQuery",
				icon: "jqueryui_32x32.png"
			},
			{
				value: "sizzlejs",
				label: "Sizzle JS",
				desc: "a pure-JavaScript CSS selector engine",
				icon: "sizzlejs_32x32.png"
			}
		];

	$( "#tags" ).autocomplete({
		minLength: 0,
		source: projects,
		focus: function( event, ui ) {
			$( "#tags" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#tags" ).val( ui.item.label );
			return false;
		}
	})
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
		.appendTo( ul );
	};	
});
