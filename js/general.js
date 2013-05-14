/*
	Patchwork - General Front End Functionality
*/
$(document).ready(function() {

	
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
	
 	/*------Logging In------*/
	
	//Show login popup onclick
	$('#login').click(function() {
		$('#loginPopup').show(200);
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
	});

	//Hide Signinpopup
	$('.cancelButton').click(function() {
		$('#signupPopup').hide(200);
	});

	//Set focus behavior for input boxes
	$('input[type=text]').each(function() {
			$(this).addClass('outfocus');
		});
	$('input[type=text]').focus(function() {
		$(this).attr('class', 'infocus');
	}).blur(function() {
		$(this).attr('class', 'outfocus');
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
